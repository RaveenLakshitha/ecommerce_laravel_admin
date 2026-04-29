<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        // === Basic Store Information ===
        'site_name',
        'site_title',
        'site_description',
        'site_logo', // path to logo image
        'site_favicon',
        'contact_email',
        'contact_phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',

        // === Currency & Pricing ===
        'currency',
        'currency_symbol',
        'currency_position', // 'left' or 'right'
        'decimal_separator',
        'thousands_separator',
        'number_of_decimals',

        // === Appearance & Theme ===
        'primary_color',
        'secondary_color',
        'accent_color',
        'theme_mode', // 'light', 'dark', or 'auto'
        'header_style',
        'footer_style',

        // === SEO & Metadata ===
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image', // default Open Graph image

        // === Shipping & Delivery (very important for clothing) ===
        'default_shipping_method',
        'free_shipping_threshold', // amount for free shipping
        'shipping_cost_per_order',
        'shipping_cost_per_item',
        'enable_shipping', // boolean as string or use separate boolean field
        'estimated_delivery_days',

        // === Tax Settings ===
        'tax_enabled',
        'default_tax_rate',
        'tax_inclusive', // true = prices include tax

        // === Payment & Checkout ===
        'cash_on_delivery_enabled',
        'bank_transfer_enabled',
        // Add more like 'paypal_enabled', 'stripe_enabled' etc. if you store flags here

        // === Order & Inventory ===
        'low_stock_threshold', // alert when stock is low
        'out_of_stock_behavior', // 'hide', 'show_with_message', 'allow_backorder'
        'allow_backorders',
        'order_prefix', // e.g., "CLOTH-" or "ORD-"

        // === Customer & Account ===
        'guest_checkout_enabled',
        'require_account_for_checkout',
        'newsletter_enabled',
        'wishlist_enabled',

        // === Clothing/Fashion Specific ===
        'size_chart_enabled',
        'default_size_unit', // 'US', 'EU', 'UK', 'cm', 'inches'
        'show_size_guide_link',
        'color_swatches_enabled', // show color as visual swatches instead of text
        'enable_product_quick_view',
        'enable_size_filter',
        'enable_color_filter',
        'enable_price_filter',

        // === Marketing & Features ===
        'enable_discounts',
        'enable_coupons',
        'enable_flash_sales',
        'enable_product_reviews',
        'enable_wishlist',
        'enable_compare',

        // === Social Media & Links ===
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'tiktok_url',

        // === Analytics & Tracking ===
        'google_analytics_id',
        'facebook_pixel_id',

        // === Email & Notifications ===
        'mail_from_name',
        'mail_from_address',
        'order_confirmation_email_enabled',
        'shipping_notification_enabled',

        // === Maintenance & Security ===
        'site_maintenance_mode',
        'maintenance_message',

        // === Storefront Customization ===
        'storefront_banners',
        'storefront_offer_text',
        'storefront_offer_link',
        'storefront_about_us',
        'storefront_marquee_text',
        'storefront_marquee_link',
        'phone',
        'email',
    ];

    protected $casts = [
        'site_maintenance_mode' => 'boolean',
        'tax_enabled' => 'boolean',
        'tax_inclusive' => 'boolean',
        'enable_shipping' => 'boolean',
        'free_shipping_threshold' => 'decimal:2',
        'shipping_cost_per_order' => 'decimal:2',
        'shipping_cost_per_item' => 'decimal:2',
        'default_tax_rate' => 'decimal:2',
        'cash_on_delivery_enabled' => 'boolean',
        'bank_transfer_enabled' => 'boolean',
        'allow_backorders' => 'boolean',
        'guest_checkout_enabled' => 'boolean',
        'require_account_for_checkout' => 'boolean',
        'newsletter_enabled' => 'boolean',
        'wishlist_enabled' => 'boolean',
        'size_chart_enabled' => 'boolean',
        'show_size_guide_link' => 'boolean',
        'color_swatches_enabled' => 'boolean',
        'enable_product_quick_view' => 'boolean',
        'enable_size_filter' => 'boolean',
        'enable_color_filter' => 'boolean',
        'enable_price_filter' => 'boolean',
        'enable_discounts' => 'boolean',
        'enable_coupons' => 'boolean',
        'enable_flash_sales' => 'boolean',
        'enable_product_reviews' => 'boolean',
        'enable_wishlist' => 'boolean',
        'enable_compare' => 'boolean',
        'order_confirmation_email_enabled' => 'boolean',
        'shipping_notification_enabled' => 'boolean',
        'number_of_decimals' => 'integer',
        'low_stock_threshold' => 'integer',
        'storefront_banners' => 'json',
    ];

    /**
     * Get all settings (returns the single settings record).
     *
     * @return \App\Models\Setting|null
     */
    public static function getAll()
    {
        return static::first();
    }

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        static $settings;
        if (!$settings) {
            $settings = self::first();
        }
        return $settings ? ($settings->{$key} ?? $default) : $default;
    }

    /**
     * Format a given price based on store currency settings.
     *
     * @param float|int $amount
     * @param string|null $overrideCurrencySymbol
     * @return string
     */
    public static function formatPrice($amount, $overrideCurrencySymbol = null)
    {
        try {
            $settings = cache('settings') ?? self::first();
        } catch (\Exception $e) {
            $settings = null;
        }
        
        $symbol = $overrideCurrencySymbol ?? ($settings->currency_symbol ?? '$');
        $position = $settings->currency_position ?? 'left';
        $decimals = $settings->number_of_decimals ?? 2;
        $dec_point = $settings->decimal_separator ?? '.';
        $thousands_sep = $settings->thousands_separator ?? ',';

        $formattedNumber = number_format((float)$amount, (int)$decimals, $dec_point, $thousands_sep);

        if ($position === 'right') {
            return $formattedNumber . ' ' . $symbol;
        }

        return $symbol . $formattedNumber;
    }
}