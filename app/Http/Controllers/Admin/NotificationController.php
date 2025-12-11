<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the admin
     */
    public function index()
    {
        try {
            $notifications = $this->getNotifications();
            
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $notifications->where('read', false)->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Notification Error: ' . $e->getMessage());
            return response()->json([
                'notifications' => [],
                'unread_count' => 0
            ]);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('id');
        
        // Store read status in session
        $readNotifications = session()->get('read_notifications', []);
        $readNotifications[] = $notificationId;
        session()->put('read_notifications', $readNotifications);
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $notifications = $this->getNotifications();
        $notificationIds = $notifications->pluck('id')->toArray();
        
        session()->put('read_notifications', $notificationIds);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get formatted notifications
     */
    private function getNotifications()
    {
        $readNotifications = session()->get('read_notifications', []);
        $notifications = collect();

        // Pending Reservations (with null-safe checks)
        $pendingReservations = Reservation::where('status', 'pending')
            ->with(['user', 'facility' => function($query) {
                $query->withTrashed(); // Include soft-deleted facilities
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($pendingReservations as $reservation) {
            // Skip if user doesn't exist
            if (!$reservation->user) continue;
            
            $notificationId = 'reservation_' . $reservation->id;
            $facilityName = $reservation->facility->name ?? 'Deleted Facility';
            $userName = $reservation->user->name ?? 'Unknown User';
            
            $notifications->push([
                'id' => $notificationId,
                'type' => 'reservation',
                'title' => 'New Reservation Request',
                'message' => "{$userName} requested to book {$facilityName}",
                'details' => "Event: " . ($reservation->event_name ?? 'No event name'),
                'time' => $reservation->created_at->diffForHumans(),
                'url' => route('admin.reservations.show', $reservation->id),
                'icon' => 'fa-calendar-check',
                'color' => 'yellow',
                'read' => in_array($notificationId, $readNotifications),
                'created_at' => $reservation->created_at
            ]);
        }

        // Pending Guest Requests
        $pendingGuests = User::where('role', 'guest')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($pendingGuests as $guest) {
            $notificationId = 'guest_' . $guest->id;
            $notifications->push([
                'id' => $notificationId,
                'type' => 'guest',
                'title' => 'New Guest Registration',
                'message' => "{$guest->name} requested guest access",
                'details' => "Email: {$guest->email}",
                'time' => $guest->created_at->diffForHumans(),
                'url' => route('admin.guests.show', $guest->id),
                'icon' => 'fa-user-plus',
                'color' => 'blue',
                'read' => in_array($notificationId, $readNotifications),
                'created_at' => $guest->created_at
            ]);
        }

        // Recently Approved Reservations (last 24 hours)
        $recentApproved = Reservation::where('status', 'approved')
            ->where('updated_at', '>=', now()->subDay())
            ->with(['user', 'facility' => function($query) {
                $query->withTrashed();
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($recentApproved as $reservation) {
            // Skip if user doesn't exist
            if (!$reservation->user) continue;
            
            $notificationId = 'approved_' . $reservation->id;
            $facilityName = $reservation->facility->name ?? 'Deleted Facility';
            $userName = $reservation->user->name ?? 'Unknown User';
            
            $notifications->push([
                'id' => $notificationId,
                'type' => 'success',
                'title' => 'Reservation Approved',
                'message' => "Booking for {$facilityName} was approved",
                'details' => "Guest: {$userName}",
                'time' => $reservation->updated_at->diffForHumans(),
                'url' => route('admin.reservations.show', $reservation->id),
                'icon' => 'fa-check-circle',
                'color' => 'green',
                'read' => in_array($notificationId, $readNotifications),
                'created_at' => $reservation->updated_at
            ]);
        }

        // Sort by most recent
        return $notifications->sortByDesc('created_at')->values();
    }
}