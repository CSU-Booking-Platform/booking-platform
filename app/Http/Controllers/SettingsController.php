<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class SettingsController extends Controller
{

    /**
     * @return Response|ResponseFactory
     */
    public function index()
    {
        return inertia('Admin/Settings/Index', [
            'settings' => Settings::all()->pluck('data', 'slug')
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeAppName(Request $request)
    {
        $request->validateWithBag('updateSetting', [
            'label' => ['required'],
            'app_name' => ['required'],
        ]);
        Settings::updateOrCreate(
            ['slug' => $request->label],
            ['data' => json_encode($request->app_name)]
        );
        return back();
    }


    public function storeAppLogo(Request $request)
    {
        $request->validate([
            'app_logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'label' => 'required'
        ]);
        $path = 'storage/'.$request->file('app_logo')->storeAs('images/logo', 'app_logo.'.$request->file('app_logo')->extension(), 'public');
        Settings::updateOrCreate(
            ['slug' => $request->label],
            ['data' => json_encode($path)]
        );
        return back();
    }


}
