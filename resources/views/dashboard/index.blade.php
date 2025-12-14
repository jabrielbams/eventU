@extends('layouts.app')

@section('content')
    <main class="grid grid-cols-3 grid-rows-[auto_1fr] gap-8 max-w-[1400px] mx-auto">

        <!-- MODULE A: IDENTITY UNIT -->
        <div
            class="col-span-2 bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] relative p-6 flex items-center gap-8">
            <!-- Rivets -->
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 left-2"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 right-2"></div>

            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=000&color=fff&size=128"
                alt="Avatar" class="w-[120px] h-[120px] border-[3px] border-black object-cover grayscale contrast-125">

            <div class="flex-grow">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-[0.8rem] font-bold uppercase tracking-widest text-black font-mono">WELCOME,</div>
                        <div class="text-[2.2rem] font-black uppercase leading-none tracking-tight">{{ $user->name }}
                        </div>
                    </div>
                    <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-500 font-mono text-right">
                        STATUS: ONLINE<br>
                        #{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t-2 border-black flex gap-5">
                    <div>
                        <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-500">Email</div>
                        <div class="font-bold font-mono">{{ $user->email }}</div>
                    </div>
                    <div>
                        <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-500">Role</div>
                        <div
                            class="inline-block border-[3px] border-industrial-red text-industrial-red px-2 py-1 rotate-0 font-black uppercase text-[0.9rem] tracking-wider mt-1">
                            {{ strtoupper($user->role) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODULE B: NEXT EVENT -->
        <div
            class="col-span-1 bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] relative p-6 flex flex-col justify-between">
            <!-- Caution Tape -->
            <div
                class="absolute top-0 left-0 right-0 h-6 border-b-[3px] border-black bg-[repeating-linear-gradient(-45deg,#ED1C24,#ED1C24_10px,#000_10px,#000_20px)]">
            </div>

            <div class="mt-9">
                <div class="text-[0.8rem] font-bold uppercase tracking-widest text-industrial-red">UPCOMING EVENT</div>

                @if ($nextEvent)
                    <div class="text-[1.5rem] font-extrabold uppercase leading-[1.1] mt-1">
                        {{ Str::limit($nextEvent->title, 15) }}</div>

                    <div class="mt-4 p-3 bg-black text-center">
                        <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-400">STARTS IN</div>
                        <div class="text-[2.2rem] font-black uppercase leading-none tracking-tight text-white">
                            {{ $daysRemaining }}</div>
                        <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-400">DAYS</div>
                    </div>
                @else
                    <div class="text-[1.5rem] font-extrabold uppercase leading-[1.1] mt-1 text-gray-400">NO UPCOMING EVENTS
                    </div>
                    <div class="mt-4 p-4 border-2 border-dashed border-gray-400 text-center">
                        <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-500">Join an event to see it
                            here</div>
                    </div>
                @endif
            </div>

            <div class="mt-auto pt-4 border-t-2 border-dashed border-gray-300">
                <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-500">Account Status</div>
                <div
                    class="inline-block border-[3px] border-industrial-red text-industrial-red px-3 py-1 -rotate-3 font-black uppercase text-[1.2rem] tracking-wider mt-2 opacity-90">
                    {{ $accountHealth }}
                </div>
            </div>
        </div>

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
                MY EVENTS
            </div>

            @if ($assignedEvents->count() > 0)
                <table class="w-full mt-4 border-collapse font-mono">
                    <thead>
                        <tr>
                            <th class="text-left border-b-[3px] border-black p-4 uppercase font-black text-[0.9rem]">Event
                                Name</th>
                            <th class="text-left border-b-[3px] border-black p-4 uppercase font-black text-[0.9rem]">Date
                            </th>
                            <th class="text-left border-b-[3px] border-black p-4 uppercase font-black text-[0.9rem]">
                                Location</th>
                            <th class="text-left border-b-[3px] border-black p-4 uppercase font-black text-[0.9rem]">Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assignedEvents as $event)
                            <tr>
                                <td class="p-4 border-b border-black font-semibold">
                                    <strong class="font-bold">{{ Str::limit($event->title, 40) }}</strong>
                                    <br><span class="text-[0.6rem] font-bold uppercase tracking-widest text-gray-500">CAT:
                                        {{ $event->category->name ?? 'GENERAL' }}</span>
                                </td>
                                <td class="p-4 border-b border-black font-semibold">{{ $event->date->format('d M Y') }}
                                </td>
                                <td class="p-4 border-b border-black font-semibold">{{ $event->location }}</td>
                                <td class="p-4 border-b border-black font-semibold">
                                    <span
                                        class="inline-block px-2 py-1 border-2 border-black text-[0.75rem] uppercase font-bold bg-white">Registered</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="flex flex-col items-center justify-center h-[200px] opacity-50">
                    <div class="text-[2.2rem] font-black uppercase leading-[0.9] tracking-tight text-gray-300">NO EVENTS
                        FOUND</div>
                    <div class="text-[0.8rem] font-bold uppercase tracking-widest text-gray-500">You haven't joined any
                        events yet.</div>
                </div>
            @endif

            <div class="mt-8 text-right">
                <a href="{{ route('events.index') }}"
                    class="inline-block w-auto px-4 py-3 bg-white border-[3px] border-black shadow-[6px_6px_0px_#000] text-black font-black uppercase text-center transition-all hover:bg-[#f0f0f0] hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] text-[0.8rem]">
                    + Browse Events
                </a>
            </div>
        </div>

    </main>
@endsection
