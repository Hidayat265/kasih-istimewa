<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Event Created by Admin - Kasih Istimewa</title>
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
        .alert-box {
            background: #eff6ff;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border: 1px solid #bfdbfe;
        }
        .alert-box strong {
            color: #3b82f6;
        }
        .event-details {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
            border-left: 4px solid #22c55e;
        }
        .event-details h3 {
            color: #22c55e;
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
            background: #22c55e;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
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
        .created-by-info {
            background: #f0fdf4;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            text-align: left;
            border: 1px solid #bbf7d0;
        }
        .created-by-info strong {
            color: #22c55e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kasih Istimewa</h1>
            <p>Admin: New Event Created</p>
        </div>
        
        <div class="content">
            <h2>Hello Admin!</h2>
            
            <div class="alert-box">
                <strong>📢 New Event Created by Admin</strong>
                <p style="margin-top: 10px; color: #333;">
                    Admin <strong>{{ $adminName }}</strong> has created a new event for themselves.
                    The event has been automatically approved and is now visible to volunteers.
                </p>
            </div>
            
            <div class="created-by-info">
                <strong>ℹ️ Created By</strong>
                <p style="margin-top: 8px;">
                    <strong>Admin:</strong> {{ $adminName }}<br>
                    <strong>Organizer:</strong> {{ $adminName }}
                </p>
            </div>
            
            <div class="event-details">
                <h3>📋 Event Details</h3>
                <p><strong>Event ID:</strong> {{ $event->event_id }}</p>
                <p><strong>Event Name:</strong> {{ $event->event_name }}</p>
                <p><strong>Organizer:</strong> {{ $event->event_company_name }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format('d F Y') }}</p>
                <p><strong>Location:</strong> {{ $event->event_location_name ?? 'To be confirmed' }}</p>
                <p><strong>Participants Needed:</strong> {{ $event->event_maximum_participant }}</p>
                <p><strong>Current Participants:</strong> {{ $event->event_current_participant ?? 0 }}</p>
                <p><strong>Status:</strong> <span class="status-badge">Approved</span></p>
            </div>
            
            <p style="font-size: 14px; color: #666;">
                The admin has been notified and can now manage this event from their dashboard.
                No further action is required from other admins unless changes are needed.
            </p>
            
            <a href="{{ route('admin.events.show', $event->event_id) }}" class="button" style="color: #FFFFFF; background: #554994;">🔍 View Event</a>
            
            <p style="font-size: 13px; color: #888;">You are receiving this email because you are an admin of Kasih Istimewa.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Kasih Istimewa. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>