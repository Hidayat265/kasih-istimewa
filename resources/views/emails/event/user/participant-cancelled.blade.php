<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Cancelled - Kasih Istimewa</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #554994 0%, #CB80AB 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 8px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 35px 30px;
        }
        .content h2 {
            color: #1f2937;
            font-size: 22px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .content p {
            color: #4b5563;
            line-height: 1.7;
            margin: 10px 0;
        }
        .event-details {
            background: #f9fafb;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #ef4444;
        }
        .event-details h3 {
            color: #ef4444;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
        }
        .event-details p {
            margin: 8px 0;
            font-size: 14px;
        }
        .event-details strong {
            color: #1f2937;
        }
        .status-badge {
            display: inline-block;
            background: #ef4444;
            color: white;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .reason-box {
            background: #fef3c7;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 15px 0;
            border-left: 4px solid #f59e0b;
        }
        .reason-box strong {
            color: #92400e;
            display: block;
            margin-bottom: 5px;
        }
        .reason-box p {
            margin: 0;
            color: #78350f;
            font-size: 14px;
        }
        .info-box {
            background: #eff6ff;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 15px 0;
            border-left: 4px solid #3b82f6;
        }
        .info-box strong {
            color: #1e40af;
            display: block;
            margin-bottom: 5px;
        }
        .info-box p {
            margin: 0;
            color: #1e3a5f;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #554994;
            color: #ffffff !important;
            padding: 12px 32px;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0 10px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }
        .button:hover {
            background: #453a7a;
        }
        .footer {
            background: #f8fafc;
            padding: 25px 30px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer strong {
            color: #64748b;
        }
        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 20px 0;
        }
        .cancelled-by {
            font-size: 13px;
            color: #6b7280;
            margin-top: 10px;
        }
        .cancelled-by strong {
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Kasih Istimewa</h1>
            <p>Registration Cancelled</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <h2>Hello {{ $participant->user_name }},</h2>
            
            <p>We regret to inform you that your registration for the following event has been <strong>cancelled</strong>.</p>
            
            <!-- Event Details -->
            <div class="event-details">
                <h3>📋 Event Details</h3>
                <p><strong>Event:</strong> {{ $event->event_name }}</p>
                <p><strong>Organizer:</strong> {{ $event->event_company_name }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format('d F Y') }}</p>
                <p><strong>Location:</strong> {{ $event->event_location_name ?? 'To be confirmed' }}</p>
                <p><strong>Status:</strong> <span class="status-badge">Cancelled</span></p>
            </div>

            <!-- Reason (if provided) -->
            @if($reason)
                <div class="reason-box">
                    <strong>📝 Reason for Cancellation</strong>
                    <p>{{ $reason }}</p>
                </div>
            @endif

            <!-- Cancelled By -->
            @if($cancelledBy)
                <div class="cancelled-by">
                    This cancellation was performed by: <strong>{{ $cancelledBy }}</strong>
                </div>
            @endif

            <hr class="divider">

            <!-- What to do next -->
            <div class="info-box">
                <strong>📌 What's Next?</strong>
                <p>You are no longer registered for this event. If you believe this is a mistake or have any questions, please contact our support team.</p>
            </div>

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ route('user.upcomingevents') }}" class="button">Browse Other Events</a>
            </div>

            <!-- Contact Info -->
            <p style="font-size: 13px; color: #94a3b8; text-align: center; margin-top: 20px;">
                If you have any questions, please contact our support team at <strong style="color: #554994;">support@kasihistimewa.com</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Kasih Istimewa. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>