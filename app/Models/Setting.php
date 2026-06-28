<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'site_title',
        'site_description',
        'site_logo', 
        'site_favicon',
        'admin_login_bg',
        'contact_email',
        'contact_phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'currency',
        'currency_symbol',
        'currency_position', 
        'decimal_separator',
        'thousands_separator',
        'number_of_decimals',
        'primary_color',
        'secondary_color',
        'accent_color',
        'theme_mode', 
        'header_style',
        'footer_style',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image', 
        'default_shipping_method',
        'free_shipping_threshold', 
        'shipping_cost_per_order',
        'shipping_cost_per_item',
        'enable_shipping', 
        'estimated_delivery_days',
        'tax_enabled',
        'default_tax_rate',
        'tax_inclusive', 
        'cash_on_delivery_enabled',
        'bank_transfer_enabled',
        'low_stock_threshold', 
        'out_of_stock_behavior', 
        'allow_backorders',
        'order_prefix', 
        'guest_checkout_enabled',
        'require_account_for_checkout',
        'newsletter_enabled',
        'wishlist_enabled',
        'size_chart_enabled',
        'default_size_unit', 
        'show_size_guide_link',
        'color_swatches_enabled', 
        'enable_product_quick_view',
        'enable_size_filter',
        'enable_color_filter',
        'enable_price_filter',
        'enable_discounts',
        'enable_coupons',
        'enable_flash_sales',
        'enable_product_reviews',
        'enable_wishlist',
        'enable_compare',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'tiktok_url',
        'google_analytics_id',
        'facebook_pixel_id',
        'mail_from_name',
        'mail_from_address',
        'order_confirmation_email_enabled',
        'shipping_notification_enabled',
        'site_maintenance_mode',
        'maintenance_message',
        'storefront_banners',
        'storefront_offer_text',
        'storefront_offer_link',
        'storefront_about_us',
        'storefront_about_us_content',
        'storefront_marquee_text',
        'storefront_marquee_link',
        'storefront_our_story_title',
        'storefront_our_story_content',
        'storefront_our_story_image',
        'storefront_stats',
        'storefront_trust_items',
        'storefront_logo_text',
        'storefront_logo_subtext',
        'phone',
        'email',
        'storefront_our_story_show',
        'storefront_stats_show',
        'storefront_trust_show',
        'storefront_use_logo_text',
        'storefront_video_url',
        'storefront_video_title',
        'storefront_video_subtitle',
        'storefront_video_show',
        'storefront_delivery_show',
        'storefront_delivery_items',
        'storefront_measure_show',
        'storefront_measure_note',
        'storefront_measure_items',
        'storefront_size_guide_show',
        'storefront_size_guide_title',
        'storefront_size_guide_headers',
        'storefront_size_guide_rows',
        'storefront_size_guide_note',
        'storefront_size_guide_bust_desc',
        'storefront_size_guide_waist_desc',
        'storefront_size_guide_hip_desc',
        'storefront_size_guide_fit_note',
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
        'storefront_stats' => 'json',
        'storefront_trust_items' => 'json',
        'storefront_delivery_items' => 'json',
        'storefront_measure_items' => 'json',
        'storefront_size_guide_headers' => 'json',
        'storefront_size_guide_rows' => 'json',
        'storefront_our_story_show' => 'boolean',
        'storefront_stats_show' => 'boolean',
        'storefront_trust_show' => 'boolean',
        'storefront_use_logo_text' => 'boolean',
        'storefront_video_show' => 'boolean',
        'storefront_delivery_show' => 'boolean',
        'storefront_measure_show' => 'boolean',
        'storefront_size_guide_show' => 'boolean',
    ];
    public static function getAll()
    {
        return static::first();
    }
    public static function getValue($key, $default = null)
    {
        static $settings;
        if (!$settings) {
            $settings = self::first();
        }
        return $settings ? ($settings->{$key} ?? $default) : $default;
    }
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