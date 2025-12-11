<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return match($this->status) {
            'approved' => $this->approvedEmail($notifiable),
            'rejected' => $this->rejectedEmail($notifiable),
            'pending' => $this->pendingEmail($notifiable),
            default => $this->defaultEmail($notifiable),
        };
    }

    /**
     * Approved email template
     */
    protected function approvedEmail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your CampusReserve Account Has Been Approved! 🎉')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your CampusReserve account has been approved by our administrator.')
            ->line('You can now log in and start using all the features of our CPAC Event & Facility Management System:')
            ->line('✓ Request and book events')
            ->line('✓ Reserve facilities')
            ->line('✓ Manage your reservations')
            ->line('✓ View availability calendars')
            ->action('Log In to CampusReserve', url('/login'))
            ->line('If you have any questions or need assistance, please don\'t hesitate to contact us.')
            ->line('Thank you for joining CampusReserve!')
            ->salutation('Best regards, The CampusReserve Team');
    }

    /**
     * Rejected email template
     */
    protected function rejectedEmail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update on Your CampusReserve Account')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for your interest in CampusReserve.')
            ->line('Unfortunately, we are unable to approve your account registration at this time.')
            ->line('If you believe this is an error or would like more information, please contact our administrator:')
            ->line('📧 Email: admin@campusreserve.edu')
            ->line('We appreciate your understanding.')
            ->salutation('Best regards, The CampusReserve Team');
    }

    /**
     * Pending email template
     */
    protected function pendingEmail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('CampusReserve Account Under Review')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your CampusReserve account is currently under review by our administrator.')
            ->line('You will receive another email once your account status has been updated.')
            ->line('Thank you for your patience!')
            ->salutation('Best regards, The CampusReserve Team');
    }

    /**
     * Default email template
     */
    protected function defaultEmail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('CampusReserve Account Status Update')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your account status has been updated.')
            ->line('Please log in to view your current account status.')
            ->action('Go to CampusReserve', url('/login'))
            ->salutation('Best regards, The CampusReserve Team');
    }
}