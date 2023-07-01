<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function showUnreadNotifications()
    {
        $user = auth()->user();

        $notifications = $user->unreadNotifications;

        if ($notifications->isEmpty()) {
            return response()->json([
                'message' => 'You have no unread notifications',
            ]);
        }
        $filteredNotifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'notifiable_id' => $notification->notifiable_id,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
            ];
        });
    
        return response()->json([
            'notifications' => $filteredNotifications,
        ]);

    }

    public function readNotification($id)
    {
        $user = auth()->user();

        $notification = $user->notifications()->findOrFail($id);
    
        if ($notification->read_at === null) {
            $notification->markAsRead();
            return response()->json([
                'message' => 'Notification marked as read'
            ]);
        }
    
        return response()->json([
            'message' => 'Notification has already been marked as read'
        ]);
    }
}
