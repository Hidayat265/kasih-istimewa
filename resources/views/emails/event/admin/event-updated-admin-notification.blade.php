<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Update Requires Approval - Kasih Istimewa</title>
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
            background: #fffbeb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border: 1px solid #fde68a;
        }
        .alert-box strong {
            color: #f59e0b;
        }
        .event-details {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
            border-left: 4px solid #f59e0b;
        }
        .event-details h3 {
            color: #f59e0b;
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
        .update-info {
            background: #eff6ff;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            text-align: left;
            border: 1px solid #bfdbfe;
        }
        .update-info strong {
            color: #3b82f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kasih Istimewa</h1>
            <p>Admin Alert: Event Update</p>
        </div>
        
        <div class="content">
            <h2>Hello Admin!</h2>
            
            <div class="alert-box">
                <strong>⚠️ Event Update Alert</strong>
                <p style="margin-top: 10px; color: #333;">
                    An event has been <strong>updated</strong> by <strong>{{ $updaterName }}</strong> 
                    @if($updaterName !== $organizerName)
                        (Organizer: {{ $organizerName }})
                    @endif
                    and requires your review.
                </p>
            </div>
            
            @if($updaterName !== $organizerName)
                <div class="update-info">
                    <strong>ℹ️ Update Information</strong>
                    <p style="margin-top: 8px;">
                        This event was updated by <strong>{{ $updaterName }}</strong> (Admin). 
                        The organizer <strong>{{ $organizerName }}</strong> has been notified of the changes.
                    </p>
                </div>
            @endif
            
            <div class="event-details">
                <h3>📋 Event Details</h3>
                <p><strong>Event ID:</strong> {{ $event->event_id }}</p>
                <p><strong>Event Name:</strong> {{ $event->event_name }}</p>
                <p><strong>Organizer:</strong> {{ $event->event_company_name }}</p>
                <p><strong>Created By:</strong> {{ $organizerName }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format('d F Y') }}</p>
                <p><strong>Location:</strong> {{ $event->event_location_name ?? 'To be confirmed' }}</p>
                <p><strong>Participants Needed:</strong> {{ $event->event_maximum_participant }}</p>
                <p><strong>Current Participants:</strong> {{ $event->event_current_participant ?? 0 }}</p>
                <p><strong>Status:</strong> <span class="status-badge">Pending Review</span></p>
            </div>
            
            <p style="font-size: 14px; color: #666;">Please review the updated event and take the necessary action (Approve, Request Changes, or Reject).</p>
            
            <a href="{{ route('admin.events.show', $event->event_id) }}" class="button" style="color: #FFFFFF; background: #554994;">🔍 Review Event</a>
            
            <p style="font-size: 13px; color: #888;">You are receiving this email because you are an admin of Kasih Istimewa.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Kasih Istimewa. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>