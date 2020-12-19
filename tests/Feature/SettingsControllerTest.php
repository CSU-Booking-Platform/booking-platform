<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * See if page loads when logged in
     *
     * @return void
     */
    public function testSettings()
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user)->get('/settings');
        $response->assertOk();
    }

    /**
     * See if page is shown when not logged in
     *
     * @return void
     */
    public function testSettingsNotLoggedIn()
    {
        $response = $this->get('/settings');
        $response->assertStatus(302);
    }


    public function testFormUpdateCreateAppName()
    {
        $user = User::factory()->make();
        $random = Str::random(40);
        //test if fucntion creates if no option is there
        $this->assertDatabaseCount('settings', 0);
        $response = $this->actingAs($user)->post('settings/app_name', [
            'label' => 'app_name',
            'app_name' => $random,
        ]);

        $this->assertDatabaseCount('settings', 1);

        $this->assertDatabaseHas('settings', [
            'slug' => 'app_name',
            'data' => json_encode($random),
        ]);
        $random = Str::random(40);
        //test if function edits if data is already there

        $this->actingAs($user)->post('settings/app_name', [
            'label' => 'app_name',
            'app_name' => $random,
        ]);

        $this->assertDatabaseCount('settings', 1);

        $this->assertDatabaseHas('settings', [
            'slug' => 'app_name',
            'data' => json_encode($random),
        ]);
    }

    /**
     * @test
     */
    public function testFormUpdateCreateAppLogo()
    {
        Storage::fake('public');
        $user = User::factory()->make();
        $random = Str::random(10);
        //test if function creates if no option is there
        $file = UploadedFile::fake()->image($random.'.png');
        $this->assertDatabaseCount('settings', 0);
        $this->actingAs($user)->post('settings/app_logo', [
            'label' => 'app_logo',
            'app_logo' => $file,
        ]);
        Storage::disk('public')->assertExists('logos/' . $file->hashName());
        $this->assertDatabaseCount('settings', 1);
        $this->assertDatabaseHas('settings', [
            'slug' => 'app_logo',
            'data' => json_encode('storage/logos/' . $file->hashName()),
        ]);
        $random = Str::random(10);
        //test if function overwrites if option is already there
        $file = UploadedFile::fake()->image($random.'.jpg');
        $this->actingAs($user)->post('settings/app_logo', [
            'label' => 'app_logo',
            'app_logo' => $file,
        ]);
        Storage::disk('public')->assertExists('logos/' . $file->hashName());
        $this->assertDatabaseCount('settings', 1);
        $this->assertDatabaseHas('settings', [
            'slug' => 'app_logo',
            'data' => json_encode('storage/logos/' . $file->hashName()),
        ]);
    }

}
