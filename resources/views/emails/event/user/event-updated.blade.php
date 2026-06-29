<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Updated - Kasih Istimewa</title>
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
            background: linear-gradient(135deg, #554994 0%, #CB80AB 100%);
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
            border-left: 4px solid #554994;
        }
        .event-details h3 {
            color: #554994;
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
            background: #f59e0b;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-badge.admin-updated {
            background: #554994;
        }
        .info-box {
            background: #e8f4f8;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        .info-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 5px 0;
            color: #555;
        }
        .admin-info {
            background: #f0fdf4;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            text-align: left;
            border: 1px solid #bbf7d0;
        }
        .admin-info strong {
            color: #22c55e;
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
            <p>Event Update Confirmation</p>
        </div>
        
        <div class="content">
            <h2>Hello, {{ $userName }}!</h2>
            
            @if($isAdminUpdate)
                <p>Your event has been <strong>updated</strong> by admin <strong>{{ $updaterName ?? 'Admin' }}</strong>.</p>
                <div class="admin-info">
                    <strong>✅ Admin Update</strong>
                    <p style="margin-top: 8px;">The admin has made changes to your event details. Please review the updated information below.</p>
                </div>
            @else
                <p>Your event has been <strong>successfully updated</strong> and is now pending admin approval again.</p>
                <p>Our admin team will review the changes and get back to you soon.</p>
            @endif
            
            <div class="event-details">
                <h3>📋 Updated Event Details</h3>
                <p><strong>Event ID:</strong> {{ $event->event_id }}</p>
                <p><strong>Event Name:</strong> {{ $event->event_name }}</p>
                <p><strong>Organizer:</strong> {{ $event->event_company_name }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format('d F Y') }}</p>
                <p><strong>Location:</strong> {{ $event->event_location_name ?? 'To be confirmed' }}</p>
                <p><strong>Participants Needed:</strong> {{ $event->event_maximum_participant }}</p>
                <p><strong>Status:</strong> 
                    @if($event->event_approval_status === 'Pending')
                        <span class="status-badge">Pending Approval</span>
                    @elseif($event->event_approval_status === 'Approved')
                        <span class="status-badge" style="background: #22c55e;">Approved</span>
                    @elseif($event->event_approval_status === 'NeedsUpdate')
                        <span class="status-badge" style="background: #f59e0b;">Needs Update</span>
                    @else
                        <span class="status-badge" style="background: #ef4444;">{{ $event->event_approval_status }}</span>
                    @endif
                </p>
            </div>
            
            @if(!$isAdminUpdate)
            <div class="info-box">
                <strong>📌 What's Next?</strong>
                <ul>
                    <li>Our admin team will review the updated event within 2-3 business days</li>
                    <li>You will receive another email once your event is approved or if changes are needed</li>
                    <li>You can continue to edit your event while it's still pending</li>
                </ul>
            </div>
            @endif
            
            <a href="{{ route('user.myEvents') }}" class="button" style="color: #FFFFFF; background: #554994;">📅 View My Events</a>
            
            <p style="font-size: 13px; color: #888;">If you have any questions, please contact our support team at <strong>support@kasihistimewa.org</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Kasih Istimewa. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>