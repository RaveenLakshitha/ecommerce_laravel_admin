<?php
namespace Database\Seeders;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRefund;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::first() ?? Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe_' . rand(100, 999) . '@example.com',
            'phone' => '1234567890',
        ]);
        $user = User::first(); 
        $variants = Variant::with('product')->inRandomOrder()->take(5)->get();
        if ($variants->isEmpty()) {
            $this->command->warn('No variants found in database. Please run ProductSeeder first if you want real items.');
        }
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'];
        $gateways = ['stripe', 'payhere', 'cod'];
        for ($i = 1; $i <= 15; $i++) {
            $status = $statuses[array_rand($statuses)];
            $payStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT) . strtoupper(Str::random(3)),
                'user_id' => $user ? $user->id : null,
                'customer_id' => $customer->id,
                'customer_name' => $customer->first_name . ' ' . $customer->last_name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone,
                'status' => $status,
                'payment_method' => $gateways[array_rand($gateways)],
                'payment_status' => $payStatus,
                'subtotal' => 0, 
                'discount_amount' => rand(0, 1) ? rand(5, 20) : 0,
                'shipping_amount' => 15,
                'tax_amount' => 0,
                'total_amount' => 0, 
                'currency' => 'LKR',
                'placed_at' => now()->subDays(rand(1, 40))->subHours(rand(1, 24)),
                'notes' => rand(0, 1) ? 'Please leave at the front door.' : null,
                'internal_notes' => rand(0, 1) ? 'Customer called to confirm order' : null,
            ]);
            $subtotal = 0;
            if ($variants->count() > 0) {
                $itemCount = rand(1, 3);
                $selectedVariants = $variants->random($itemCount);
                foreach ($selectedVariants as $variant) {
                    $qty = rand(1, 3);
                    $price = $variant->sale_price ?? $variant->price ?? rand(500, 2500);
                    $rowTotal = $qty * $price;
                    $subtotal += $rowTotal;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'variant_id' => $variant->id,
                        'product_name_snapshot' => $variant->product->name . ' - ' . $variant->sku,
                        'variant_attributes' => json_encode(['Size' => 'M', 'Color' => 'Red']), 
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'subtotal' => $rowTotal,
                        'total' => $rowTotal,
                    ]);
                }
            } else {
                $price = rand(1000, 5000);
                $subtotal += $price;
                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => null,
                    'product_name_snapshot' => 'Dummy Product ' . $i,
                    'quantity' => 1,
                    'unit_price' => $price,
                    'subtotal' => $price,
                    'total' => $price,
                ]);
            }
            $totalAmount = $subtotal - $order->discount_amount + $order->shipping_amount + $order->tax_amount;
            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
            ]);
            PaymentTransaction::create([
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'gateway' => $order->payment_method,
                'transaction_id' => 'TXN' . strtoupper(Str::random(10)),
                'amount' => $totalAmount,
                'currency' => 'LKR',
                'status' => $payStatus === 'paid' ? 'completed' : ($payStatus === 'failed' ? 'failed' : 'pending'),
                'is_manual' => $order->payment_method === 'cod',
            ]);
            if (in_array($payStatus, ['refunded', 'partially_refunded'])) {
                $refundAmount = $payStatus === 'refunded' ? $totalAmount : ($totalAmount / 2);
                OrderRefund::create([
                    'order_id' => $order->id,
                    'amount' => $refundAmount,
                    'reason' => 'Customer requested refund',
                    'status' => 'processed',
                    'refunded_at' => now()->subDays(1),
                    'performed_by' => null,
                ]);
            }
        }
    }
}
