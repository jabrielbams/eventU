@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-3xl font-black uppercase mb-8">Edit Pengumuman</h1>
    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST" class="bg-white border-[3px] border-black shadow-[4px_4px_0px_#000] p-8 flex flex-col gap-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-bold mb-2 uppercase">Judul</label>
            <input type="text" name="title" class="w-full border-[2px] border-black p-3 font-mono" required value="{{ old('title', $announcement->title) }}">
        </div>
        <div>
            <label class="block font-bold mb-2 uppercase">Isi Pengumuman</label>
            <textarea name="content" rows="6" class="w-full border-[2px] border-black p-3 font-mono" required>{{ old('content', $announcement->content) }}</textarea>
        </div>
        <div>
            <label class="block font-bold mb-2 uppercase">Terkait Event (Opsional)</label>
            <select name="event_id" class="w-full border-[2px] border-black p-3 font-mono">
                <option value="">- Umum / Tidak Terkait Event -</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ $announcement->event_id == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="submit" class="px-6 py-3 bg-industrial-red text-white border-[3px] border-black font-black uppercase shadow-[4px_4px_0px_#000] hover:bg-red-700 transition-all">Simpan</button>
            <a href="{{ route('announcements.index') }}" class="px-6 py-3 bg-white text-black border-[3px] border-black font-black uppercase shadow-[4px_4px_0px_#000] hover:bg-gray-200 transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection
