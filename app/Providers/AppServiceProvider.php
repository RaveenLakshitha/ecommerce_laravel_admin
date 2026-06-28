<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Models\Setting;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Cart', \Darryldecode\Cart\Facades\CartFacade::class);
    }
    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
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
        if ($setting->site_name) {
            config(['app.name' => $setting->site_name]);
        }
        View::share([
            'store_name' => $setting->site_name ?? config('app.name', 'Your Clothing Store'),
            'store_tagline' => $setting->tagline ?? 'Trendy Fashion for Everyone',
            'site_name' => $setting->site_name ?? config('app.name', 'Your Site Name'),
            'site_logo' => !empty($setting->site_logo)
                ? asset('storage/' . $setting->site_logo)
                : null,
            'site_address' => $setting->address ?? 'No. 45, Main Street, Colombo 03, Sri Lanka',
            'site_phone' => $setting->contact_phone ?? $setting->phone ?? '+94 11 234 5678',
            'site_email' => $setting->contact_email ?? $setting->email ?? 'support@yourstore.lk',
            'store_email' => $setting->contact_email ?? $setting->email ?? 'support@yourstore.lk',
            'store_phone' => $setting->contact_phone ?? $setting->phone ?? '+94 11 234 5678',
            'store_address' => $setting->address ?? 'No. 45, Main Street, Colombo 03, Sri Lanka',
            'store_whatsapp' => $setting->whatsapp ?? '+94 77 123 4567',
            'store_logo' => !empty($setting->site_logo)
                ? asset('storage/' . $setting->site_logo)
                : asset('images/default-logo.png'),
            'store_favicon' => !empty($setting->site_favicon)
                ? asset('storage/' . $setting->site_favicon)
                : asset('images/favicon.ico'),
            'admin_login_bg' => !empty($setting->admin_login_bg)
                ? asset('storage/' . $setting->admin_login_bg)
                : null,
            'primary_color' => $setting->primary_color ?? '#c02628',      
            'secondary_color' => $setting->secondary_color ?? '#111827',    
            'currency_code' => $setting->currency ?? 'USD',
            'currency_symbol' => $setting->currency_symbol ?? '$',
            'storefront_offer_text' => $setting->storefront_offer_text,
            'storefront_offer_link' => $setting->storefront_offer_link,
            'storefront_marquee_text' => $setting->storefront_marquee_text,
            'storefront_marquee_link' => $setting->storefront_marquee_link,
            'storefront_banners' => $setting->storefront_banners,
            'storefront_about_us' => $setting->storefront_about_us,
            'storefront_stats' => $setting->storefront_stats,
            'storefront_trust_items' => $setting->storefront_trust_items,
            'storefront_delivery_items' => $setting->storefront_delivery_items,
            'storefront_delivery_show' => $setting->storefront_delivery_show,
            'storefront_measure_items' => $setting->storefront_measure_items,
            'storefront_measure_note' => $setting->storefront_measure_note,
            'storefront_measure_show' => $setting->storefront_measure_show,
            'storefront_logo_text' => $setting->storefront_logo_text,
            'storefront_logo_subtext' => $setting->storefront_logo_subtext,
            'storefront_use_logo_text' => $setting->storefront_use_logo_text,
            'storefront_size_guide_show' => $setting->storefront_size_guide_show,
            'storefront_size_guide_title' => $setting->storefront_size_guide_title,
            'storefront_size_guide_headers' => $setting->storefront_size_guide_headers,
            'storefront_size_guide_rows' => $setting->storefront_size_guide_rows,
            'storefront_size_guide_note' => $setting->storefront_size_guide_note,
            'storefront_size_guide_bust_desc' => $setting->storefront_size_guide_bust_desc,
            'storefront_size_guide_waist_desc' => $setting->storefront_size_guide_waist_desc,
            'storefront_size_guide_hip_desc' => $setting->storefront_size_guide_hip_desc,
            'storefront_size_guide_fit_note' => $setting->storefront_size_guide_fit_note,
            'meta_title' => $setting->meta_title ?? $setting->site_title ?? $setting->site_name ?? config('app.name'),
            'meta_description' => $setting->meta_description ?? $setting->site_description ?? '',
            'meta_keywords' => $setting->meta_keywords ?? '',
            'og_image' => !empty($setting->og_image) ? asset('storage/' . $setting->og_image) : (!empty($setting->site_logo) ? asset('storage/' . $setting->site_logo) : asset('images/default-logo.png')),
            'store_city' => $setting->city,
            'store_state' => $setting->state,
            'store_country' => $setting->country,
            'store_postal_code' => $setting->postal_code,
            'free_shipping_threshold' => $setting->free_shipping_threshold ?? 5000,
            'shipping_cost_per_order' => $setting->shipping_cost_per_order ?? 0,
            'return_period_days' => $setting->return_period_days ?? 14,
        ]);
        View::composer(['frontend.layouts.app', 'frontend.layouts.layoutdark', 'frontend.layouts.noir'], function ($view) {
            $categories = \App\Models\Category::whereNull('parent_id')
                ->where('is_active', true)
                ->with([
                    'children' => function ($q) {
                        $q->where('is_active', true)->orderBy('order');
                    },
                    'children.children' => function ($q) {
                        $q->where('is_active', true)->orderBy('order');
                    }
                ])
                ->orderBy('order')
                ->get();
            $view->with('globalCategories', $categories);
        });
        \Illuminate\Support\Facades\Blade::directive('price', function ($expression) {
            return "<?php echo \App\Models\Setting::formatPrice($expression); ?>";
        });
        \Illuminate\Support\Facades\Blade::directive('placeholder', function ($expression) {
            return "<?php 
                \$placeholders = ['white.jpg', 'black.jpg', 'mink.jpg'];
                // Use the expression as a seed for consistent placeholder picking if provided
                \$seed = !empty($expression) ? (is_numeric($expression) ? (int)$expression : crc32((string)$expression)) : rand(0, 2000);
                echo asset('images/placeholders/' . \$placeholders[abs(\$seed) % 3]);
            ?>";
        });
    }
}
