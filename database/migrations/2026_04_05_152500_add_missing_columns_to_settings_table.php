<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // === Basic Store Information ===
            if (!Schema::hasColumn('settings', 'site_title')) $table->string('site_title')->nullable();
            if (!Schema::hasColumn('settings', 'site_description')) $table->text('site_description')->nullable();
            if (!Schema::hasColumn('settings', 'site_logo')) $table->string('site_logo')->nullable(); // model uses site_logo
            if (!Schema::hasColumn('settings', 'site_favicon')) $table->string('site_favicon')->nullable();
            if (!Schema::hasColumn('settings', 'contact_email')) $table->string('contact_email')->nullable();
            if (!Schema::hasColumn('settings', 'contact_phone')) $table->string('contact_phone')->nullable();
            if (!Schema::hasColumn('settings', 'city')) $table->string('city')->nullable();
            if (!Schema::hasColumn('settings', 'state')) $table->string('state')->nullable();
            if (!Schema::hasColumn('settings', 'country')) $table->string('country')->nullable();
            if (!Schema::hasColumn('settings', 'postal_code')) $table->string('postal_code')->nullable();

            // === Currency & Pricing ===
            if (!Schema::hasColumn('settings', 'currency_position')) $table->string('currency_position')->default('left');
            if (!Schema::hasColumn('settings', 'decimal_separator')) $table->string('decimal_separator')->default('.');
            if (!Schema::hasColumn('settings', 'thousands_separator')) $table->string('thousands_separator')->default(',');
            if (!Schema::hasColumn('settings', 'number_of_decimals')) $table->integer('number_of_decimals')->default(2);

            // === Appearance & Theme ===
            if (!Schema::hasColumn('settings', 'accent_color')) $table->string('accent_color')->nullable();
            if (!Schema::hasColumn('settings', 'theme_mode')) $table->string('theme_mode')->default('light');
            if (!Schema::hasColumn('settings', 'header_style')) $table->string('header_style')->nullable();
            if (!Schema::hasColumn('settings', 'footer_style')) $table->string('footer_style')->nullable();

            // === SEO & Metadata ===
            if (!Schema::hasColumn('settings', 'meta_title')) $table->string('meta_title')->nullable();
            if (!Schema::hasColumn('settings', 'meta_description')) $table->text('meta_description')->nullable();
            if (!Schema::hasColumn('settings', 'meta_keywords')) $table->text('meta_keywords')->nullable();
            if (!Schema::hasColumn('settings', 'og_image')) $table->string('og_image')->nullable();

            // === Shipping & Delivery ===
            if (!Schema::hasColumn('settings', 'default_shipping_method')) $table->string('default_shipping_method')->nullable();
            if (!Schema::hasColumn('settings', 'free_shipping_threshold')) $table->decimal('free_shipping_threshold', 10, 2)->nullable();
            if (!Schema::hasColumn('settings', 'shipping_cost_per_order')) $table->decimal('shipping_cost_per_order', 10, 2)->nullable();
            if (!Schema::hasColumn('settings', 'shipping_cost_per_item')) $table->decimal('shipping_cost_per_item', 10, 2)->nullable();
            if (!Schema::hasColumn('settings', 'enable_shipping')) $table->boolean('enable_shipping')->default(true);
            if (!Schema::hasColumn('settings', 'estimated_delivery_days')) $table->string('estimated_delivery_days')->nullable();

            // === Tax Settings ===
            if (!Schema::hasColumn('settings', 'tax_enabled')) $table->boolean('tax_enabled')->default(false);
            if (!Schema::hasColumn('settings', 'default_tax_rate')) $table->decimal('default_tax_rate', 5, 2)->nullable();
            if (!Schema::hasColumn('settings', 'tax_inclusive')) $table->boolean('tax_inclusive')->default(true);

            // === Payment & Checkout ===
            if (!Schema::hasColumn('settings', 'cash_on_delivery_enabled')) $table->boolean('cash_on_delivery_enabled')->default(true);
            if (!Schema::hasColumn('settings', 'bank_transfer_enabled')) $table->boolean('bank_transfer_enabled')->default(false);

            // === Order & Inventory ===
            if (!Schema::hasColumn('settings', 'low_stock_threshold')) $table->integer('low_stock_threshold')->default(10);
            if (!Schema::hasColumn('settings', 'out_of_stock_behavior')) $table->string('out_of_stock_behavior')->default('show_with_message');
            if (!Schema::hasColumn('settings', 'allow_backorders')) $table->boolean('allow_backorders')->default(false);
            if (!Schema::hasColumn('settings', 'order_prefix')) $table->string('order_prefix')->nullable();

            // === Customer & Account ===
            if (!Schema::hasColumn('settings', 'guest_checkout_enabled')) $table->boolean('guest_checkout_enabled')->default(true);
            if (!Schema::hasColumn('settings', 'require_account_for_checkout')) $table->boolean('require_account_for_checkout')->default(false);
            if (!Schema::hasColumn('settings', 'newsletter_enabled')) $table->boolean('newsletter_enabled')->default(true);
            if (!Schema::hasColumn('settings', 'wishlist_enabled')) $table->boolean('wishlist_enabled')->default(true);

            // === Clothing/Fashion Specific ===
            if (!Schema::hasColumn('settings', 'size_chart_enabled')) $table->boolean('size_chart_enabled')->default(true);
            if (!Schema::hasColumn('settings', 'default_size_unit')) $table->string('default_size_unit')->default('US');
            if (!Schema::hasColumn('settings', 'show_size_guide_link')) $table->boolean('show_size_guide_link')->default(true);
            if (!Schema::hasColumn('settings', 'color_swatches_enabled')) $table->boolean('color_swatches_enabled')->default(true);
            if (!Schema::hasColumn('settings', 'enable_product_quick_view')) $table->boolean('enable_product_quick_view')->default(true);
            if (!Schema::hasColumn('settings', 'enable_size_filter')) $table->boolean('enable_size_filter')->default(true);
            if (!Schema::hasColumn('settings', 'enable_color_filter')) $table->boolean('enable_color_filter')->default(true);
            if (!Schema::hasColumn('settings', 'enable_price_filter')) $table->boolean('enable_price_filter')->default(true);

            // === Marketing & Features ===
            if (!Schema::hasColumn('settings', 'enable_discounts')) $table->boolean('enable_discounts')->default(true);
            if (!Schema::hasColumn('settings', 'enable_coupons')) $table->boolean('enable_coupons')->default(true);
            if (!Schema::hasColumn('settings', 'enable_flash_sales')) $table->boolean('enable_flash_sales')->default(true);
            if (!Schema::hasColumn('settings', 'enable_product_reviews')) $table->boolean('enable_product_reviews')->default(true);
            if (!Schema::hasColumn('settings', 'enable_wishlist')) $table->boolean('enable_wishlist')->default(true);
            if (!Schema::hasColumn('settings', 'enable_compare')) $table->boolean('enable_compare')->default(true);

            // === Social Media & Links ===
            if (!Schema::hasColumn('settings', 'facebook_url')) $table->string('facebook_url')->nullable();
            if (!Schema::hasColumn('settings', 'instagram_url')) $table->string('instagram_url')->nullable();
            if (!Schema::hasColumn('settings', 'twitter_url')) $table->string('twitter_url')->nullable();
            if (!Schema::hasColumn('settings', 'youtube_url')) $table->string('youtube_url')->nullable();
            if (!Schema::hasColumn('settings', 'tiktok_url')) $table->string('tiktok_url')->nullable();

            // === Analytics & Tracking ===
            if (!Schema::hasColumn('settings', 'google_analytics_id')) $table->string('google_analytics_id')->nullable();
            if (!Schema::hasColumn('settings', 'facebook_pixel_id')) $table->string('facebook_pixel_id')->nullable();

            // === Email & Notifications ===
            if (!Schema::hasColumn('settings', 'mail_from_name')) $table->string('mail_from_name')->nullable();
            if (!Schema::hasColumn('settings', 'mail_from_address')) $table->string('mail_from_address')->nullable();
            if (!Schema::hasColumn('settings', 'order_confirmation_email_enabled')) $table->boolean('order_confirmation_email_enabled')->default(true);
            if (!Schema::hasColumn('settings', 'shipping_notification_enabled')) $table->boolean('shipping_notification_enabled')->default(true);

            // === Maintenance & Security ===
            if (!Schema::hasColumn('settings', 'site_maintenance_mode')) $table->boolean('site_maintenance_mode')->default(false);
            if (!Schema::hasColumn('settings', 'maintenance_message')) $table->text('maintenance_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_title', 'site_description', 'site_logo', 'site_favicon', 'contact_email', 'contact_phone',
                'city', 'state', 'country', 'postal_code',
                'currency_position', 'decimal_separator', 'thousands_separator', 'number_of_decimals',
                'accent_color', 'theme_mode', 'header_style', 'footer_style',
                'meta_title', 'meta_description', 'meta_keywords', 'og_image',
                'default_shipping_method', 'free_shipping_threshold', 'shipping_cost_per_order', 'shipping_cost_per_item', 'enable_shipping', 'estimated_delivery_days',
                'tax_enabled', 'default_tax_rate', 'tax_inclusive',
                'cash_on_delivery_enabled', 'bank_transfer_enabled',
                'low_stock_threshold', 'out_of_stock_behavior', 'allow_backorders', 'order_prefix',
                'guest_checkout_enabled', 'require_account_for_checkout', 'newsletter_enabled', 'wishlist_enabled',
                'size_chart_enabled', 'default_size_unit', 'show_size_guide_link', 'color_swatches_enabled',
                'enable_product_quick_view', 'enable_size_filter', 'enable_color_filter', 'enable_price_filter',
                'enable_discounts', 'enable_coupons', 'enable_flash_sales', 'enable_product_reviews', 'enable_wishlist', 'enable_compare',
                'facebook_url', 'instagram_url', 'twitter_url', 'youtube_url', 'tiktok_url',
                'google_analytics_id', 'facebook_pixel_id',
                'mail_from_name', 'mail_from_address', 'order_confirmation_email_enabled', 'shipping_notification_enabled',
                'site_maintenance_mode', 'maintenance_message',
            ]);
        });
    }
};
