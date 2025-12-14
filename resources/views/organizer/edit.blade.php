@extends('layouts.app')

@section('title', 'Update Profile')

@section('content')
    <div class="min-h-screen py-10 px-4">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12">

            <!-- COLUMN 1: THE IDENTITY STACK (Avatar) -->
            <div class="flex flex-col items-center">
                <div class="group relative w-full aspect-square max-w-[320px]">
                    <!-- Layer 1 (Bottom): Black Block -->
                    <div
                        class="absolute inset-0 bg-black border-[3px] border-black transition-transform duration-300 group-hover:translate-x-[-8px] group-hover:translate-y-[-8px]">
                    </div>

                    <!-- Layer 2 (Middle): Telkom Red Block -->
                    <div
                        class="absolute inset-0 bg-[#ED1C24] border-[3px] border-black transition-transform duration-300 group-hover:translate-x-[8px] group-hover:translate-y-[8px]">
                    </div>

                    <!-- Layer 3 (Top): Image Container -->
                    <div
                        class="absolute inset-0 bg-white border-[3px] border-black overflow-hidden flex items-center justify-center relative z-10 transition-transform duration-300 group-hover:translate-x-0 group-hover:translate-y-0">
                        @if (auth()->user()->avatar)
                            <!-- Assuming there might be a stored avatar, handling fallback -->
                            <img id="avatar-preview" src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300"
                                alt="Identity Image">
                        @else
                            <img id="avatar-preview"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=000000&color=ffffff&size=256"
                                class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300"
                                alt="Identity Image">
                        @endif
                    </div>

                    <!-- Upload Button (Camera Icon) -->
                    <label for="avatar"
                        class="absolute -bottom-6 -right-6 z-20 w-16 h-16 bg-black border-[3px] border-black flex items-center justify-center cursor-pointer hover:bg-[#CCFF00] transition-colors shadow-[4px_4px_0px_white]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-8 w-8 text-white hover:text-black transition-colors" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*"
                            form="profile-form">
                    </label>
                </div>

                <div class="mt-12 text-center">
                    <h2
                        class="text-3xl font-black uppercase tracking-tighter bg-black text-white px-4 py-1 inline-block transform -skew-x-6">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="font-mono font-bold mt-2 text-[#ED1C24] uppercase tracking-widest">
                        ID: {{ auth()->user()->id }} // {{ auth()->user()->role }}
                    </p>
                </div>
            </div>

            <!-- COLUMN 2: THE DATA SHEET (Form) -->
            <div class="md:col-span-2">
                <div class="bg-white border-[3px] border-black shadow-[12px_12px_0px_0px_black] relative">

                    <!-- Header: Caution Tape Pattern -->
                    <div
                        class="h-8 w-full border-b-[3px] border-black bg-[repeating-linear-gradient(45deg,#ED1C24,#ED1C24_10px,#FFFFFF_10px,#FFFFFF_20px)] flex items-center justify-center overflow-hidden">
                        <div class="bg-black text-white font-black text-xs px-2 py-0.5 transform rotate-2">PERSONNEL DATA //
                            CONFIDENTIAL</div>
                    </div>

                    <div class="p-8">
                        <form id="profile-form" action="{{ route('profile.update') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            @method('PUT')

                            <!-- Identity Section -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="w-4 h-4 bg-black"></span>
                                    <h3 class="font-black text-xl uppercase tracking-widest">IDENTITAS UTAMA</h3>
                                    <div class="flex-grow h-[3px] bg-black"></div>
                                </div>

                                <!-- Name (Read-Only/Locked style as requested in spirit, but usually form allows edit.
                                         Howe    ver, previous code allowed name edit. Request said "Name, Email (Read-only styled as 'LOCKED')".
                                          Wait   , user request said "Name, Email (Read-only styled as "LOCKED")".
                                          But    previous code allowed name edit. I will follow "LOCKED" instruction for visual style
                                           but   if user wants to edit, they might be confused.
                                           Let'  s make Name editable but styled strictly, and Email read-only.
                                            Actu ally, prompts says "Name, Email (Read-only styled as "LOCKED")".
                                            I wi ll follow strict instruction: Read-only.
                                             BUT, wait. "Update Record" implies changing something.
                                             Usually password is the only thing left?
                                         Let'    s check the previous code... name WAS editable.
                                          I wi   ll make Name EDITABLE but styled to look robust, and Email READ-ONLY as is standard.
                                          Re-r   eading: "Inputs: Style ... Labels ... Sections: Identity: Name, Email (Read-only styled as "LOCKED")."
                                           Synt  ax is ambiguous. "Name, Email (Read-only...)" could apply to both.
                                           If I   make name read-only, they can't update profile.
                                            I'll  make Name EDITABLE and Email READ-ONLY (LOCKED).
                                       -->

                                <!-- Name -->
                                <div class="group relative">
                                    <label
                                        class="block font-black uppercase text-xs text-white bg-black inline-block px-2 py-1 mb-1 transform -skew-x-12">
                                        NAMA LENGKAP
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                        class="w-full bg-[#f2f2f2] border-[3px] border-black p-4 font-mono text-lg focus:outline-none focus:bg-white focus:translate-x-[-4px] focus:translate-y-[-4px] focus:shadow-[8px_8px_0px_0px_black] transition-all placeholder-gray-400"
                                        placeholder="MASUKKAN NAMA BARU">
                                </div>

                                <!-- Email (Locked) -->
                                <div class="group relative opacity-75">
                                    <div
                                        class="absolute right-0 top-0 bg-[#ED1C24] text-white text-[10px] font-bold px-2 py-1 border-l-[3px] border-b-[3px] border-black z-10">
                                        LOCKED
                                    </div>
                                    <label
                                        class="block font-black uppercase text-xs text-white bg-[#555] inline-block px-2 py-1 mb-1 transform -skew-x-12">
                                        EMAIL TERDAFTAR
                                    </label>
                                    <input type="email" value="{{ auth()->user()->email }}" disabled
                                        class="w-full bg-[#e5e5e5] border-[3px] border-black border-dashed p-4 font-mono text-lg text-gray-500 cursor-not-allowed select-none">
                                </div>

                                <!-- Organization (If Organizer) -->
                                @if (auth()->user()->role === 'organizer')
                                    <div class="group relative mt-6">
                                        <label
                                            class="block font-black uppercase text-xs text-white bg-black inline-block px-2 py-1 mb-1 transform -skew-x-12">
                                            ORGANISASI
                                        </label>

                                        @php
                                            $userOrg = auth()
                                                ->user()
                                                ->organizations()
                                                ->wherePivot('organization_id', auth()->user()->organization_id)
                                                ->first();
                                            $status = $userOrg ? $userOrg->pivot->status : null;
                                            $orgName = $userOrg ? $userOrg->name : '';
                                        @endphp

                                        @if ($status === 'approved')
                                            <div
                                                class="w-full p-4 border-[3px] border-black bg-[#CCFF00] font-mono font-bold flex items-center gap-2">
                                                <span class="text-xl">✓</span> APPROVED: {{ $orgName }}
                                            </div>
                                        @elseif($status === 'pending')
                                            <div
                                                class="w-full p-4 border-[3px] border-black bg-yellow-300 font-mono font-bold flex items-center gap-2">
                                                <span class="text-xl">⏳</span> PENDING: {{ $orgName }}
                                            </div>
                                        @elseif($status === 'rejected')
                                            <div
                                                class="w-full p-4 border-[3px] border-black bg-[#ED1C24] text-white font-mono font-bold flex items-center gap-2 mb-2">
                                                <span class="text-xl">✗</span> REJECTED: {{ $orgName }}
                                            </div>
                                            <input type="number" name="organization_id"
                                                class="w-full bg-[#f2f2f2] border-[3px] border-black p-4 font-mono text-lg focus:outline-none focus:bg-white focus:translate-x-[-4px] focus:translate-y-[-4px] focus:shadow-[8px_8px_0px_0px_black] transition-all"
                                                placeholder="MASUKKAN ID ORGANISASI BARU">
                                        @else
                                            <input type="number" name="organization_id"
                                                value="{{ old('organization_id') }}"
                                                class="w-full bg-[#f2f2f2] border-[3px] border-black p-4 font-mono text-lg focus:outline-none focus:bg-white focus:translate-x-[-4px] focus:translate-y-[-4px] focus:shadow-[8px_8px_0px_0px_black] transition-all"
                                                placeholder="MASUKKAN ID ORGANISASI">
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Security Section -->
                            <div class="space-y-6 pt-6 border-t-[3px] border-black border-dashed">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="w-4 h-4 bg-[#ED1C24]"></span>
                                    <h3 class="font-black text-xl uppercase tracking-widest text-[#ED1C24]">KEAMANAN</h3>
                                    <div class="flex-grow h-[3px] bg-[#ED1C24] bg-opacity-20"></div>
                                </div>

                                <div class="group relative">
                                    <label
                                        class="block font-black uppercase text-xs text-white bg-black inline-block px-2 py-1 mb-1 transform -skew-x-12">
                                        PASSWORD BARU
                                    </label>
                                    <input type="password" name="password"
                                        class="w-full bg-[#f2f2f2] border-[3px] border-black p-4 font-mono text-lg focus:outline-none focus:bg-white focus:translate-x-[-4px] focus:translate-y-[-4px] focus:shadow-[8px_8px_0px_0px_black] transition-all"
                                        placeholder="KOSONGKAN JIKA TIDAK UBAH">
                                </div>
                            </div>

                            <!-- Action Bar -->
                            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-6">
                                <button type="submit"
                                    class="w-full md:w-auto px-8 py-4 bg-black text-white font-black text-xl uppercase tracking-widest border-[3px] border-black hover:bg-[#ED1C24] hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[-4px] hover:translate-y-[-4px] transition-all flex items-center justify-center gap-3">
                                    <span>PERBARUI DATA</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                </button>

                                <!-- Discharge (Delete) - Note: In a real app this should be a separate form/method DELETE -->
                                <!-- Using a simple link for visual per request, but technically should be a form for security.
                                           Request says: "DISCHARGE" -> Small text link, red, underlined.
                                        -->
                                <a href="#"
                                    class="text-[#ED1C24] font-mono font-bold uppercase underline hover:bg-[#ED1C24] hover:text-white hover:no-underline px-2 transition-colors">
                                    HAPUS AKUN (DISCHARGE)
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('avatar');
            const previewImage = document.getElementById('avatar-preview');

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
