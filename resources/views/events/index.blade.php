@extends('layouts.app')

@section('title', 'Daftar Event')

@section('content')
<div class="py-10 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="neo-box bg-white p-8 mb-8 flex flex-wrap justify-between items-center gap-4">
            <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter">
                Daftar Event
            </h1>
            <a href="{{ route('dashboard') }}" class="neo-button-default">
                &larr; Dashboard
            </a>
        </div>

        <!-- Control Panel -->
        <form method="GET" action="{{ route('events.index') }}" class="neo-box bg-white p-6 mb-12 flex flex-wrap gap-4 items-stretch">
            <div class="flex-grow min-w-[300px]">
                <input type="text" name="search" class="neo-input uppercase placeholder:text-gray-400" placeholder="SEARCH EVENTS..." value="{{ request('search') }}">
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="submit" name="category" value="all" class="neo-button-default {{ request('category', 'all') === 'all' ? 'bg-telkom-red text-white' : '' }}">
                    All
                </button>
                <button type="submit" name="category" value="workshop" class="neo-button-default {{ request('category') === 'workshop' ? 'bg-telkom-red text-white' : '' }}">
                    Workshop
                </button>
                <button type="submit" name="category" value="seminar" class="neo-button-default {{ request('category') === 'seminar' ? 'bg-telkom-red text-white' : '' }}">
                    Seminar
                </button>
                <button type="submit" name="category" value="competition" class="neo-button-default {{ request('category') === 'competition' ? 'bg-telkom-red text-white' : '' }}">
                    Competition
                </button>
            </div>
        </form>

        <!-- Event Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @if(isset($events['data']) && count($events['data']) > 0)
                @foreach($events['data'] as $event)
                    @php
                        $dateObj = new DateTime($event['date']);
                        $day = $dateObj->format('d');
                        $month = strtoupper($dateObj->format('M'));
                        $categoryName = $event['category']['name'] ?? 'Event';
                        $imageUrl = $event['image'] ? (str_starts_with($event['image'], 'http') ? $event['image'] : asset('storage/' . $event['image'])) : 'https://placehold.co/600x400';
                    @endphp
                    <div class="neo-box neo-box-hover bg-white flex flex-col overflow-hidden">
                        <!-- Image Container -->
                        <div class="relative w-full aspect-video border-b-[3px] border-telkom-black">
                            <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $event['title'] }}">
                            <!-- Date Badge -->
                            <div class="absolute top-4 right-4 bg-white border-[3px] border-telkom-black p-2 text-center min-w-[60px] shadow-[4px_4px_0px_0px_#1A1A1A]">
                                <span class="block text-xs font-bold uppercase bg-telkom-black text-white px-1">{{ $month }}</span>
                                <span class="block text-2xl font-black leading-none py-1">{{ $day }}</span>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 flex flex-col gap-4 flex-grow">
                            <!-- Category Tag -->
                            <div>
                                <span class="inline-block bg-telkom-red text-white px-3 py-1 rounded-full text-xs font-bold uppercase border-2 border-telkom-black">
                                    {{ $categoryName }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-2xl font-black uppercase leading-tight">
                                {{ $event['title'] }}
                            </h3>

                            <!-- Details -->
                            <div class="mt-auto border-t-[3px] border-telkom-black pt-4 font-bold text-sm space-y-1">
                                <div>📍 {{ $event['location'] }}</div>
                                <div>🕒 {{ $event['time'] }}</div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('events.show', $event['id']) }}" class="block w-full bg-telkom-red text-white border-t-[3px] border-telkom-black p-4 text-center text-lg font-black uppercase hover:bg-telkom-black transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-16">
                    <h2 class="text-3xl font-black uppercase">TIDAK ADA EVENT DITEMUKAN</h2>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if(isset($events['prev_page_url']) || isset($events['next_page_url']))
        <div class="flex justify-center gap-4">
            @if(isset($events['prev_page_url']) && $events['prev_page_url'])
                <a href="{{ route('events.index', array_merge(request()->query(), ['page' => request('page', 1) - 1])) }}" class="neo-button-default">
                    Sebelumnya
                </a>
            @else
                <button class="neo-button-default opacity-50 cursor-not-allowed" disabled>Sebelumnya</button>
            @endif

            @if(isset($events['next_page_url']) && $events['next_page_url'])
                <a href="{{ route('events.index', array_merge(request()->query(), ['page' => request('page', 1) + 1])) }}" class="neo-button-default">
                    Berikutnya
                </a>
            @else
                <button class="neo-button-default opacity-50 cursor-not-allowed" disabled>Berikutnya</button>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
