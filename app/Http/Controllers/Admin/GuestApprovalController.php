<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GuestApprovedMail;
use App\Mail\GuestRejectedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GuestApprovalController extends Controller
{
    /**
     * Display pending guest approvals
     */
    public function index()
    {
        $pendingGuests = User::where('role', 'guest')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.guest-approvals.index', compact('pendingGuests'));
    }

    /**
     * Approve a guest account
     */
    public function approve($id)
    {
        try {
            $guest = User::findOrFail($id);
            
            // Check if user is a guest
            if ($guest->role !== 'guest') {
                return redirect()->back()->with('error', 'This user is not a guest account.');
            }

            // Check if already approved
            if ($guest->status === 'approved') {
                return redirect()->back()->with('info', 'This guest account is already approved.');
            }

            // Update status
            $guest->status = 'approved';
            $guest->save();

            // Send approval email
            Mail::to($guest->email)->send(new GuestApprovedMail($guest));

            return redirect()->back()->with('success', "Guest account for {$guest->name} has been approved and notification email sent!");
            
        } catch (\Exception $e) {
            \Log::error('Guest approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve guest account. Please try again.');
        }
    }

    /**
     * Reject a guest account
     */
    public function reject($id)
    {
        try {
            $guest = User::findOrFail($id);
            
            // Check if user is a guest
            if ($guest->role !== 'guest') {
                return redirect()->back()->with('error', 'This user is not a guest account.');
            }

            // Update status
            $guest->status = 'rejected';
            $guest->save();

            // Send rejection email
            Mail::to($guest->email)->send(new GuestRejectedMail($guest));

            return redirect()->back()->with('success', "Guest account for {$guest->name} has been rejected and notification sent.");
            
        } catch (\Exception $e) {
            \Log::error('Guest rejection failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject guest account. Please try again.');
        }
    }

    /**
     * Delete a guest request
     */
    public function destroy($id)
    {
        try {
            $guest = User::findOrFail($id);
            
            // Check if user is a guest
            if ($guest->role !== 'guest') {
                return redirect()->back()->with('error', 'This user is not a guest account.');
            }

            $guest->delete();

            return redirect()->back()->with('success', 'Guest account deleted successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Guest deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete guest account. Please try again.');
        }
    }
}

// ============================================
// 2. EMAIL TEMPLATE - GUEST APPROVED
// File: resources/views/emails/guest-approved.blade.php
// ============================================
?>

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
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #667eea;
            font-size: 20px;
            margin-top: 0;
        }
        .email-body p {
            margin: 15px 0;
            color: #555555;
        }
        .info-box {
            background-color: #f8f9ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 5px 0;
        }
        .info-box strong {
            color: #333333;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #777777;
            border-top: 1px solid #e9ecef;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Account Approved!</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Hello {{ $guest->name }}!</h2>
            
            <p>Great news! Your guest account for <strong>CPAC ESFMS</strong> has been approved by the administrator.</p>
            
            <div class="info-box">
                <p><strong>Account Details:</strong></p>
                <p>📧 Email: {{ $guest->email }}</p>
                <p>👤 Name: {{ $guest->name }}</p>
                <p>✅ Status: Approved</p>
            </div>

            <p>You can now access the system and start making facility reservations.</p>

            <div class="button-container">
                <a href="{{ url('/login') }}" class="button">Login to ESFMS</a>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #777;">
                <strong>What's Next?</strong><br>
                • Log in using your registered credentials<br>
                • Browse available facilities<br>
                • Make reservations for your events<br>
                • Track your reservation status
            </p>

            <p style="font-size: 14px; color: #777;">
                If you have any questions or need assistance, please contact the administrator.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 5px 0;">
                <strong>CPAC Educational Services Facility Management System</strong>
            </p>
            <p style="margin: 5px 0; font-size: 12px;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>