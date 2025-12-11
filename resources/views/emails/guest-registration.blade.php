<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #14b8a6 0%, #0891b2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-left: 4px solid #14b8a6;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Registration Received!</h1>
        </div>
        <div class="content">
            <p>Dear {{ $guest->name }},</p>
            
            <p>Thank you for registering for a guest account with the <strong>Educational and Sports Facility Management System (ESFMS)</strong>.</p>
            
            <div class="info-box">
                <h3>📋 Registration Details:</h3>
                <ul>
                    <li><strong>Name:</strong> {{ $guest->name }}</li>
                    <li><strong>Email:</strong> {{ $guest->email }}</li>
                    <li><strong>Contact:</strong> {{ $guest->contact_number }}</li>
                    @if($guest->organization)
                    <li><strong>Organization:</strong> {{ $guest->organization }}</li>
                    @endif
                    <li><strong>Status:</strong> <span style="color: #f59e0b;">⏳ Pending Approval</span></li>
                </ul>
            </div>
            
            <h3>⏱️ What Happens Next?</h3>
            <p>Your registration request has been submitted successfully and is now <strong>pending administrator review</strong>. Our team will evaluate your request and notify you via email once a decision has been made.</p>
            
            <p>This process typically takes <strong>1-2 business days</strong>. Once approved, you'll be able to:</p>
            <ul>
                <li>✅ Browse available facilities</li>
                <li>✅ Make reservation requests</li>
                <li>✅ Manage your bookings</li>
            </ul>
            
            <p><strong>Need to contact us?</strong> If you have any questions, please reach out to our administration office at <a href="mailto:admin@esfms.edu">admin@esfms.edu</a>.</p>
            
            <p>Thank you for your patience!</p>
            
            <p>Best regards,<br>
            <strong>ESFMS Administration Team</strong></p>
        </div>
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Educational and Sports Facility Management System</p>
        </div>
    </div>
</body>
</html>