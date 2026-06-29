<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Report - Kasih Istimewa</title>

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
                    <p class="text-xl font-bold text-gray-900">Administrator Report</p>
                    
                </div>
                <div class="text-right text-sm text-gray-600">
                    <p>Report ID: ADM-REP-{{ date('YmdHis') }}</p>
                    <p>Generated: {{ now()->format('d M Y H:i') }}</p>
                    <p>Generated By: {{ auth()->user()->user_name ?? 'System' }}</p>
                </div>
            </div>
        </div>

        {{-- EXECUTIVE SUMMARY --}}
        <div class="p-8 executive-summary">
            <h2 class="text-lg font-semibold mb-5">Executive Summary</h2>
            <div class="grid grid-cols-2 gap-6">
                <div class="summary-card">
                    <div class="summary-title">Administrator Overview</div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Total Administrators</span>
                            <strong>{{ $totalAdmins ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Active Administrators</span>
                            <strong>{{ $activeAdmins ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Inactive Administrators</span>
                            <strong>{{ $inactiveAdmins ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>New Administrators (This Month)</span>
                            <strong>{{ $newAdminsCount ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-title">Activity Metrics</div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Total Administrator Actions</span>
                            <strong>{{ $totalActions ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Events Approved</span>
                            <strong>{{ $approvedEvents ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Events Rejected</span>
                            <strong>{{ $rejectedEvents ?? 0 }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Events Requested Update</span>
                            <strong>{{ $updateRequests ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ADMIN LIST --}}
        <div class="px-8 pb-8">
            <h2 class="text-lg font-semibold mb-4">Administrator List</h2>
            <table class="table-report">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Admin ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date of Birth</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins ?? [] as $index => $admin)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $admin->user_id }}</td>
                        <td>{{ $admin->user_name }}</td>
                        <td>{{ $admin->user_email }}</td>
                        <td>{{ $admin->user_phone_number ?? '-' }}</td>
                        <td>{{ $admin->user_dob ? \Carbon\Carbon::parse($admin->user_dob)->format('d M Y') : '-' }}</td>
                        <td>
                            <span class="">
                                {{ ucfirst($admin->user_status ?? 'Inactive') }}
                            </span>
                        </td>
                        <td>{{ $admin->created_at ? \Carbon\Carbon::parse($admin->created_at)->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            No admins found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="grand-total-row">
                        <td colspan="2"><strong>TOTAL</strong></td>
                        <td colspan="6">{{ $totalAdmins ?? 0 }} Administrators</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="p-8 brand-footer">
            <div class="flex justify-between text-xs text-gray-500">
                <span>
                    Generated by <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:10px; display:inline-block; vertical-align:baseline;"> Administrator Management System
                </span>
                <span>
                    Report ID: ADM-REP-{{ date('YmdHis') }}
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