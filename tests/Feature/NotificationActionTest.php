<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationActionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
    }

    public function test_user_can_delete_notification()
    {
        $this->user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'TestNotification',
            'data' => ['message' => 'Test', 'title' => 'Test'],
            'read_at' => null,
        ]);
        $notification = $this->user->notifications()->first();

        $this->actingAs($this->user)
            ->delete(route('notifications.destroy', $notification->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id
        ]);
    }

    public function test_dashboard_notifications_are_paginated()
    {
        for ($i = 0; $i < 15; $i++) {
            $this->user->notifications()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'TestNotification',
                'data' => ['message' => 'Test ' . $i, 'title' => 'Test'],
                'read_at' => null,
            ]);
        }

        $this->actingAs($this->user)
            ->get(route('dashboard', ['tab' => 'notifications']))
            ->assertStatus(200)
            ->assertViewHas('notifications');

        $notifications = $this->actingAs($this->user)->get(route('dashboard'))->viewData('notifications');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $notifications);
        $this->assertEquals(10, $notifications->perPage());
    }
}
