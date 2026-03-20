<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-screen">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Manager') | {{ $site_name ?? 'App' }}</title>

    <link rel="icon" href="{{ $site_logo ?? asset('images/default-logo.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

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
    <style>
        html {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        body { font-family: 'DM Sans', system-ui, sans-serif; }
        [data-tooltip] {
            position: relative;
        }
        [data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 50%;
            top: 100%;
            transform: translateX(-50%);
            margin-top: 8px;
            background: #1f2937;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s;
            z-index: 50;
        }
        [data-tooltip]:hover::after {
            opacity: 1;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Dark Mode Scrollbar */
        .dark ::-webkit-scrollbar-track { background: #111113; }
        .dark ::-webkit-scrollbar-thumb { background: #2e2e35; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #3a3a45; }
    </style>
</head>

<body class="h-screen overflow-hidden bg-gray-50 dark:bg-surface-tonal-a10 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden flex-col">
        <!-- Header -->
        <header class="bg-white dark:bg-surface-tonal-a20 shadow-sm border-b border-gray-200 dark:border-surface-tonal-a30 flex-shrink-0 z-40">
            <div class="flex items-center justify-between px-4 sm:px-6 py-2 h-12">
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="{{ route('orders.index') }}" class="p-2 -ml-2 rounded-lg hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 text-gray-500 dark:text-gray-400 transition-colors focus:ring-2 focus:ring-accent/50 outline-none" data-tooltip="Back to Orders">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5"></path>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                    </a>
                    <h1 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-primary-a0 tracking-tight">
                        @yield('title', 'Order Fulfillment Manager')
                    </h1>
                </div>
                
                <div class="flex items-center gap-1 sm:gap-4">
                    <!-- Language Changer -->
                    <div x-data="{ open: false }" x-init="open = false" class="relative">
                        <button @click="open = !open"
                            class="flex items-center space-x-1 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-accent focus:outline-none focus:ring-2 focus:ring-accent/30 rounded-md px-2 py-1 transition">
                            <span>{{ strtoupper(app()->getLocale() ?? 'EN') }}</span>
                            <svg class="w-4 h-4 transition-transform hidden sm:block" :class="{ 'rotate-180': open }" fill="none"
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
                            @if(Route::has('language.switch'))
                            <form method="POST" action="{{ route('language.switch') }}">
                                @csrf
                                <input type="hidden" name="locale" value="en">
                                <button type="submit"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    {{ __('English') }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('language.switch') }}">
                                @csrf
                                <input type="hidden" name="locale" value="es">
                                <button type="submit"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    {{ __('Español') }}
                                </button>
                            </form>
                            @else
                            <div class="px-4 py-2.5 text-sm text-gray-500">Language routes missing</div>
                            @endif
                        </div>
                    </div>

                    <!-- Dark Mode Changer -->
                    <button id="theme-toggle-navbar"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition-colors text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-accent/50 group">
                        <svg id="sun-icon-navbar" class="w-5 h-5 hidden group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg id="moon-icon-navbar" class="w-5 h-5 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Fullscreen Changer -->
                    <button id="fullscreen-toggle" aria-label="Toggle fullscreen"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-surface-tonal-a30 transition-colors text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-accent/50">
                        <svg id="enter-fullscreen-icon" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 3H5a2 2 0 00-2 2v3M16 3h3a2 2 0 012 2v3M8 21H5a2 2 0 01-2-2v-3M16 21h3a2 2 0 002-2v-3" />
                        </svg>
                        <svg id="exit-fullscreen-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 9H5V5M15 9h4V5M9 15H5v4M15 15h4v4" />
                        </svg>
                    </button>
                    
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 min-h-0 overflow-hidden bg-gray-50 dark:bg-surface-tonal-a10 flex flex-col">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    
    <script>
        const html = document.documentElement;
        const themeToggle = document.getElementById('theme-toggle-navbar');
        const sunIcon = document.getElementById('sun-icon-navbar');
        const moonIcon = document.getElementById('moon-icon-navbar');

        function applyTheme(theme) {
            if (theme === 'dark') {
                html.classList.add('dark');
                sunIcon?.classList.remove('hidden');
                moonIcon?.classList.add('hidden');
            } else {
                html.classList.remove('dark');
                sunIcon?.classList.add('hidden');
                moonIcon?.classList.remove('hidden');
            }
            localStorage.setItem('theme', theme);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            applyTheme(saved || (prefersDark ? 'dark' : 'light'));
        });

        themeToggle?.addEventListener('click', () => {
            applyTheme(html.classList.contains('dark') ? 'light' : 'dark');
        });

        const fullscreenToggle = document.getElementById('fullscreen-toggle');
        const enterIcon = document.getElementById('enter-fullscreen-icon');
        const exitIcon = document.getElementById('exit-fullscreen-icon');

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
            document.addEventListener(event, () => {
                updateFullscreenIcon();
                if (document.fullscreenElement) {
                    localStorage.setItem('lastFullscreenTime', Date.now().toString());
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            updateFullscreenIcon();
            if (localStorage.getItem('autoFullscreen') === 'true') {
                const lastTime = localStorage.getItem('lastFullscreenTime');
                const now = Date.now();
                if (lastTime && (now - parseInt(lastTime)) < 5000) {
                    setTimeout(enterFullscreen, 50);
                }
            }
        });
    </script>

    <!-- Dynamic Notifications Container -->
    <div id="notification-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

    <script>
        function showNotification(message, type = 'success', duration = 3000) {
            const container = document.getElementById('notification-container');
            if(!container) return;
            const notification = document.createElement('div');

            const icons = {
                success: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
                error: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
                info: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            };

            const colors = {
                success: 'from-emerald-500 to-teal-600 shadow-emerald-500/20',
                error: 'from-rose-500 to-red-600 shadow-rose-500/20',
                info: 'from-blue-500 to-indigo-600 shadow-blue-500/20'
            };

            notification.className = `pointer-events-auto flex items-center min-w-[320px] max-w-md bg-gradient-to-r ${colors[type] || colors.info} text-white p-4 rounded-2xl shadow-2xl transform translate-x-full transition-all duration-500 ease-out opacity-0 backdrop-blur-md border border-white/10`;
            notification.style.setProperty('--duration', `${duration}ms`);

            notification.innerHTML = `
                <div class="flex-shrink-0 bg-white/20 p-2 rounded-xl backdrop-blur-sm mr-3">
                    ${icons[type] || icons.info}
                </div>
                <div class="flex-grow font-medium text-sm pr-2">
                    ${message}
                </div>
                <button class="flex-shrink-0 hover:bg-white/20 p-1 rounded-lg transition-colors ml-2" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="absolute bottom-0 left-0 h-1 bg-white/30 rounded-full overflow-hidden" style="width: 100%">
                    <div class="h-full bg-white/50 animate-progress" style="animation-duration: var(--duration)"></div>
                </div>
            `;

            container.appendChild(notification);

            requestAnimationFrame(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
            });

            setTimeout(() => {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => notification.remove(), 500);
            }, duration);
        }
    </script>
</body>
</html>
