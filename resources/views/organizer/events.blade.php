@extends('layouts.app')

@section('title')

@section('content')
<div class="max-w-[1400px] mx-auto">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-24 right-4 z-50 bg-yellow-300 border-[3px] border-black shadow-[4px_4px_0px_#000] p-4 font-bold uppercase max-w-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-24 right-4 z-50 bg-industrial-red text-white border-[3px] border-black shadow-[4px_4px_0px_#000] p-4 font-bold uppercase max-w-md">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] p-8 mb-8 flex flex-wrap justify-between items-center gap-4 relative">
        <!-- Rivets -->
        <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2"></div>
        <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2"></div>
        <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 left-2"></div>
        <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 right-2"></div>

        <div>
            <h1 class="text-[2.5rem] md:text-[3.5rem] font-black uppercase tracking-tighter leading-none">
                Management Events
            </h1>
            <p class="text-sm font-bold uppercase tracking-widest text-gray-500 mt-2">Event Management Dashboard</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-white border-[3px] border-black shadow-[6px_6px_0px_#000] text-black font-black uppercase text-center transition-all hover:bg-[#f0f0f0] hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000]">
                &larr; Dashboard
            </a>
            <a href="{{ route('events.create') }}" class="inline-block px-6 py-3 bg-industrial-red text-white border-[3px] border-black shadow-[6px_6px_0px_#000] font-black uppercase text-center transition-all hover:bg-red-700 hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000]">
                + Buat Event
            </a>
        </div>
    </div>

    <!-- Control Panel -->
    <form method="GET" action="{{ route('organizer.events') }}" class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] p-6 mb-12 flex flex-wrap gap-4 items-stretch relative">
        <div class="flex-grow min-w-[300px]">
            <input type="text" name="search" class="w-full h-full p-4 border-[3px] border-black font-mono placeholder:text-gray-400 focus:shadow-[4px_4px_0px_0px_#000] outline-none transition-all uppercase" placeholder="Cari Event..." value="{{ request('search') }}">
        </div>
        <div class="flex gap-2 items-stretch">
            <select name="status" class="p-4 border-[3px] border-black font-mono uppercase font-bold cursor-pointer focus:shadow-[4px_4px_0px_0px_#000] outline-none transition-all bg-white">
                <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>ALL STATUS</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>DRAFT</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>PUBLISHED</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>COMPLETED</option>
            </select>
            <button type="submit" class="px-6 py-3 bg-industrial-red text-white border-[3px] border-black shadow-[6px_6px_0px_#000] font-black uppercase transition-all hover:bg-red-700 hover:-translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#000]">
                Cari
            </button>
        </div>
    </form>

        <!-- Events List -->
        @if($events->count() > 0)
            <div class="space-y-6 mb-16">
                @foreach($events as $event)
                    @php
                        $dateObj = new DateTime($event->date);
                        $day = $dateObj->format('d');
                        $month = strtoupper($dateObj->format('M'));
                        $categoryName = $event->category->name ?? 'Event';
                    $imageUrl = $event->image
                        ? (str_starts_with($event->image, 'http')
                            ? $event->image
                            : asset('storage/' . $event->image))
                        : 'https://placehold.co/600x400';
                    $registrantsCount = $event->users->count();
                @endphp

                <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] overflow-hidden grid grid-cols-1 md:grid-cols-4 gap-0 relative transition-transform hover:-translate-y-1">
                    <!-- Rivets -->
                    <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2 z-10"></div>
                    <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2 z-10"></div>

                    <!-- Image -->
                    <div class="relative aspect-video md:aspect-auto group">
                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover md:border-r-[3px] md:border-black grayscale transition-all duration-300 group-hover:grayscale-0" alt="{{ $event->title }}">
                        <!-- Date Badge -->
                        <div class="absolute top-4 left-4 bg-white border-[3px] border-black p-2 text-center min-w-[60px] shadow-[4px_4px_0px_0px_#000]">
                            <span class="block text-xs font-bold uppercase bg-black text-white px-1">{{ $month }}</span>
                            <span class="block text-2xl font-black leading-none py-1">{{ $day }}</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="md:col-span-3 p-6 flex flex-col md:flex-row gap-6">
                        <div class="flex-grow">
                            <!-- Status Badge -->
                            <span class="inline-block mb-3 px-3 py-1 text-xs font-bold uppercase border-[2px] border-black shadow-[2px_2px_0px_0px_#000]
                                @if($event->status === 'published') bg-green-500 text-white
                                @elseif($event->status === 'draft') bg-yellow-500 text-white
                                @elseif($event->status === 'cancelled') bg-red-500 text-white
                                @else bg-gray-500 text-white
                                @endif">
                                {{ $event->status }}
                            </span>

                            <!-- Category -->
                            <span class="inline-block mb-3 ml-2 bg-industrial-red text-white px-3 py-1 text-xs font-bold uppercase border-[2px] border-black shadow-[2px_2px_0px_0px_#000]">
                                {{ $categoryName }}
                            </span>

                            <!-- Title -->
                            <h3 class="text-2xl md:text-3xl font-black uppercase leading-[0.95] tracking-tight mb-4">
                                {{ $event->title }}
                            </h3>

                            <!-- Meta Info -->
                            <div class="border-t-[3px] border-black pt-4 font-mono text-sm space-y-2 text-gray-600">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">📍</span> {{ $event->location }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-base">🕒</span> {{ $event->time->format('H:i') }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-base">👥</span> {{ $registrantsCount }} Peserta
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-base">📅</span> {{ $event->date->format('d M Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex md:flex-col gap-3 md:justify-evenly md:min-w-[180px] border-t-[3px] md:border-t-0 md:border-l-[3px] border-black pt-4 md:pt-0 md:pl-6">
                            <a href="{{ route('events.show', $event->id) }}"
                               class="flex-1 md:flex-none px-4 py-3 bg-white text-black border-[3px] border-black font-black uppercase text-center transition-all hover:bg-black hover:text-white hover:-translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#000] text-sm">
                                Detail
                            </a>
                            <a href="{{ route('events.registrants', $event->id) }}"
                               class="flex-1 md:flex-none px-4 py-3 bg-white text-black border-[3px] border-black font-black uppercase text-center transition-all hover:bg-black hover:text-white hover:-translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#000] text-sm">
                                Lihat Peserta
                            </a>
                            <a href="{{ route('events.edit', $event->id) }}"
                               class="flex-1 md:flex-none px-4 py-3 bg-white text-black border-[3px] border-black font-black uppercase text-center transition-all hover:bg-black hover:text-white hover:-translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#000] text-sm">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
            <div class="flex justify-center gap-4 mb-8">
                @if($events->onFirstPage())
                    <span class="px-6 py-3 bg-gray-300 border-[3px] border-black font-black uppercase opacity-50 cursor-not-allowed">
                        &larr; Kembali
                    </span>
                @else
                    <a href="{{ $events->previousPageUrl() }}" class="px-6 py-3 bg-white border-[3px] border-black shadow-[6px_6px_0px_#000] text-black font-black uppercase transition-all hover:bg-[#f0f0f0] hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000]">
                        &larr; Kembali
                    </a>
                @endif

                <div class="bg-white border-[3px] border-black px-6 py-3 flex items-center">
                    <span class="font-black uppercase font-mono">Page {{ $events->currentPage() }} / {{ $events->lastPage() }}</span>
                </div>

                @if($events->hasMorePages())
                    <a href="{{ $events->nextPageUrl() }}" class="px-6 py-3 bg-white border-[3px] border-black shadow-[6px_6px_0px_#000] text-black font-black uppercase transition-all hover:bg-[#f0f0f0] hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000]">
                        Berikutnya &rarr;
                    </a>
                @else
                    <span class="px-6 py-3 bg-gray-300 border-[3px] border-black font-black uppercase opacity-50 cursor-not-allowed">
                        Berikutnya &rarr;
                    </span>
                @endif
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] p-16 text-center relative">
            <!-- Rivets -->
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 right-2"></div>

            <div class="text-[4rem] font-black mb-4 opacity-20">📝</div>
            <h3 class="text-[2rem] font-black uppercase leading-none tracking-tight mb-2">Belum Ada Event</h3>
            <p class="text-gray-600 font-bold uppercase text-sm tracking-wider mb-8">Buat event baru!</p>
            <a href="{{ route('events.create') }}" class="inline-block px-6 py-3 bg-industrial-red text-white border-[3px] border-black shadow-[6px_6px_0px_#000] font-black uppercase transition-all hover:bg-red-700 hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000]">
                + Buat Event
            </a>
        </div>
    @endif
</div>
@endsection
