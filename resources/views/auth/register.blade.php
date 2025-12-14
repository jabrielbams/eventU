@extends('layouts.guest')

@section('title', 'Registrasi Akun')

@section('content')
    <div class="flex justify-center items-center py-10 w-full relative">
        {{-- Card: Neo-Brutalism --}}
        <div
            class="w-full max-w-lg bg-white border-[3px] border-black shadow-[12px_12px_0px_0px_black] relative overflow-hidden">

            {{-- Header: Hazard Strip --}}
            <div
                class="h-4 w-full bg-[repeating-linear-gradient(45deg,#ED1C24,#ED1C24_10px,#ffffff_10px,#ffffff_20px)] border-b-[3px] border-black">
            </div>

            {{-- Header: Title Bar --}}
            <div class="bg-black text-white p-4 text-center border-b-[3px] border-black">
                <h1 class="text-xl font-bold uppercase tracking-widest">REGISTRASI AKUN</h1>
            </div>

            {{-- The Form --}}
            <form id="registerForm" method="POST" action="{{ route('register.post') }}" class="p-8 space-y-5">
                @csrf

                {{-- Success Message --}}
                @if (session('success'))
                    <div
                        class="bg-[#CCFF00] text-black p-3 border-[3px] border-black font-mono text-sm shadow-[4px_4px_0px_0px_black] mb-4 font-bold uppercase">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error Message (Hidden) --}}
                <div id="error-message" class="hidden"></div>

                {{-- Server-side Errors --}}
                @if ($errors->any())
                    <div
                        class="bg-[#ED1C24] text-white p-3 border-[3px] border-black font-mono text-sm shadow-[4px_4px_0px_0px_black] mb-4">
                        @foreach ($errors->all() as $error)
                            <p class="uppercase">ERROR: {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Input: Name --}}
                <div>
                    <label for="name" class="block text-lg font-bold mb-1 uppercase tracking-wide">NAMA LENGKAP</label>
                    <input type="text" name="name" id="name" required autofocus
                        class="w-full border-[3px] border-black p-3 text-lg font-medium focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow placeholder-gray-500"
                        placeholder="Nama Mahasiswa / Organisasi" value="{{ old('name') }}">
                </div>

                {{-- Input: Email --}}
                <div>
                    <label for="email" class="block text-lg font-bold mb-1 uppercase tracking-wide">ALAMAT EMAIL</label>
                    <input type="email" name="email" id="email" required
                        class="w-full border-[3px] border-black p-3 text-lg font-medium focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow"
                        value="{{ old('email') }}">
                </div>

                {{-- Input: Role --}}
                <div>
                    <label for="role" class="block text-lg font-bold mb-1 uppercase tracking-wide">TIPE AKUN</label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Option: Student -->
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="student" class="peer sr-only"
                                {{ old('role', 'student') == 'student' ? 'checked' : '' }}>
                            <div
                                class="w-full border-[3px] border-black p-3 text-center text-lg font-bold uppercase transition-all
                                peer-checked:bg-black peer-checked:text-white peer-checked:shadow-[4px_4px_0px_0px_#CCFF00]
                                hover:bg-gray-100">
                                Mahasiswa
                            </div>
                        </label>
                        <!-- Option: Organizer -->
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="organizer" class="peer sr-only"
                                {{ old('role') == 'organizer' ? 'checked' : '' }}>
                            <div
                                class="w-full border-[3px] border-black p-3 text-center text-lg font-bold uppercase transition-all
                                peer-checked:bg-black peer-checked:text-white peer-checked:shadow-[4px_4px_0px_0px_#CCFF00]
                                hover:bg-gray-100">
                                Panitia
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Input: Password --}}
                <div>
                    <label for="password" class="block text-lg font-bold mb-1 uppercase tracking-wide">KATA SANDI</label>
                    <input type="password" name="password" id="password" required
                        class="w-full border-[3px] border-black p-3 text-lg font-medium focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow">
                </div>

                {{-- Input: Confirm Password --}}
                <div>
                    <label for="password_confirmation"
                        class="block text-lg font-bold mb-1 uppercase tracking-wide">KONFIRMASI KATA SANDI</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full border-[3px] border-black p-3 text-lg font-medium focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow">
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full bg-black text-white border-[3px] border-black py-3 text-xl font-bold uppercase hover:bg-[#CCFF00] hover:text-black transition-colors">
                    DAFTAR SEKARANG
                </button>

                {{-- Login Link --}}
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}"
                        class="text-black font-medium underline decoration-2 hover:text-[#ED1C24] transition-colors">
                        Sudah punya akun? Masuk di sini
                    </a>
                </div>

            </form>
        </div>
    </div>
@endsection
