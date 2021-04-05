<?php

namespace Tests\Feature;

use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $user = $this->createUserWithPermissions(['settings.edit']);
        $response = $this->actingAs($user)->get('/admin/settings');
        $response->assertOk();
        $this->seed(SettingsSeeder::class);
        $response = $this->actingAs($user)->get('/admin/settings');
        $response->assertOk();
    }

    /**
     * See if page is shown when not logged in
     *
     * @return void
     */
    public function testSettingsNotLoggedIn()
    {
        $response = $this->get('/admin/settings');
        $response->assertStatus(302);
    }


    public function testFormUpdateCreateAppName()
    {
        $user = $this->createUserWithPermissions(['settings.edit']);
        $random = Str::random(40);
        //test if fucntion creates if no option is there
        $this->assertDatabaseCount('settings', 0);
        $this->actingAs($user)->post('/admin/settings/app_name', [
            'label' => 'app_name',
            'app_name' => $random,
        ]);

        $this->assertDatabaseCount('settings', 1);

        $this->assertDatabaseHas('settings', [
            'slug' => 'app_name',
            'data' => json_encode(['name' => $random]),
        ]);
        $random = Str::random(40);
        //test if function edits if data is already there

        $this->actingAs($user)->post('/admin/settings/app_name', [
            'label' => 'app_name',
            'app_name' => $random,
        ]);

        $this->assertDatabaseCount('settings', 1);

        $this->assertDatabaseHas('settings', [
            'slug' => 'app_name',
            'data' => json_encode(['name' => $random]),
        ]);
    }

    /**
     * @test
     */
    public function testFormUpdateCreateAppLogo()
    {
        Carbon::setTestNow(now());
        Storage::fake('public');
        $user = $this->createUserWithPermissions(['settings.edit']);
        $random = Str::random(10);
        //test if function creates if no option is there
        $file = UploadedFile::fake()->image($random . '.png');
        $this->assertDatabaseCount('settings', 0);
        $this->actingAs($user)->post('/admin/settings/app_logo', [
            'label' => 'app_logo',
            'app_logo' => $file,
        ]);
        Storage::disk('public')->assertExists('logos/' . $file->hashName());
        $this->assertDatabaseCount('settings', 1);
        $this->assertDatabaseHas('settings', [
            'slug' => 'app_logo',
            'data' => json_encode(['path' => '/storage/logos/' . $file->hashName()]),
        ]);
        $random = Str::random(10);
        Carbon::setTestNow(now());
        //test if function overwrites if option is already there
        $file = UploadedFile::fake()->image($random . '.jpg');
        $this->actingAs($user)->post('/admin/settings/app_logo', [
            'label' => 'app_logo',
            'app_logo' => $file,
        ]);
        Storage::disk('public')->assertExists('logos/' . $file->hashName());
        $this->assertDatabaseCount('settings', 1);
        $this->assertDatabaseHas('settings', [
            'slug' => 'app_logo',
            'data' => json_encode(['path' => '/storage/logos/' . $file->hashName()]),
        ]);
    }

    public function testFormUpdateCreateAppConfig()
    {
        $user = $this->createUserWithPermissions(['settings.edit']);
        $random = Str::random(40);
        //test if fucntion creates if no option is there
        $this->assertDatabaseCount('settings', 0);
        $this->actingAs($user)->post('/admin/settings/app_config', [
            'label' => 'app_config',
            'client_secret' => $random,
            'client_id' => $random,
            'redirect_uri' => $random,
            'tenant' => $random
        ]);

        $this->assertDatabaseCount('settings', 1);

        $this->assertDatabaseHas('settings', [
            'slug' => 'app_config',
            'data' => json_encode([
                'secret' => $random,
                'id' => $random,
                'uri' => $random,
                'tenant' => $random
            ]),
        ]);
        $random = Str::random(40);
        //test if function edits if data is already there

        $this->actingAs($user)->post('/admin/settings/app_config', [
            'label' => 'app_config',
            'client_secret' => $random,
            'client_id' => $random,
            'redirect_uri' => $random,
            'tenant' => $random
        ]);

        $this->assertDatabaseCount('settings', 1);

        $this->assertDatabaseHas('settings', [
            'slug' => 'app_config',
            'data' => json_encode([
                'secret' => $random,
                'id' => $random,
                'uri' => $random,
                'tenant' => $random
            ]),
        ]);
    }

        /**
     * @test
     */
    public function testCanEditBookingInformations()
    {
        $setting = Settings::create([
            'slug' => 'event_description',
            'data' => json_encode([
                'html' => '<p>This is a test</p>'
            ])
        ]);
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.update']))->patch('api/booking-setting', [
            'slug' => 'event_description',
            'data' => '<p>holla</p>'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('settings', ['slug' => $setting->slug, 'data' => json_encode([
            'html' => '<p>holla</p>'
        ])]);
    }
}
