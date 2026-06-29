<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Event Pending Approval - Kasih Istimewa</title>
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
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        .alert-box strong {
            color: #92400e;
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
        .warning-badge {
            display: inline-block;
            background: #f59e0b;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
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
        .button {
            background: #554994;
            color: #FFFFFF !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 5px;
            font-weight: bold;
            font-size: 14px;
        }
        .button-secondary {
            background: #6b7280;
            color: #FFFFFF !important;
        }
        .button:hover {
            opacity: 0.85;
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
            <p>Admin Notification System</p>
        </div>
        
        <div class="content">
            <h2>Hello Admin,</h2>
            
            <div class="alert-box">
                <strong>🔔 New Event Pending Approval!</strong>
                <p style="margin: 8px 0 0 0;">A new event has been created and requires your review.</p>
            </div>
            
            <div class="event-details">
                <h3>📋 Event Details</h3>
                <p><strong>Event ID:</strong> {{ $event->event_id }}</p>
                <p><strong>Event Name:</strong> {{ $event->event_name }}</p>
                <p><strong>Organizer:</strong> {{ $event->event_company_name }}</p>
                <p><strong>Created By:</strong> {{ $creatorName }} (ID: {{ $event->event_created_by_id }})</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format('d F Y') }}</p>
                <p><strong>Location:</strong> {{ $event->event_location_name ?? 'To be confirmed' }}</p>
                <p><strong>Participants Needed:</strong> {{ $event->event_maximum_participant }}</p>
                <p><strong>Created At:</strong> {{ $event->created_at ? \Carbon\Carbon::parse($event->created_at)->format('d F Y, h:i A') : 'N/A' }}</p>
                <p><strong>Status:</strong> <span class="warning-badge">Pending Approval</span></p>
            </div>
            
            <div class="event-details" style="margin-top: 10px;">
                <h3>📍 Location Details</h3>
                <p><strong>Venue Name:</strong> {{ $event->event_location_name ?? 'Not specified' }}</p>
                @if($event->event_location_address)
                <p><strong>Full Address:</strong> {{ $event->event_location_address }}</p>
                @endif
                @if($event->event_location_latitude && $event->event_location_longitude)
                <p><strong>Coordinates:</strong> {{ $event->event_location_latitude }}, {{ $event->event_location_longitude }}</p>
                @endif
            </div>
            
            @if($event->event_document)
            <div class="info-box" style="background: #f0fdf4; border-left-color: #22c55e;">
                <strong>📎 Supporting Document:</strong>
                <p style="margin: 5px 0 0; font-size: 14px;">The event creator has attached a supporting document for this event.</p>
            </div>
            @endif
            
            <div>
                <a href="{{ route('admin.events.show', $event->event_id) }}" class="button" style="color: #FFFFFF; background: #554994;">📖 Review Event</a>
                <a href="{{ route('admin.pendingevent') }}" class="button button-secondary" style="color: #FFFFFF; background: #6b7280;">📋 View All Pending</a>
            </div>
            
            <div class="info-box">
                <strong>⚡ Quick Actions:</strong>
                <ul>
                    <li>Review event details carefully before approving</li>
                    <li>Contact the organizer if you need clarification</li>
                    <li>You can approve, reject, or request updates from the event page</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Kasih Istimewa. All rights reserved.</p>
            <p>This is an automated notification from the system.</p>
            <p>You are receiving this because you are an administrator.</p>
        </div>
    </div>
</body>
</html>