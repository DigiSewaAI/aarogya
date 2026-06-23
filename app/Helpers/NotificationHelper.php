<?php

use App\Models\Notification;

if (!function_exists('createNotification')) {
    function createNotification($userId, $type, $message, $data = null, $link = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'data' => $data,
            'link' => $link,
            'is_read' => false,
        ]);
    }
}

if (!function_exists('notifyUser')) {
    function notifyUser($user, $type, $messageKey, $data = null, $link = null)
    {
        $message = __('messages.' . $messageKey);
        
        if ($data) {
            foreach ($data as $key => $value) {
                $message = str_replace(':' . $key, $value, $message);
            }
        }
        
        return createNotification($user->id, $type, $message, $data, $link);
    }
}