@extends('layouts.app')

@section('title', 'Daftar Organisasi')

@section('content')
    <div class="min-h-screen py-10 px-4">
        <div class="max-w-6xl mx-auto">

            <!-- Header Section -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-4xl font-black uppercase tracking-tighter bg-black text-white px-4 py-2 inline-block transform -skew-x-3">
                        Daftar Organisasi
                    </h1>
                    <p class="mt-2 font-mono text-sm text-gray-600">Total: {{ $organizations->count() }} organisasi terdaftar</p>
                </div>

                <a href="{{ route('organizations.create') }}"
                   class="bg-[#CCFF00] border-[3px] border-black px-6 py-3 font-black uppercase shadow-[6px_6px_0px_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] hover:shadow-[8px_8px_0px_#000] transition-all">
                    + Buat Organisasi Baru
                </a>
            </div>

            <!-- Organizations Grid -->
            @if($organizations->isEmpty())
                <div class="bg-white border-[3px] border-black p-12 text-center shadow-[8px_8px_0px_#000]">
                    <span class="text-6xl mb-4 block">🏢</span>
                    <p class="font-mono text-gray-500 uppercase mb-4">Belum ada organisasi terdaftar.</p>
                    <a href="{{ route('organizations.create') }}"
                       class="inline-block bg-black text-white px-6 py-3 font-bold uppercase hover:bg-[#ED1C24] transition-colors">
                        Buat Organisasi Pertama
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($organizations as $org)
                        <div class="bg-white border-[3px] border-black shadow-[6px_6px_0px_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] hover:shadow-[8px_8px_0px_#000] transition-all group">
                            <!-- Card Header with Logo -->
                            <div class="h-32 bg-gray-100 border-b-[3px] border-black relative overflow-hidden">
                                @if($org->logo)
                                    <img src="{{ asset('storage/' . $org->logo) }}" 
                                         alt="{{ $org->name }}" 
                                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[repeating-linear-gradient(45deg,#f0f0f0,#f0f0f0_10px,#ffffff_10px,#ffffff_20px)]">
                                        <span class="text-5xl">🏢</span>
                                    </div>
                                @endif
                                <!-- ID Badge -->
                                <div class="absolute top-2 right-2 bg-black text-white text-xs font-mono px-2 py-1">
                                    ID: {{ $org->id }}
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4">
                                <h3 class="font-black text-lg uppercase tracking-tight mb-2 line-clamp-1">
                                    {{ $org->name }}
                                </h3>
                                
                                @if($org->description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $org->description }}</p>
                                @endif

                                <!-- Stats -->
                                <div class="flex items-center gap-4 mb-4 text-xs font-mono">
                                    <span class="bg-[#CCFF00] border border-black px-2 py-1">
                                        👥 {{ $org->members_count }} anggota
                                    </span>
                                    @if($org->email)
                                        <span class="text-gray-500 truncate">📧 {{ $org->email }}</span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <a href="{{ route('organizations.edit', $org->id) }}"
                                       class="flex-1 bg-black text-white text-center py-2 font-bold text-xs uppercase hover:bg-[#333] transition-colors">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('organizations.destroy', $org->id) }}" method="POST" class="flex-1"
                                          onsubmit="return confirm('Yakin ingin menghapus organisasi {{ $org->name }}? Semua data terkait akan hilang!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full bg-[#ED1C24] text-white py-2 font-bold text-xs uppercase hover:bg-red-700 transition-colors">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Info Box -->
            <div class="mt-8 bg-black text-white p-6 border-[3px] border-black">
                <div class="flex items-start gap-4">
                    <span class="text-3xl">💡</span>
                    <div>
                        <h3 class="font-black uppercase mb-2">Informasi</h3>
                        <ul class="font-mono text-sm space-y-1 text-gray-300">
                            <li>• Kamu hanya bisa mengedit/menghapus organisasi yang kamu menjadi anggota (approved).</li>
                            <li>• Saat membuat organisasi baru, kamu otomatis menjadi anggota dengan status approved.</li>
                            <li>• Gunakan menu <span class="text-[#CCFF00]">Kelola Organisasi</span> untuk menyetujui anggota baru.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
