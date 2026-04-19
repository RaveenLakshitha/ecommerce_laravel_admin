<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Setting;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $settingId = Setting::first()?->id;

        return [
            // === Basic Store Information ===
            'site_name'         => 'required|string|max:255',
            'site_title'        => 'nullable|string|max:255',
            'site_description'  => 'nullable|string',
            'site_logo'         => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'site_favicon'      => 'nullable|image|mimes:png,jpg,jpeg,ico,svg|max:1024',
            'contact_email'     => 'required|email|max:255',
            'contact_phone'     => 'required|string|max:20',
            'address'           => 'required|string',
            'city'              => 'nullable|string|max:100',
            'state'             => 'nullable|string|max:100',
            'country'           => 'nullable|string|max:100',
            'postal_code'       => 'nullable|string|max:20',

            // === Currency & Pricing ===
            'currency'            => 'required|string|size:3',
            'currency_symbol'     => 'nullable|string|max:10',
            'currency_position'   => 'nullable|in:left,right',
            'decimal_separator'   => 'nullable|string|max:1',
            'thousands_separator' => 'nullable|string|max:1',
            'number_of_decimals'  => 'nullable|integer|min:0|max:4',

            // === Appearance & Theme ===
            'primary_color'     => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color'   => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'accent_color'      => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'theme_mode'        => 'nullable|in:light,dark,auto',
            'header_style'      => 'nullable|string|max:50',
            'footer_style'      => 'nullable|string|max:50',

            // === SEO & Metadata ===
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'og_image'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',

            // === Shipping & Delivery ===
            'default_shipping_method' => 'nullable|string|max:100',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'shipping_cost_per_order' => 'nullable|numeric|min:0',
            'shipping_cost_per_item'  => 'nullable|numeric|min:0',
            'enable_shipping'         => 'nullable|boolean',
            'estimated_delivery_days' => 'nullable|string|max:50',

            // === Tax Settings ===
            'tax_enabled'       => 'nullable|boolean',
            'default_tax_rate'  => 'nullable|numeric|min:0|max:100',
            'tax_inclusive'     => 'nullable|boolean',

            // === Payment & Checkout ===
            'cash_on_delivery_enabled'     => 'nullable|boolean',
            'bank_transfer_enabled'        => 'nullable|boolean',
            'guest_checkout_enabled'       => 'nullable|boolean',
            'require_account_for_checkout' => 'nullable|boolean',

            // === Order & Inventory ===
            'low_stock_threshold'    => 'nullable|integer|min:0',
            'out_of_stock_behavior'  => 'nullable|in:hide,show_with_message,allow_backorder',
            'allow_backorders'       => 'nullable|boolean',
            'order_prefix'           => 'nullable|string|max:20',

            // === Clothing/Fashion Specific ===
            'size_chart_enabled'        => 'nullable|boolean',
            'default_size_unit'         => 'nullable|string|max:10',
            'show_size_guide_link'      => 'nullable|boolean',
            'color_swatches_enabled'    => 'nullable|boolean',
            'enable_product_quick_view' => 'nullable|boolean',
            'enable_size_filter'        => 'nullable|boolean',
            'enable_color_filter'       => 'nullable|boolean',
            'enable_price_filter'       => 'nullable|boolean',

            // === Marketing & Features ===
            'enable_discounts'       => 'nullable|boolean',
            'enable_coupons'         => 'nullable|boolean',
            'enable_flash_sales'     => 'nullable|boolean',
            'enable_product_reviews' => 'nullable|boolean',
            'enable_wishlist'        => 'nullable|boolean',
            'enable_compare'         => 'nullable|boolean',
            'newsletter_enabled'     => 'nullable|boolean',

            // === Social Media & Links ===
            'facebook_url'  => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url'   => 'nullable|url|max:255',
            'youtube_url'   => 'nullable|url|max:255',
            'tiktok_url'    => 'nullable|url|max:255',

            // === Analytics & Tracking ===
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id'   => 'nullable|string|max:50',

            // === Email & Notifications ===
            'mail_from_name'                   => 'nullable|string|max:255',
            'mail_from_address'                => 'nullable|email|max:255',
            'order_confirmation_email_enabled' => 'nullable|boolean',
            'shipping_notification_enabled'   => 'nullable|boolean',

            // === Maintenance & Security ===
            'site_maintenance_mode' => 'nullable|boolean',
            'maintenance_message'   => 'nullable|string',

            // === Backward Compatibility / Additional Fields ===
            'site_id'       => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('settings', 'site_id')->ignore($settingId),
            ],
            'website'         => 'nullable|url',
            'tax_id'          => 'nullable|string|max:50',
            'timezone'        => 'nullable|timezone',
            'date_format'     => 'nullable|string',
            'time_format'     => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert checkbox strings to booleans if necessary
        $booleans = [
            'enable_shipping', 'tax_enabled', 'tax_inclusive', 'cash_on_delivery_enabled',
            'bank_transfer_enabled', 'guest_checkout_enabled', 'require_account_for_checkout',
            'allow_backorders', 'size_chart_enabled', 'show_size_guide_link', 'color_swatches_enabled',
            'enable_product_quick_view', 'enable_size_filter', 'enable_color_filter', 'enable_price_filter',
            'enable_discounts', 'enable_coupons', 'enable_flash_sales', 'enable_product_reviews',
            'enable_wishlist', 'enable_compare', 'newsletter_enabled', 'wishlist_enabled', 
            'order_confirmation_email_enabled', 'shipping_notification_enabled', 'site_maintenance_mode'
        ];

        $data = [];
        foreach ($booleans as $field) {
            if ($this->has($field)) {
                $data[$field] = $this->boolean($field);
            }
        }

        $this->merge($data);
    }
}
