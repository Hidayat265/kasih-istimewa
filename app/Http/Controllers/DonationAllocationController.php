<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationAllocation;
use App\Models\DonationAllocationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonationAllocationController extends Controller
{
    /**
     * Display allocation page
     */
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month');
        
        // If no month selected, use current month for display but show all data
        if (!$selectedMonth) {
            $selectedMonth = now()->format('Y-m');
        }

        $allocations = DonationAllocation::with([
                'category',
                'changedByUser'
            ])
            ->where('allocation_month', $selectedMonth)
            ->orderByDesc('created_at')
            ->paginate(10);

        $categories = DonationAllocationCategory::where('alc_cat_is_active', true)
            ->orderBy('alc_cat_name')
            ->get();

        // Calculate totals based on filter
        if ($request->get('month')) {
            // FILTER APPLIED - Monthly data
            $totalPercent = DonationAllocation::where('allocation_month', $selectedMonth)->sum('allocation_percent');
            $monthlyTotal = DonationAllocation::getMonthlyTotal($selectedMonth);
        } else {
            // NO FILTER - ALL TIME data
            // Get the MOST RECENT month's allocation percentages
            $latestMonth = DonationAllocation::select('allocation_month')
                ->distinct()
                ->orderBy('allocation_month', 'desc')
                ->first();
            
            if ($latestMonth) {
                $totalPercent = DonationAllocation::where('allocation_month', $latestMonth->allocation_month)->sum('allocation_percent');
            } else {
                $totalPercent = 0;
            }
            
            // Total donations ALL TIME
            $monthlyTotal = Donation::where('donation_status', 'success')->sum('donation_amount');
        }

        return view('admin.donations.allocations', compact(
            'allocations',
            'categories',
            'selectedMonth',
            'totalPercent',
            'monthlyTotal'
        ));
    }

    public function getAllocationsData(Request $request)
    {
        $query = DonationAllocation::with([
            'category',
            'changedByUser'
        ]);

        /**
         * FILTERS
         */
        if ($request->month) {
            $query->where('allocation_month', $request->month);
        }

        if ($request->category) {
            $query->where('allocation_category_id', $request->category);
        }

        if ($request->search) {
            $query->where('allocation_notes', 'like', '%' . $request->search . '%');
        }

        /**
         * SORTING
         */
        $sortMap = [
            'id'       => 'allocation_id',
            'month'    => 'allocation_month',
            'percent'  => 'allocation_percent',
            'amount'   => 'allocation_amount',
        ];

        $sort = $sortMap[$request->sort] ?? 'created_at';
        $direction = $request->direction ?? 'desc';

        $query->orderBy($sort, $direction);

        /**
         * PAGINATION
         */
        $allocations = $query->paginate(10);

        /**
         * TOTALS - FIXED
         */
        if ($request->month) {
            // ============================================
            // FILTER APPLIED - Show monthly data
            // ============================================
            $selectedMonth = $request->month;
            
            // Monthly allocations
            $monthlyAllocations = DonationAllocation::where('allocation_month', $selectedMonth);
            $totalPercent = $monthlyAllocations->sum('allocation_percent');
            $totalAmount = $monthlyAllocations->sum('allocation_amount');
            $remainingPercent = max(0, 100 - $totalPercent);
            
            // Monthly donations
            $monthlyDonations = DonationAllocation::getMonthlyTotal($selectedMonth);
            
        } else {
            // ============================================
            // NO FILTER - Show ALL TIME data
            // ============================================
            
            // Get ALL months with allocations
            $months = DonationAllocation::select('allocation_month')
                ->distinct()
                ->orderBy('allocation_month', 'desc')
                ->pluck('allocation_month');
            
            if ($months->isNotEmpty()) {
                $totalPercentSum = 0;
                $monthCount = 0;
                
                foreach ($months as $month) {
                    $monthPercent = DonationAllocation::where('allocation_month', $month)->sum('allocation_percent');
                    $totalPercentSum += $monthPercent;
                    $monthCount++;
                }
                
                // Calculate AVERAGE percentage across all months
                $totalPercent = $totalPercentSum / $monthCount;
                $remainingPercent = max(0, 100 - $totalPercent);
            } else {
                $totalPercent = 0;
                $remainingPercent = 100;
            }
            
            // Total allocated amount - ALL TIME
            $totalAmount = DonationAllocation::sum('allocation_amount');
            
            // Total donations - ALL TIME
            $monthlyDonations = Donation::where('donation_status', 'success')->sum('donation_amount');
        }

        return response()->json([
            'success' => true,

            'html' => view(
                'admin.donations.partials.allocation-rows',
                compact('allocations')
            )->render(),

            'pagination' => view(
                'admin.donations.partials.allocation-pagination',
                compact('allocations')
            )->render(),

            'from' => $allocations->firstItem(),
            'to' => $allocations->lastItem(),
            'total' => $allocations->total(),

            'stats' => [
                'monthlyDonations' => $monthlyDonations,
                'totalPercent' => $totalPercent,
                'totalAmount' => $totalAmount,
                'remainingPercent' => $remainingPercent,
            ]
        ]);
    }

    /**
     * Store allocation
     */
    public function store(Request $request)
    {
        $request->validate([
            'allocation_month' => 'required|date_format:Y-m',
            'allocation_percent' => 'required|numeric|min:0.01|max:100',
            'allocation_notes' => 'nullable|string|max:1000',
        ]);

        /**
         * ======================================================
         * CATEGORY SECTION
         * ======================================================
         */

        if ($request->category_mode === 'new') {

            $request->validate([
                'new_category_name' => 'required|string|max:255|unique:donation_allocation_categories,alc_cat_name',
            ]);

            // Generate Category ID
            $latest = DonationAllocationCategory::orderByDesc('alc_cat_id')->first();

            $nextNumber = 1;

            if ($latest) {
                $number = (int) str_replace('ALC-CAT-', '', $latest->alc_cat_id);
                $nextNumber = $number + 1;
            }

            $categoryId = 'ALC-CAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $category = DonationAllocationCategory::create([
                'alc_cat_id' => $categoryId,
                'alc_cat_name' => $request->new_category_name,
                'alc_cat_icon' => $request->new_category_icon ?? 'fas fa-heart',
                'alc_cat_color' => $request->new_category_color ?? '#554994',
                'alc_cat_is_active' => true,
            ]);

            $allocationCategoryId = $category->alc_cat_id;

        } else {

            $request->validate([
                'allocation_category_id' => 'required|exists:donation_allocation_categories,alc_cat_id',
            ]);

            $allocationCategoryId = $request->allocation_category_id;
        }

        /**
         * ======================================================
         * CHECK DUPLICATE
         * ======================================================
         */

        $exists = DonationAllocation::where('allocation_month', $request->allocation_month)
            ->where('allocation_category_id', $allocationCategoryId)
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->with('error', 'This category already exists for this month.');
        }

        /**
         * ======================================================
         * CHECK TOTAL PERCENT
         * ======================================================
         */

        $currentTotal = DonationAllocation::where('allocation_month', $request->allocation_month)
            ->sum('allocation_percent');

        if (($currentTotal + $request->allocation_percent) > 100) {

            return back()
                ->withInput()
                ->with('error', 'Total allocation percent cannot exceed 100%.');
        }

        /**
         * ======================================================
         * GENERATE ALLOCATION ID
         * ======================================================
         */

        $latestAllocation = DonationAllocation::orderByDesc('allocation_id')->first();

        $nextAllocationNumber = 1;

        if ($latestAllocation) {
            $number = (int) str_replace('ALLOC-', '', $latestAllocation->allocation_id);
            $nextAllocationNumber = $number + 1;
        }

        $allocationId = 'ALLOC-' . str_pad($nextAllocationNumber, 4, '0', STR_PAD_LEFT);

        /**
         * ======================================================
         * CALCULATE AMOUNT
         * ======================================================
         */

        $monthlyTotal = DonationAllocation::getMonthlyTotal($request->allocation_month);

        $allocationAmount = ($monthlyTotal * $request->allocation_percent) / 100;

        /**
         * ======================================================
         * STORE
         * ======================================================
         */

        DonationAllocation::create([
            'allocation_id' => $allocationId,
            'allocation_category_id' => $allocationCategoryId,
            'allocation_month' => $request->allocation_month,
            'allocation_percent' => $request->allocation_percent,
            'allocation_amount' => $allocationAmount,
            'allocation_changed_by' => auth()->user()->user_id,
            'allocation_notes' => $request->allocation_notes,
        ]);

        return redirect()
            ->route('admin.donations.allocations', [
                'month' => $request->allocation_month
            ])
            ->with('success', 'Allocation added successfully!');
    }

    /**
     * Update allocation
     */
    public function update(Request $request, $id)
    {
        $allocation = DonationAllocation::findOrFail($id);

        $request->validate([
            'allocation_month' => 'required|date_format:Y-m',
            'allocation_category_id' => 'required|exists:donation_allocation_categories,alc_cat_id',
            'allocation_percent' => 'required|numeric|min:0.01|max:100',
            'allocation_notes' => 'nullable|string|max:1000',
        ]);

        /**
         * CHECK DUPLICATE
         */

        $exists = DonationAllocation::where('allocation_month', $request->allocation_month)
            ->where('allocation_category_id', $request->allocation_category_id)
            ->where('allocation_id', '!=', $id)
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->with('error', 'This category already exists for this month.');
        }

        /**
         * CHECK TOTAL %
         */

        $currentTotal = DonationAllocation::where('allocation_month', $request->allocation_month)
            ->where('allocation_id', '!=', $id)
            ->sum('allocation_percent');

        if (($currentTotal + $request->allocation_percent) > 100) {

            return back()
                ->withInput()
                ->with('error', 'Total allocation cannot exceed 100%.');
        }

        /**
         * RECALCULATE AMOUNT
         */

        $monthlyTotal = DonationAllocation::getMonthlyTotal($request->allocation_month);

        $allocationAmount = ($monthlyTotal * $request->allocation_percent) / 100;

        /**
         * UPDATE
         */

        $allocation->update([
            'allocation_category_id' => $request->allocation_category_id,
            'allocation_month' => $request->allocation_month,
            'allocation_percent' => $request->allocation_percent,
            'allocation_amount' => $allocationAmount,
            'allocation_changed_by' => auth()->user()->user_id,
            'allocation_notes' => $request->allocation_notes,
        ]);

        return redirect()
            ->route('admin.donations.allocations', [
                'month' => $request->allocation_month
            ])
            ->with('success', 'Allocation updated successfully!');
    }

    /**
     * Delete allocation
     */
    public function destroy($id)
    {
        try {
            $allocation = DonationAllocation::findOrFail($id);
            $month = $allocation->allocation_month;
            $allocation->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Allocation deleted successfully!',
                    'month' => $month
                ]);
            }

            return redirect()
                ->route('admin.donations.allocations', [
                    'month' => $month
                ])
                ->with('success', 'Allocation deleted successfully!');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete allocation: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete allocation.');
        }
    }

    /**
     * Recalculate allocations
     */
    public function recalculate(Request $request)
    {
        $month = $request->month;

        if (!$month) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Month is required.'
                ], 400);
            }
            return back()->with('error', 'Month is required.');
        }

        try {
            DonationAllocation::recalculateMonth($month);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Allocations recalculated successfully for ' . $month . '!'
                ]);
            }

            return redirect()
                ->route('admin.donations.allocations', [
                    'month' => $month
                ])
                ->with('success', 'Allocations recalculated successfully!');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to recalculate: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to recalculate: ' . $e->getMessage());
        }
    }

    /**
     * AJAX Summary
     */
    public function getSummary($month)
    {
        return response()->json([
            'success' => true,
            'data' => DonationAllocation::getMonthSummary($month)
        ]);
    }
}