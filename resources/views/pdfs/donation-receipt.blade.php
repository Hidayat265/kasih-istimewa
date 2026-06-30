<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Donation Receipt - Kasih Istimewa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: #ffffff;
            padding: 40px;
            color: #1f2937;
        }
        
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 45px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        .text-right {
            text-align: right;
        }
        
        .mb-4 {
            margin-bottom: 16px;
        }
        
        .mb-6 {
            margin-bottom: 24px;
        }
        
        .mt-4 {
            margin-top: 16px;
        }
        
        .mt-2 {
            margin-top: 8px;
        }
        
        .mt-1 {
            margin-top: 4px;
        }
        
        .mt-3 {
            margin-top: 12px;
        }
        
        .pt-4 {
            padding-top: 16px;
        }
        
        .border-t {
            border-top: 1px solid #e5e7eb;
        }
        
        .border-gray-200 {
            border-color: #e5e7eb;
        }
        
        .text-sm {
            font-size: 14px;
        }
        
        .text-xs {
            font-size: 12px;
        }
        
        .font-normal {
            font-weight: 400;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }
        
        .text-gray-800 {
            color: #1f2937;
        }
        
        .text-gray-700 {
            color: #374151;
        }
        
        .text-gray-600 {
            color: #4b5563;
        }
        
        .text-gray-500 {
            color: #6b7280;
        }
        
        .text-gray-400 {
            color: #9ca3af;
        }
        
        .text-green-600 {
            color: #16a34a;
        }
        
        .text-primary {
            color: #554994;
        }
        
        .text-secondary {
            color: #CB80AB;
        }
        
        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        
        .table-container tr {
            border-bottom: 1px solid #f3f4f6;
        }
        
        .table-container tr:last-child {
            border-bottom: none;
        }
        
        .table-container td {
            padding: 10px 8px;
            font-size: 14px;
        }
        
        .table-container .label {
            color: #6b7280;
            width: 35%;
            font-weight: 400;
            padding-left: 0;
        }
        
        .table-container .value {
            color: #1f2937;
            text-align: left;
            width: 65%;
            font-weight: 500;
            padding-right: 0;
        }
        
        .total-row {
            border-top: 2px solid #554994 !important;
            border-bottom: none !important;
        }
        
        .total-row td {
            padding-top: 16px;
            padding-bottom: 4px;
        }
        
        .total-row .label {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
        }
        
        .total-row .value {
            font-size: 20px;
            font-weight: 700;
            color: #16a34a;
        }
        
        .divider {
            border: none;
            border-top: 1px dashed #d1d5db;
            margin: 16px 0 4px 0;
        }
        
        .status-text {
            font-weight: 500;
        }
        
        .status-text.success {
            color: #16a34a;
        }
        
        .status-text.pending {
            color: #d97706;
        }
        
        .status-text.failed {
            color: #dc2626;
        }
        
        .brand-logo {
            max-width: 200px;
            height: auto;
            display: inline-block;
        }
        
        .receipt-header {
            padding-bottom: 20px;
            border-bottom: 2px solid #554994;
            margin-bottom: 20px;
        }
        
        .receipt-footer {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f3f5 100%);
            border-radius: 12px;
            padding: 24px 28px;
            margin-top: 20px;
            border: 1px solid #ffffff;
        }
        
        .receipt-footer .thank-you {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            letter-spacing: 0.3px;
        }
        
        .receipt-footer .sub-text {
            font-size: 12px;
            color: #6b7280;
            font-weight: 400;
            line-height: 1.6;
        }
        
        .receipt-footer .brand-small {
            max-width: 140px;
            height: auto;
            display: inline-block;
            margin-top: 8px;
        }
        
        .receipt-footer .footer-divider {
            width: 60px;
            height: 2px;
            background: #554994;
            margin: 12px auto;
            border-radius: 2px;
        }
        
        .receipt-footer .contact-info {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 8px;
            letter-spacing: 0.5px;
        }
        
        .receipt-footer .contact-info span {
            margin: 0 6px;
        }
        
        @media print {
            body {
                padding: 20px;
                background: #ffffff;
            }
            .receipt-container {
                box-shadow: none;
                border: 1px solid #ffffff;
            }
            .receipt-footer {
                background: #f8fafc;
                border: 1px solid #e5e7eb;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="text-center receipt-header">
            <img src="{{ public_path('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" class="brand-logo">
            <p class="text-gray-500 text-sm mt-2" style="font-weight: 400;">Official Donation Receipt</p>
        </div>
        
        <!-- Donation Details Table -->
        <table class="table-container">
            <tr>
                <td class="label">Receipt No</td>
                <td class="value">{{ $donation->donation_id }}</td>
            </tr>
            <tr>
                <td class="label">Date</td>
                <td class="value">{{ $donation->updated_at ? \Carbon\Carbon::parse($donation->updated_at)->format('d F Y, h:i A') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Donor Name</td>
                <td class="value">{{ $donation->donor_name ?? 'Anonymous' }}</td>
            </tr>
            <tr>
                <td class="label">Donor Email</td>
                <td class="value">{{ $donation->donor_email ?? 'Not provided' }}</td>
            </tr>
            <tr>
                <td class="label">Phone Number</td>
                <td class="value">{{ $donation->donor_phone ?? 'Not provided' }}</td>
            </tr>
            <tr>
                <td class="label">Payment Method</td>
                <td class="value">{{ ucfirst($donation->donation_payment_method ?? 'Not provided') }}</td>
            </tr>
            <tr>
                <td class="label">Received By</td>
                <td class="value">
                    @if($donation->donation_received_by)
                        @php
                            $admin = \App\Models\User::where('user_id', $donation->donation_received_by)->first();
                        @endphp
                        @if($admin)
                            {{ $admin->user_name }} ({{ $admin->user_id }})
                        @else
                            {{ $donation->donation_received_by }}
                        @endif
                    @else
                        <span style="color: #9ca3af;">Not provided</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="value">
                    <span class="status-text {{ $donation->donation_status === 'success' ? 'success' : ($donation->donation_status === 'pending' ? 'pending' : 'failed') }}">
                        {{ ucfirst($donation->donation_status ?? 'Success') }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Transaction ID</td>
                <td class="value" style="font-family: 'Courier New', monospace;">{{ $donation->transaction_id ?? 'N/A' }}</td>
            </tr>
            <tr class="total-row">
                <td class="label">Total Donation</td>
                <td class="value">RM {{ number_format($donation->donation_amount, 2) }}</td>
            </tr>
        </table>
        
        <!-- Footer -->
        <div class="receipt-footer text-center">
            <p class="thank-you">Thank You for Your Generous Donation!</p>
            
            <div class="footer-divider"></div>

            <img src="{{ public_path('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" class="brand-small">
            
            <p class="sub-text" style="margin-top: 10px;">Your contribution helps us make a meaningful difference<br>in our community through compassion and action.</p>
            
            
            
            <p class="sub-text" style="font-size: 11px; margin-top: 50px;">
                This receipt is electronically generated and does not require a signature.
            </p>
            
            <p class="contact-info">
                <span>•</span>
                <span> support@kasihistimewa.my</span>
                <span>•</span>
                <span> www.kasihistimewa.com</span>
                <span>•</span>
            </p>
        </div>
    </div>
</body>
</html>