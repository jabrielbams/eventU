@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-black uppercase">Manajemen Pengumuman</h1>
        <a href="{{ route('announcements.create') }}" class="px-6 py-3 bg-industrial-red text-white border-[3px] border-black font-black uppercase shadow-[4px_4px_0px_#000] hover:bg-red-700 transition-all">+ Buat Pengumuman</a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-600 text-green-800 font-bold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-600 text-red-800 font-bold">{{ session('error') }}</div>
    @endif
    <div class="bg-white border-[3px] border-black shadow-[4px_4px_0px_#000] p-6">
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th class="p-2 border-b-2 border-black">Judul</th>
                    <th class="p-2 border-b-2 border-black">Event</th>
                    <th class="p-2 border-b-2 border-black">Tanggal</th>
                    <th class="p-2 border-b-2 border-black">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                    <tr>
                        <td class="p-2 font-bold">{{ $announcement->title }}</td>
                        <td class="p-2">{{ $announcement->event->title ?? '-' }}</td>
                        <td class="p-2">{{ $announcement->created_at->format('d M Y') }}</td>
                        <td class="p-2 flex gap-2">
                            <a href="#" class="px-3 py-1 bg-black text-white font-bold uppercase text-xs border-[2px] border-black hover:bg-industrial-red transition-all">Lihat</a>
                            <a href="{{ route('announcements.edit', $announcement->id) }}" class="px-3 py-1 bg-white text-black font-bold uppercase text-xs border-[2px] border-black hover:bg-gray-200 transition-all">Edit</a>
                            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-white text-industrial-red font-bold uppercase text-xs border-[2px] border-black hover:bg-industrial-red hover:text-white transition-all">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-400 font-bold">Belum ada pengumuman</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">{{ $announcements->links() }}</div>
    </div>
</div>
@endsection
