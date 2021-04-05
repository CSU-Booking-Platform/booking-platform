<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Inertia\Response;
use Inertia\ResponseFactory;
use Laravel\Fortify\Fortify;

class UserController extends Controller
{
    const MAX_255 = 'max:255';

    use PasswordValidationRules;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|Response|ResponseFactory
     */
    public function index()
    {
        return inertia('Admin/Users/Index', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all()
        ]);
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param Request $request
     * @return User|RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validateWithBag('createUser', [
            'name' => ['required', 'string', self::MAX_255],
            'email' => ['required', 'string', 'email', self::MAX_255, 'unique:users'],
            'password' => ['required', 'string', self::MAX_255, 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('flash', ['we good']);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validateWithBag('updateUser', [
            'name' => ['nullable', 'string', self::MAX_255],
            'email' => ['nullable', 'string', 'email', self::MAX_255],
            'roles' => ['array'],
        ]);

        if ($request->name) {
            $user->name = $request->name;
        }

        if ($request->email) {
            $user->email = $request->email;
        }

        $user->syncRoles($request->roles);

        $user->save();

        return redirect(route('admin.users.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('flash', ['User deleted successfully']);
    }

    public function getResetToken(User $user) {
        Password::deleteToken($user);
        $token = Password::createToken($user);
        return \response()->json([
            'token' => $token,
            'link' => route('password.reset', $token)
        ]);
    }
}
