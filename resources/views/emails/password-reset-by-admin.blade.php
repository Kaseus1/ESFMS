<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2563eb;">🔐 Password Reset</h2>
        
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        
        <p>Your password has been reset by an administrator.</p>
        
        <div style="background: #fef3c7; padding: 15px; border-radius: 5px; border-left: 4px solid #f59e0b; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>New Temporary Password:</strong></p>
            <p style="margin: 5px 0; font-size: 18px; font-family: monospace; background: white; padding: 10px;">{{ $newPassword }}</p>
        </div>
        
        <p><strong>⚠️ Important:</strong> Please login and change this password immediately.</p>
        
        <p style="margin-top: 20px;">
            <a href="{{ route(strtolower($user->role) . '.login') }}" 
               style="display: inline-block; background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                Login Now →
            </a>
        </p>
    </div>
</body>
</html>