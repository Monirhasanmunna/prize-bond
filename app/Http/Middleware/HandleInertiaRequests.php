<?php

namespace App\Http\Middleware;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $settings = AppSetting::whereIn('key', ['favicon', 'logo', 'app_name', 'contact_info', 'social_links', 'copyright'])->select('key', 'value')->get()->keyBy('key');

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'fileBase' => request()->getSchemeAndHttpHost(),
            'favicon' => isset($settings['favicon']) ? json_decode($settings['favicon']->value, true) : null,
            'logo' => isset($settings['logo']) ? json_decode($settings['logo']->value, true) : null,
            'app_settings' => $settings,
        ];
    }
}
