<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #667eea;
            font-size: 22px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .email-body p {
            margin: 15px 0;
            color: #555555;
            font-size: 16px;
        }
        .info-box {
            background-color: #f8f9ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 8px 0;
            font-size: 15px;
        }
        .info-box strong {
            color: #333333;
            font-weight: 600;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(102, 126, 234, 0.4);
        }
        .next-steps {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .next-steps h3 {
            color: #333333;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .next-steps ul {
            margin: 0;
            padding-left: 20px;
        }
        .next-steps li {
            color: #555555;
            margin: 10px 0;
            font-size: 15px;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            font-size: 14px;
            color: #777777;
            border-top: 1px solid #e9ecef;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">🎉</div>
            <h1>Account Approved!</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Hello {{ $guest->name }}!</h2>
            
            <p>Great news! Your guest account for <strong>CPAC ESFMS</strong> (Educational Services Facility Management System) has been approved by the administrator.</p>
            
            <div class="info-box">
                <p><strong>📧 Email:</strong> {{ $guest->email }}</p>
                <p><strong>👤 Name:</strong> {{ $guest->name }}</p>
                @if($guest->organization)
                <p><strong>🏢 Organization:</strong> {{ $guest->organization }}</p>
                @endif
                <p><strong>✅ Status:</strong> Approved</p>
                <p><strong>📅 Approved on:</strong> {{ now()->format('F d, Y') }}</p>
            </div>

            <p style="font-size: 17px; font-weight: 500; color: #333;">You can now access the system and start making facility reservations!</p>

            <div class="button-container">
                <a href="{{ url('/guest/login') }}" class="button">Login to ESFMS</a>
            </div>

            <div class="next-steps">
                <h3>📋 What's Next?</h3>
                <ul>
                    <li>Log in using your registered credentials</li>
                    <li>Browse available facilities</li>
                    <li>Make reservations for your events</li>
                    <li>Track your reservation status in real-time</li>
                    <li>Receive notifications about your bookings</li>
                </ul>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #777; line-height: 1.8;">
                <strong>Need Help?</strong><br>
                If you have any questions or need assistance navigating the system, please don't hesitate to contact the CPAC administrator.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="font-weight: 600; font-size: 15px; color: #555;">
                CPAC Educational Services Facility Management System
            </p>
            <p style="font-size: 13px;">
                This is an automated email. Please do not reply to this message.
            </p>
            <p style="font-size: 12px; margin-top: 10px;">
                © {{ date('Y') }} CPAC. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>