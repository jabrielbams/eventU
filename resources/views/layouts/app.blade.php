<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TelyuEvents - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Courier+Prime:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                        mono: ['Courier Prime', 'monospace'],
                    },
                    colors: {
                        'industrial-red': '#ED1C24',
                        'industrial-black': '#000000',
                        'industrial-white': '#FFFFFF',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js (From previous layout, preserved just in case) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans text-industrial-black overflow-x-hidden m-0 p-0">

    <!-- Background: Technical Graph Paper -->
    <div
        class="min-h-screen w-full bg-[#f2f2f2] bg-[radial-gradient(#000000_1px,transparent_1px)] [background-size:20px_20px]">

        <!-- THE SWITCHBOARD (Fixed Sidebar) -->
        <aside
            class="fixed top-0 bottom-0 left-0 w-[280px] bg-white border-r-[3px] border-black p-8 flex flex-col gap-6 z-50">
            <!-- Decorative Line -->
            <div
                class="absolute top-0 right-[10px] bottom-0 w-[2px] bg-[repeating-linear-gradient(to_bottom,black_0,black_10px,transparent_10px,transparent_20px)] pointer-events-none">
            </div>

            <div class="mb-8 border-b-[3px] border-black pb-5">
                <div class="text-[2.2rem] font-black uppercase leading-[0.9] tracking-tight">Dashboard</div>
                <div class="mt-2 text-[0.8rem] font-bold uppercase tracking-widest text-gray-500">
                    @auth
                        ID:
                        {{ Auth::user()->organization_id ? 'ORG-' . Auth::user()->organization_id : 'STD-' . str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) }}
                    @else
                        ID: GUEST
                    @endauth
                </div>
            </div>

            <nav class="flex flex-col gap-6">
                @php
                    $btnClass =
                        'block w-full p-4 border-[3px] border-black text-black font-black uppercase text-center transition-all duration-100 font-mono relative';
                    $btnHover =
                        'hover:bg-[#f0f0f0] hover:-translate-x-[2px] hover:-translate-y-[2px] hover:shadow-[8px_8px_0px_#000]';
                    $btnActive = 'bg-black text-white shadow-none translate-x-[6px] translate-y-[6px]';
                    $btnDefault = 'bg-white shadow-[6px_6px_0px_#000]';
                @endphp

                <a href="{{ route('dashboard') }}"
                    class="{{ $btnClass }} {{ request()->routeIs('dashboard') ? $btnActive : $btnDefault . ' ' . $btnHover }}">::
                    Beranda</a>
                <a href="{{ route('events.index') }}"
                    class="{{ $btnClass }} {{ request()->routeIs('events.*') ? $btnActive : $btnDefault . ' ' . $btnHover }}">::
                    Cari Event</a>
                <a href="{{ route('profile.edit') }}"
                    class="{{ $btnClass }} {{ request()->routeIs('profile.*') ? $btnActive : $btnDefault . ' ' . $btnHover }}">::
                    Profil Saya</a>
            </nav>

            <!-- Bottom Controls -->
            <div class="mt-auto flex flex-col gap-3">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="{{ $btnClass }} bg-black text-white shadow-[6px_6px_0px_#000] hover:bg-industrial-red hover:shadow-[8px_8px_0px_#000] hover:-translate-y-[2px] hover:-translate-x-[2px]">
                            LOGOUT
                        </button>
                    </form>
                @endauth
            </div>
        </aside>

        <!-- MAIN LAYOUT CONTENT -->
        <div class="ml-[280px] p-12 w-[calc(100%-280px)]">
            @yield('content')
        </div>
    </div>

</body>

</html>
