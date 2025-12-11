<!DOCTYPE html>
<html>
<head>
    <title>Registration Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #dc2626;">Registration Update</h2>
        
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        
        <p>Thank you for your interest in {{ config('app.name') }}. Unfortunately, we are unable to approve your registration at this time.</p>
        
        <div style="background: #fef2f2; padding: 15px; border-radius: 5px; border-left: 4px solid #dc2626; margin: 20px 0;">
            <p><strong>Reason:</strong></p>
            <p>{{ $reason }}</p>
        </div>
        
        <p>If you believe this is an error or have questions, please contact the administrator.</p>
        
        <p style="margin-top: 30px;">Thank you for your understanding.</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 12px; color: #6b7280;">
            {{ config('app.name') }}
        </p>
    </div>
</body>
</html>