@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
    <div class="flex justify-center items-center py-10 w-full relative px-4">
        {{-- Card: Mission Manifesto --}}
        <div
            class="w-full max-w-4xl bg-white border-[3px] border-black shadow-[12px_12px_0px_0px_black] relative overflow-hidden">

            {{-- Header: Hazard Strip --}}
            <div
                class="h-4 w-full bg-[repeating-linear-gradient(45deg,#ED1C24,#ED1C24_10px,#ffffff_10px,#ffffff_20px)] border-b-[3px] border-black">
            </div>

            {{-- Header: Title Bar --}}
            <div class="bg-black text-white p-6 border-b-[3px] border-black flex justify-between items-center">
                <h1 class="text-3xl font-black uppercase leading-none tracking-tighter">EDIT EVENT</h1>
                <a href="{{ route('organizer.events') }}"
                   class="px-4 py-2 bg-white text-black border-[2px] border-white font-bold uppercase text-sm hover:bg-[#CCFF00] hover:border-black transition-all">
                    &larr; KEMBALI
                </a>
            </div>

            <form action="{{ route('events.update', $event->id) }}" method="POST" id="edit-event-form"
                class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Global Error Alert --}}
                @if (session('error'))
                    <div
                        class="col-span-1 md:col-span-2 bg-[#ED1C24] text-white p-3 border-[3px] border-black font-mono text-sm shadow-[4px_4px_0px_0px_black] uppercase font-bold">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Judul Event (Full Width) --}}
                <div class="col-span-1 md:col-span-2">
                    <label for="title"
                        class="block text-xl font-black mb-2 uppercase tracking-wide border-l-4 border-black pl-2">JUDUL
                        EVENT</label>
                    <input type="text" name="title" id="title" required
                        class="w-full border-[3px] border-black p-4 text-2xl font-bold uppercase focus:outline-none focus:shadow-[8px_8px_0px_0px_#CCFF00] transition-shadow placeholder-gray-400"
                        placeholder="MASUKKAN NAMA EVENT..." value="{{ old('title', $event->title) }}">
                    @error('title')
                        <p class="text-[#ED1C24] font-bold text-sm mt-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="category_id"
                        class="block text-lg font-bold mb-2 uppercase tracking-wide border-l-4 border-black pl-2">KATEGORI</label>
                    <div class="relative">
                        <select name="category_id" id="category_id" required
                            class="w-full border-[3px] border-black p-3 text-lg font-medium appearance-none focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow cursor-pointer bg-white uppercase">
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-black font-bold text-xl">
                            ▼
                        </div>
                    </div>
                    @error('category_id')
                        <p class="text-[#ED1C24] font-bold text-sm mt-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal & Waktu (Split) --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="date"
                            class="block text-lg font-bold mb-2 uppercase tracking-wide border-l-4 border-black pl-2">TANGGAL</label>
                        <input type="date" name="date" id="date" required
                            class="w-full border-[3px] border-black p-3 text-lg font-medium focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow"
                            value="{{ old('date', $event->date instanceof \Carbon\Carbon ? $event->date->format('Y-m-d') : $event->date) }}">
                        @error('date')
                            <p class="text-[#ED1C24] font-bold text-sm mt-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="time"
                            class="block text-lg font-bold mb-2 uppercase tracking-wide border-l-4 border-black pl-2">WAKTU</label>
                        <input type="time" name="time" id="time" required
                            class="w-full border-[3px] border-black p-3 text-lg font-medium focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow"
                            value="{{ old('time', $event->time instanceof \Carbon\Carbon ? $event->time->format('H:i') : $event->time) }}">
                        @error('time')
                            <p class="text-[#ED1C24] font-bold text-sm mt-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Lokasi / Tempat (Full Width) --}}
                <div class="col-span-1 md:col-span-2">
                    <label for="location"
                        class="block text-lg font-bold mb-2 uppercase tracking-wide border-l-4 border-black pl-2">LOKASI /
                        TEMPAT</label>
                    <input type="text" name="location" id="location" required
                        class="w-full border-[3px] border-black p-3 text-lg font-medium focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow placeholder-gray-500"
                        placeholder="Gedung Serbaguna, Zoom, dll." value="{{ old('location', $event->location) }}">
                    @error('location')
                        <p class="text-[#ED1C24] font-bold text-sm mt-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi Lengkap --}}
                <div class="col-span-1 md:col-span-2">
                    <label for="description"
                        class="block text-lg font-bold mb-2 uppercase tracking-wide border-l-4 border-black pl-2">DESKRIPSI
                        LENGKAP</label>
                    <textarea name="description" id="description" rows="6"
                        class="w-full border-[3px] border-black p-3 text-lg font-mono focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow placeholder-gray-500 h-40 resize-y"
                        placeholder="Jelaskan detail misi event ini...">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="text-[#ED1C24] font-bold text-sm mt-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Poster (The Drop Zone) --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-lg font-bold mb-2 uppercase tracking-wide border-l-4 border-black pl-2">POSTER
                        EVENT</label>
                    <div id="drop-zone"
                        class="border-[3px] border-dashed border-black bg-gray-50 hover:bg-[#FFFACD] transition-colors cursor-pointer p-10 text-center relative flex flex-col items-center justify-center min-h-[200px] group">
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*">

                        {{-- Default State --}}
                        <div id="drop-zone-text"
                            class="pointer-events-none flex flex-col items-center gap-2 {{ $event->image ? 'hidden' : '' }}">
                            <svg class="w-12 h-12 text-black mb-2 group-hover:scale-110 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            <span class="text-xl font-black uppercase text-gray-500">KLIK ATAU TARUH POSTER DI SINI</span>
                        </div>

                        {{-- Preview Image --}}
                        <img id="image-preview"
                            src="{{ $event->image ? asset('storage/' . $event->image) : '#' }}"
                            alt="Poster Preview"
                            class="{{ $event->image ? '' : 'hidden' }} max-h-[300px] w-full object-contain border-[3px] border-black shadow-[4px_4px_0px_0px_black]">
                    </div>
                    @error('image')
                        <p class="text-[#ED1C24] font-bold text-sm mt-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="col-span-1 md:col-span-2 flex flex-col md:flex-row gap-4 mt-4">
                    <button type="submit"
                        class="flex-1 bg-black text-white border-[3px] border-black py-4 text-xl font-black uppercase hover:bg-[#ED1C24] hover:shadow-[4px_4px_0px_0px_black] transition-all">
                        SIMPAN PERUBAHAN
                    </button>
                    <a href="{{ route('organizer.events') }}"
                        class="flex-1 bg-white text-black border-[3px] border-black py-4 text-xl font-bold uppercase text-center hover:bg-gray-200 transition-colors">
                        BATAL
                    </a>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dropZone = document.getElementById("drop-zone");
            const imageInput = document.getElementById("image-input");
            const imagePreview = document.getElementById("image-preview");
            const dropZoneText = document.getElementById("drop-zone-text");

            if (!dropZone || !imageInput || !imagePreview || !dropZoneText) {
                return;
            }

            dropZone.addEventListener("click", () => imageInput.click());

            imageInput.addEventListener("change", function() {
                if (this.files && this.files[0]) {
                    showPreview(this.files[0]);
                }
            });

            ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ["dragenter", "dragover"].forEach((eventName) => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ["dragleave", "drop"].forEach((eventName) => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add("bg-[#FFFACD]");
                dropZone.classList.remove("bg-gray-50");
            }

            function unhighlight(e) {
                dropZone.classList.remove("bg-[#FFFACD]");
                dropZone.classList.add("bg-gray-50");
            }

            dropZone.addEventListener("drop", handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files && files[0]) {
                    imageInput.files = files;
                    showPreview(files[0]);
                }
            }

            function showPreview(file) {
                if (file.type.startsWith("image/")) {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function() {
                        imagePreview.src = reader.result;
                        imagePreview.classList.remove("hidden");
                        dropZoneText.classList.add("hidden");
                    };
                }
            }
        });
    </script>
@endsection
