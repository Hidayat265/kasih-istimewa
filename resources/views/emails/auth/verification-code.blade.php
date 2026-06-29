<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
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
        .content {
            padding: 30px;
            text-align: center;
        }
        .code {
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 10px;
            color: #554994;
            background: #f3f4f6;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-family: monospace;
        }
        .footer {
            background: #f3f4f6;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 0 0 10px 10px;
        }
        .button {
            background: #554994;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kasih Istimewa</h1>
            <p>Email Verification</p>
        </div>
        <div class="content">
            <h2>Hello, {{ $name }}!</h2>
            <p>Thank you for registering with Kasih Istimewa. Please use the verification code below to complete your registration:</p>
            
            <div class="code">{{ $code }}</div>
            
            <p>This code will expire in <strong>30 minutes</strong>.</p>
            <p>If you didn't request this, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Kasih Istimewa. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>