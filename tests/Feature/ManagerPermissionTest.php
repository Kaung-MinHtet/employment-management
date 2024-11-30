<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
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

    #[Test]
    public function manager_can_update_assigned_company_employee_test(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager1 = User::factory()->create(); // default role is manager
        $manager2 = User::factory()->create();

        // dd($manager1);

        Storage::fake('logos');
        $file = UploadedFile::fake()->image('logo.jpg');

        // create company
        $company1 = Company::factory()->create([
            'name' => 'Company 1'
        ]);
        $company2 = Company::factory()->create([
            'name' => 'Company 2'
        ]);

        $employee1 = Employee::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'phone' => '09123456789',
            'profile_picture' => $file,
            'company_id' => $company1->id,
        ]);

        $employee2 = Employee::create([
            'name' => 'test 2',
            'email' => 'test2@example.com',
            'phone' => '09123456789',
            'profile_picture' => $file,
            'company_id' => $company2->id,
        ]);

        $this->assertEquals('Company 1', $company1->name);
        $this->assertEquals('Company 2', $company2->name);

        // assign company 1 to manager 1
        $assign_response1 = $this->actingAs($admin)->put(route('user.update', $manager1->id), [
            'name' => $manager1->name,
            'email' => $manager1->email,
            'role' => 'manager',
            'company_id' => $company1->id
        ]);
        // $assign_response1->ddSession();
        $assign_response1->assertRedirect(route('user.index'));

        // assign company 2 to manager 2
        $assign_response2 = $this->actingAs($admin)->put(route('user.update', $manager2->id), [
            'name' => $manager2->name,
            'email' => $manager2->email,
            'role' => 'manager',
            'company_id' => $company2->id
        ]);
        $assign_response2->assertRedirect(route('user.index'));

        // update company2's user by logging with company1's user
        $manager_response = $this->actingAs($manager1)->put(route('user.update', $manager2->id), [
            'name' => 'update manager 2',
        ]);

        $manager_response->assertForbidden(); // unauthorize
    }
}
