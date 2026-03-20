@php
    if(!function_exists('adjustBrightness')){
        function adjustBrightness($hex, $percent) {
            $hex = ltrim($hex, '#');
            if (strlen($hex) == 3) {
                $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            }
            if (!ctype_xdigit($hex)) return '#0f172a'; // fallback
            $rgb = array_map('hexdec', str_split($hex, 2));
            foreach ($rgb as &$value) {
                $value = max(0, min(255, $value + ($value * $percent / 100)));
            }
            return '#' . sprintf('%02x%02x%02x', ...$rgb);
        }
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $site_name ?? __('messages.Admin Portal') }}</title>

    <link rel="icon" href="{{ $site_logo ?? asset('images/default-logo.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: {{ $primary_color ?? '#0f172a' }}; /* Default modern dark blue (slate-900) */
            --primary-light: {{ adjustBrightness($primary_color ?? '#0f172a', 20) }};
            --primary-dark: {{ adjustBrightness($primary_color ?? '#0f172a', -20) }};
            --text-main: #1e293b;
        }

        body { font-family: 'Outfit', system-ui, -apple-system, sans-serif; background-color: #f8fafc; }

        .image-section {
            background-image: url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(51, 65, 85, 0.9) 100%);
            mix-blend-mode: multiply;
        }

        .image-content {
            position: relative;
            z-index: 10;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .form-container {
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .bg-pattern {
            background-image: radial-gradient(#94a3b8 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.2;
        }
    </style>
</head>
<body class="antialiased text-slate-800 selection:bg-slate-200 selection:text-slate-900 overflow-hidden">
    
    <div class="flex min-h-screen bg-slate-50 relative">
        <!-- Language Switcher -->
<div class="absolute top-5 right-6 lg:top-8 lg:right-10 z-50">
    <div x-data="{ langOpen: false }" class="relative">
        <button 
            @click="langOpen = !langOpen" 
            class="flex items-center space-x-2.5 
                   text-slate-700 bg-white/85 
                   backdrop-blur-md rounded-full 
                   px-5 py-2.5 
                   border border-slate-200 shadow-sm
                   focus:outline-none focus:ring-2 focus:ring-slate-400/40">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
            </svg>
            <span class="font-semibold text-sm tracking-wide">
                {{ app()->getLocale() == 'es' ? 'ES' : 'EN' }}
            </span>
            <svg class="w-4 h-4 transition-transform duration-200" 
                 :class="langOpen ? 'rotate-180' : ''" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div 
            x-show="langOpen" 
            @click.away="langOpen = false" 
            x-transition.opacity.duration.150
            class="absolute right-0 mt-3 w-44 
                   bg-slate-800 text-white 
                   rounded-xl shadow-xl border border-slate-700/30 
                   py-3 overflow-hidden z-50">

            <form action="{{ route('language.switch') }}" method="POST">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit" 
                        class="w-full text-left px-5 py-3 text-sm font-medium 
                               transition-colors duration-100
                               {{ app()->getLocale() == 'en' ? 'bg-slate-700/80' : 'hover:bg-slate-700/40' }}">
                    <span class="mr-3 text-base">🇺🇸</span>
                    {{ __('messages.English') }}
                </button>
            </form>

            <form action="{{ route('language.switch') }}" method="POST">
                @csrf
                <input type="hidden" name="locale" value="es">
                <button type="submit" 
                        class="w-full text-left px-5 py-3 text-sm font-medium 
                               transition-colors duration-100
                               {{ app()->getLocale() == 'es' ? 'bg-slate-700/80' : 'hover:bg-slate-700/40' }}">
                    <span class="mr-3 text-base">🇪🇸</span>
                    {{ __('messages.Español') }}
                </button>
            </form>
        </div>
    </div>
</div>

        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-pattern z-0 pointer-events-none"></div>

        <!-- Left Image Section (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 image-section relative items-center justify-center overflow-hidden">
            <div class="image-overlay"></div>
            
            <!-- Floating Elements Animation -->
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl mix-blend-overlay animate-pulse" style="animation-duration: 4s;"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-slate-400/20 rounded-full blur-3xl mix-blend-overlay animate-pulse" style="animation-duration: 6s;"></div>

            <div class="image-content text-white p-12 max-w-xl w-full">
                <div class="glass-panel p-8 rounded-3xl shadow-2xl transform transition-transform duration-700 hover:scale-[1.02]">
                    <div class="flex items-center space-x-4 mb-8">
                        @if($site_logo ?? false)
                            <img src="{{ $site_logo }}" alt="{{ $site_name ?? 'Admin' }}" class="w-16 h-16 rounded-2xl object-cover shadow-lg ring-4 ring-white/20">
                        @else
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center p-3 shadow-lg">
                                <svg class="w-10 h-10 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l8 3.556v7.11m-8 6.425l-8-3.556v-7.11"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10.5l8-3.556m-8 3.556l-8-3.556m8 3.556v10.944"></path>
                                </svg>
                            </div>
                        @endif
                        <h2 class="text-3xl font-bold tracking-tight">{{ $site_name ?? __('messages.Admin Portal') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative z-10 overflow-y-auto">
            <div class="w-full max-w-md form-container">
                {{ $slot }}
            </div>
        </div>
    </div>
    
    @stack('scripts')
    

</body>
</html>

