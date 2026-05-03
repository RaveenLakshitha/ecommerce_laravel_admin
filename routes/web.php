<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

// Redirect root domain (karbnzol.com) → shop subdomain
Route::domain('karbnzol.com')->group(function () {
    Route::redirect('/', 'http://shop.karbnzol.com', 301);
    Route::redirect('/{any}', 'http://shop.karbnzol.com/{any}', 301)->where('any', '.*');
});

Route::domain('shop.karbnzol.com')->group(function () {
    Route::get('/wheels', [App\Http\Controllers\Frontend\WheelsController::class, 'index'])->name('homewheels');
});

// Customer / Shop routes
Route::domain('shop.karbnzol.com')->group(function () {

    Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');

    Route::get('/about', [App\Http\Controllers\Frontend\HomeController::class, 'about'])->name('frontend.about');

    Route::get('/products', [App\Http\Controllers\Frontend\ProductController::class, 'index'])->name('frontend.products.index');
    Route::get('/products/{slug}', [App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('frontend.products.show');

    Route::get('/collections', [App\Http\Controllers\Frontend\CollectionController::class, 'index'])->name('frontend.collections.index');

    // Newsletter subscription
    Route::post('/newsletter/subscribe', [App\Http\Controllers\Frontend\NewsletterController::class, 'subscribe'])
        ->name('newsletter.subscribe');

    // Cart routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [App\Http\Controllers\Frontend\CartController::class, 'index'])->name('index');
        Route::post('/add/{variant}', [App\Http\Controllers\Frontend\CartController::class, 'add'])->name('add');
        Route::patch('/update', [App\Http\Controllers\Frontend\CartController::class, 'update'])->name('update');
        Route::delete('/remove/{rowId}', [App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('remove');
        Route::post('/promo', [App\Http\Controllers\Frontend\CartController::class, 'applyPromo'])->name('promo.apply');
        Route::delete('/promo', [App\Http\Controllers\Frontend\CartController::class, 'removePromo'])->name('promo.remove');
    });

    // Checkout routes
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [App\Http\Controllers\Frontend\CheckoutController::class, 'process'])->name('process');
        Route::get('/success', [App\Http\Controllers\Frontend\CheckoutController::class, 'success'])->name('success');
        Route::post('/stripe/confirm', [App\Http\Controllers\Frontend\CheckoutController::class, 'stripeConfirm'])->name('stripe.confirm');
        Route::get('/stripe/pay', [App\Http\Controllers\Frontend\CheckoutController::class, 'stripePayPage'])->name('stripe.pay');
        Route::get('/stripe/return', [App\Http\Controllers\Frontend\CheckoutController::class, 'stripeReturn'])->name('stripe.return');
    });

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Google OAuth (Socialite)
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

    Route::middleware('auth:web')->group(function () {
        Route::get('/account', [\App\Http\Controllers\Frontend\AccountController::class, 'index'])->name('account.dashboard');

        // Profile update
        Route::put('/account/profile', [\App\Http\Controllers\Frontend\AccountController::class, 'updateProfile'])->name('account.profile.update');
        Route::put('/account/password', [\App\Http\Controllers\Frontend\AccountController::class, 'updatePassword'])->name('account.password.update');

        // Orders
        Route::get('/account/orders/{order}', [\App\Http\Controllers\Frontend\AccountController::class, 'showOrder'])->name('account.orders.show');
        Route::post('/account/orders/{order}/refund', [\App\Http\Controllers\Frontend\AccountController::class, 'requestRefund'])->name('account.orders.refund-request');

        // Address management
        Route::post('/account/addresses', [\App\Http\Controllers\Frontend\AccountController::class, 'storeAddress'])->name('account.addresses.store');
        Route::put('/account/addresses/{address}', [\App\Http\Controllers\Frontend\AccountController::class, 'updateAddress'])->name('account.addresses.update');
        Route::delete('/account/addresses/{address}', [\App\Http\Controllers\Frontend\AccountController::class, 'destroyAddress'])->name('account.addresses.destroy');
        Route::patch('/account/addresses/{address}/default', [\App\Http\Controllers\Frontend\AccountController::class, 'setDefaultAddress'])->name('account.addresses.set-default');
    });

});

// Language switch
Route::post('/language', [LanguageController::class, 'switch'])->name('language.switch');

// Stripe Webhook (CSRF excluded in bootstrap/app.php)
Route::post('/stripe/webhook', [App\Http\Controllers\Frontend\StripeWebhookController::class, 'handle'])->name('stripe.webhook');

// Admin routes
Route::domain('admin.karbnzol.com')->group(function () {
    Route::get('/', function () {
        return auth('admin')->check()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('admin.login');
    });

    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        // Roles & Permissions
        Route::get('roles/datatable', [\App\Http\Controllers\Admin\RoleController::class, 'datatable'])->name('roles.datatable');
        Route::post('roles/bulk-delete', [\App\Http\Controllers\Admin\RoleController::class, 'bulkDelete'])->name('roles.bulkDelete');
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);

        // Brands Management
        Route::get('brands/datatable', [\App\Http\Controllers\Admin\BrandController::class, 'datatable'])->name('brands.datatable');
        Route::post('brands/bulk-delete', [\App\Http\Controllers\Admin\BrandController::class, 'bulkDelete'])->name('brands.bulkDelete');
        Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);

        // Product Management
        Route::get('products/datatable', [\App\Http\Controllers\Admin\ProductController::class, 'datatable'])->name('products.datatable');
        Route::post('products/bulk-delete', [\App\Http\Controllers\Admin\ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::delete('products/{product}/images/{image}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.images.destroy');

        // Product Variants
        Route::resource('products.variants', \App\Http\Controllers\Admin\VariantController::class)->except(['index', 'show']);
        Route::delete('products/{product}/variants/{variant}/images/{image}', [\App\Http\Controllers\Admin\VariantController::class, 'deleteImage'])->name('products.variants.images.destroy');

        // Categories Management
        Route::get('categories/manager', [\App\Http\Controllers\CategoryController::class, 'tree'])->name('categories.tree');
        Route::get('categories/datatable', [\App\Http\Controllers\CategoryController::class, 'datatable'])->name('categories.datatable');
        Route::get('categories/{category}/details', [\App\Http\Controllers\CategoryController::class, 'details'])->name('categories.details');
        Route::post('categories/bulk-delete', [\App\Http\Controllers\CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
        Route::resource('categories', \App\Http\Controllers\CategoryController::class);

        // Collections Management
        Route::get('collections/datatable', [\App\Http\Controllers\Admin\CollectionController::class, 'datatable'])->name('collections.datatable');
        Route::post('collections/bulk-delete', [\App\Http\Controllers\Admin\CollectionController::class, 'bulkDelete'])->name('collections.bulkDelete');
        Route::resource('collections', \App\Http\Controllers\Admin\CollectionController::class);

        // Attributes Management
        Route::get('attributes/datatable', [\App\Http\Controllers\Admin\AttributeController::class, 'datatable'])->name('attributes.datatable');
        Route::post('attributes/bulk-delete', [\App\Http\Controllers\Admin\AttributeController::class, 'bulkDelete'])->name('attributes.bulkDelete');
        Route::resource('attributes', \App\Http\Controllers\Admin\AttributeController::class);
        Route::post('attributes/{attribute}/values', [\App\Http\Controllers\Admin\AttributeController::class, 'storeValue'])->name('attributes.values.store');
        Route::delete('attributes/{attribute}/values/{value}', [\App\Http\Controllers\Admin\AttributeController::class, 'destroyValue'])->name('attributes.values.destroy');

        // CRM & Customers
        Route::get('customers/datatable', [\App\Http\Controllers\Admin\CustomerController::class, 'datatable'])->name('customers.datatable');
        Route::post('customers/bulk-delete', [\App\Http\Controllers\Admin\CustomerController::class, 'bulkDelete'])->name('customers.bulkDelete');
        Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'show', 'destroy']);
        Route::post('customers/{customer}/notes', [\App\Http\Controllers\Admin\CustomerController::class, 'addNote'])->name('customers.notes.add');
        Route::post('customers/{customer}/tags', [\App\Http\Controllers\Admin\CustomerController::class, 'syncTags'])->name('customers.tags.sync');

        Route::get('subscribers/datatable', [\App\Http\Controllers\Admin\SubscriberController::class, 'datatable'])->name('subscribers.datatable');
        Route::post('subscribers/bulk-delete', [\App\Http\Controllers\Admin\SubscriberController::class, 'bulkDelete'])->name('subscribers.bulkDelete');
        Route::resource('subscribers', \App\Http\Controllers\Admin\SubscriberController::class)->only(['index', 'destroy']);

        Route::get('reviews/datatable', [\App\Http\Controllers\Admin\ReviewController::class, 'datatable'])->name('reviews.datatable');
        Route::post('reviews/bulk-delete', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkDelete'])->name('reviews.bulkDelete');
        Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'update', 'destroy']);
        // Orders Management
        Route::get('orders/manager', [\App\Http\Controllers\Admin\OrderController::class, 'manager'])->name('orders.manager');
        Route::get('orders/datatable', [\App\Http\Controllers\Admin\OrderController::class, 'datatable'])->name('orders.datatable');
        Route::post('orders/bulk-delete', [\App\Http\Controllers\Admin\OrderController::class, 'bulkDelete'])->name('orders.bulkDelete');
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'destroy']);
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'printInvoice'])->name('orders.invoice');
        Route::post('orders/{order}/refund', [\App\Http\Controllers\Admin\OrderController::class, 'processRefund'])->name('orders.refund');
        Route::post('orders/{order}/notes', [\App\Http\Controllers\Admin\OrderController::class, 'addNote'])->name('orders.notes.add');

        // Inventory Management
        Route::get('inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('inventory/datatable', [\App\Http\Controllers\Admin\InventoryController::class, 'datatable'])->name('inventory.datatable');
        Route::post('inventory/{variant}/adjust', [\App\Http\Controllers\Admin\InventoryController::class, 'adjust'])->name('inventory.adjust');
        Route::get('inventory/{variant}/history', [\App\Http\Controllers\Admin\InventoryController::class, 'history'])->name('inventory.history');

        Route::post('inventory/bulk-delete', [\App\Http\Controllers\Admin\InventoryController::class, 'bulkDelete'])->name('inventory.bulkDelete');
        Route::delete('inventory/{variant}', [\App\Http\Controllers\Admin\InventoryController::class, 'destroy'])->name('inventory.destroy');

        // Shipping & Delivery
        Route::prefix('shipping')->name('shipping.')->group(function () {
            Route::get('couriers/datatable', [\App\Http\Controllers\Admin\CourierController::class, 'datatable'])->name('couriers.datatable');
            Route::post('couriers/bulk-delete', [\App\Http\Controllers\Admin\CourierController::class, 'bulkDelete'])->name('couriers.bulkDelete');
            Route::resource('couriers', \App\Http\Controllers\Admin\CourierController::class);
            Route::get('zones/datatable', [\App\Http\Controllers\Admin\ShippingZoneController::class, 'datatable'])->name('zones.datatable');
            Route::post('zones/bulk-delete', [\App\Http\Controllers\Admin\ShippingZoneController::class, 'bulkDelete'])->name('zones.bulkDelete');
            Route::resource('zones', \App\Http\Controllers\Admin\ShippingZoneController::class);
            Route::get('pickups/datatable', [\App\Http\Controllers\Admin\PickupLocationController::class, 'datatable'])->name('pickups.datatable');
            Route::post('pickups/bulk-delete', [\App\Http\Controllers\Admin\PickupLocationController::class, 'bulkDelete'])->name('pickups.bulkDelete');
            Route::resource('pickups', \App\Http\Controllers\Admin\PickupLocationController::class);
            Route::get('rates/datatable', [\App\Http\Controllers\Admin\ShippingRateController::class, 'datatable'])->name('rates.datatable');
            Route::post('rates/bulk-delete', [\App\Http\Controllers\Admin\ShippingRateController::class, 'bulkDelete'])->name('rates.bulkDelete');
            Route::resource('rates', \App\Http\Controllers\Admin\ShippingRateController::class);
            Route::get('shipments/datatable', [\App\Http\Controllers\Admin\ShipmentController::class, 'datatable'])->name('shipments.datatable');
            Route::post('shipments/bulk-delete', [\App\Http\Controllers\Admin\ShipmentController::class, 'bulkDelete'])->name('shipments.bulkDelete');
            Route::resource('shipments', \App\Http\Controllers\Admin\ShipmentController::class)->only(['index', 'show', 'update', 'destroy', 'store']);
            Route::post('shipments/{shipment}/tracking', [\App\Http\Controllers\Admin\ShipmentController::class, 'addTracking'])->name('shipments.tracking');
        });

        // Discounts & Promotions
        Route::get('coupons/datatable', [\App\Http\Controllers\Admin\CouponController::class, 'datatable'])->name('coupons.datatable');
        Route::post('coupons/bulk-delete', [\App\Http\Controllers\Admin\CouponController::class, 'bulkDelete'])->name('coupons.bulkDelete');
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
        Route::get('discount-rules/datatable', [\App\Http\Controllers\Admin\DiscountRuleController::class, 'datatable'])->name('discount-rules.datatable');
        Route::post('discount-rules/bulk-delete', [\App\Http\Controllers\Admin\DiscountRuleController::class, 'bulkDelete'])->name('discount-rules.bulkDelete');
        Route::post('discount-rules/{discount_rule}/duplicate', [\App\Http\Controllers\Admin\DiscountRuleController::class, 'duplicate'])->name('discount-rules.duplicate');
        Route::resource('discount-rules', \App\Http\Controllers\Admin\DiscountRuleController::class);

        // Payments & Finances
        Route::get('transactions/datatable', [\App\Http\Controllers\Admin\PaymentTransactionController::class, 'datatable'])->name('transactions.datatable');
        Route::post('transactions/bulk-delete', [\App\Http\Controllers\Admin\PaymentTransactionController::class, 'bulkDelete'])->name('transactions.bulkDelete');
        Route::resource('transactions', \App\Http\Controllers\Admin\PaymentTransactionController::class)->only(['index', 'show', 'destroy']);
        Route::patch('transactions/{transaction}/mark-as-paid', [\App\Http\Controllers\Admin\PaymentTransactionController::class, 'markAsPaid'])->name('transactions.mark-as-paid');

        Route::get('refunds/datatable', [\App\Http\Controllers\Admin\RefundController::class, 'datatable'])->name('refunds.datatable');
        Route::post('refunds/bulk-delete', [\App\Http\Controllers\Admin\RefundController::class, 'bulkDelete'])->name('refunds.bulkDelete');
        Route::resource('refunds', \App\Http\Controllers\Admin\RefundController::class)->only(['index', 'show', 'destroy']);
        Route::patch('refunds/{refund}/approve', [\App\Http\Controllers\Admin\RefundController::class, 'approve'])->name('refunds.approve');
        Route::patch('refunds/{refund}/reject', [\App\Http\Controllers\Admin\RefundController::class, 'reject'])->name('refunds.reject');

        // General Settings
        Route::get('settings/general', [\App\Http\Controllers\SettingsController::class, 'general'])->name('settings.general');
        Route::put('settings/general', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

        Route::get('settings/payment-gateways', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'index'])->name('settings.payment-gateways');
        Route::patch('settings/payment-gateways/{gateway}', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'update'])->name('settings.payment-gateways.update');

        // Storefront Settings
        Route::get('settings/storefront', [\App\Http\Controllers\Admin\StorefrontController::class, 'index'])->name('admin.storefront.index');
        Route::put('settings/storefront', [\App\Http\Controllers\Admin\StorefrontController::class, 'update'])->name('admin.storefront.update');
    });
});