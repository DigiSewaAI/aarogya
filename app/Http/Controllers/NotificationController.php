<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications for the authenticated user.
     * Route: {role}.notifications (patient, doctor, clinic, admin)
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a single notification as read.
     * Route: {role}.notifications.read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        // If it's an AJAX request, return JSON response
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('messages.notification_marked_read')]);
        }

        return back()->with('success', __('messages.notification_marked_read'));
    }

    /**
     * Mark all notifications as read.
     * Route: {role}.notifications.read-all
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // If it's an AJAX request, return JSON response
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('messages.notifications_marked_read')]);
        }

        return back()->with('success', __('messages.notifications_marked_read'));
    }

    /**
     * Get unread notification count (for AJAX polling/badge).
     * Route: {role}.notifications.unread-count (if added) or API endpoint
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Delete a notification.
     * Route: {role}.notifications.delete (if added)
     */
    public function delete($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('messages.notification_deleted')]);
        }

        return back()->with('success', __('messages.notification_deleted'));
    }

    /**
     * Delete all read notifications.
     * Route: {role}.notifications.clear-read (if added)
     */
    public function clearRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', true)
            ->delete();

        return back()->with('success', __('messages.notifications_cleared'));
    }
}