<?php
namespace App\Services;
use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
class NotificationService
{
    public static function send(string $eventType, $notification, array $extraUsers = [])
    {
        $enabledRoles = NotificationSetting::where('event_type', $eventType)
            ->where('is_enabled', true)
            ->pluck('role_name')
            ->toArray();
        if (empty($enabledRoles)) {
            if (!empty($extraUsers)) {
                Notification::send($extraUsers, $notification);
            }
            return;
        }
        $usersToNotify = User::role($enabledRoles)->get();
        if (!empty($extraUsers)) {
            $usersToNotify = $usersToNotify->merge($extraUsers)->unique('id');
        }
        if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, $notification);
        }
    }
}
