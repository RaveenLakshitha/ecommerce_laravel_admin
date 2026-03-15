<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Models\NotificationSetting;
use App\Notifications\AppointmentApproved;
use App\Notifications\AppointmentAssigned;
use App\Notifications\AppointmentCompleted;
use App\Notifications\AppointmentPaid;
use App\Notifications\AppointmentRejected;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $doctor;
    protected $doctorUser;
    protected $patient;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->doctorUser = User::factory()->create();
        $this->doctorUser->assignRole('doctor');
        
        $this->doctor = Doctor::factory()->create([
            'user_id' => $this->doctorUser->id,
            'is_active' => true
        ]);
        
        $this->patient = Patient::factory()->create();
    }

    public function test_appointment_approved_notification_is_sent()
    {
        Notification::fake();
        
        NotificationSetting::create([
            'event_type' => 'appointment_approved',
            'role_name' => 'admin',
            'is_enabled' => true
        ]);

        $appointment = Appointment::factory()->create([
            'status' => Appointment::STATUS_PENDING,
            'doctor_id' => $this->doctor->id,
            'scheduled_start' => now()->addDay(),
            'scheduled_end' => now()->addDay()->addMinutes(30),
        ]);

        $this->actingAs($this->admin)
            ->patch(route('appointments.approve', $appointment));

        Notification::assertSentTo(
            [$this->admin, $this->doctorUser],
            AppointmentApproved::class
        );
    }

    public function test_appointment_assigned_notification_is_sent()
    {
        Notification::fake();
        
        NotificationSetting::create([
            'event_type' => 'appointment_assigned',
            'role_name' => 'admin',
            'is_enabled' => true
        ]);

        $appointment = Appointment::factory()->create([
            'status' => Appointment::STATUS_PENDING,
        ]);

        $this->actingAs($this->admin)
            ->patch(route('appointments.assign', $appointment), [
                'specialization_id' => $this->doctor->primary_specialization_id,
                'doctor_id' => $this->doctor->id,
                'date' => now()->addDay()->toDateString(),
                'slot' => '10:00|10:30',
            ]);

        Notification::assertSentTo(
            [$this->admin, $this->doctorUser],
            AppointmentAssigned::class
        );
    }

    public function test_appointment_completed_notification_is_sent()
    {
        Notification::fake();
        
        NotificationSetting::create([
            'event_type' => 'appointment_completed',
            'role_name' => 'admin',
            'is_enabled' => true
        ]);

        $appointment = Appointment::factory()->create([
            'status' => Appointment::STATUS_APPROVED,
            'doctor_id' => $this->doctor->id,
            'scheduled_start' => now()->subDay(),
        ]);

        $this->actingAs($this->admin)
            ->post(route('appointments.complete', $appointment));

        Notification::assertSentTo(
            [$this->admin, $this->doctorUser],
            AppointmentCompleted::class
        );
    }

    public function test_appointment_rejected_notification_is_sent()
    {
        Notification::fake();
        
        NotificationSetting::create([
            'event_type' => 'appointment_rejected',
            'role_name' => 'admin',
            'is_enabled' => true
        ]);

        $appointment = Appointment::factory()->create([
            'status' => Appointment::STATUS_PENDING,
        ]);

        $this->actingAs($this->admin)
            ->patch(route('appointments.reject', $appointment), [
                'rejection_reason' => 'Testing rejection',
            ]);

        Notification::assertSentTo(
            $this->admin,
            AppointmentRejected::class
        );
    }
}
