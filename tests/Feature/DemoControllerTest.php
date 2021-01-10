<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DemoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function testDemoIndexPageLoads()
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user)->get('/demo/tables');
        $response->assertOk();
        $response->assertSee("Tables");
    }
}



