<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TelyuEvents - @yield('title', 'Student Hub')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        user: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
                    },
                    colors: {
                        'telkom-red': '#EE2E24',
                        'telkom-white': '#FFFFFF',
                        'telkom-black': '#1A1A1A',
                    },
                    spacing: {
                        'container': 'clamp(1rem, 5vw, 3rem)',
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .neo-box {
                @apply border-[3px] border-telkom-black shadow-[5px_5px_0px_0px_#1A1A1A] transition-all duration-200 ease-in-out;
            }
            .neo-box-hover {
                @apply hover:shadow-[2px_2px_0px_0px_#1A1A1A] hover:translate-x-[3px] hover:translate-y-[3px];
            }
            .neo-button {
                @apply bg-telkom-red text-telkom-white font-bold uppercase py-3 px-6 border-[3px] border-telkom-black shadow-[4px_4px_0px_0px_#1A1A1A];
            }
            .neo-button:hover {
                @apply shadow-[2px_2px_0px_0px_#1A1A1A] translate-x-[2px] translate-y-[2px];
            }
            .neo-button-default {
                @apply bg-telkom-white text-telkom-black font-bold uppercase py-3 px-6 border-[3px] border-telkom-black shadow-[4px_4px_0px_0px_#1A1A1A];
            }
            .neo-button-default:hover {
                @apply bg-gray-100 shadow-[2px_2px_0px_0px_#1A1A1A] translate-x-[2px] translate-y-[2px];
            }
            .neo-input {
                @apply w-full border-[3px] border-telkom-black p-3 font-medium outline-none bg-telkom-white shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)];
            }
            .neo-input:focus {
                @apply shadow-[6px_6px_0px_0px_#1A1A1A];
            }
        }
    </style>

    <!-- Alpine.js (CDN) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Smooth Transitions (Optional CDN, can be enabled if requested) -->
    <!-- <script src="https://unpkg.com/swup@4"></script> -->
</head>
<body class="bg-[#F0F0F0] text-telkom-black font-user antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-telkom-white border-b-4 border-telkom-black sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="text-3xl font-bold tracking-tighter hover:text-telkom-red transition-colors">
                        TELYU<span class="text-telkom-red">EVENTS</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('events.index') }}" class="font-bold hover:underline decoration-4 underline-offset-4 decoration-telkom-red">Events</a>
                    <a href="#" class="font-bold hover:underline decoration-4 underline-offset-4 decoration-telkom-red">Competitions</a>
                

                    <!-- Auth Buttons -->
                    <!-- Auth Buttons -->
                    @guest
                        <a href="{{ route('login') }}" class="neo-button-default text-sm mr-4">Login</a>
                        <a href="{{ route('register') }}" class="neo-button text-sm">
                            Join Now
                        </a>
                    @endguest

                    @auth
                        <a href="{{ route('dashboard') }}" class="font-bold hover:underline decoration-4 underline-offset-4 decoration-telkom-red">Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="font-bold hover:underline decoration-4 underline-offset-4 decoration-telkom-red ml-4">My Identity</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="neo-button text-sm bg-telkom-black hover:bg-telkom-red">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>

                <!-- Mobile Menu Button (Alpine) -->
                <div class="-mr-2 flex items-center md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-telkom-black hover:bg-telkom-red hover:text-white focus:outline-none border-2 border-telkom-black shadow-[2px_2px_0px_#000]">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    
                    <!-- Mobile Menu Dropdown -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="absolute top-20 right-0 w-full bg-telkom-white border-b-4 border-telkom-black p-4 shadow-lg flex flex-col space-y-4">
                        <a href="#" class="font-bold text-lg">Events</a>
                        <a href="#" class="font-bold text-lg">Competitions</a>
                        <div class="flex space-x-4">
                            <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'font-black text-telkom-red' : '' }}">EN</a>
                            <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'font-black text-telkom-red' : '' }}">ID</a>
                        </div>
                        @guest
                            <a href="{{ route('login') }}" class="neo-button-default text-center">Login</a>
                            <a href="{{ route('register') }}" class="neo-button text-center">Join Now</a>
                        @endguest
                        @auth
                            <a href="{{ route('dashboard') }}" class="font-bold text-center">My Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="font-bold text-center">My Identity</a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="neo-button text-center w-full bg-telkom-black hover:bg-telkom-red">Logout</button>
                            </form>
                        @endauth
                        
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 neo-box bg-green-400 p-4 relative">
                <div class="flex justify-between items-center">
                    <p class="font-bold text-telkom-black text-lg">{{ session('success') }}</p>
                    <button @click="show = false" class="font-bold hover:text-white">&times;</button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-telkom-black text-telkom-white py-8 border-t-4 border-telkom-black mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="font-bold tracking-wider">&copy; {{ date('Y') }} TELYU EVENTS. BUILD BOLD.</p>
        </div>
    </footer>

</body>
</html>
