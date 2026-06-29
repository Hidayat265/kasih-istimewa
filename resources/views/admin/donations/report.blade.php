<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monetary Report - Kasih Istimewa</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body{
            background:#f5f5f5;
            font-family:'Segoe UI',sans-serif;
        }

        .report-container{
            background:#fff;
        }

        .table-report{
            width:100%;
            border-collapse:collapse;
        }

        .table-report th{
            background:#f3f4f6;
            font-size:12px;
            text-transform:uppercase;
            letter-spacing:.5px;
            font-weight:600;
        }

        .table-report th,
        .table-report td{
            border:1px solid #d1d5db;
            padding:12px;
        }

        .table-report tbody tr:nth-child(even){
            background:#fafafa;
        }

        .summary-card{
            border:1px solid #e5e7eb;
            border-radius:8px;
            padding:20px;
            background:white;
        }

        .summary-title{
            font-size:12px;
            text-transform:uppercase;
            color:#6b7280;
            font-weight:600;
            margin-bottom:15px;
        }

        .print-btn{
            background:#111827;
            color:white;
            padding:12px 24px;
            border-radius:6px;
            font-weight:600;
        }

        .close-btn{
            background:#6b7280;
            color:white;
            padding:12px 24px;
            border-radius:6px;
            font-weight:600;
        }

        .month-header {
            background-color: #f3f4f6;
            font-weight: 700;
        }

        .month-header td {
            padding: 10px 12px;
            border-bottom: 2px solid #d1d5db;
        }

        .subtotal-row td {
            background-color: #eff6ff;
            font-weight: 600;
        }

        .grand-total-row td {
            background-color: #1f2937;
            color: white;
            font-weight: 700;
        }

        .unallocated-row td {
            background-color: #fffbeb;
            color: #d97706;
            font-weight: 600;
        }

        /* Kasih Istimewa Brand Colors - ONLY FOR ORGANIZATION NAME */
        .kasih-primary {
            color: #554994;
        }

        .kasih-secondary {
            color: #CB80AB;
        }

        .brand-header {
            border-bottom: 3px solid #e5e7eb;
        }

        .brand-footer {
            border-top: 2px solid #e5e7eb;
        }

        /* ======================================== */
        /* PRINT STYLES - KEEP ALL COLORS */
        /* ======================================== */
        @media print {

            body{
                background:white !important;
                padding:0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .no-print{
                display:none !important;
            }

            .report-container{
                max-width:100%;
                border:none !important;
                box-shadow:none !important;
            }

            thead{
                display:table-header-group;
            }

            tfoot {
                display: table-row-group !important;
            }

            tr{
                page-break-inside:avoid;
            }

            table{
                page-break-inside:auto;
            }

            /* KEEP ALL COLORS IN PRINT */
            .month-header {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .subtotal-row td {
                background-color: #eff6ff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .grand-total-row td {
                background-color: #1f2937 !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .unallocated-row td {
                background-color: #fffbeb !important;
                color: #d97706 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .kasih-primary {
                color: #554994 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .kasih-secondary {
                color: #CB80AB !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .brand-header {
                border-bottom: 3px solid #e5e7eb !important;
            }

            .brand-footer {
                border-top: 2px solid #e5e7eb !important;
            }

            .summary-card {
                border: 1px solid #e5e7eb !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .table-report th {
                background: #f3f4f6 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .table-report tbody tr:nth-child(even) {
                background: #fafafa !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* ======================================== */
            /* PAGE BREAKS */
            /* ======================================== */
            .page-break-payment {
                page-break-before: always !important;
            }

            .page-break-allocation {
                page-break-before: always !important;
            }

            .page-break-records {
                page-break-before: always !important;
            }
        }
    </style>
</head>
<body class="p-6">

    {{-- BUTTONS --}}
    <div class="no-print text-center mb-6">

        <button onclick="window.print()" class="print-btn">
            Print / Download PDF
        </button>

        <button onclick="window.close()" class="close-btn ml-3">
            Close
        </button>

    </div>

    <div class="max-w-7xl mx-auto report-container border shadow-sm">

        {{-- HEADER --}}
        <div class="p-8 brand-header">

            <div class="flex justify-between">

                <div>

                    <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:25px; margin-bottom:10px;">

                    <p class="text-xl font-bold text-gray-900">
                        Monetary Report
                    </p>

                    <p class="text-lg text-gray-500 mt-3">

                        Reporting Period:

                        @if($period === 'custom')

                            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                            -
                            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}

                        @elseif($period !== 'all')

                            {{ ucfirst(str_replace('_',' ',$period)) }}

                        @else

                            All Time

                        @endif

                    </p>

                </div>

                <div class="text-right text-sm text-gray-600">

                    <p>
                        Report ID:
                        MON-REP-{{ date('Ymd') }}
                    </p>

                    <p>
                        Generated:
                        {{ now()->format('d M Y H:i') }}
                    </p>

                    <p>
                        Generated By:
                        {{ auth()->user()->user_name ?? 'System' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- PAGE 1: REPORT INFORMATION & EXECUTIVE SUMMARY --}}
        <div class="p-8 border-b">

            <h2 class="text-lg font-semibold mb-4">
                Report Information
            </h2>

            <div class="grid grid-cols-2 gap-8 text-sm">

                <div>
                    <p><strong>Organization:</strong> <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:11px; display:inline-block; vertical-align:baseline;"></p>
                    <p><strong>Report Type:</strong> Monetary Report</p>
                    <p><strong>Total Records:</strong> {{ $report['totalRecords'] }}</p>
                </div>

                <div>
                    <p><strong>Successful Donations:</strong> {{ $report['successfulCount'] }}</p>
                    <p><strong>Pending Donations:</strong> {{ $report['pendingCount'] }}</p>
                    <p><strong>Success Rate:</strong> {{ $report['successRate'] }}%</p>
                </div>

            </div>

        </div>

        <div class="p-8 executive-summary">

            <h2 class="text-lg font-semibold mb-5">
                Executive Summary
            </h2>

            <div class="grid grid-cols-2 gap-6">

                <div class="summary-card">

                    <div class="summary-title">
                        Donation Metrics
                    </div>

                    <div class="space-y-3">

                        <div class="flex justify-between">
                            <span>Total Donations</span>
                            <strong>{{ $report['totalRecords'] }}</strong>
                        </div>

                        <div class="flex justify-between">
                            <span>Successful</span>
                            <strong>{{ $report['successfulCount'] }}</strong>
                        </div>

                        <div class="flex justify-between">
                            <span>Pending</span>
                            <strong>{{ $report['pendingCount'] }}</strong>
                        </div>

                        <div class="flex justify-between">
                            <span>Success Rate</span>
                            <strong>{{ $report['successRate'] }}%</strong>
                        </div>

                    </div>

                </div>

                <div class="summary-card">

                    <div class="summary-title">
                        Financial Metrics
                    </div>

                    <div class="space-y-3">

                        <div class="flex justify-between">
                            <span>Total Revenue</span>
                            <strong>RM {{ number_format($report['totalAmount'],2) }}</strong>
                        </div>

                        <div class="flex justify-between">
                            <span>Cash Revenue</span>
                            <strong>RM {{ number_format($report['cashTotal'],2) }}</strong>
                        </div>

                        <div class="flex justify-between">
                            <span>Online Revenue</span>
                            <strong>RM {{ number_format($report['onlineTotal'],2) }}</strong>
                        </div>

                        <div class="flex justify-between">
                            <span>Average Donation</span>
                            <strong>RM {{ number_format($report['averageDonation'],2) }}</strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- PAGE 2: PAYMENT METHOD BREAKDOWN --}}
        <div class="page-break-payment px-8 pb-8">

            <h2 class="text-lg font-semibold mb-4">
                Payment Method Breakdown
            </h2>

            <table class="table-report">

                <thead>
                    <tr>
                        <th>Method</th>
                        <th>Count</th>
                        <th>Amount</th>
                        <th>Pending Count</th>
                        <th>Pending Amount</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>Cash</td>
                        <td class="text-center">{{ $report['cashCount'] }}</td>
                        <td class="text-right">
                            RM {{ number_format($report['cashTotal'],2) }}
                        </td>
                        <td class="text-center">{{ $report['cashPendingCount'] ?? 0 }}</td>
                        <td class="text-right">
                            RM {{ number_format($report['cashPendingAmount'] ?? 0,2) }}
                        </td>
                    </tr>

                    <tr>
                        <td>Online</td>
                        <td class="text-center">{{ $report['onlineCount'] }}</td>
                        <td class="text-right">
                            RM {{ number_format($report['onlineTotal'],2) }}
                        </td>
                        <td class="text-center">{{ $report['onlinePendingCount'] ?? 0 }}</td>
                        <td class="text-right">
                            RM {{ number_format($report['onlinePendingAmount'] ?? 0,2) }}
                        </td>
                    </tr>

                </tbody>

                <tfoot>
                    <tr class="grand-total-row">
                        <td><strong>TOTAL</strong></td>
                        <td class="text-center">{{ $report['cashCount'] + $report['onlineCount'] }}</td>
                        <td class="text-right">RM {{ number_format($report['totalAmount'],2) }}</td>
                        <td class="text-center">{{ $report['pendingCount'] }}</td>
                        <td class="text-right">RM {{ number_format($report['pendingAmount'] ?? 0,2) }}</td>
                    </tr>
                </tfoot>

            </table>

        </div>

        {{-- PAGE 3: ALLOCATION BREAKDOWN --}}
        @if(isset($allocationData) && $allocationData->isNotEmpty())
        <div class="page-break-allocation px-8 pb-8">
            <h2 class="text-lg font-semibold mb-4">
                Allocation Breakdown
            </h2>

            <table class="table-report">
                <thead>
                    <tr>
                        <th class="text-left">Month / Category</th>
                        <th class="text-center">Allocation %</th>
                        <th class="text-right">Amount (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotalPercent = 0;
                        $grandTotalAmount = 0;
                        $monthCount = 0;
                        $totalUnallocatedAmount = 0;
                    @endphp

                    @foreach($allocationData as $month => $monthData)
                        @php
                            $monthTotalPercent = $monthData['total_percent'];
                            $monthTotalAmount = $monthData['total_amount'];
                            $monthlyDonations = $monthData['monthly_donations'];
                            $unallocatedAmount = $monthlyDonations - $monthTotalAmount;
                            
                            $grandTotalPercent += $monthTotalPercent;
                            $grandTotalAmount += $monthTotalAmount;
                            $totalUnallocatedAmount += $unallocatedAmount;
                            $monthCount++;
                        @endphp
                        <tr class="month-header">
                            <td colspan="3">
                                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</strong>
                                <span class="text-sm text-gray-500 ml-2">
                                    ({{ $monthData['categories']->count() }} categories)
                                </span>
                            </td>
                        </tr>
                        @foreach($monthData['categories'] as $category)
                            <tr>
                                <td class="pl-8">
                                    {{ $category['name'] }}
                                </td>
                                <td class="text-center">{{ number_format($category['percent'], 2) }}%</td>
                                <td class="text-right">RM {{ number_format($category['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="subtotal-row">
                            <td class="text-right pr-4">Subtotal for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                            <td class="text-center">{{ number_format($monthTotalPercent, 2) }}%</td>
                            <td class="text-right">RM {{ number_format($monthTotalAmount, 2) }}</td>
                        </tr>
                        @if($monthTotalPercent < 100)
                            <tr class="unallocated-row">
                                <td class="text-right pr-4">Unallocated Balance</td>
                                <td class="text-center">{{ number_format(100 - $monthTotalPercent, 2) }}%</td>
                                <td class="text-right">RM {{ number_format($unallocatedAmount, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $avgPercent = $monthCount > 0 ? $grandTotalPercent / $monthCount : 0;
                    @endphp
                    <tr class="grand-total-row">
                        <td class="text-right pr-4">GRAND TOTAL</td>
                        <td class="text-center">{{ number_format($avgPercent, 2) }}% (Avg)</td>
                        <td class="text-right">RM {{ number_format($grandTotalAmount, 2) }}</td>
                    </tr>
                    @if($avgPercent < 100)
                        <tr class="unallocated-row">
                            <td class="text-right pr-4">Total Unallocated Balance</td>
                            <td class="text-center">{{ number_format(100 - $avgPercent, 2) }}% (Avg)</td>
                            <td class="text-right">RM {{ number_format($totalUnallocatedAmount, 2) }}</td>
                        </tr>
                    @endif
                </tfoot>
            </table>
        </div>
        @endif

        {{-- PAGE 4: DONATION RECORDS --}}
        <div class="page-break-records px-8 pb-8">

            <h2 class="text-lg font-semibold mb-4">
                Donation Records
            </h2>

            <table class="table-report">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Donation ID</th>
                        <th>Donor Details</th>
                        <th>Amount (RM)</th>
                        <th>Method</th>
                        <th>Received By</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                @php
                    $sortedDonations = $donations->sortBy('donation_id');
                @endphp

                @foreach($sortedDonations as $index => $donation)

                @php
                    $receivedBy = $donation->donation_received_by;
                    $displayName = $receivedBy;
                    $adminId = null;

                    if (
                        is_string($receivedBy) &&
                        !in_array(strtolower($receivedBy), ['toyyibpay', 'stripe'])
                    ) {

                        $admin = \App\Models\User::where(
                            'user_id',
                            $receivedBy
                        )->first();

                        if ($admin) {
                            $displayName = $admin->user_name;
                            $adminId = $admin->user_id;
                        }

                    } else {

                        $displayName = match(strtolower($receivedBy ?? '')) {
                            'toyyibpay' => 'ToyyibPay',
                            'stripe'    => 'Stripe',
                            default     => $receivedBy ?? '-'
                        };

                    }
                @endphp

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $donation->donation_id }}
                    </td>

                    <td>

                        <div class="font-medium text-gray-900">
                            {{ $donation->donor_name }}
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ $donation->donor_email }}
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ $donation->donor_phone ?? '-' }}
                        </div>

                    </td>

                    <td class="text-right">
                        {{ number_format($donation->donation_amount, 2) }}
                    </td>

                    <td class="text-center">
                        {{ ucfirst($donation->donation_payment_method) }}
                    </td>

                    <td class="text-center">
                        {{ $displayName }}
                        @if($adminId)
                            <div class="text-xs text-gray-400">
                                (ID: {{ $adminId }})
                            </div>
                        @endif
                    </td>
                    
                    <td>
                        {{ $donation->donation_transaction_id ?? '-' }}
                    </td>

                    <td class="text-center">

                        @if($donation->donation_status === 'success')

                            <span class="font-semibold text-green-700">
                                Success
                            </span>

                        @elseif($donation->donation_status === 'pending')

                            <span class="font-semibold text-yellow-700">
                                Pending
                            </span>

                        @else

                            <span class="font-semibold text-red-700">
                                Failed
                            </span>

                        @endif

                    </td>

                    <td class="text-center">
                        {{ $donation->created_at->format('d M Y H:i') }}
                    </td>

                </tr>

                @endforeach

                </tbody>

                <tfoot>
                    <tr class="grand-total-row">
                        <td colspan="3" class="text-right">
                            TOTAL
                        </td>
                        <td class="text-right">
                            RM {{ number_format($report['totalAmount'],2) }}
                        </td>
                        <td colspan="2"></td>
                        <td></td>
                        <td class="text-center">
                            <span class="text-white">Pending: {{ $report['pendingCount'] }}</span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>

            </table>

        </div>

        {{-- FOOTER --}}
        <div class="p-8 brand-footer">

            <div class="flex justify-between text-xs text-gray-500">

                <span>
                    Generated by <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:10px; display:inline-block; vertical-align:baseline;"> Donation Management System
                </span>

                <span>
                    Report ID: MON-REP-{{ date('Ymd') }}
                </span>

            </div>

            <div class="text-center text-xs text-gray-400 mt-2">

                Confidential Internal Report

                •

                © {{ date('Y') }} <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:10px; display:inline-block; vertical-align:baseline;">

            </div>

        </div>

    </div>

</body>
</html>