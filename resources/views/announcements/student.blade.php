@extends('layouts.app')

@section('title', 'Pengumuman Event Saya')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-3xl font-black uppercase mb-8">Pengumuman Event Saya</h1>
    <div class="bg-white border-[3px] border-black shadow-[4px_4px_0px_#000] p-6">
        <ul class="divide-y divide-black">
            @forelse($announcements as $announcement)
                <li class="py-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-lg">{{ $announcement->title }}</span>
                        <span class="text-xs bg-industrial-red text-white px-2 py-1 rounded font-bold uppercase">{{ $announcement->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="mb-2 text-gray-700">{!! nl2br(e($announcement->content)) !!}</div>
                    @if($announcement->event)
                        <div class="text-xs text-gray-500 font-mono">Terkait Event: <span class="font-bold">{{ $announcement->event->title }}</span></div>
                    @endif
                </li>
            @empty
                <li class="py-8 text-center text-gray-400 font-bold">Belum ada pengumuman untuk event yang kamu ikuti.</li>
            @endforelse
        </ul>
        <div class="mt-6">{{ $announcements->links() }}</div>
    </div>
</div>
@endsection
