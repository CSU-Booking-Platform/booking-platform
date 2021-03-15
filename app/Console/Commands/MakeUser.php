<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @codeCoverageIgnore
 */
class MakeUser extends Command
{
    protected $signature = 'user:create {email} {--p|password=} {--a|admin} {--N|name=}';

    protected $description = 'Create a new user in the system';

    public function handle()
    {
        $validator = Validator::make($this->arguments(), [
            'email' => ['required', 'email'],
            'password' => ['string'],
            'name' => ['string']
        ]);

        try {
            $arguments = $validator->validate();
        } catch (ValidationException $e) {
            dd($validator->errors());
        }


        $name = $this->hasArgument('name') ? $arguments['name'] : $this->ask('Name?');
        $pwd = $this->hasArgument('password') ? $arguments['password'] : $this->secret('Password?');

        $user = User::create([
            'name' => $name,
            'email' => $this->argument('email'),
            'email_verified_at' => now(),
            'password' => Hash::make($pwd), // password
            'remember_token' => Str::random(10),
        ]);

        $this->info("User {$user->name} created: OK");
        if ($this->option('admin')) {
            $user->assignRole(['super-admin']);
            $user->save();
            $this->info("Assigned admin role: OK");
        }
    }
}
