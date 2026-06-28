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
    public function general(): View
    {
        $setting = Setting::firstOrCreate([], [
            'site_name' => config('app.name', 'Your Store'),
            'primary_color' => '#c02628',
            'currency' => 'USD',
        ]);
        $tabs = [
            'general'    => 'General Settings',
            'store'      => 'Store Information',
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
    public function index(): View
    {
        return $this->general();
    }
    public function edit(): View
    {
        return $this->general();
    }
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        try {
            $setting = Setting::firstOrCreate([]);
            $data = $request->validated();
            $files = [
                'site_logo'      => 'logos',
                'site_favicon'   => 'favicons',
                'admin_login_bg' => 'backgrounds',
                'og_image'       => 'seo',
            ];
            foreach ($files as $field => $folder) {
                if ($request->hasFile($field) && $request->file($field)->isValid()) {
                    if ($setting->{$field} && Storage::disk('public')->exists($setting->{$field})) {
                        Storage::disk('public')->delete($setting->{$field});
                    }
                    $data[$field] = $request->file($field)->store($folder, 'public');
                }
            }
            $setting->update($data);
            $this->clearSettingsCache();
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
    protected function clearSettingsCache(): void
    {
        Cache::forget('settings');
        Cache::forget('app_settings');
        Cache::forget('clinic_settings');
    }
}
