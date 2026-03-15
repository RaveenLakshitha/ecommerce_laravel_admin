<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserDoctorCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the doctor role exists
        if (!Role::where('name', 'Doctor')->exists()) {
            Role::create(['name' => 'Doctor']);
        }
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }
    }

    public function test_admin_can_create_user_with_doctor_role()
    {
        // Create an admin user to act as
        $admin = User::factory()->create([
            'is_active' => true,
            'is_deleted' => false
        ]);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Dr. Test Dummy',
            'email' => 'dr.test@example.com',
            'phone' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Doctor',
            'is_active' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('users.index'));

        // Assert user was actually created
        $this->assertDatabaseHas('users', [
            'name' => 'Dr. Test Dummy',
            'email' => 'dr.test@example.com',
            'phone' => '+1234567890',
        ]);

        // Assert role was assigned
        $user = User::where('email', 'dr.test@example.com')->first();
        $this->assertTrue($user->hasRole('Doctor'));
    }
}
