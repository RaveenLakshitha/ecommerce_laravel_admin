<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.index', ['only' => ['index', 'show']]);
        $this->middleware('permission:roles.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles.delete', ['only' => ['destroy']]);
    }

    /**
     * Roles that are considered "system" roles.
     * Their names cannot be changed and they cannot be deleted.
     * Only their permissions may be edited.
     */
    protected array $systemRoles = ['admin'];

    /**
     * Only these CRUD actions are shown in the permissions matrix.
     */
    protected array $allowedActions = ['index', 'create', 'edit', 'delete'];

    /**
     * Build the permissions matrix:
     * [ 'resource' => [ 'index' => Permission|null, 'create' => ..., 'edit' => ..., 'delete' => ... ] ]
     */
    protected function getPermissionsMatrix(): \Illuminate\Support\Collection
    {
        return Permission::orderBy('name')
            ->get()
            ->filter(fn($p) => in_array(Str::after($p->name, '.'), $this->allowedActions))
            ->groupBy(fn($p) => Str::before($p->name, '.'))
            ->map(fn($group) => $group->keyBy(fn($p) => Str::after($p->name, '.')))
            ->sortKeys();
    }

    public function index()
    {
        $systemRoles = $this->systemRoles;
        return view('admin.roles.index', compact('systemRoles'));
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $searchValue = trim($request->input('search.value', ''));

        $query = Role::with('permissions');
        
        if ($searchValue !== '') {
            $query->where('name', 'like', "%{$searchValue}%");
        }

        $totalRecords = Role::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'name',
            default => 'created_at',
        };

        if ($sortColumn === 'created_at') {
            $query->orderBy('created_at', $orderDir);
        } else {
            $query->orderBy($sortColumn, $orderDir);
        }
        
        $roles = $query->offset($start)->limit($length)->get();

        $data = $roles->map(function ($role) {
            $isSystem = in_array($role->name, $this->systemRoles);
            
            $roleLabelKey = 'file.role_' . $role->name;
            $roleLabel = __($roleLabelKey) !== $roleLabelKey ? __($roleLabelKey) : ucfirst(str_replace('_', ' ', $role->name));
            
            $nameHtml = '<div class="flex items-center gap-2"><span class="text-sm font-medium text-gray-900 dark:text-primary-a0">' . htmlspecialchars($roleLabel) . '</span>';
            if ($isSystem) {
                $nameHtml .= '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-700"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>' . __('file.system_role') . '</span>';
            }
            $nameHtml .= '</div>';
            
            // Permissions count
            $permissionsCount = $role->permissions->count();
            $permissionsHtml = '<span class="text-sm text-gray-600 dark:text-gray-400">' . $permissionsCount . ' ' . \Illuminate\Support\Str::plural(__('file.permissions'), $permissionsCount) . '</span>';

            return [
                'id' => $role->id,
                'name_html' => $nameHtml,
                'permissions_html' => $permissionsHtml,
                'is_system' => $isSystem,
                'edit_url' => route('roles.edit', $role->id),
                'delete_url' => route('roles.destroy', $role->id),
                'name' => $role->name,
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }

    public function create()
    {
        $permissionsMatrix = $this->getPermissionsMatrix();
        $allowedActions = $this->allowedActions;

        return view('admin.roles.create', compact('permissionsMatrix', 'allowedActions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')
            ->with('success', __('file.role_created_successfully'));
    }

    public function edit(Role $role)
    {
        $permissionsMatrix = $this->getPermissionsMatrix();
        $allowedActions = $this->allowedActions;

        $role->load('permissions');
        $isSystemRole = in_array($role->name, $this->systemRoles);

        return view('admin.roles.edit', compact('role', 'permissionsMatrix', 'allowedActions', 'isSystemRole'));
    }

    public function update(Request $request, Role $role)
    {
        $isSystemRole = in_array($role->name, $this->systemRoles);

        if ($isSystemRole) {
            $request->validate(['permissions' => 'nullable|array']);
        }
        else {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
                'permissions' => 'nullable|array',
            ]);
            $role->update(['name' => $request->name]);
        }

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')
            ->with('success', __('file.role_updated_successfully'));
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if (in_array($role->name, $this->systemRoles)) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => __('file.cannot_delete_system_role')], 403);
            }
            return redirect()->route('roles.index')
                ->with('error', __('file.cannot_delete_system_role'));
        }

        $role->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.role_deleted_successfully')]);
        }

        return redirect()->route('roles.index')
            ->with('success', __('file.role_deleted_successfully'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids ?? '')));
        }

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.'], 400);
        }

        \Spatie\Permission\Models\Role::whereIn('id', $ids)
                                      ->whereNotIn('name', $this->systemRoles)
                                      ->delete();

        return response()->json([
            'success' => true,
            'message' => __('file.role_deleted_successfully')
        ]);
    }
}
