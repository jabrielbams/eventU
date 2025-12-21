@extends('layouts.app')

@section('title', 'Edit Organisasi')

@section('content')
    <div class="min-h-screen py-10 px-4">
        <div class="max-w-3xl mx-auto">

            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('organizations.index') }}" 
                   class="inline-flex items-center gap-2 font-mono text-sm uppercase hover:text-[#ED1C24] transition-colors mb-4">
                    ← Kembali ke Daftar
                </a>
                <h1 class="text-4xl font-black uppercase tracking-tighter bg-black text-white px-4 py-2 inline-block transform -skew-x-3">
                    Edit Organisasi
                </h1>
                <p class="mt-2 font-mono text-sm text-gray-600">ID: {{ $organization->id }}</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000]">
                <!-- Header: Caution Tape Pattern -->
                <div class="h-8 w-full border-b-[3px] border-black bg-[repeating-linear-gradient(45deg,#ED1C24,#ED1C24_10px,#FFFFFF_10px,#FFFFFF_20px)] flex items-center justify-center">
                    <div class="bg-black text-white font-black text-xs px-2 py-0.5">
                        EDIT DATA // {{ strtoupper($organization->name) }}
                    </div>
                </div>

                <form action="{{ route('organizations.update', $organization->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Organization Name -->
                    <div class="group relative">
                        <label class="block font-black uppercase text-xs text-white bg-black inline-block px-2 py-1 mb-2 transform -skew-x-12">
                            Nama Organisasi <span class="text-[#ED1C24]">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $organization->name) }}" required
                               class="w-full bg-[#f2f2f2] border-[3px] border-black p-4 font-mono text-lg focus:outline-none focus:bg-white focus:translate-x-[-4px] focus:translate-y-[-4px] focus:shadow-[8px_8px_0px_0px_black] transition-all @error('name') border-[#ED1C24] @enderror"
                               placeholder="Contoh: Himpunan Mahasiswa Informatika">
                        @error('name')
                            <p class="mt-1 text-[#ED1C24] font-mono text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="group relative">
                        <label class="block font-black uppercase text-xs text-white bg-black inline-block px-2 py-1 mb-2 transform -skew-x-12">
                            Deskripsi
                        </label>
                        <textarea name="description" rows="4"
                                  class="w-full bg-[#f2f2f2] border-[3px] border-black p-4 font-mono focus:outline-none focus:bg-white focus:translate-x-[-4px] focus:translate-y-[-4px] focus:shadow-[8px_8px_0px_0px_black] transition-all resize-none @error('description') border-[#ED1C24] @enderror"
                                  placeholder="Jelaskan tentang organisasi kamu...">{{ old('description', $organization->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-[#ED1C24] font-mono text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email & Phone Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="group relative">
                            <label class="block font-black uppercase text-xs text-white bg-black inline-block px-2 py-1 mb-2 transform -skew-x-12">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email', $organization->email) }}"
                                   class="w-full bg-[#f2f2f2] border-[3px] border-black p-4 font-mono focus:outline-none focus:bg-white focus:translate-x-[-4px] focus:translate-y-[-4px] focus:shadow-[8px_8px_0px_0px_black] transition-all @error('email') border-[#ED1C24] @enderror"
                                   placeholder="email@organisasi.com">
                            @error('email')
                                <p class="mt-1 text-[#ED1C24] font-mono text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="group relative">
                            <label class="block font-black uppercase text-xs text-white bg-black inline-block px-2 py-1 mb-2 transform -skew-x-12">
                                Telepon
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $organization->phone) }}"
                                   class="w-full bg-[#f2f2f2] border-[3px] border-black p-4 font-mono focus:outline-none focus:bg-white focus:translate-x-[-4px] focus:translate-y-[-4px] focus:shadow-[8px_8px_0px_0px_black] transition-all @error('phone') border-[#ED1C24] @enderror"
                                   placeholder="08123456789">
                            @error('phone')
                                <p class="mt-1 text-[#ED1C24] font-mono text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="group relative">
                        <label class="block font-black uppercase text-xs text-white bg-black inline-block px-2 py-1 mb-2 transform -skew-x-12">
                            Alamat
                        </label>
                        <textarea name="address" rows="2"
                                  class="w-full bg-[#f2f2f2] border-[3px] border-black p-4 font-mono focus:outline-none focus:bg-white focus:translate-x-[-4px] focus:translate-y-[-4px] focus:shadow-[8px_8px_0px_0px_black] transition-all resize-none @error('address') border-[#ED1C24] @enderror"
                                  placeholder="Alamat sekretariat organisasi...">{{ old('address', $organization->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-[#ED1C24] font-mono text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div class="group relative">
                        <label class="block font-black uppercase text-xs text-white bg-black inline-block px-2 py-1 mb-2 transform -skew-x-12">
                            Logo Organisasi
                        </label>
                        <div class="flex items-center gap-4">
                            <div id="logo-preview" class="w-24 h-24 bg-gray-100 border-[3px] border-black flex items-center justify-center overflow-hidden">
                                @if($organization->logo)
                                    <img src="{{ asset('storage/' . $organization->logo) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl">🏢</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="logo" id="logo-input" accept="image/jpeg,image/png,image/jpg"
                                       class="w-full bg-[#f2f2f2] border-[3px] border-black p-3 font-mono text-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-black file:text-white file:font-bold file:uppercase file:cursor-pointer hover:file:bg-[#ED1C24] transition-all">
                                <p class="mt-1 font-mono text-xs text-gray-500">Format: JPG, PNG. Max: 2MB. Kosongkan jika tidak ingin mengubah.</p>
                            </div>
                        </div>
                        @error('logo')
                            <p class="mt-1 text-[#ED1C24] font-mono text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 border-t-[3px] border-black border-dashed flex gap-4">
                        <button type="submit"
                                class="flex-1 bg-[#CCFF00] border-[3px] border-black p-4 font-black uppercase text-xl shadow-[6px_6px_0px_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] hover:shadow-[8px_8px_0px_#000] transition-all">
                            ✓ Simpan Perubahan
                        </button>
                        <a href="{{ route('organizations.index') }}"
                           class="bg-gray-200 border-[3px] border-black p-4 font-black uppercase shadow-[6px_6px_0px_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] hover:shadow-[8px_8px_0px_#000] transition-all">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        // Logo preview
        document.getElementById('logo-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logo-preview').innerHTML = 
                        '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
