<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function notify(): void
    {
        $notify = ['notify' => SettingController::restartSysteme()];
        $notify_url = SettingController::getNotificationURL();
        APIController::post($notify_url, $notify, 'notification');
    }
}
