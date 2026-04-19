<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.index', ['only' => ['general', 'index']]);
        $this->middleware('permission:settings.edit', ['only' => ['edit', 'update']]);
    }

    /**
     * Display the general settings page.
     */
    public function general(): View
    {
        $setting = Setting::firstOrCreate([], [
            'site_name' => config('app.name', 'Your Store'),
            'primary_color' => '#c02628',
            'currency' => 'USD',
        ]);

        // Define groups for tabs
        $tabs = [
            'general'    => 'General Settings',
            'store'      => 'Store Information',
            'appearance' => 'Appearance',
            'currency'   => 'Currency & Pricing',
            'seo'        => 'SEO & Meta',
            'shipping'   => 'Shipping & Delivery',
            'tax'        => 'Tax Settings',
            'payments'   => 'Payments & Checkout',
            'inventory'  => 'Order & Inventory',
            'customer'   => 'Customer & Auth',
            'features'   => 'Features & UI',
            'marketing'  => 'Marketing',
            'social'     => 'Social Media',
            'analytics'  => 'Analytics',
            'email'      => 'Emails',
            'maintenance'=> 'Maintenance',
        ];

        return view('admin.settings.index', compact('setting', 'tabs'));
    }

    /**
     * Alias for general() to support index route.
     */
    public function index(): View
    {
        return $this->general();
    }

    /**
     * Show the form for editing the settings.
     */
    public function edit(): View
    {
        return $this->general();
    }

    /**
     * Update the settings in storage.
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        try {
            $setting = Setting::firstOrCreate([]);

            $data = $request->validated();

            // Handle file uploads
            $files = [
                'site_logo'   => 'logos',
                'site_favicon' => 'favicons',
                'og_image'    => 'seo',
            ];

            foreach ($files as $field => $folder) {
                if ($request->hasFile($field) && $request->file($field)->isValid()) {
                    // Delete old file if exists
                    if ($setting->{$field} && Storage::disk('public')->exists($setting->{$field})) {
                        Storage::disk('public')->delete($setting->{$field});
                    }
                    $data[$field] = $request->file($field)->store($folder, 'public');
                }
            }

            // Sync legacy fields if they match
            if (isset($data['site_logo'])) $data['logo_path'] = $data['site_logo'];
            if (isset($data['contact_email'])) $data['email'] = $data['contact_email'];
            if (isset($data['contact_phone'])) $data['phone'] = $data['contact_phone'];

            $setting->update($data);

            $this->clearSettingsCache();

            // Store in cache for performance
            Cache::put('settings', $setting->fresh(), now()->addHour());

            return redirect()
                ->back()
                ->with('success', __('file.settings_updated_successfully'));

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while updating settings', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Database error occurred while saving settings.');

        } catch (\Exception $e) {
            Log::error('Unexpected error updating settings', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update settings. Please try again.');
        }
    }

    /**
     * Clear all settings related caches.
     */
    protected function clearSettingsCache(): void
    {
        Cache::forget('settings');
        Cache::forget('app_settings');
        Cache::forget('clinic_settings');
    }
}
