<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        Config::set('app.name', Settings::where('slug', 'app_name')->first()->data['name'] ?? 'Laravel');

        $logo = Settings::where('slug', 'app_logo')->first()->data['path'] ?? null;

        return array_merge(parent::share($request), [
            'app_logo' => $logo,
        ]);
    }
}
