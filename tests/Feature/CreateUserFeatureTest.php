<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateUserPageLoads()
    {
        $response = $this->get('/users/create');
        $response->assertStatus(200);
    }

    public function testFormCreatesUserName()
    {
        $this->assertDatabaseCount('users', 0);
        $this->post('users', [
            'name' => 'John Doe',
            'email' => 'email@test.ca',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'email@test.ca',
            'password' => Hash::make('password123')
        ]);
    }
}
