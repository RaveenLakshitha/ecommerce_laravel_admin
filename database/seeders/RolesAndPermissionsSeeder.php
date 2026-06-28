<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Admin;
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'dashboard' => ['view'],
            'products' => ['index', 'create', 'edit', 'delete'],
            'categories' => ['index', 'create', 'edit', 'delete'],
            'brands' => ['index', 'create', 'edit', 'delete'],
            'attributes' => ['index', 'create', 'edit', 'delete'],
            'tags' => ['index', 'create', 'edit', 'delete'],
            'variants' => ['index', 'create', 'edit', 'delete'],
            'collections' => ['index', 'create', 'edit', 'delete'],
            'orders' => ['index', 'create', 'edit', 'delete', 'refund'],
            'invoices' => ['index', 'create', 'edit', 'delete', 'pos'],
            'cash-registers' => ['index', 'create', 'edit', 'delete', 'transactions'],
            'customers' => ['index', 'create', 'edit', 'delete'],
            'subscribers' => ['index', 'create', 'edit', 'delete'],
            'promotions' => ['index', 'create', 'edit', 'delete'],
            'shipments' => ['index', 'create', 'edit', 'delete'],
            'couriers' => ['index', 'create', 'edit', 'delete'],
            'reports' => ['index', 'financial', 'inventory', 'sales'],
            'users' => ['index', 'create', 'edit', 'delete'],
            'roles' => ['index', 'create', 'edit', 'delete'],
            'settings' => ['index', 'create', 'edit', 'delete', 'general'],
            'dropdowns' => ['index', 'create', 'edit', 'delete'],
        ];
        $allPermissions = [];
        foreach ($permissions as $section => $actions) {
            foreach ($actions as $action) {
                $permissionName = "{$section}.{$action}";
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'admin']);
                $allPermissions[] = $permissionName;
            }
        }
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $role->syncPermissions($allPermissions);
        $admin = Admin::first();
        if ($admin) {
            $admin->assignRole($role);
        }
    }
}
