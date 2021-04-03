<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_app_logo = 'img/default_app_logo.png';
        Settings::updateOrCreate(
            ['slug' => 'app_logo'],
            ['data' => ['path' => $default_app_logo]]
        );
    }
}
