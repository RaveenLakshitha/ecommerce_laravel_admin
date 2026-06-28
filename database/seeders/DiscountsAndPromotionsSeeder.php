<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\DiscountRule;
use Carbon\Carbon;
class DiscountsAndPromotionsSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::create([
            'code' => 'WELCOME10',
            'description' => '$10 off your first purchase',
            'type' => 'fixed',
            'value' => 10.00,
            'min_order_amount' => 50.00,
            'usage_limit' => null,
            'usage_per_user' => 1,
            'is_active' => true,
            'applies_to' => 'all',
        ]);
        Coupon::create([
            'code' => 'SUMMER20',
            'description' => '20% off all products for the summer',
            'type' => 'percentage',
            'value' => 20.00,
            'max_discount_amount' => 50.00,
            'is_active' => true,
            'applies_to' => 'all',
            'expires_at' => Carbon::now()->addMonths(2),
        ]);
        Coupon::create([
            'code' => 'TECH50',
            'description' => 'Huge 50% discount on tech',
            'type' => 'percentage',
            'value' => 50.00,
            'usage_limit' => 100, 
            'is_active' => true,
            'applies_to' => 'specific_categories',
        ]);
        DiscountRule::create([
            'name' => 'Buy 2 Get 1 Free Tees',
            'description' => 'Add 3 t-shirts to your cart and the cheapest is free.',
            'type' => 'bogo',
            'buy_quantity' => 2,
            'get_quantity' => 1,
            'applies_to' => 'all',
            'priority' => 10,
            'is_active' => true,
            'is_flash_sale' => false,
        ]);
        DiscountRule::create([
            'name' => 'Midnight Flash Sale',
            'description' => '15% off everything for 24 hours!',
            'type' => 'percentage',
            'value' => 15.00,
            'applies_to' => 'all',
            'priority' => 100, 
            'is_active' => true,
            'is_flash_sale' => true,
            'starts_at' => Carbon::now()->subHours(1), 
            'expires_at' => Carbon::now()->addHours(23),
        ]);
        DiscountRule::create([
            'name' => 'Saver Tier 1',
            'description' => 'Spend over $100 and automatically save $5.',
            'type' => 'fixed',
            'value' => 5.00,
            'min_order_amount' => 100.00,
            'applies_to' => 'all',
            'priority' => 5,
            'is_active' => true,
            'is_flash_sale' => false,
        ]);
    }
}
