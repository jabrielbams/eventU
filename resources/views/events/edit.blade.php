@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="flex justify-center items-center py-10 w-full relative px-4">

    <div class="w-full max-w-4xl">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter text-telkom-black uppercase drop-shadow-[3px_3px_0px_rgba(238,46,36,1)]">
                Edit Event
            </h1>
            <p class="mt-2 text-lg font-medium text-gray-600">Perbarui informasi event Anda.</p>
        </div>

        <!-- Form Card -->
        <div class="neo-box bg-white p-8 relative overflow-hidden">
            <!-- Decorative Shape -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-telkom-red rounded-full border-4 border-telkom-black z-0"></div>

            <form action="{{ route('events.update', $event->id) }}" method="POST" id="edit-event-form" class="relative z-10 space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div>
                    <label for="title" class="block text-lg font-bold mb-2 uppercase tracking-wide">Judul Event</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}"
                        class="neo-input focus:ring-0"
                        placeholder="Masukkan judul event..." required>

                    @error('title')
                    <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1 inline-block">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid for Category, Date, Time -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-lg font-bold mb-2 uppercase tracking-wide">Kategori</label>
                        <select name="category_id" id="category_id" class="neo-input focus:ring-0" required>
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>

                        @error('category_id')
                        <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1 inline-block">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-lg font-bold mb-2 uppercase tracking-wide">Tanggal</label>
                        <input type="date" name="date" id="date" value="{{ old('date', $event->date) }}" class="neo-input focus:ring-0" required>

                        @error('date')
                        <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1 inline-block">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time -->
                    <div>
                        <label for="time" class="block text-lg font-bold mb-2 uppercase tracking-wide">Waktu</label>
                        <input type="time" name="time" id="time" value="{{ old('time', $event->time) }}" class="neo-input focus:ring-0" required>

                        @error('time')
                        <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1 inline-block">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-lg font-bold mb-2 uppercase tracking-wide">Lokasi Event</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}"
                        class="neo-input focus:ring-0"
                        placeholder="Venue atau Link Meeting" required>

                    @error('location')
                    <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1 inline-block">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-lg font-bold mb-2 uppercase tracking-wide">Deskripsi Event</label>
                    <textarea name="description" id="description" rows="6"
                        class="neo-input focus:ring-0 resize-y"
                        placeholder="Masukkan detail event...">{{ old('description', $event->description) }}</textarea>

                    @error('description')
                    <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1 inline-block">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-lg font-bold mb-2 uppercase tracking-wide">Poster Event</label>
                    <div id="drop-zone" class="border-3 border-dashed border-telkom-black bg-gray-50 p-10 text-center cursor-pointer hover:bg-gray-100 transition-colors relative min-h-[200px] flex flex-col items-center justify-center">
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*">
                        <div id="drop-zone-text" class="text-xl font-black uppercase text-gray-400 pointer-events-none {{ $event->image ? 'hidden' : '' }}">
                            Drag & Drop Gambar Di Sini atau Klik untuk Memilih
                        </div>
                        <img id="image-preview" src="{{ $event->image ? asset('storage/' . $event->image) : '' }}" class="{{ $event->image ? '' : 'hidden' }} max-h-[300px] mt-4 border-2 border-telkom-black object-contain" alt="Preview">
                    </div>

                    @error('image')
                    <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1 inline-block">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-launch">Update Event</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/event-image-upload.js'])
@endpush
