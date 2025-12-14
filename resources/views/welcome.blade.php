<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TelyuEvents - Pusat Kegiatan Mahasiswa</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Custom Dot Pattern Background */
        .bg-dot-pattern {
            background-image: radial-gradient(#000 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Marquee Animation */
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            animation: marquee 20s linear infinite;
        }
    </style>
</head>

<body class="bg-[#F2F2F2] bg-dot-pattern text-black font-['Unbounded',_sans-serif] overflow-x-hidden">

    <!-- Section A: The Hero -->
    <div
        class="min-h-[80vh] flex flex-col lg:flex-row items-center justify-center border-b-[3px] border-black bg-white relative">

        <!-- Left Side (Text) -->
        <div class="w-full lg:w-1/2 p-8 lg:p-16 flex flex-col justify-center items-start space-y-8 z-10">
            <!-- Badge -->
            <div
                class="inline-block bg-black text-white px-4 py-2 font-bold uppercase rotate-[-2deg] border-[2px] border-transparent transform hover:rotate-0 transition-transform duration-300">
                OFFICIAL PLATFORM
            </div>

            <!-- Headline -->
            <h1 class="text-5xl lg:text-7xl font-black uppercase leading-none tracking-tighter shadow-none">
                PUSAT <span class="text-[#ED1C24]">KEGIATAN</span> MAHASISWA TELKOM <span
                    class="text-[#ED1C24]">UNIVERSITY</span>
            </h1>

            <!-- Sub-headline -->
            <p class="text-xl font-bold uppercase max-w-lg leading-tight text-gray-800">
                Satu portal untuk semua event, seminar, dan kompetisi. Terintegrasi & Tervalidasi.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <a href="{{ route('register') }}"
                    class="px-8 py-4 bg-black text-white text-xl font-black uppercase border-[3px] border-black hover:bg-[#CCFF00] hover:text-black hover:shadow-[6px_6px_0px_0px_#000] transition-all transform hover:-translate-y-1">
                    GABUNG SEKARANG
                </a>
                <a href="#events"
                    class="px-8 py-4 bg-white text-black text-xl font-black uppercase border-[3px] border-black hover:bg-gray-100 hover:shadow-[6px_6px_0px_0px_#000] transition-all transform hover:-translate-y-1">
                    JELAJAHI EVENT
                </a>
            </div>
        </div>

        <!-- Right Side (Visual) -->
        <div
            class="w-full lg:w-1/2 h-[50vh] lg:h-full flex items-center justify-center relative bg-white lg:border-l-[3px] lg:border-black">
            <!-- Abstract Composition -->
            <div class="relative w-64 h-64 lg:w-96 lg:h-96">
                <!-- Red Circle -->
                <div
                    class="absolute inset-0 rounded-full bg-[#ED1C24] border-[3px] border-black shadow-[12px_12px_0px_0px_#000]">
                </div>
                <!-- Black Square -->
                <div
                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 lg:w-64 lg:h-64 bg-black border-[3px] border-white rotate-12 flex items-center justify-center shadow-[8px_8px_0px_0px_rgba(0,0,0,0.2)]">
                    <span class="text-white text-6xl font-black">T/U</span>
                </div>
                <!-- Floating Elements -->
                <div
                    class="absolute -top-10 -right-10 w-24 h-24 bg-[#CCFF00] border-[3px] border-black rounded-full animate-bounce">
                </div>
                <div
                    class="absolute -bottom-5 -left-5 w-16 h-16 bg-white border-[3px] border-black transform rotate-45">
                </div>
            </div>
        </div>
    </div>

    <!-- Section B: The "Running Text" (Marquee) -->
    <div class="w-full bg-[#CCFF00] border-b-[3px] border-black overflow-hidden py-4">
        <div class="whitespace-nowrap flex animate-marquee">
            <span class="text-3xl font-black uppercase mx-4">• OPEN RECRUITMENT • SEMINAR NASIONAL • LOMBA CODING •
                WORKSHOP DESAIN • OPEN RECRUITMENT • SEMINAR NASIONAL • LOMBA CODING • WORKSHOP DESAIN</span>
            <span class="text-3xl font-black uppercase mx-4">• OPEN RECRUITMENT • SEMINAR NASIONAL • LOMBA CODING •
                WORKSHOP DESAIN • OPEN RECRUITMENT • SEMINAR NASIONAL • LOMBA CODING • WORKSHOP DESAIN</span>
        </div>
    </div>

    <!-- Section C: Event Teaser ("Misi Terbaru") -->
    <div id="events" class="max-w-7xl mx-auto px-4 py-20 relative">
        <!-- Header -->
        <div class="flex items-center justify-between mb-12 border-b-[3px] border-black pb-4">
            <h2
                class="text-5xl md:text-6xl font-black uppercase bg-white border-[3px] border-black p-4 shadow-[8px_8px_0px_0px_#000] inline-block transform -rotate-1">
                EVENT TERBARU
            </h2>
            <div class="hidden md:block w-32 h-6 bg-stripes-black"></div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @forelse($featuredEvents as $event)
                <!-- Event Card (Classified File Style) -->
                @php
                    $month = strtoupper(\Carbon\Carbon::parse($event->date)->format('M'));
                    $day = \Carbon\Carbon::parse($event->date)->format('d');
                    $imgUrl = $event->image
                        ? (str_starts_with($event->image, 'http')
                            ? $event->image
                            : asset('storage/' . $event->image))
                        : 'https://placehold.co/600x400';
                @endphp
                <div
                    class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] flex flex-col relative transition-transform hover:-translate-y-2 group">
                    <!-- Image Container -->
                    <div class="relative w-full aspect-video border-b-[3px] border-black overflow-hidden">
                        <img src="{{ $imgUrl }}"
                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300"
                            alt="{{ $event->title }}">
                        <div
                            class="absolute top-4 right-4 bg-white border-[3px] border-black p-2 text-center min-w-[60px] shadow-[4px_4px_0px_0px_#000]">
                            <span
                                class="block text-xs font-bold uppercase bg-black text-white px-1">{{ $month }}</span>
                            <span class="block text-2xl font-black leading-none py-1">{{ $day }}</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex flex-col gap-4 flex-grow">
                        <div class="flex justify-between items-start">
                            <span
                                class="inline-block bg-[#ED1C24] text-white px-2 py-1 text-xs font-bold uppercase border-[2px] border-black">
                                {{ $event->category->name ?? 'EVENT' }}
                            </span>
                            @if ($event->organization)
                                <span
                                    class="text-xs font-bold uppercase underline">{{ $event->organization->name }}</span>
                            @endif
                        </div>

                        <h3 class="text-2xl font-black uppercase leading-[0.95] tracking-tight line-clamp-2">
                            {{ $event->title }}
                        </h3>

                        <div class="mt-auto border-t-[3px] border-black pt-4 font-mono text-sm space-y-1 text-gray-600">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-black block"></span> {{ Str::limit($event->location, 30) }}
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 border-2 border-black block"></span>
                                {{ \Carbon\Carbon::parse($event->time)->format('H:i') }} WIB
                            </div>
                        </div>
                    </div>

                    <!-- Action -->
                    <a href="{{ route('events.show', $event->id) }}"
                        class="block w-full bg-black text-white border-t-[3px] border-black p-4 text-center text-lg font-black uppercase hover:bg-[#ED1C24] hover:text-white transition-colors">
                        LIHAT DETAIL &rarr;
                    </a>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 border-[3px] border-black border-dashed bg-white opacity-75">
                    <h3 class="text-2xl font-black uppercase text-gray-500">BELUM ADA EVENT AKTIF</h3>
                    <p class="font-bold uppercase mt-2">Cek kembali nanti.</p>
                </div>
            @endforelse
        </div>

        <!-- Start Footer Link -->
        <div class="text-center">
            <a href="{{ route('events.index') }}"
                class="inline-block text-4xl md:text-6xl font-black uppercase hover:text-[#ED1C24] hover:underline decoration-4 underline-offset-8 transition-all">
                LIHAT SEMUA EVENT ->
            </a>
        </div>
    </div>

    <!-- Section D: Footer (Simple) -->
    <footer class="bg-black text-white py-12 border-t-[3px] border-black mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="mb-4">
                <h2 class="text-3xl font-black uppercase tracking-widest text-[#ED1C24]">TELYUEVENTS</h2>
            </div>
            <p class="font-bold uppercase tracking-wider text-sm">
                © 2025 TelyuEvents. Dibuat oleh Mahasiswa, untuk Mahasiswa.
            </p>
        </div>
    </footer>

</body>

</html>
