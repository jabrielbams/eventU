@extends('layouts.app')

@section('title', 'Access Your Hub')

@section('content')
<div class="flex justify-center items-center py-10 w-full relative">

    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter text-telkom-black uppercase drop-shadow-[3px_3px_0px_rgba(238,46,36,1)]">
                Selamat Datang Kembali
            </h1>
            <p class="mt-2 text-lg font-medium text-gray-600">Lanjutkan perjalanan Anda di TelyuEvents.</p>
        </div>

        <!-- Form Card -->
        <div class="neo-box bg-white p-8 relative overflow-hidden">
            <!-- Decorative Shape -->
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-telkom-red rounded-full border-4 border-telkom-black z-0"></div>

            <form action="{{ route('login.post') }}" method="POST" class="relative z-10 space-y-6">
                @csrf

                <!-- Success Message (from registration) -->
                @if(session('success'))
                <div class="neo-box bg-green-500 p-4 text-white font-bold mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Login Error Alert -->
                @if ($errors->any())
                <div class="neo-box bg-telkom-red p-4 text-white font-bold mb-4">
                    {{ $errors->first() }}
                </div>
                @endif

                <!-- Email -->
                <div>
                    <label for="email" class="block text-lg font-bold mb-2 uppercase tracking-wide">Email</label>
                    <input type="email" name="email" id="email"
                        class="neo-input focus:ring-0"
                        placeholder="student@telkomuniversity.ac.id"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1 inline-block">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-lg font-bold mb-2 uppercase tracking-wide">Password</label>
                    <input type="password" name="password" id="password"
                        class="neo-input focus:ring-0"
                        placeholder="Password">
                    @error('password')
                        <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1 inline-block">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full neo-button text-xl tracking-widest mt-4">
                    Login
                </button>

                <p class="text-center text-sm font-medium mt-4">
                    Baru di sini? <a href="{{ route('register') }}" class="underline decoration-2 decoration-telkom-red hover:text-telkom-red">Buat Akun</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
