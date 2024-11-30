<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ManagerPermissionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    #[Test]
    public function company_permission_test(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Storage::fake('logos');
        $file = UploadedFile::fake()->image('logo.jpg');

        $admin_response = $this->actingAs($admin)->post(route('company.store'), [
            'name' => 'test name',
            'email' => 'test@example.com',
            'logo' => $file,
        ]);

        $admin_response->assertRedirect(route('company.index'));
        $admin_response->assertSessionHas('toast_message', 'Company stored successfully!');

        $manager = User::factory()->create(); // default role is manager

        $manager_response = $this->actingAs($manager)->post(route('company.store'), [
            'name' => 'test manager',
            'email' => 'manager@example.com',
            'logo' => $file,
        ]);

        $manager_response->assertForbidden(); // unauthorize
    }
}
