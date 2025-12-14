@extends('layouts.app')

@section('title', $event['title'] ?? 'Detail Event')

@section('content')
<div class="py-10 px-4">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-24 right-4 z-50 neo-box bg-yellow-300 p-4 font-bold uppercase max-w-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-24 right-4 z-50 neo-box bg-telkom-red text-white p-4 font-bold uppercase max-w-md">
            {{ session('error') }}
        </div>
    @endif

    <!-- Back Button -->
    <div class="max-w-6xl mx-auto mb-8">
        <a href="{{ route('events.index') }}" class="neo-button-default inline-block">
            &larr; Kembali ke Daftar Event
        </a>
    </div>

    <!-- Event Detail Container -->
    <div class="max-w-6xl mx-auto">
        <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] overflow-hidden relative">
            <!-- Rivets -->
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2 z-10"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2 z-10"></div>

            @php
                $imageUrl = $event['image_url'] ?? 'https://placehold.co/800x1200';
                $categoryName = $event['category']['name'] ?? 'Event';
                $organizerName = $event['organization']['name'] ?? 'Unknown';

                // Format date and time
                $dateFormatted = \Carbon\Carbon::parse($event['date'])->locale('id')->isoFormat('dddd, D MMMM YYYY');
                $timeFormatted = \Carbon\Carbon::parse($event['time'])->format('H:i') . ' WIB';
            @endphp

            <!-- Event Poster - TOP -->
            <div class="relative w-full h-[400px] md:h-[500px] bg-gray-100">
                <img src="{{ $imageUrl }}"
                     class="w-full h-full object-contain"
                     alt="Event Poster">
            </div>

            <!-- Event Details - BOTTOM -->
            <div class="p-8 md:p-12 border-t-[3px] border-black">
                <!-- Category Badge -->
                <span class="inline-block bg-industrial-red text-white px-4 py-2 border-[2px] border-black shadow-[2px_2px_0px_0px_#000] font-black uppercase text-sm mb-6">
                    {{ $categoryName }}
                </span>

                <!-- Title -->
                <h1 class="text-3xl md:text-5xl font-black uppercase leading-[0.95] mb-8 tracking-tight">
                    {{ $event['title'] }}
                </h1>

                <!-- Meta Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 pb-8 border-b-[3px] border-black">
                    <div class="bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">📅</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Tanggal</strong>
                        </div>
                        <span class="text-lg font-bold block font-mono">{{ $dateFormatted }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">🕒</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Waktu</strong>
                        </div>
                        <span class="text-lg font-bold block font-mono">{{ $timeFormatted }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">📍</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Lokasi</strong>
                        </div>
                        <span class="text-lg font-bold block font-mono">{{ $event['location'] }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">🏢</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Penyelenggara</strong>
                        </div>
                        <span class="text-lg font-bold block font-mono">{{ $organizerName }}</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-black uppercase mb-4 tracking-tight">Deskripsi Event</h2>
                    <p class="text-base leading-relaxed text-gray-700 font-medium">
                        {{ $event['description'] }}
                    </p>
                </div>

                @if(auth()->user() && auth()->user()->role === 'organizer')
                    <!-- Organizer Actions -->
                    <div class="pt-6 border-t-[3px] border-black">
                        <a href="{{ route('events.registrants', $event['id']) }}"
                           class="block w-full text-center bg-white text-black font-black uppercase py-4 px-6 border-[3px] border-black shadow-[6px_6px_0px_#000] hover:bg-black hover:text-white hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] transition-all">
                            📋 Kelola Peserta
                        </a>
                    </div>
                @endif

                <!-- Student Actions -->
                @if(auth()->user() && auth()->user()->role === 'student')
                    <div class="pt-6 border-t-[3px] border-black">
                        <div class="flex gap-4">
                    <!-- Register Button -->
                    <form method="POST" action="{{ route('events.register', $event['id']) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-industrial-red text-white font-black uppercase text-xl py-4 px-6 border-[3px] border-black shadow-[6px_6px_0px_#000] hover:bg-red-700 hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] transition-all">
                                    ✨ Daftar Event Sekarang
                                </button>
                            </form>
                    </div>

                    <!-- Bookmark Button -->
                    @if (isset($event['is_bookmarked']) && $event['is_bookmarked'])
                        <!-- Unbookmark Form -->
                        <form method="POST" action="{{ route('events.unbookmark', $event['id']) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-telkom-red text-telkom-white font-bold uppercase px-6 py-4 border-[3px] border-telkom-black shadow-[4px_4px_0px_0px_#1A1A1A] hover:bg-red-700 hover:shadow-[2px_2px_0px_0px_#1A1A1A] hover:translate-x-[2px] hover:translate-y-[2px] transition-all"
                                title="Remove bookmark">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                </svg>
                            </button>
                        </form>
                    @else
                        <!-- Bookmark Form -->
                        <form method="POST" action="{{ route('events.bookmark', $event['id']) }}">
                            @csrf
                            <button type="submit"
                                class="bg-telkom-white text-telkom-black font-bold uppercase px-6 py-4 border-[3px] border-telkom-black shadow-[4px_4px_0px_0px_#1A1A1A] hover:bg-gray-100 hover:shadow-[2px_2px_0px_0px_#1A1A1A] hover:translate-x-[2px] hover:translate-y-[2px] transition-all"
                                title="Add bookmark">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 19V5z"/>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
