<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use App\Models\NotificationSetting;
use App\Notifications\AppointmentApproved;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SmokeNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_simple_notification_send()
    {
        Notification::fake();
        
        $user = User::factory()->create();
        
        Notification::send($user, new AppointmentApproved(new Appointment()));
        
        Notification::assertSentTo($user, AppointmentApproved::class);
    }
}
