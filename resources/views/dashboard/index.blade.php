@extends('layouts.app')

@section('content')
    <main class="grid grid-cols-3 grid-rows-[auto_1fr] gap-8 max-w-[1400px] mx-auto">

        <!-- MODULE A: IDENTITY UNIT -->
        <div
            class="{{ $user->isStudent() ? 'col-span-2' : 'col-span-3' }} bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] relative p-8 flex items-center gap-10">
            <!-- Rivets -->
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 right-2"></div>

            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=000&color=fff&size=128"
                alt="Avatar" class="w-[140px] h-[140px] border-[3px] border-black object-cover grayscale contrast-125 flex-shrink-0">

            <div class="flex-grow">
                <div class="flex justify-between items-start mb-5">
                    <div>
                        <div class="text-[0.7rem] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono mb-1">SELAMAT DATANG,</div>
                        <div class="text-[2.5rem] font-black uppercase leading-[0.9] tracking-tight">{{ $user->name }}</div>
                    </div>
                    <div class="text-right">
                        <div
                            class="inline-block border-[3px] border-industrial-red bg-industrial-red text-white px-4 py-2 font-black uppercase text-[0.85rem] tracking-wider shadow-[3px_3px_0px_#000]">
                            {{ strtoupper($user->role) }}
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t-[3px] border-black grid grid-cols-{{ $user->organizations()->wherePivot('status', 'approved')->exists() ? '3' : '2' }} gap-6">
                    <div>
                        <div class="text-[0.65rem] font-bold uppercase tracking-[0.15em] text-gray-400 mb-2">📧 Email</div>
                        <div class="font-bold font-mono text-sm">{{ $user->email }}</div>
                    </div>

                    @php
                        $organization = $user->organizations()->wherePivot('status', 'approved')->first();
                    @endphp
                    @if($organization)
                        <div>
                            <div class="text-[0.65rem] font-bold uppercase tracking-[0.15em] text-gray-400 mb-2">🏢 Organisasi</div>
                            <div class="font-bold font-mono text-sm">{{ $organization->name }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- MODULE B: NEXT EVENT / ANNOUNCEMENTS (Organizer) -->
        @if($user->isStudent())
            <div
                class="col-span-1 bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] relative p-6 flex flex-col justify-between">
                <!-- Caution Tape -->
                <div
                    class="absolute top-0 left-0 right-0 h-6 border-b-[3px] border-black bg-[repeating-linear-gradient(-45deg,#ED1C24,#ED1C24_10px,#000_10px,#000_20px)]">
                </div>

                <div class="mt-9">
                    <div class="text-[0.8rem] font-bold uppercase tracking-widest text-industrial-red">Event Terdekat</div>

                    @if ($nextEvent)
                        <div class="text-[1.5rem] font-extrabold uppercase leading-[1.1] mt-1">
                            {{ Str::limit($nextEvent->title, 15) }}</div>

                        <div class="mt-4 p-3 bg-black text-center">
                            <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-400">MULAI DALAM</div>
                            <div class="text-[2.2rem] font-black uppercase leading-none tracking-tight text-white">
                                {{ $daysRemaining }}</div>
                            <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-400">Hari</div>
                        </div>
                    @else
                        <div class="text-[1.5rem] font-extrabold uppercase leading-[1.1] mt-1 text-gray-400">Tidak ada event terdekat</div>
                        <div class="mt-4 p-4 border-2 border-dashed border-gray-400 text-center">
                            <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-500">Gabung ke event untuk melihatnya di sini</div>
                        </div>
                    @endif
                </div>
            </div>
        @elseif($user->isOrganizer())
            <div class="col-span-3 bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] relative p-6 flex flex-col justify-between">
                <!-- Announcement Banner -->
                <div class="absolute top-0 left-0 right-0 h-6 border-b-[3px] border-black bg-[repeating-linear-gradient(-45deg,#ED1C24,#ED1C24_10px,#000_10px,#000_20px)]"></div>
                <div class="mt-9 flex flex-col gap-4">
                    <div class="text-[0.8rem] font-black uppercase tracking-widest text-industrial-red">Pengumuman & Berita</div>
                    <div class="text-gray-700 text-sm mb-2">Kelola pengumuman untuk event yang kamu buat. Pengumuman akan tampil ke peserta event.</div>
                    <div class="w-full flex justify-end">
                        <a href="{{ route('announcements.index') }}"
                            class="inline-block px-6 py-3 bg-industrial-red text-white border-[3px] border-black font-black uppercase shadow-[4px_4px_0px_#000] hover:bg-red-700 transition-all text-sm">
                            Kelola Pengumuman
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- MODULE C: BOOKMARKED EVENTS -->
        @if($user->isStudent())
            <div
                class="col-span-3 bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] relative p-6">
                <!-- Rivets -->
                <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2"></div>
                <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2"></div>
                <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 left-2"></div>
                <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 right-2"></div>

                <div
                    class="inline-block bg-industrial-red text-white px-6 py-3 font-black uppercase tracking-widest transform -translate-y-[50%] translate-x-[20px] shadow-[4px_4px_0px_rgba(0,0,0,0.3)] w-max mb-0">
                    Event Ditandai
                </div>

                @if($bookmarkedEvents->count() > 0)
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($bookmarkedEvents as $event)
                            @php
                                $isPast = $event->date->isPast();
                            @endphp
                            <div class="relative block bg-white border-[3px] border-black p-4 transition-all {{ $isPast ? 'opacity-75' : '' }}">
                                <!-- Unbookmark Button -->
                                <form method="POST" action="{{ route('events.unbookmark', $event->id) }}" class="absolute top-2 right-2 z-10">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-industrial-red text-white p-2 border-[2px] border-black shadow-[2px_2px_0px_#000] hover:bg-red-700 hover:-translate-y-[1px] hover:-translate-x-[1px] hover:shadow-[3px_3px_0px_#000] transition-all"
                                        title="Hapus Bookmark">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                        </svg>
                                    </button>
                                </form>

                                <!-- Event Card Content -->
                                <a href="{{ route('events.show', $event->id) }}" class="block">
                                    <div class="flex items-start justify-between mb-3 pr-8">
                                        <span class="inline-block bg-industrial-red text-white px-2 py-1 text-[0.6rem] font-black uppercase tracking-wider border-[2px] border-black shadow-[2px_2px_0px_0px_#000]">
                                            {{ $event->category->name ?? 'UMUM' }}
                                        </span>
                                    </div>
                                    <h3 class="font-black uppercase text-sm leading-tight mb-3">
                                        {{ Str::limit($event->title, 50) }}
                                    </h3>
                                    <div class="space-y-2 text-xs">
                                        <div class="flex items-center gap-2 text-gray-600">
                                            <span>📅</span>
                                            <span class="font-mono font-bold">{{ $event->date->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-600">
                                            <span>📍</span>
                                            <span class="font-semibold">{{ Str::limit($event->location, 25) }}</span>
                                        </div>
                                        <!-- Status Badge -->
                                        @if($isPast)
                                            <div class="pt-2 border-t border-gray-200">
                                                <span class="inline-block px-2 py-1 border-[2px] border-gray-400 bg-gray-100 text-gray-600 text-[0.6rem] font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(0,0,0,0.2)]">
                                                    Event sudah Selesai
                                                </span>
                                            </div>
                                        @else
                                            <div class="pt-2 border-t border-gray-200">
                                                <span class="inline-block px-2 py-1 border-[2px] border-green-600 bg-green-100 text-green-700 text-[0.6rem] font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(0,0,0,0.2)]">
                                                    Mendatang
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-[200px] border-[3px] border-dashed border-gray-300 mt-4">
                        <div class="text-[4rem] mb-4 opacity-20">🔖</div>
                        <div class="text-[1.5rem] font-black uppercase leading-none tracking-tight text-gray-400 mb-2">
                            Belum Ada Bookmark
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- MODULE D: EVENT FEED (ROSTER) -->
        <div
            class="col-span-3 min-h-[300px] bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] relative p-6 flex flex-col">
            <!-- Rivets -->
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 right-2"></div>

            <div
                class="inline-block bg-industrial-black text-white px-6 py-3 font-black uppercase tracking-widest transform -translate-y-[50%] translate-x-[20px] shadow-[4px_4px_0px_rgba(0,0,0,0.3)] w-max mb-0">
                @if($user->isStudent())
                    📋 Event Terdaftar
                @else
                    🎯 Event Terbuat
                @endif
            </div>

            @if ($assignedEvents->count() > 0)
                <div class="overflow-x-auto mt-4">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="text-left border-[3px] border-black p-4 uppercase font-black text-xs tracking-wider">
                                    📌 Event Name
                                </th>
                                <th class="text-left border-[3px] border-black p-4 uppercase font-black text-xs tracking-wider">
                                    📅 Date
                                </th>
                                <th class="text-left border-[3px] border-black p-4 uppercase font-black text-xs tracking-wider">
                                    📍 Location
                                </th>
                                <th class="text-left border-[3px] border-black p-4 uppercase font-black text-xs tracking-wider">
                                    @if($user->isStudent())
                                        ✅ Status
                                    @else
                                        👥 Participants
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignedEvents as $event)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4 border-b-[2px] border-black">
                                        <div class="font-bold text-base">{{ Str::limit($event->title, 40) }}</div>
                                        <div class="inline-block mt-1 bg-industrial-red text-white px-2 py-1 text-[0.65rem] font-black uppercase tracking-wider border-[2px] border-black shadow-[2px_2px_0px_0px_#000]">
                                            {{ $event->category->name ?? 'GENERAL' }}
                                        </div>
                                    </td>
                                    <td class="p-4 border-b-[2px] border-black">
                                        <div class="font-mono font-bold text-sm">{{ $event->date->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500 font-semibold uppercase">{{ $event->date->locale('id')->isoFormat('dddd') }}</div>
                                    </td>
                                    <td class="p-4 border-b-[2px] border-black font-semibold text-sm">
                                        {{ Str::limit($event->location, 30) }}
                                    </td>
                                    <td class="p-4 border-b-[2px] border-black">
                                        @if($user->isStudent())
                                            <span class="inline-block px-3 py-2 border-[2px] border-green-600 bg-green-100 text-green-800 text-xs uppercase font-black shadow-[2px_2px_0px_0px_#000]">
                                                Terdaftar
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-2 border-[2px] border-black bg-white text-xs uppercase font-black shadow-[2px_2px_0px_0px_#000]">
                                                {{ $event->users->count() }} Peserta
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-[250px] border-[3px] border-dashed border-gray-300 mt-4">
                    <div class="text-[4rem] mb-4 opacity-20">
                        @if($user->isStudent())
                            📝
                        @else
                            🎪
                        @endif
                    </div>
                    <div class="text-[1.5rem] font-black uppercase leading-none tracking-tight text-gray-400 mb-2">
                        Tidak Ada Event
                    </div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-500">
                        @if($user->isStudent())
                            Kamu belum mendaftar di event manapun
                        @else
                            Kamu belum membuat event apapun
                        @endif
                    </div>
                </div>
            @endif

            <div class="mt-6 pt-6 border-t-[3px] border-black flex justify-between items-center">
                <div class="text-xs font-bold uppercase text-gray-500 font-mono">
                    Total: {{ $assignedEvents->count() }} Event{{ $assignedEvents->count() !== 1 ? 's' : '' }}
                </div>
                <a href="{{ route($ctaRoute) }}"
                    class="inline-block px-6 py-3 bg-white border-[3px] border-black shadow-[6px_6px_0px_#000] text-black font-black uppercase text-center transition-all hover:bg-black hover:text-white hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] text-sm">
                    {{ $ctaText }} →
                </a>
            </div>
        </div>

    </main>
@endsection
