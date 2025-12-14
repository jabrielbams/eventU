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
        <div class="neo-box bg-white overflow-hidden grid grid-cols-1 md:grid-cols-5 gap-0">
            @php
                $imageUrl = $event['image_url'] ?? 'https://placehold.co/800x1200';
                $categoryName = $event['category']['name'] ?? 'Event';
                $organizerName = $event['organization']['name'] ?? 'Unknown';
            @endphp

            <!-- Event Poster -->
            <div class="md:col-span-2 relative min-h-[400px] md:min-h-[600px]">
                <img src="{{ $imageUrl }}"
                     class="w-full h-full object-cover md:border-r-[3px] md:border-telkom-black"
                     alt="Event Poster">
            </div>

            <!-- Event Details -->
            <div class="md:col-span-3 p-8 md:p-12 flex flex-col justify-center">
                <!-- Category Badge -->
                <span class="inline-block bg-telkom-red text-white px-4 py-2 border-2 border-telkom-black font-bold uppercase text-sm mb-4 self-start">
                    {{ $categoryName }}
                </span>

                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-black uppercase leading-tight mb-8 tracking-tight">
                    {{ $event['title'] }}
                </h1>

                <!-- Meta Information Grid -->
                <div class="grid grid-cols-2 gap-6 mb-8 py-6 border-y-2 border-telkom-black">
                    <div>
                        <strong class="block text-xs font-bold uppercase text-gray-500 mb-1">Tanggal</strong>
                        <span class="text-lg font-semibold">{{ $event['date'] }}</span>
                    </div>
                    <div>
                        <strong class="block text-xs font-bold uppercase text-gray-500 mb-1">Waktu</strong>
                        <span class="text-lg font-semibold">{{ $event['time'] }}</span>
                    </div>
                    <div>
                        <strong class="block text-xs font-bold uppercase text-gray-500 mb-1">Lokasi</strong>
                        <span class="text-lg font-semibold">{{ $event['location'] }}</span>
                    </div>
                    <div>
                        <strong class="block text-xs font-bold uppercase text-gray-500 mb-1">Penyelenggara</strong>
                        <span class="text-lg font-semibold">{{ $organizerName }}</span>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-lg leading-relaxed mb-8 text-gray-700">
                    {{ $event['description'] }}
                </p>

                @if(auth()->user() && auth()->user()->role === 'organizer')
                    <!-- Organizer Actions -->
                    <div class="mb-6">
                        <a href="{{ route('events.registrants', $event['id']) }}" class="block w-full text-center bg-telkom-white text-telkom-black font-bold uppercase py-3 px-6 border-[3px] border-telkom-black shadow-[4px_4px_0px_0px_#1A1A1A] hover:shadow-[2px_2px_0px_0px_#1A1A1A] hover:translate-x-[2px] hover:translate-y-[2px] transition-all mb-4">
                            📋 Manage Registrants
                        </a>
                    </div>
                @endif

                <!-- Student Actions -->
                @if(auth()->user() && auth()->user()->role === 'student')
                <div class="flex gap-4">
                    <!-- Register Button -->
                    <form method="POST" action="{{ route('events.register', $event['id']) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="bg-telkom-red text-telkom-white font-bold uppercase w-full text-xl py-4 border-[3px] border-telkom-black shadow-[4px_4px_0px_0px_#1A1A1A] hover:bg-red-700 hover:shadow-[2px_2px_0px_0px_#1A1A1A] hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                            Daftar Event
                        </button>
                    </form>

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
