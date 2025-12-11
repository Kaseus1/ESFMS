<!DOCTYPE html>
<html>
<head>
    <title>Registration Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #10b981;">✅ Registration Approved!</h2>
        
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        
        <p>Great news! Your registration for {{ config('app.name') }} has been <strong>approved</strong>.</p>
        
        <div style="background: #f0fdf4; padding: 15px; border-radius: 5px; border-left: 4px solid #10b981; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>✓ Account Status:</strong> Approved</p>
            <p style="margin: 5px 0;"><strong>✓ Email:</strong> {{ $user->email }}</p>
            @if($tempPassword)
            <p style="margin: 5px 0;"><strong>✓ Temporary Password:</strong> <code style="background: #e5e7eb; padding: 2px 6px;">{{ $tempPassword }}</code></p>
            @endif
        </div>
        
        @if($tempPassword)
        <p><strong>⚠️ Important Security Note:</strong></p>
        <p>Please login and <strong>change your password immediately</strong> for security reasons.</p>
        @endif
        
        <p style="margin-top: 20px;">
            <a href="{{ route(strtolower($user->role) . '.login') }}" 
               style="display: inline-block; background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                Login Now →
            </a>
        </p>
        
        <p style="margin-top: 30px;">Welcome aboard! 🎉</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 12px; color: #6b7280;">
            If you have any questions, please contact the administrator.
        </p>
    </div>
</body>
</html>