<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Events Report - Kasih Istimewa</title>

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
            cursor:pointer;
            border:none;
        }

        .close-btn{
            background:#6b7280;
            color:white;
            padding:12px 24px;
            border-radius:6px;
            font-weight:600;
            cursor:pointer;
            border:none;
        }

        .grand-total-row td {
            background-color: #1f2937;
            color: white;
            font-weight: 700;
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

        .status-badge {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            background: #f3f4f6;
            color: #1f2937;
            border: 1px solid #d1d5db;
        }

        /* Kasih Istimewa Brand Colors */
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

            .grand-total-row td {
                background-color: #1f2937 !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

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

            .status-badge {
                background: #f3f4f6 !important;
                color: #1f2937 !important;
                border: 1px solid #d1d5db !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .page-break-summary {
                page-break-before: always !important;
            }

            .page-break-events {
                page-break-before: always !important;
            }

            .page-break-monthly {
                page-break-before: always !important;
            }
        }
    </style>
</head>
<body class="p-6">

    {{-- BUTTONS --}}
    <div class="no-print text-center mb-6">
        <button onclick="window.print()" class="print-btn">
            <i class="fas fa-print mr-2"></i> Print / Download PDF
        </button>
        <button onclick="window.close()" class="close-btn ml-3">
            <i class="fas fa-times mr-2"></i> Close
        </button>
    </div>

    <div class="max-w-7xl mx-auto report-container border shadow-sm">

        {{-- HEADER --}}
        <div class="p-8 brand-header">
            <div class="flex justify-between">
                <div>
                    <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:25px; margin-bottom:10px;">
                    <p class="text-xl font-bold text-gray-900">Past Events Report</p>
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
                    <p>Report ID: EVT-REP-{{ date('Ymd') }}</p>
                    <p>Generated: {{ now()->format('d M Y H:i') }}</p>
                    <p>Generated By: {{ auth()->user()->user_name ?? 'System' }}</p>
                </div>
            </div>
        </div>

        {{-- REPORT INFORMATION --}}
        <div class="p-8 border-b">
            <h2 class="text-lg font-semibold mb-4">Report Information</h2>
            <div class="grid grid-cols-2 gap-8 text-sm">
                <div>
                    <p><strong>Organization:</strong> <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:11px; display:inline-block; vertical-align:baseline;"></p>
                    <p><strong>Report Type:</strong> Past Events Report</p>
                    <p><strong>Total Events:</strong> {{ $report['total'] }}</p>
                </div>
                <div>
                    <p><strong>Successful:</strong> {{ $report['successful'] ?? 0 }}</p>
                    <p><strong>Unsuccessful:</strong> {{ $report['unsuccessful'] ?? 0 }}</p>
                    <p><strong>Rejected:</strong> {{ $report['rejected'] ?? 0 }}</p>
                    <p><strong>Unknown:</strong> {{ $report['unknown'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- EXECUTIVE SUMMARY --}}
        <div class="p-8 executive-summary">
            <h2 class="text-lg font-semibold mb-5">Executive Summary</h2>
            <div class="grid grid-cols-2 gap-6">
                <div class="summary-card">
                    <div class="summary-title">Event Status Overview</div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Total Events</span>
                            <strong>{{ $report['total'] }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Successful</span>
                            <strong>{{ $report['successful'] ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Unsuccessful</span>
                            <strong>{{ $report['unsuccessful'] ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Rejected</span>
                            <strong>{{ $report['rejected'] ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Unknown</span>
                            <strong>{{ $report['unknown'] ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-title">Event Metrics</div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Total Participants</span>
                            <strong>{{ $report['total_participants'] ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Capacity</span>
                            <strong>{{ $report['total_capacity'] ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Average Participants per Event</span>
                            <strong>{{ number_format($report['average_participants'] ?? 0, 1) }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Unique Organizers</span>
                            <strong>{{ $report['unique_organizers'] ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATUS BREAKDOWN --}}
        <div class="page-break-summary px-8 pb-8">
            <h2 class="text-lg font-semibold mb-4">Event Status Breakdown</h2>
            <table class="table-report">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th class="text-center">Count</th>
                        <th class="text-right">Percentage</th>
                        <th class="text-center">Total Participants</th>
                        <th class="text-center">Total Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statuses = [
                            'Successful' => ['count' => $report['successful'] ?? 0, 'participants' => 0, 'capacity' => 0],
                            'Unsuccessful' => ['count' => $report['unsuccessful'] ?? 0, 'participants' => 0, 'capacity' => 0],
                            'Rejected' => ['count' => $report['rejected'] ?? 0, 'participants' => 0, 'capacity' => 0],
                            'Unknown' => ['count' => $report['unknown'] ?? 0, 'participants' => 0, 'capacity' => 0],
                        ];

                        foreach($events as $event) {
                            $status = $event->event_status ?? 'Unknown';
                            if (isset($statuses[$status])) {
                                $statuses[$status]['participants'] += $event->event_current_participant ?? 0;
                                $statuses[$status]['capacity'] += $event->event_maximum_participant ?? 0;
                            }
                        }

                        $total = $report['total'] ?: 1;
                    @endphp

                    @foreach($statuses as $status => $data)
                        <tr>
                            <td>
                                <span class="">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="text-center">{{ $data['count'] }}</td>
                            <td class="text-right">{{ number_format(($data['count'] / $total) * 100, 1) }}%</td>
                            <td class="text-center">{{ $data['participants'] }}</td>
                            <td class="text-center">{{ $data['capacity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="grand-total-row">
                        <td><strong>TOTAL</strong></td>
                        <td class="text-center">{{ $report['total'] }}</td>
                        <td class="text-right">100%</td>
                        <td class="text-center">{{ $report['total_participants'] ?? 0 }}</td>
                        <td class="text-center">{{ $report['total_capacity'] ?? 0 }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- MONTHLY BREAKDOWN --}}
        @php
            $monthlyData = [];
            foreach($events as $event) {
                // Use event_start_date instead of created_at
                $month = \Carbon\Carbon::parse($event->event_start_date)->format('Y-m');
                if (!isset($monthlyData[$month])) {
                    $monthlyData[$month] = [
                        'total' => 0,
                        'successful' => 0,
                        'unsuccessful' => 0,
                        'rejected' => 0,
                        'unknown' => 0,
                        'participants' => 0,
                        'capacity' => 0
                    ];
                }
                $monthlyData[$month]['total']++;
                $status = $event->event_status ?? 'Unknown';
                if ($status === 'Successful') {
                    $monthlyData[$month]['successful']++;
                } elseif ($status === 'Unsuccessful') {
                    $monthlyData[$month]['unsuccessful']++;
                } elseif ($status === 'Rejected') {
                    $monthlyData[$month]['rejected']++;
                } else {
                    $monthlyData[$month]['unknown']++;
                }
                $monthlyData[$month]['participants'] += $event->event_current_participant ?? 0;
                $monthlyData[$month]['capacity'] += $event->event_maximum_participant ?? 0;
            }

            // Sort months in descending order (newest first)
            krsort($monthlyData);
        @endphp

        @if(!empty($monthlyData))
        <div class="page-break-monthly px-8 pb-8">
            <h2 class="text-lg font-semibold mb-4">Monthly Event Breakdown</h2>
            <table class="table-report">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-center">Total Events</th>
                        <th class="text-center">Successful</th>
                        <th class="text-center">Unsuccessful</th>
                        <th class="text-center">Rejected</th>
                        <th class="text-center">Unknown</th>
                        <th class="text-center">Participants</th>
                        <th class="text-center">Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotal = 0;
                        $grandSuccessful = 0;
                        $grandUnsuccessful = 0;
                        $grandRejected = 0;
                        $grandUnknown = 0;
                        $grandParticipants = 0;
                        $grandCapacity = 0;
                    @endphp

                    @foreach($monthlyData as $month => $data)
                        @php
                            $grandTotal += $data['total'];
                            $grandSuccessful += $data['successful'];
                            $grandUnsuccessful += $data['unsuccessful'];
                            $grandRejected += $data['rejected'];
                            $grandUnknown += $data['unknown'];
                            $grandParticipants += $data['participants'];
                            $grandCapacity += $data['capacity'];
                        @endphp
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</strong></td>
                            <td class="text-center">{{ $data['total'] }}</td>
                            <td class="text-center">{{ $data['successful'] }}</td>
                            <td class="text-center">{{ $data['unsuccessful'] }}</td>
                            <td class="text-center">{{ $data['rejected'] }}</td>
                            <td class="text-center">{{ $data['unknown'] }}</td>
                            <td class="text-center">{{ $data['participants'] }}</td>
                            <td class="text-center">{{ $data['capacity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="grand-total-row">
                        <td><strong>TOTAL</strong></td>
                        <td class="text-center">{{ $grandTotal }}</td>
                        <td class="text-center">{{ $grandSuccessful }}</td>
                        <td class="text-center">{{ $grandUnsuccessful }}</td>
                        <td class="text-center">{{ $grandRejected }}</td>
                        <td class="text-center">{{ $grandUnknown }}</td>
                        <td class="text-center">{{ $grandParticipants }}</td>
                        <td class="text-center">{{ $grandCapacity }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        {{-- EVENT RECORDS --}}
        <div class="page-break-events px-8 pb-8">
            <h2 class="text-lg font-semibold mb-4">Event Records</h2>
            <table class="table-report">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event ID</th>
                        <th>Event Name</th>
                        <th>Organizer</th>
                        <th>Start Date / Session</th>
                        <th>End Date / Session</th>
                        <th class="text-center">Participants / Capacity</th>
                        <th class="text-center">Status</th>
                        <th>Approver</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($events as $index => $event)
                @php
                    $approver = $event->approver ? $event->approver->user_name : 'N/A';
                    $approverId = $event->event_approver_id ?? 'N/A';
                    $startSession = $event->event_start_session ?? '';
                    $endSession = $event->event_end_session ?? '';
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $event->event_id }}</td>
                    <td>
                        <div class="font-medium text-gray-900">{{ $event->event_name }}</div>
                        <div class="text-xs text-gray-500">{{ $event->event_location_name ?? '-' }}</div>
                    </td>
                    <td>
                        <div>{{ $event->event_company_name }}</div>
                        <div class="text-xs text-gray-400">
                            {{ $event->creator->user_name ?? 'Unknown' }}
                            @if($event->event_created_by_id)
                                (ID: {{ $event->event_created_by_id }})
                            @endif
                        </div>
                    </td>
                    <td>
                        <div>{{ \Carbon\Carbon::parse($event->event_start_date)->format('d M Y') }}</div>
                        @if($startSession)
                            <div class="text-xs text-gray-500">{{ $startSession }}</div>
                        @endif
                    </td>
                    <td>
                        <div>{{ \Carbon\Carbon::parse($event->event_end_date)->format('d M Y') }}</div>
                        @if($endSession)
                            <div class="text-xs text-gray-500">{{ $endSession }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $event->event_current_participant ?? 0 }} / {{ $event->event_maximum_participant ?? 0 }}
                    </td>
                    <td class="text-center">
                        <span class="">
                            {{ $event->event_status ?? 'Unknown' }}
                        </span>
                    </td>
                    <td>
                        <div>{{ $approver }}</div>
                        @if($approverId && $approverId !== 'N/A')
                            <div class="text-xs text-gray-400">ID: {{ $approverId }}</div>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr class="grand-total-row">
                        <td colspan="2" class="text-right">TOTAL</td>
                        <td colspan="2">{{ $report['total'] }} Events</td>
                        <td colspan="2"></td>
                        <td class="text-center">{{ $report['total_participants'] ?? 0 }} / {{ $report['total_capacity'] ?? 0 }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="p-8 brand-footer">
            <div class="flex justify-between text-xs text-gray-500">
                <span>
                    Generated by 
                    <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:10px; display:inline-block; vertical-align:baseline;"> 
                    Event Management System
                </span>
                <span>
                    Report ID: EVT-REP-{{ date('Ymd') }}
                </span>
            </div>
            <div class="text-center text-xs text-gray-400 mt-2">
                Confidential Internal Report
                •
                © {{ date('Y') }} <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:10px; display:inline-block; vertical-align:baseline;">
            </div>
        </div>

    </div>

    {{-- Font Awesome for icons --}}
    <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
</body>
</html>