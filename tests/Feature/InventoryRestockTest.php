<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InventoryRestockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate Admin user
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->actingAs($admin, 'admin');

        // Insert a dummy user in users table with same ID to satisfy performed_by foreign key constraint
        DB::table('users')->insert([
            'id' => $admin->id,
            'name' => 'Admin User Mirror',
            'email' => 'admin-mirror@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Bypass permission middleware specifically so route model binding still works
        $this->withoutMiddleware([
            \Spatie\Permission\Middleware\PermissionMiddleware::class,
            \Spatie\Permission\Middleware\RoleMiddleware::class,
        ]);
    }

    /** @test */
    public function test_inventory_index_can_load_preselected_variant()
    {
        // Create Category, Product, Variant
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'base_price' => 10.00,
            'category_id' => $category->id,
            'is_visible' => true,
        ]);

        $variant = Variant::create([
            'product_id' => $product->id,
            'sku' => 'TEST-SKU',
            'price' => 10.00,
            'stock_quantity' => 5,
        ]);

        // Request inventory page with variant_id
        $response = $this->get(route('inventory.index', ['variant_id' => $variant->id]));

        $response->assertStatus(200);
        $response->assertViewHas('preselectedVariant');
        $response->assertSee('TEST-SKU');
    }

    /** @test */
    public function test_inventory_adjust_with_add_type_logs_restock()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'base_price' => 10.00,
            'category_id' => $category->id,
            'is_visible' => true,
        ]);

        $variant = Variant::create([
            'product_id' => $product->id,
            'sku' => 'TEST-SKU',
            'price' => 10.00,
            'stock_quantity' => 5,
        ]);

        // Post stock adjustment: Add
        $response = $this->post(route('inventory.adjust', $variant->id), [
            'adjustment_type' => 'add',
            'quantity' => 10,
            'notes' => 'Restocking test',
        ]);

        $response->assertRedirect(route('inventory.index'));
        $response->assertSessionHas('success', 'Stock adjusted successfully.');

        // Assert stock was incremented
        $variant->refresh();
        $this->assertEquals(15, $variant->stock_quantity);

        // Assert transaction was logged as 'restock'
        $transaction = InventoryTransaction::where('variant_id', $variant->id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals(10, $transaction->quantity_change);
        $this->assertEquals('restock', $transaction->type);
        $this->assertEquals('Restocking test', $transaction->notes);
    }

    /** @test */
    public function test_inventory_adjust_with_subtract_type_logs_adjustment()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'base_price' => 10.00,
            'category_id' => $category->id,
            'is_visible' => true,
        ]);

        $variant = Variant::create([
            'product_id' => $product->id,
            'sku' => 'TEST-SKU',
            'price' => 10.00,
            'stock_quantity' => 15,
        ]);

        // Post stock adjustment: Subtract
        $response = $this->post(route('inventory.adjust', $variant->id), [
            'adjustment_type' => 'subtract',
            'quantity' => 5,
            'notes' => 'Subtracting test',
        ]);

        $response->assertRedirect(route('inventory.index'));
        $response->assertSessionHas('success', 'Stock adjusted successfully.');

        // Assert stock was decremented
        $variant->refresh();
        $this->assertEquals(10, $variant->stock_quantity);

        // Assert transaction was logged as 'adjustment'
        $transaction = InventoryTransaction::where('variant_id', $variant->id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals(-5, $transaction->quantity_change);
        $this->assertEquals('adjustment', $transaction->type);
        $this->assertEquals('Subtracting test', $transaction->notes);
    }
}
