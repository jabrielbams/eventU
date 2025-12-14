@extends('layouts.app')

@section('title', 'All Events')

@section('content')
    <div class="max-w-[1400px] mx-auto">
        <!-- Header -->
        <div
            class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] p-8 mb-8 flex flex-wrap justify-between items-center gap-4 relative">
            <!-- Rivets -->
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 right-2"></div>

            <h1 class="text-[2.5rem] md:text-[3.5rem] font-black uppercase tracking-tighter leading-none">
                All Events
            </h1>
            <a href="{{ route('dashboard') }}"
                class="inline-block px-6 py-3 bg-white border-[3px] border-black shadow-[6px_6px_0px_#000] text-black font-black uppercase text-center transition-all hover:bg-[#f0f0f0] hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000]">
                &larr; Dashboard
            </a>
        </div>

        <!-- Control Panel -->
        <form method="GET" action="{{ route('events.index') }}"
            class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] p-6 mb-12 flex flex-wrap gap-4 items-stretch relative">
            <div class="absolute top-0 left-0 title-tag bg-black text-white px-2 py-1 text-xs font-bold uppercase">SEARCH
                PARAMETERS</div>

            <div class="flex-grow min-w-[300px]">
                <input type="text" name="search"
                    class="w-full h-full p-4 border-[3px] border-black font-mono placeholder:text-gray-400 focus:shadow-[4px_4px_0px_0px_#000] outline-none transition-all uppercase"
                    placeholder="SEARCH EVENTS..." value="{{ request('search') }}">
            </div>
            <div class="flex flex-wrap gap-2">
                @php
                    $filterBtnBase =
                        'px-6 py-3 border-[3px] border-black font-bold uppercase transition-all hover:-translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#000]';
                    $activeClass = 'bg-industrial-red text-white';
                    $inactiveClass = 'bg-white text-black hover:bg-gray-100';
                @endphp

                <button type="submit" name="category" value="all"
                    class="{{ $filterBtnBase }} {{ request('category', 'all') === 'all' ? $activeClass : $inactiveClass }}">
                    All
                </button>
                <button type="submit" name="category" value="workshop"
                    class="{{ $filterBtnBase }} {{ request('category') === 'workshop' ? $activeClass : $inactiveClass }}">
                    Workshop
                </button>
                <button type="submit" name="category" value="seminar"
                    class="{{ $filterBtnBase }} {{ request('category') === 'seminar' ? $activeClass : $inactiveClass }}">
                    Seminar
                </button>
                <button type="submit" name="category" value="competition"
                    class="{{ $filterBtnBase }} {{ request('category') === 'competition' ? $activeClass : $inactiveClass }}">
                    Competition
                </button>
                @if (Auth::check() && Auth::user()->isStudent())
                    <button type="submit" name="category" value="bookmark"
                        class="{{ $filterBtnBase }} {{ request('category') === 'bookmark' ? $activeClass : $inactiveClass }}">
                        Bookmark
                    </button>
                @endif
            </div>
        </form>

        <!-- Event Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @if (isset($events['data']) && count($events['data']) > 0)
                @foreach ($events['data'] as $event)
                    @php
                        $dateObj = new DateTime($event['date']);
                        $day = $dateObj->format('d');
                        $month = strtoupper($dateObj->format('M'));
                        $categoryName = $event['category']['name'] ?? 'Event';
                        $imageUrl = $event['image_url'] ??
                            ($event['image']
                                ? (str_starts_with($event['image'], 'http')
                                    ? $event['image']
                                    : asset('storage/' . $event['image']))
                                : 'https://placehold.co/600x400');

                        // Format date and time
                        $carbonDate = \Carbon\Carbon::parse($event['date']);
                        $carbonTime = \Carbon\Carbon::parse($event['time']);
                        $formattedTime = $carbonTime->format('H:i') . ' WIB';
                        $formattedDate = $carbonDate->locale('id')->isoFormat('dddd, D MMMM YYYY');
                    @endphp
                    <div
                        class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] flex flex-col relative transition-transform hover:-translate-y-1">
                        <!-- Image Container -->
                        <div class="relative w-full aspect-video border-b-[3px] border-black overflow-hidden group">
                            <img src="{{ $imageUrl }}"
                                class="w-full h-full object-cover grayscale transition-all duration-300 group-hover:grayscale-0"
                                alt="{{ $event['title'] }}">
                            <!-- Date Badge -->
                            <div
                                class="absolute top-4 right-4 bg-white border-[3px] border-black p-2 text-center min-w-[60px] shadow-[4px_4px_0px_0px_#000]">
                                <span
                                    class="block text-xs font-bold uppercase bg-black text-white px-1">{{ $month }}</span>
                                <span class="block text-2xl font-black leading-none py-1">{{ $day }}</span>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 flex flex-col gap-4 flex-grow">
                            <!-- Category Tag -->
                            <div>
                                <span
                                    class="inline-block bg-industrial-red text-white px-3 py-1 text-xs font-bold uppercase border-[2px] border-black shadow-[2px_2px_0px_0px_#000]">
                                    {{ $categoryName }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-2xl font-black uppercase leading-[0.95] tracking-tight min-h-[3rem]">
                                {{ $event['title'] }}
                            </h3>

                            <!-- Details -->
                            <div class="mt-auto border-t-[3px] border-black pt-4 space-y-3">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-base">📍</span>
                                    <span class="font-bold text-gray-700">{{ Str::limit($event['location'], 30) }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-base">📅</span>
                                    <span class="font-bold text-gray-700">{{ $formattedDate }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-base">🕒</span>
                                    <span class="font-bold font-mono text-gray-700">{{ $formattedTime }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex border-t-[3px] border-black">
                            <a href="{{ route('events.show', $event['id']) }}"
                                class="flex-grow bg-industrial-black text-white p-4 text-center text-lg font-black uppercase hover:bg-industrial-red hover:text-white transition-colors">
                                View Details &rarr;
                            </a>

                            <!-- Bookmark Button (only for students) -->
                            @if (Auth::check() && Auth::user()->isStudent())
                                @if (isset($event['is_bookmarked']) && $event['is_bookmarked'])
                                    <!-- Unbookmark Form -->
                                    <form method="POST" action="{{ route('events.unbookmark', $event['id']) }}" class="border-l-[3px] border-black">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="h-full bg-industrial-red text-white px-6 shadow-[4px_4px_0px_0px_#000] hover:bg-red-700 transition-all"
                                            title="Hapus Bookmark">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <!-- Bookmark Form -->
                                    <form method="POST" action="{{ route('events.bookmark', $event['id']) }}" class="border-l-[3px] border-black">
                                        @csrf
                                        <button type="submit"
                                            class="h-full bg-white text-black px-6 shadow-[4px_4px_0px_0px_#000] hover:bg-gray-100 transition-all"
                                            title="Tambah Bookmark">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 19V5z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full py-16 text-center border-[3px] border-black border-dashed opacity-50">
                    <h2 class="text-3xl font-black uppercase text-gray-400">NO EVENTS FOUND</h2>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if (isset($events['prev_page_url']) || isset($events['next_page_url']))
            <div class="flex justify-center gap-4 mb-12">
                @if (isset($events['prev_page_url']) && $events['prev_page_url'])
                    <a href="{{ route('events.index', array_merge(request()->query(), ['page' => request('page', 1) - 1])) }}"
                        class="px-6 py-3 bg-white border-[3px] border-black font-bold uppercase shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                        &larr; Previous
                    </a>
                @else
                    <button
                        class="px-6 py-3 bg-gray-200 border-[3px] border-gray-400 font-bold uppercase text-gray-400 cursor-not-allowed"
                        disabled>&larr; Previous</button>
                @endif

                @if (isset($events['next_page_url']) && $events['next_page_url'])
                    <a href="{{ route('events.index', array_merge(request()->query(), ['page' => request('page', 1) + 1])) }}"
                        class="px-6 py-3 bg-white border-[3px] border-black font-bold uppercase shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                        Next &rarr;
                    </a>
                @else
                    <button
                        class="px-6 py-3 bg-gray-200 border-[3px] border-gray-400 font-bold uppercase text-gray-400 cursor-not-allowed"
                        disabled>Next &rarr;</button>
                @endif
            </div>
        @endif
    </div>
@endsection
