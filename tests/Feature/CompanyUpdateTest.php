<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_company_with_valid_data()
    {
        // Create a company instance
        $company = Company::factory()->create();

        // Simulate the request with valid data
        $response = $this->post(route('company.update', $company->id), [
            'name' => 'Updated Company Name',
            'email' => 'updated@example.com',
            'website' => 'https://updatedcompany.com',
        ]);

        // Assert the company was updated in the database
        $company->refresh();
        $this->assertEquals('Updated Company Name', $company->name);
        $this->assertEquals('updated@example.com', $company->email);
        $this->assertEquals('https://updatedcompany.com', $company->website);

        // Assert the response is a redirect (assuming it redirects after update)
        $response->assertRedirect(route('company.index'));

        // Optionally, check for a success message
        $response->assertSessionHas('status', 'Company updated successfully!');
    }

    /** @test */
    public function company_update_requires_valid_data()
    {
        // Create a company instance
        $company = Company::factory()->create();

        // Simulate the request with invalid data (e.g., missing name)
        $response = $this->post(route('company.update', $company->id), [
            'name' => '', // Invalid name
            'email' => 'invalid-email',
            'website' => 'not-a-url',
        ]);

        // Assert the validation error is returned
        $response->assertSessionHasErrors(['name', 'email', 'website']);
    }
}