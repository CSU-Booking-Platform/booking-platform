<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EasyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('email', 'admin@email.com')->first();
        if ($admin){
               $admin->name = 'Joseph doe';
               $admin->email = 'admin@email.com';
               $admin->password = Hash::make('password');
               $admin->save();
        }else{
            User::create([
                'name' => 'Joseph doe',
                'email' => 'admin@email.com',
                'password' => Hash::make('password'),
            ]);
        }

    }
}
