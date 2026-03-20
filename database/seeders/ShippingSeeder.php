<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courier;
use App\Models\ShippingZone;
use App\Models\ShippingRate;
use App\Models\PickupLocation;
use App\Models\Shipment;
use App\Models\ShipmentTrackingEvent;
use App\Models\Order;
use Illuminate\Support\Str;

class ShippingSeeder extends Seeder
{
    public function run()
    {
        // 1. Couriers
        $domex = Courier::create([
            'name' => 'DOMEX',
            'slug' => 'domex',
            'description' => 'Fast delivery across Sri Lanka.',
            'base_url' => 'https://api.domex.lk/v1/',
            'supports_tracking' => true,
            'supports_cod' => true,
            'default_for_cod' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $prompt = Courier::create([
            'name' => 'Prompt Xpress',
            'slug' => 'prompt-xpress',
            'description' => 'Reliable nationwide courier service.',
            'base_url' => 'https://api.promptxpress.lk/',
            'supports_tracking' => true,
            'supports_cod' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // 2. Shipping Zones
        $western = ShippingZone::create([
            'name' => 'Western Province',
            'country_code' => 'LK',
            'region' => 'Western',
            'is_active' => true,
        ]);

        $islandwide = ShippingZone::create([
            'name' => 'Island-Wide (Sri Lanka)',
            'country_code' => 'LK',
            'is_active' => true,
        ]);

        // 3. Shipping Rates
        ShippingRate::create([
            'shipping_zone_id' => $western->id,
            'courier_id' => $domex->id,
            'name' => 'Standard Delivery (Western Province)',
            'rate_amount' => 350.00,
            'free_shipping_threshold' => 15000.00,
            'is_active' => true,
        ]);

        ShippingRate::create([
            'shipping_zone_id' => $islandwide->id,
            'name' => 'Outstation Delivery',
            'rate_amount' => 450.00,
            'free_shipping_threshold' => 20000.00,
            'is_active' => true,
        ]);

        ShippingRate::create([
            'shipping_zone_id' => $islandwide->id,
            'name' => 'Heavy Items (> 5kg)',
            'min_weight' => 5.01,
            'rate_amount' => 800.00,
            'is_active' => true,
        ]);

        // 4. Pickup Locations
        $colomboPickup = PickupLocation::create([
            'name' => 'Colombo Main Store',
            'address_line_1' => '123 Galle Road',
            'city' => 'Colombo 03',
            'postal_code' => '00300',
            'country' => 'LK',
            'phone' => '+94 11 234 5678',
            'email' => 'pickup.colombo@example.com',
            'is_active' => true,
        ]);

        $kandyPickup = PickupLocation::create([
            'name' => 'Kandy Branch',
            'address_line_1' => '456 Peradeniya Road',
            'city' => 'Kandy',
            'postal_code' => '20000',
            'country' => 'LK',
            'phone' => '+94 81 234 5678',
            'email' => 'pickup.kandy@example.com',
            'is_active' => true,
        ]);

        // 5. Shipments
        $orders = Order::inRandomOrder()->take(3)->get();
        if ($orders->count() > 0) {
            foreach ($orders as $index => $order) {
                if ($index == 0) {
                    // Pending Shipment
                    Shipment::create([
                        'order_id' => $order->id,
                        'courier_id' => $domex->id,
                        'status' => 'pending',
                        'notes' => 'Awaiting fulfillment.',
                    ]);
                } elseif ($index == 1) {
                    // In-Transit Shipment
                    $shipment = Shipment::create([
                        'order_id' => $order->id,
                        'courier_id' => $prompt->id,
                        'tracking_number' => 'PX' . mt_rand(100000, 999999),
                        'status' => 'out_for_delivery',
                        'shipped_at' => now()->subDays(1),
                    ]);

                    ShipmentTrackingEvent::create([
                        'shipment_id' => $shipment->id,
                        'status' => 'shipped',
                        'location' => 'Colombo Hub',
                        'description' => 'Package processed at origin hub.',
                        'created_at' => now()->subDays(1),
                        'updated_at' => now()->subDays(1),
                    ]);
                    ShipmentTrackingEvent::create([
                        'shipment_id' => $shipment->id,
                        'status' => 'out_for_delivery',
                        'location' => 'Kandy Branch',
                        'description' => 'Package is out for delivery with courier.',
                        'created_at' => now()->subHours(2),
                        'updated_at' => now()->subHours(2),
                    ]);
                } elseif ($index == 2) {
                    // In-Store Pickup
                    $shipment = Shipment::create([
                        'order_id' => $order->id,
                        'pickup_location_id' => $colomboPickup->id,
                        'status' => 'delivered',
                        'delivered_at' => now()->subHours(5),
                        'notes' => 'Picked up by customer.',
                    ]);
                    ShipmentTrackingEvent::create([
                        'shipment_id' => $shipment->id,
                        'status' => 'delivered',
                        'location' => 'Colombo Main Store',
                        'description' => 'Customer picked up the order successfully.',
                        'created_at' => now()->subHours(5),
                        'updated_at' => now()->subHours(5),
                    ]);
                }
            }
        }
    }
}
