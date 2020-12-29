<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Inertia\ResponseFactory;

class SettingsController extends Controller
{

    /**
     * @return Response|ResponseFactory
     */
    public function index()
    {
        $settings = Settings::all()->pluck('data', 'slug');
        if ($settings->isNotEmpty()) {
            return inertia('Admin/Settings/Index', [
                'settings' => $settings
            ]);
        } else {
            return inertia('Admin/Settings/Index');
        }

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeAppName(Request $request)
    {
        $request->validateWithBag('updateSetting', [
            'label' => ['required'],
            'app_name' => ['required', 'string'],
        ]);
        Settings::updateOrCreate(
            ['slug' => $request->label],
            ['data' => ['name' => $request->app_name]]
        );
        return back();
    }


    public function storeAppLogo(Request $request)
    {
        $request->validate([
            'app_logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'label' => 'required'
        ]);
        $path = 'storage/' . Storage::disk('public')->put('logos', $request->file('app_logo'));
        Settings::updateOrCreate(
            ['slug' => $request->label],
            ['data' => ['path' => $path]]
        );
        return back();
    }


}
