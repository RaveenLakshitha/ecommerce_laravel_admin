<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Cart', \Darryldecode\Cart\Facades\CartFacade::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only load settings when not in console or testing
        if (!app()->runningInConsole() && !app()->runningUnitTests()) {
            try {
                $setting = cache('settings')
                    ?? cache()->remember('settings', now()->addHour(), fn() => Setting::first() ?? new Setting());
            } catch (\Throwable $e) {
                $setting = new Setting();
            }
        } else {
            $setting = new Setting();
        }

        // Share global view variables for the clothing store
        View::share([
            // Store branding
            'store_name' => $setting->store_name ?? config('app.name', 'Your Clothing Store'),
            'store_tagline' => $setting->tagline ?? 'Trendy Fashion for Everyone',

            // Site variables for admin layout
            'site_name' => $setting->site_name ?? config('app.name', 'Your Site Name'),
            'site_logo' => !empty($setting->logo_path)
                ? Storage::url($setting->logo_path)
                : asset('images/default-logo.png'),
            'site_address' => $setting->address ?? 'No. 45, Main Street, Colombo 03, Sri Lanka',
            'site_phone' => $setting->phone ?? '+94 11 234 5678',
            'site_email' => $setting->email ?? 'support@yourstore.lk',

            // Contact information
            'store_email' => $setting->email ?? 'support@yourstore.lk',
            'store_phone' => $setting->phone ?? '+94 11 234 5678',
            'store_address' => $setting->address ?? 'No. 45, Main Street, Colombo 03, Sri Lanka',
            'store_whatsapp' => $setting->whatsapp ?? '+94 77 123 4567',

            // Branding assets
            'store_logo' => !empty($setting->logo_path)
                ? Storage::url($setting->logo_path)
                : asset('images/default-logo.png'),

            'store_favicon' => !empty($setting->favicon_path)
                ? Storage::url($setting->favicon_path)
                : asset('images/favicon.ico'),

            // Visual styling
            'primary_color' => $setting->primary_color ?? '#c02628',      // example: deep red
            'secondary_color' => $setting->secondary_color ?? '#111827',    // example: dark gray

            // Currency & localization
            'currency_code' => $setting->currency ?? 'USD',
            'currency_symbol' => $setting->currency_symbol ?? '$',

            // Storefront Customization
            'storefront_offer_text' => $setting->storefront_offer_text,
            'storefront_offer_link' => $setting->storefront_offer_link,
            'storefront_marquee_text' => $setting->storefront_marquee_text,
            'storefront_marquee_link' => $setting->storefront_marquee_link,
            'storefront_banners'    => $setting->storefront_banners,
            'storefront_about_us'   => $setting->storefront_about_us,

            // SEO & Metadata
            'meta_title' => $setting->meta_title ?? $setting->site_title ?? $setting->site_name ?? config('app.name'),
            'meta_description' => $setting->meta_description ?? $setting->site_description ?? '',
            'meta_keywords' => $setting->meta_keywords ?? '',
            'og_image' => !empty($setting->og_image) ? Storage::url($setting->og_image) : (!empty($setting->logo_path) ? Storage::url($setting->logo_path) : asset('images/default-logo.png')),

            // Business info
            'free_shipping_threshold' => $setting->free_shipping_threshold ?? 5000,
            'shipping_cost_per_order' => $setting->shipping_cost_per_order ?? 0,
            'return_period_days' => $setting->return_period_days ?? 14,
        ]);

        View::composer(['frontend.layouts.app', 'frontend.layouts.layoutdark', 'frontend.layouts.noir'], function ($view) {
            $categories = \App\Models\Category::whereNull('parent_id')
                ->where('is_active', true)
                ->with(['children' => function($q) {
                    $q->where('is_active', true)->orderBy('name');
                }, 'children.children' => function($q) {
                    $q->where('is_active', true)->orderBy('name');
                }])
                ->orderBy('name')
                ->get();
            $view->with('globalCategories', $categories);
        });

        // Register custom blade directives
        \Illuminate\Support\Facades\Blade::directive('price', function ($expression) {
            return "<?php echo \App\Models\Setting::formatPrice($expression); ?>";
        });
    }
}
