<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Admin;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define sections/models and their specific permissions
        $permissions = [
            'dashboard' => ['view'],
            
            // Catalog
            'products' => ['index', 'create', 'edit', 'delete'],
            'categories' => ['index', 'create', 'edit', 'delete'],
            'brands' => ['index', 'create', 'edit', 'delete'],
            'attributes' => ['index', 'create', 'edit', 'delete'],
            'tags' => ['index', 'create', 'edit', 'delete'],
            'variants' => ['index', 'create', 'edit', 'delete'],
            'collections' => ['index', 'create', 'edit', 'delete'],
            
            // Sales & Billing
            'orders' => ['index', 'create', 'edit', 'delete', 'refund'],
            'invoices' => ['index', 'create', 'edit', 'delete', 'pos'],
            'cash-registers' => ['index', 'create', 'edit', 'delete', 'transactions'],
            
            // Customers
            'customers' => ['index', 'create', 'edit', 'delete'],
            'subscribers' => ['index', 'create', 'edit', 'delete'],
            
            // Marketing
            'promotions' => ['index', 'create', 'edit', 'delete'],
            
            // Shipping
            'shipments' => ['index', 'create', 'edit', 'delete'],
            'couriers' => ['index', 'create', 'edit', 'delete'],
            
            // Reports
            'reports' => ['index', 'financial', 'inventory', 'sales'],
            
            // Administration
            'users' => ['index', 'create', 'edit', 'delete'],
            'roles' => ['index', 'create', 'edit', 'delete'],
            'settings' => ['index', 'create', 'edit', 'delete', 'general'],
            'dropdowns' => ['index', 'create', 'edit', 'delete'],
        ];

        $allPermissions = [];

        foreach ($permissions as $section => $actions) {
            foreach ($actions as $action) {
                // e.g. products.index, products.create
                $permissionName = "{$section}.{$action}";
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'admin']);
                $allPermissions[] = $permissionName;
            }
        }

        // Create Super Admin role and assign all permissions
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $role->syncPermissions($allPermissions);

        // Assign role to the first admin if exists
        $admin = Admin::first();
        if ($admin) {
            $admin->assignRole($role);
        }
    }
}
