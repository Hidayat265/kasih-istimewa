<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\DonationReceiptMail;
use Spatie\Browsershot\Browsershot;
use App\Traits\RecalculatesAllocations;
use Barryvdh\DomPDF\Facade\Pdf;

class DonationController extends Controller
{
    use RecalculatesAllocations;

    public function index(Request $request)
    {
        $query = Donation::query();
        
        $receivedByRaw = Donation::select('donation_received_by')
            ->distinct()
            ->whereNotNull('donation_received_by')
            ->orderBy('donation_received_by')
            ->get()
            ->pluck('donation_received_by');
        
        $receivedByOptions = [];
        $receivedByMapping = [];
        
        foreach ($receivedByRaw as $option) {
            $label = $option;
            $value = $option;
            
            if (is_string($option) && str_starts_with($option, 'USR')) {
                $admin = User::where('user_id', $option)->first();
                if ($admin) {
                    $label = $admin->user_name;
                    $value = $option;
                    $receivedByMapping[$option] = $admin->user_name;
                } else {
                    $receivedByMapping[$option] = $option;
                }
            } elseif (strtolower($option) === 'toyyibpay') {
                $label = 'ToyyibPay';
                $value = $option;
                $receivedByMapping[$option] = 'ToyyibPay';
            } elseif (strtolower($option) === 'stripe') {
                $label = 'Stripe';
                $value = $option;
                $receivedByMapping[$option] = 'Stripe';
            } else {
                $receivedByMapping[$option] = $option;
            }
            
            $receivedByOptions[] = [
                'value' => $value,
                'label' => $label
            ];
        }
        
        $search = $request->get('search');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                    ->orWhere('donation_id', 'like', "%{$search}%")
                    ->orWhere('donation_amount', 'like', "%{$search}%");
            });
        }
        
        $donations = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $totalDonationCount = Donation::count();
        $totalAmount = Donation::where('donation_status', 'success')->sum('donation_amount');
        $cashTotal = Donation::where('donation_payment_method', 'cash')->where('donation_status', 'success')->sum('donation_amount');
        $onlineTotal = Donation::where('donation_payment_method', 'online')->where('donation_status', 'success')->sum('donation_amount');
        
        return view('admin.donations.index', compact(
            'donations', 
            'totalDonationCount', 
            'totalAmount', 
            'cashTotal', 
            'onlineTotal', 
            'receivedByOptions', 
            'receivedByMapping', 
            'search'
        ));
    }

/**
 * Get donations data for AJAX with sorting, filtering, and pagination
 */
public function getDonationsData(Request $request)
    {
        $query = Donation::query();
        
        // Date Range Filter
        if ($request->date_range) {
            switch($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'last_month':
                    $lastMonth = now()->subMonth();
                    $query->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year);
                    break;
                case 'this_year':
                    $query->whereYear('created_at', now()->year);
                    break;
                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    }
                    break;
            }
        }
        
        if ($request->status) {
            $query->where('donation_status', $request->status);
        }
        if ($request->method) {
            $query->where('donation_payment_method', $request->method);
        }
        if ($request->received_by) {
            $query->where('donation_received_by', $request->received_by);
        }
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                    ->orWhere('donation_id', 'like', "%{$search}%")
                    ->orWhere('donation_amount', 'like', "%{$search}%");
            });
        }
        
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        
        if ($sort == 'no') {
            $sort = 'created_at';
        }
        
        $query->orderBy($sort, $direction);
        
        $statsQuery = clone $query;
        $totalDonations = $statsQuery->count();
        $totalAmount = $statsQuery->where('donation_status', 'success')->sum('donation_amount');
        
        $cashQuery = clone $statsQuery;
        $cashTotal = $cashQuery->where('donation_payment_method', 'cash')
            ->where('donation_status', 'success')
            ->sum('donation_amount');
        
        $onlineQuery = clone $statsQuery;
        $onlineTotal = $onlineQuery->where('donation_payment_method', '!=', 'cash')
            ->where('donation_status', 'success')
            ->sum('donation_amount');
        
        $donations = $query->paginate(10);
        
        if ($request->ajax()) {
            $html = view('admin.donations.partials.table-rows', compact('donations'))->render();
            $paginationHtml = view('admin.donations.partials.pagination', compact('donations'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $paginationHtml,
                'from' => $donations->firstItem(),
                'to' => $donations->lastItem(),
                'total' => $donations->total(),
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'stats' => [
                    'totalDonations' => $totalDonations,
                    'totalAmount' => $totalAmount,
                    'cashTotal' => $cashTotal,
                    'onlineTotal' => $onlineTotal
                ]
            ]);
        }
        
        $totalDonationCount = $totalDonations;
        $totalAmount = $totalAmount;
        $cashTotal = $cashTotal;
        $onlineTotal = $onlineTotal;
        $receivedByOptions = Donation::select('donation_received_by')
            ->distinct()
            ->whereNotNull('donation_received_by')
            ->orderBy('donation_received_by')
            ->pluck('donation_received_by');
        
        return view('admin.donations.index', compact(
            'donations', 
            'totalDonationCount', 
            'totalAmount', 
            'cashTotal', 
            'onlineTotal', 
            'receivedByOptions'
        ));
    }

/**
 * Apply date range filter to query
 */
private function applyDateRangeFilter($query, $request)
{
    $range = $request->date_range;
    $startDate = $request->start_date;
    $endDate = $request->end_date;
    
    switch ($range) {
        case 'today':
            $query->whereDate('created_at', today());
            break;
        case 'this_week':
            $query->whereBetween('created_at', [
                now()->startOfWeek(), 
                now()->endOfWeek()
            ]);
            break;
        case 'this_month':
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            break;
        case 'last_month':
            $lastMonth = now()->subMonth();
            $query->whereMonth('created_at', $lastMonth->month)
                  ->whereYear('created_at', $lastMonth->year);
            break;
        case 'this_year':
            $query->whereYear('created_at', now()->year);
            break;
        case 'custom':
            if ($startDate && $endDate) {
                try {
                    $start = \Carbon\Carbon::parse($startDate)->startOfDay();
                    $end = \Carbon\Carbon::parse($endDate)->endOfDay();
                    $query->whereBetween('created_at', [$start, $end]);
                } catch (\Exception $e) {
                    // Invalid date format, ignore
                }
            }
            break;
    }
}

/**
 * Get user donations data for AJAX with sorting and pagination
 */
public function getUserDonationsData(Request $request)
{
    $userEmail = auth()->user()->user_email;
    
    $search = $request->get('search', '');
    $sort = $request->get('sort', 'created_at_desc');
    $sortColumn = $request->get('sort_column', 'created_at');
    $sortDirection = $request->get('sort_direction', 'desc');
    
    $query = Donation::where('donor_email', $userEmail);

    // Apply search
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('donation_id', 'like', "%{$search}%")
                ->orWhere('donor_name', 'like', "%{$search}%")
                ->orWhere('donation_amount', 'like', "%{$search}%");
        });
    }
    
    // Apply sorting
    $validSortColumns = ['donation_id', 'donation_amount', 'created_at', 'donation_payment_method', 'donation_received_by', 'donation_status'];
    if (in_array($sortColumn, $validSortColumns)) {
        $query->orderBy($sortColumn, $sortDirection);
    } else {
        $query->orderBy('created_at', 'desc');
    }
    
    $donations = $query->paginate(10);
    
    // Calculate stats
    $statsQuery = Donation::where('donor_email', $userEmail);
    if ($search) {
        $statsQuery->where(function($q) use ($search) {
            $q->where('donation_id', 'like', "%{$search}%")
                ->orWhere('donor_name', 'like', "%{$search}%")
                ->orWhere('donation_amount', 'like', "%{$search}%");
        });
    }
    
    $totalDonations = $statsQuery->count();
    $totalAmount = $statsQuery->where('donation_status', 'success')->sum('donation_amount');
    $monthlyCount = $statsQuery->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();
    
    if ($request->ajax() || $request->wantsJson()) {
        $html = view('user.donation.partials.table-rows', compact('donations'))->render();
        $paginationHtml = view('user.donation.partials.pagination', compact('donations'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'pagination' => $paginationHtml,
            'from' => $donations->firstItem(),
            'to' => $donations->lastItem(),
            'total' => $donations->total(),
            'stats' => [
                'total_donations' => $totalDonations,
                'total_amount' => $totalAmount,
                'monthly_count' => $monthlyCount
            ]
        ]);
    }
    
    return view('user.donation.index', compact('donations', 'totalDonations', 'totalAmount', 'monthlyCount'));
}


    public function store(Request $request)
    {
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:1',
            'payment_method' => ['required', 'string', Rule::in(['cash', 'toyyibpay', 'stripe'])],
            'transaction_id' => 'nullable|string',
        ]);

        $last = Donation::orderBy('donation_id', 'desc')->first();
        $number = $last ? ((int)substr($last->donation_id, 4)) + 1 : 1;
        $donationId = 'DON-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        if ($request->payment_method === 'cash') {
            $receivedBy = (string)(Auth::user()->user_id ?? Auth::id());
            $status = 'success';
        } elseif ($request->payment_method === 'toyyibpay') {
            $receivedBy = 'ToyyibPay';
            $status = 'pending';
        } elseif ($request->payment_method === 'stripe') {
            $receivedBy = 'Stripe';
            $status = 'pending';
        } else {
            $receivedBy = (string)(Auth::user()->user_id ?? Auth::id());
            $status = 'pending';
        }

        $user = User::where('user_email', $request->donor_email)->first();
        $userId = $user ? $user->user_id : null;

        $donation = Donation::create([
            'donation_id' => $donationId,
            'user_id' => $userId,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone,
            'donation_amount' => $request->amount,
            'donation_payment_method' => $request->payment_method,
            'donation_transaction_id' => $request->transaction_id,
            'donation_received_by' => $receivedBy,
            'donation_status' => $status,
        ]);

        $this->recalculateAllocationsForDonation($donation);

        return redirect()->back()->with('success', 'Donation added successfully! Donation ID: ' . $donation->donation_id);
    }

    public function userDonations()
    {
        $userEmail = auth()->user()->user_email;
        
        $donations = Donation::where('donor_email', $userEmail)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $totalDonations = Donation::where('donor_email', $userEmail)->count();
        $totalAmount = Donation::where('donor_email', $userEmail)
            ->where('donation_status', 'success')
            ->sum('donation_amount');
        $monthlyCount = Donation::where('donor_email', $userEmail)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        return view('user.donation.index', compact(
            'donations', 
            'totalDonations', 
            'totalAmount', 
            'monthlyCount'
        ));
    }

    public function getReceipt($donationId)
    {
        try {
            $donation = Donation::where('donation_id', $donationId)->firstOrFail();
            $userEmail = auth()->user()->user_email;
            
            if ($donation->donor_email != $userEmail && !auth()->user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - This receipt does not belong to you'
                ], 403);
            }
            
            $html = view('components.donation-receipt', compact('donation'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'receipt' => $donation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Receipt not found'
            ], 404);
        }
    }
    
    /**
     * Generate Donation Report
     */
    public function generateReport(Request $request)
    {
        $period = $request->get('period', 'all');
        $startDate = $request->get('start');
        $endDate = $request->get('end');
        $format = $request->get('format', 'html');

        $query = Donation::query();

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'last_month':
                $lastMonth = now()->subMonth();
                $query->whereMonth('created_at', $lastMonth->month)
                    ->whereYear('created_at', $lastMonth->year);
                break;
            case 'this_year':
                $query->whereYear('created_at', now()->year);
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [
                        $startDate,
                        $endDate
                    ]);
                }
                break;
        }

        $donations = $query->latest()->get();

        $successfulDonations = $donations->where('donation_status', 'success');

        $cashDonations = $successfulDonations
            ->where('donation_payment_method', 'cash');

        $onlineDonations = $successfulDonations
            ->where('donation_payment_method', 'online');

        $cashPending = $donations
            ->where('donation_payment_method', 'cash')
            ->where('donation_status', 'pending');

        $onlinePending = $donations
            ->where('donation_payment_method', 'online')
            ->where('donation_status', 'pending');

        $report = [
            'totalRecords' => $donations->count(),
            'successfulCount' => $successfulDonations->count(),
            'pendingCount' => $donations->where('donation_status', 'pending')->count(),
            'pendingAmount' => $donations->where('donation_status', 'pending')->sum('donation_amount'),
            'failedCount' => $donations->where('donation_status', 'failed')->count(),
            'totalAmount' => $successfulDonations->sum('donation_amount'),
            'cashTotal' => $cashDonations->sum('donation_amount'),
            'onlineTotal' => $onlineDonations->sum('donation_amount'),
            'cashCount' => $cashDonations->count(),
            'onlineCount' => $onlineDonations->count(),
            'cashPendingCount' => $cashPending->count(),
            'cashPendingAmount' => $cashPending->sum('donation_amount'),
            'onlinePendingCount' => $onlinePending->count(),
            'onlinePendingAmount' => $onlinePending->sum('donation_amount'),
            'averageDonation' => $successfulDonations->count()
                ? $successfulDonations->sum('donation_amount') / $successfulDonations->count()
                : 0,
            'successRate' => $donations->count()
                ? round(($successfulDonations->count() / $donations->count()) * 100, 1)
                : 0
        ];

        // ============================================
        // ALLOCATION DATA - PER MONTH (RESPECT FILTER)
        // ============================================
        
        $allocationData = collect();

        // Determine which months to show based on period
        if ($period === 'all') {
            // All Time: Show all months with allocations
            $months = \App\Models\DonationAllocation::select('allocation_month')
                ->distinct()
                ->orderBy('allocation_month', 'desc')
                ->pluck('allocation_month');
        } elseif ($period === 'this_month') {
            // This Month: Show only current month
            $months = collect([now()->format('Y-m')]);
        } elseif ($period === 'last_month') {
            // Last Month: Show only last month
            $months = collect([now()->subMonth()->format('Y-m')]);
        } elseif ($period === 'this_year') {
            // This Year: Show all months in current year
            $months = \App\Models\DonationAllocation::select('allocation_month')
                ->distinct()
                ->whereYear('allocation_month', now()->year)
                ->orderBy('allocation_month', 'desc')
                ->pluck('allocation_month');
        } elseif ($period === 'custom') {
            // Custom: Show months within the date range
            $startMonth = \Carbon\Carbon::parse($startDate)->format('Y-m');
            $endMonth = \Carbon\Carbon::parse($endDate)->format('Y-m');
            $months = \App\Models\DonationAllocation::select('allocation_month')
                ->distinct()
                ->whereBetween('allocation_month', [$startMonth, $endMonth])
                ->orderBy('allocation_month', 'desc')
                ->pluck('allocation_month');
        } else {
            // Default: Show all months
            $months = \App\Models\DonationAllocation::select('allocation_month')
                ->distinct()
                ->orderBy('allocation_month', 'desc')
                ->pluck('allocation_month');
        }

        foreach ($months as $month) {
            $allocations = \App\Models\DonationAllocation::where('allocation_month', $month)
                ->with('category')
                ->get();
            
            if ($allocations->isNotEmpty()) {
                $monthlyTotal = \App\Models\DonationAllocation::getMonthlyTotal($month);
                
                $categories = $allocations->map(function ($allocation) use ($monthlyTotal) {
                    return [
                        'name' => $allocation->category?->alc_cat_name ?? 'Unknown',
                        'percent' => $allocation->allocation_percent,
                        'amount' => round($monthlyTotal * ($allocation->allocation_percent / 100), 2),
                    ];
                });
                
                $allocationData->put($month, [
                    'total_percent' => $allocations->sum('allocation_percent'),
                    'total_amount' => $allocations->sum('allocation_amount'),
                    'monthly_donations' => $monthlyTotal,
                    'categories' => $categories,
                ]);
            }
        }

        if ($format === 'csv') {
            return $this->exportCSV($donations);
        }

        if ($format === 'pdf') {
            return $this->exportPDF($donations, $report);
        }

        return view(
            'admin.donations.report',
            compact(
                'donations',
                'report',
                'period',
                'startDate',
                'endDate',
                'allocationData'
            )
        );
    }
    
    private function exportExcel($donations)
    {
        return $this->exportCSV($donations);
    }
    
    private function exportPDF($donations, $report)
    {
        $html = view('admin.donations.report-pdf', compact('donations', 'report'))->render();
        return view('admin.donations.report-pdf', compact('donations', 'report'));
    }

    public function exportDonations(Request $request)
    {
        $format = $request->get('format', 'csv');
        $donations = Donation::orderBy('created_at', 'desc')->get();
        
        if ($format === 'csv') {
            return $this->exportCSV($donations);
        }
        
        if ($format === 'excel') {
            return $this->exportExcel($donations);
        }
        
        return $this->exportCSV($donations);
    }

    /**
     * Generate and view donation receipt as PDF (opens in new tab)
     * Accessible by both the donor and admin
     */
    public function viewReceipt($donationId)
    {
        // Find the donation by donation_id only
        $donation = Donation::where('donation_id', $donationId)->firstOrFail();
        
        // Check if user is authorized to view this receipt
        $user = Auth::user();
        
        // Allow if:
        // 1. User is the donor (email matches)
        // 2. User is an admin
        if ($donation->donor_email != $user->user_email && !$user->is_admin) {
            abort(403, 'Unauthorized access. You are not the donor of this donation.');
        }
        
        // Generate PDF from the pdfs folder
        $pdf = Pdf::loadView('pdfs.donation-receipt', compact('donation'));
        
        // Stream PDF to browser (open in new tab)
        return $pdf->stream('Donation_Receipt_' . ($donation->donation_id ?? $donation->id) . '.pdf');
    }

    /**
     * Download donation receipt as PDF
     * Accessible by both the donor and admin
     */
    public function downloadReceipt($donationId)
    {
        // Find the donation by donation_id only
        $donation = Donation::where('donation_id', $donationId)->firstOrFail();
        
        // Check if user is authorized to view this receipt
        $user = Auth::user();
        
        // Allow if:
        // 1. User is the donor (email matches)
        // 2. User is an admin
        if ($donation->donor_email != $user->user_email && !$user->is_admin) {
            abort(403, 'Unauthorized access. You are not the donor of this donation.');
        }
        
        // Generate PDF from the pdfs folder
        $pdf = Pdf::loadView('pdfs.donation-receipt', compact('donation'));
        
        // Download PDF file
        return $pdf->download('Donation_Receipt_' . ($donation->donation_id ?? $donation->id) . '.pdf');
    }
}