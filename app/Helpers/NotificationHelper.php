<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    public static function create($userId, $type, $title, $message, $link = null, $icon = 'bell', $color = 'blue')
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'icon' => $icon,
            'color' => $color,
        ]);
    }

    public static function notifyAdmins($type, $title, $message, $link = null, $icon = 'bell', $color = 'blue')
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            self::create($admin->id, $type, $title, $message, $link, $icon, $color);
        }
    }

    // Specific notification types
    public static function newReservation($reservation)
    {
        self::notifyAdmins(
            'reservation',
            'New Reservation Request',
            "{$reservation->user->name} requested to book {$reservation->facility->name}",
            route('admin.reservations.show', $reservation->id),
            'calendar-check',
            'yellow'
        );
    }

    public static function newGuestRequest($guest)
    {
        self::notifyAdmins(
            'guest',
            'New Guest Registration',
            "{$guest->name} from {$guest->organization} requested access",
            route('admin.guests.show', $guest->id),
            'user-group',
            'purple'
        );
    }

    public static function reservationApproved($reservation)
    {
        self::create(
            $reservation->user_id,
            'reservation',
            'Reservation Approved',
            "Your reservation for {$reservation->facility->name} has been approved",
            route('admin.reservations.show', $reservation->id),
            'check-circle',
            'green'
        );
    }

    public static function reservationRejected($reservation, $reason = null)
    {
        $message = "Your reservation for {$reservation->facility->name} was rejected";
        if ($reason) {
            $message .= ": {$reason}";
        }

        self::create(
            $reservation->user_id,
            'reservation',
            'Reservation Rejected',
            $message,
            null,
            'times-circle',
            'red'
        );
    }

    public static function guestApproved($guest)
    {
        self::create(
            $guest->id,
            'guest',
            'Access Approved',
            "Your guest account has been approved. You can now make reservations!",
            null,
            'check-circle',
            'green'
        );
    }
}