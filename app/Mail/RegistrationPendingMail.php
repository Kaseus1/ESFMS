<?php
// ============================================
// File: app/Mail/RegistrationPendingMail.php
// ============================================

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Registration Pending Approval - ' . config('app.name'))
                    ->view('emails.registration-pending');
    }
}

// ============================================
// File: app/Mail/AdminNewRegistrationMail.php
// ============================================

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNewRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('New User Registration - Approval Required')
                    ->view('emails.admin-new-registration');
    }
}

// ============================================
// File: app/Mail/RegistrationApprovedMail.php
// ============================================

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $tempPassword;

    public function __construct(User $user, $tempPassword = null)
    {
        $this->user = $user;
        $this->tempPassword = $tempPassword;
    }

    public function build()
    {
        return $this->subject('Registration Approved - Welcome to ' . config('app.name'))
                    ->view('emails.registration-approved');
    }
}

// ============================================
// File: app/Mail/RegistrationRejectedMail.php
// ============================================

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;

    public function __construct(User $user, $reason = null)
    {
        $this->user = $user;
        $this->reason = $reason ?? 'Your registration did not meet our requirements.';
    }

    public function build()
    {
        return $this->subject('Registration Update - ' . config('app.name'))
                    ->view('emails.registration-rejected');
    }
}

// ============================================
// File: app/Mail/PasswordResetByAdminMail.php
// ============================================

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetByAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newPassword;

    public function __construct(User $user, $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        return $this->subject('Password Reset - ' . config('app.name'))
                    ->view('emails.password-reset-by-admin');
    }
}