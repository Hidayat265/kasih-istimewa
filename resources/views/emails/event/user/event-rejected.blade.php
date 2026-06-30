<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Rejected - Kasih Istimewa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        .content h2 {
            color: #333;
            margin-top: 0;
        }
        .content p {
            color: #666;
            line-height: 1.6;
            margin: 15px 0;
        }
        .event-details {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
            border-left: 4px solid #dc2626;
        }
        .event-details h3 {
            color: #dc2626;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .event-details p {
            margin: 8px 0;
            font-size: 14px;
        }
        .event-details strong {
            color: #333;
        }
        .status-badge {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .reason-box {
            background: #fef2f2;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border: 1px solid #fecaca;
        }
        .reason-box p {
            margin: 5px 0;
            color: #555;
        }
        .reason-box strong {
            color: #dc2626;
        }
        .info-box {
            background: #fefce8;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border: 1px solid #fde68a;
        }
        .info-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 5px 0;
            color: #555;
        }
        .button {
            background: #554994;
            color: #FFFFFF !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
            font-size: 14px;
        }
        .button:hover {
            background: #453a7a;
            color: #FFFFFF !important;
        }
        .footer {
            background: #f3f4f6;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 0 0 10px 10px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kasih Istimewa</h1>
            <p>Event Rejected</p>
        </div>
        
        <div class="content">
            <h2>Hello, {{ $userName }}!</h2>
            <p>We regret to inform you that your event has been <strong>rejected</strong> by our admin team.</p>
            
            <div class="event-details">
                <h3>❌ Event Details</h3>
                <p><strong>Event ID:</strong> {{ $event->event_id }}</p>
                <p><strong>Event Name:</strong> {{ $event->event_name }}</p>
                <p><strong>Organizer:</strong> {{ $event->event_company_name }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format('d F Y') }}</p>
                <p><strong>Status:</strong> <span class="status-badge">Rejected</span></p>
            </div>
            
            @if($reason)
            <div class="reason-box">
                <strong>📝 Reason for Rejection:</strong>
                <p style="margin-top: 10px; color: #333;">{{ $reason }}</p>
            </div>
            @endif
            
            <div class="info-box">
                <strong>💡 What Can You Do?</strong>
                <ul>
                    <li>Review the rejection reason above</li>
                    <li>Create a new event with the necessary corrections</li>
                    <li>Contact our support team if you need clarification</li>
                </ul>
            </div>
            
            <a href="{{ route('user.events.create') }}" class="button" style="color: #FFFFFF; background: #554994;">📝 Create New Event</a>
            
            <p style="font-size: 13px; color: #888;">If you have any questions, please contact our support team at <strong>support@kasihistimewa.my</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Kasih Istimewa. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>