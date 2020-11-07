<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Inertia\ResponseFactory;

class UserController extends Controller
{
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'max:255', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('flash', ['we good']);

    }

//
//    /**
//     * Display the specified resource.
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        //
//    }
//
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
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'roles' => ['array'],
        ]);

        if ($request->name)
            $user->name = $request->name;
        if ($request->email)
            $user->email = $request->email;

        $user->syncRoles($request->roles);

        $user->save();

        return back()->with('flash', ['User updated']);

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
        return back()->with('flash', ['we good']);
    }
}
