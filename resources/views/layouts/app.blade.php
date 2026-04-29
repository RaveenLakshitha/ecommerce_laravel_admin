<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-screen">

<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if(View::hasSection('title'))@yield('title') | {{ $site_name }}@else{{ $site_name }}@endif</title>

    <link rel="icon" href="{{ $site_logo ?? asset('images/default-logo.png') }}" type="image/png">

    {{-- DRAPE theme fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">


    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
</head>

<body
    class="min-h-screen bg-gray-50 dark:bg-surface-tonal-a10 text-gray-900 dark:text-gray-50 transition-colors duration-300 font-inter">

    <div id="notification-container"></div>

    @include('partials.sidebar')

    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div id="main-content" class="transition-all duration-300 lg:ml-64" style="margin-left: 0;">

        <nav id="navbar"
            class="fixed top-0 h-16 backdrop-blur-md shadow-sm border-b border-gray-200 dark:border-white/5 z-40 flex items-center justify-between px-6 transition-all duration-300 left-0 right-0 lg:left-64">
            <button id="mobile-menu-btn"
                class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="flex items-center space-x-4">
                <a href="{{ route('orders.manager') }}"
                    class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 border border-emerald-500/20 rounded-xl font-bold text-[11px] text-white uppercase tracking-wider transition-all shadow-lg shadow-emerald-600/20 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    {{ __('file.order_manager') }}
                </a>
            </div>

            <div class="flex items-center space-x-4">

                <div x-data="{ open: false }" x-init="open = false" class="relative">
                    <button @click="open = !open"
                        class="flex items-center space-x-1 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-accent focus:outline-none focus:ring-2 focus:ring-accent/30 rounded-md px-2 py-1 transition">
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-36 origin-top-right bg-white dark:bg-surface-tonal-a20 rounded-md shadow-lg ring-1 ring-gray-200 dark:ring-gray-700 z-50 overflow-hidden border border-gray-200 dark:border-surface-tonal-a30">
                        <form method="POST" action="{{ route('language.switch') }}">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <button type="submit"
                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                {{ __('file.english') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('language.switch') }}">
                            @csrf
                            <input type="hidden" name="locale" value="es">
                            <button type="submit"
                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                {{ __('file.spanish') }}
                            </button>
                        </form>
                    </div>
                </div>

                <button id="theme-toggle"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-200">
                    <svg id="sun-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="moon-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <button id="fullscreen-toggle" aria-label="Toggle fullscreen"
                    class="p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-accent/50 transition">
                    <svg id="enter-fullscreen-icon" class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 3H5a2 2 0 00-2 2v3M16 3h3a2 2 0 012 2v3M8 21H5a2 2 0 01-2-2v-3M16 21h3a2 2 0 002-2v-3" />
                    </svg>
                    <svg id="exit-fullscreen-icon" class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 9H5V5M15 9h4V5M9 15H5v4M15 15h4v4" />
                    </svg>
                </button>


                <div class="relative" x-data="{ open: false }" x-cloak x-init="open = false">
                    <button id="user-menu-button" @click="open = !open"
                        class="flex items-center space-x-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-accent focus:outline-none focus:ring-2 focus:ring-accent/30 rounded-md px-2 py-1 transition">
                        <img class="h-8 w-8 rounded-full object-cover ring-2 ring-gray-300 dark:ring-gray-600"
                            src="{{ auth()->user()?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()?->name) . '&background=6366f1&color=fff' }}"
                            alt="{{ auth()->user()?->name }}">
                        <span class="hidden sm:block">{{ auth()->user()?->name }}</span>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 origin-top-right bg-white dark:bg-surface-tonal-a20 rounded-md shadow-lg ring-1 ring-gray-200 dark:ring-gray-700 z-50 overflow-hidden border border-gray-200 dark:border-surface-tonal-a30">

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                {{ __('file.sign_out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="pt-32 p-4 sm:p-6 min-h-screen bg-gray-50 dark:bg-surface-tonal-a10"
            style="transition: background-color 0.3s;">
            @yield('content')
        </main>
    </div>

    {{-- Body-level drawers rendered outside stacking contexts --}}
    @stack('drawers')

    @stack('scripts')

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const navbar = document.getElementById('navbar');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const fullscreenToggle = document.getElementById('fullscreen-toggle');
        const enterIcon = document.getElementById('enter-fullscreen-icon');
        const exitIcon = document.getElementById('exit-fullscreen-icon');

        mobileMenuBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('hidden');
        });

        mobileOverlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
        });

        function isDesktop() {
            return window.innerWidth >= 1024;
        }

        function closeAllDropdowns() {
            sidebar.querySelectorAll('[x-data]').forEach(el => {
                if (el._x_dataStack && el._x_dataStack[0]) {
                    if (typeof el._x_dataStack[0].open !== 'undefined') {
                        el._x_dataStack[0].open = false;
                    }
                }
                if (window.Alpine && el.__x) {
                    Alpine.evaluate(el, 'open = false');
                }
            });
            setTimeout(() => {
                sidebar.querySelectorAll('[x-show]').forEach(dropdown => {
                    if (dropdown.style) {
                        dropdown.style.display = 'none';
                    }
                });
            }, 50);
        }

        toggleBtn?.addEventListener('click', () => {
            if (!isDesktop()) {
                sidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.add('hidden');
                return;
            }
            const collapsed = sidebar.classList.toggle('sidebar-collapsed');
            const iconExpanded = document.getElementById('icon-expanded');
            const iconCollapsed = document.getElementById('icon-collapsed');

            if (collapsed) {
                closeAllDropdowns();
                sidebar.style.width = '74px';
                mainContent.style.marginLeft = '74px';
                navbar.style.left = '74px';
                iconExpanded?.classList.replace('opacity-100', 'opacity-0');
                iconCollapsed?.classList.replace('opacity-0', 'opacity-100');
            } else {
                sidebar.style.width = '16rem';
                mainContent.style.marginLeft = '16rem';
                navbar.style.left = '16rem';
                iconExpanded?.classList.replace('opacity-0', 'opacity-100');
                iconCollapsed?.classList.replace('opacity-100', 'opacity-0');
            }
            localStorage.setItem('sidebarCollapsed', collapsed);
        });

        document.addEventListener('DOMContentLoaded', () => {
            sidebar?.addEventListener('click', (e) => {
                const collapsed = sidebar.classList.contains('sidebar-collapsed');
                if (collapsed) {
                    const button = e.target.closest('button[\\@click]');
                    if (button && button.getAttribute('@click')?.includes('open')) {
                        e.stopPropagation();
                        e.preventDefault();
                        return false;
                    }
                }
            }, true);
        });

        window.addEventListener('resize', () => {
            if (isDesktop()) {
                mobileOverlay.classList.add('hidden');
                sidebar.classList.remove('-translate-x-full');
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (collapsed) {
                    sidebar.classList.add('sidebar-collapsed');
                    closeAllDropdowns();
                    sidebar.style.width = '74px';
                    mainContent.style.marginLeft = '74px';
                    navbar.style.left = '74px';
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.style.width = '16rem';
                    mainContent.style.marginLeft = '16rem';
                    navbar.style.left = '16rem';
                }
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.style.width = '16rem';
                mainContent.style.marginLeft = '0';
                navbar.style.left = '0';
                sidebar.classList.add('-translate-x-full');
            }
        });

        if (isDesktop() && localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('sidebar-collapsed');
            closeAllDropdowns();
            sidebar.style.width = '74px';
            mainContent.style.marginLeft = '74px';
            navbar.style.left = '74px';
        } else if (isDesktop()) {
            mainContent.style.marginLeft = '16rem';
            navbar.style.left = '16rem';
        }

        const html = document.documentElement;
        const themeToggle = document.getElementById('theme-toggle');
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');

        function applyTheme(theme) {
            if (theme === 'dark') {
                html.classList.add('dark');
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                html.classList.remove('dark');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        }

        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        applyTheme(savedTheme || (prefersDark ? 'dark' : 'light'));

        themeToggle?.addEventListener('click', () => {
            const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });

        function enterFullscreen() {
            const elem = document.documentElement;
            const promise = elem.requestFullscreen?.() || elem.webkitRequestFullscreen?.() || elem.msRequestFullscreen?.();
            if (promise) {
                promise.then(() => {
                    localStorage.setItem('autoFullscreen', 'true');
                    localStorage.setItem('lastFullscreenTime', Date.now().toString());
                }).catch(() => { });
            }
        }

        function exitFullscreen() {
            const promise = document.exitFullscreen?.() || document.webkitExitFullscreen?.() || document.msExitFullscreen?.();
            if (promise) promise.catch(() => { });
            localStorage.removeItem('autoFullscreen');
            localStorage.removeItem('lastFullscreenTime');
        }

        function updateFullscreenIcon() {
            const isFullscreen = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
            enterIcon?.classList.toggle('hidden', isFullscreen);
            exitIcon?.classList.toggle('hidden', !isFullscreen);
        }

        fullscreenToggle?.addEventListener('click', () => {
            if (document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement) {
                exitFullscreen();
            } else {
                enterFullscreen();
            }
        });

        ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange'].forEach(event => {
            document.addEventListener(event, updateFullscreenIcon);
        });

        document.addEventListener('DOMContentLoaded', updateFullscreenIcon);

        function showNotification(title, message, type = 'info', duration = 5000) {
            const container = document.getElementById('notification-container');
            if (!container) return;

            const icons = {
                success: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                error: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                warning: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                info: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            };

            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.style.setProperty('--duration', `${duration}ms`);
            notification.innerHTML = `
            <div class="notification-icon">${icons[type] || icons.info}</div>
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" onclick="removeNotification(this)">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            ${duration > 0 ? '<div class="notification-progress"></div>' : ''}
        `;
            container.appendChild(notification);
            if (duration > 0) {
                setTimeout(() => {
                    if (notification && notification.parentNode) {
                        removeNotification(notification.querySelector('.notification-close'));
                    }
                }, duration);
            }
        }

        function removeNotification(button) {
            const notification = button.closest('.notification');
            if (notification) {
                notification.classList.add('removing');
                setTimeout(() => notification.remove(), 300);
            }
        }

        window.showNotification = showNotification;
        window.removeNotification = removeNotification;

        document.addEventListener('DOMContentLoaded', () => {
            if (performance.navigation.type === 2) return;

            @if(session('success'))
                showNotification('{{ __("Success") }}', "{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                showNotification('{{ __("Error") }}', "{{ session('error') }}", 'error');
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    showNotification('{{ __("Error") }}', "{{ addslashes($error) }}", 'error');
                @endforeach
            @endif
    });


    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>
