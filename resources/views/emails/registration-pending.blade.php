<!DOCTYPE html>
<html>
<head>
    <title>Registration Pending</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2563eb;">Registration Received!</h2>
        
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        
        <p>Thank you for registering for {{ config('app.name') }}. Your registration has been received and is currently <strong>pending approval</strong>.</p>
        
        <div style="background: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Name:</strong> {{ $user->name }}</p>
            <p style="margin: 5px 0;"><strong>Email:</strong> {{ $user->email }}</p>
            <p style="margin: 5px 0;"><strong>School ID:</strong> {{ $user->school_id }}</p>
            <p style="margin: 5px 0;"><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
            <p style="margin: 5px 0;"><strong>Department:</strong> {{ $user->department }}</p>
        </div>
        
        <p>Our administrators will review your registration shortly. You will receive an email once your account has been approved.</p>
        
        <p><strong>⏱️ What happens next?</strong></p>
        <ul>
            <li>Admin reviews your information</li>
            <li>You'll receive approval/rejection email within 1-2 business days</li>
            <li>Once approved, you can login and start making reservations</li>
        </ul>
        
        <p style="margin-top: 30px;">Thank you for your patience!</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 12px; color: #6b7280;">
            This is an automated message from {{ config('app.name') }}. Please do not reply to this email.
        </p>
    </div>
</body>
</html>