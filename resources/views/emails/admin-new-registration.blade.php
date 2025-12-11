<!DOCTYPE html>
<html>
<head>
    <title>New Registration</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #dc2626;">⚠️ New User Registration - Action Required</h2>
        
        <p>A new user has registered and requires approval:</p>
        
        <div style="background: #fef2f2; padding: 15px; border-left: 4px solid #dc2626; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Name:</strong> {{ $user->name }}</p>
            <p style="margin: 5px 0;"><strong>Email:</strong> {{ $user->email }}</p>
            <p style="margin: 5px 0;"><strong>School ID:</strong> {{ $user->school_id }}</p>
            <p style="margin: 5px 0;"><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
            <p style="margin: 5px 0;"><strong>Department:</strong> {{ $user->department }}</p>
            <p style="margin: 5px 0;"><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
            <p style="margin: 5px 0;"><strong>Registered:</strong> {{ $user->created_at->format('M d, Y h:i A') }}</p>
        </div>
        
        <p>
            <a href="{{ route('admin.users.index', ['status' => 'pending']) }}" 
               style="display: inline-block; background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                Review Registration →
            </a>
        </p>
        
        <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
            Please review and approve/reject this registration from the admin panel.
        </p>
    </div>
</body>
</html>