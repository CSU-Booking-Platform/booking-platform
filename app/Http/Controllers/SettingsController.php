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
    $path = '/storage/' . Storage::disk('public')->putFileAs(
        'logos',
        $request->file('app_logo'),
        $request->file('app_logo')->hashName());
    Settings::updateOrCreate(
      ['slug' => $request->label],
      ['data' => ['path' => $path]]
    );
    return back();
  }

  public function setAppConfig(Request $request)
  {
    $request->validateWithBag('updateSetting', [
        'label' => ['required'],
        'client_secret' => ['required', 'string'],
        'client_id' => ['required', 'string'],
        'redirect_uri' => ['required', 'string'],
        'tenant' => ['required', 'string']
    ]);
    Settings::updateOrCreate(
        ['slug' => $request->label],
        ['data' => [
            'secret' => $request->client_secret,
            'id' => $request->client_id,
            'uri' => $request->redirect_uri,
            'tenant' => $request->tenant
            ]]
    );
    
    return back();
  }
  public function storeBookingGeneralInformation(Request $request)
  {
    $request->validate([
      'slug' => 'required',
      'data' => 'required'
    ]);

    Settings::updateOrCreate(
      ['slug' => $request->slug],
      ['data' => ['html' => $request->data]]
    );

    return response()->json();
  }

}
