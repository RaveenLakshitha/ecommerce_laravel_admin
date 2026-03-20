{{-- partials/sidebar-sub-item.blade.php
Required vars: $route, $active (bool), $label
--}}
<a href="{{ Route::has($route) ? route($route) : '#' }}"
     class="block px-3 py-2 text-sm rounded-lg transition-all duration-300 sidebar-item-hover {{ $active ? 'sidebar-sub-active font-semibold' : 'text-gray-600 dark:text-gray-200' }}">
     {{ $label }}
</a>

