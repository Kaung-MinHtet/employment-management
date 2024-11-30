<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CompanyUpdateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function update_company()
    {

        // Create a user
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Create a company instance
        $company = Company::factory()->create();

        // Simulate the request with valid data
        $response = $this->post(route('company.update', $company->id), [
            'name' => 'Updated Company Name',
            'email' => 'updated@example.com',
            'website' => 'https://updatedcompany.com',
        ]);

        // $response->dump();

        // Assert the response is a redirect (assuming it redirects after update)
        $response->assertRedirect(route('company.index'));

        // Optionally, check for a success message
        $response->assertSessionHas('toast_message', 'Company updated successfully!');

        // Re-fetch the company from the database
        $updatedCompany = Company::find($company->id);

        $this->assertEquals('Updated Company Name', $updatedCompany->name);
        $this->assertEquals('updated@example.com', $updatedCompany->email);
        $this->assertEquals('https://updatedcompany.com', $updatedCompany->website);

        // $response->ddSession();

    }

    #[Test]
    public function company_update_requires_valid_data()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate the user
        // $this->actingAs($user);

        // Create a company instance
        $company = Company::factory()->create();

        // Simulate the request with invalid data (e.g., missing name)
        $response = $this->actingAs($user)->post(route('company.update', $company->id), [
            'name' => '', // Invalid name
            'email' => 'invalid-email',
            // 'website' => 'not-a-url',
        ]);

        // Assert the validation error is returned
        $response->assertSessionHasErrors(['name', 'email']);

        // $response->ddSession();
        // $response->dumpHeaders();
    }
}