@extends('layouts.guest')

@section('title', 'Akses Kontrol')

@section('content')
    {{-- Main Card: Neo-Brutalist Container --}}
    <div
        class="w-full max-w-md bg-white border-[3px] border-black shadow-[12px_12px_0px_0px_black] relative overflow-hidden">

        {{-- Header: Hazard Strip --}}
        <div
            class="h-4 w-full bg-[repeating-linear-gradient(45deg,#ED1C24,#ED1C24_10px,#ffffff_10px,#ffffff_20px)] border-b-[3px] border-black">
        </div>

        {{-- Header: Title Bar --}}
        <div class="bg-black text-white p-4 text-center border-b-[3px] border-black">
            <h1 class="text-xl font-bold uppercase tracking-widest">LOGIN</h1>
        </div>

        {{-- The Form --}}
        <form id="loginForm" method="POST" action="{{ route('login.post') }}" class="p-8 space-y-6">
            @csrf

            {{-- Error Message Container (Hidden by default as requested) --}}
            <div id="error-message"
                class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{-- JS can inject errors here --}}
            </div>

            {{-- Standard Blade functionality for server-side errors --}}
            @if ($errors->any())
                <div
                    class="bg-[#ED1C24] text-white p-3 border-[3px] border-black font-mono text-sm shadow-[4px_4px_0px_0px_black]">
                    @foreach ($errors->all() as $error)
                        <p class="uppercase">ERROR: {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Input: Email --}}
            <div>
                <label for="email" class="block bg-black text-white px-2 py-1 font-bold text-sm uppercase w-fit mb-2">
                    EMAIL
                </label>
                <input type="email" id="email" name="email"
                    class="w-full border-[3px] border-black p-3 font-mono text-lg focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow placeholder-gray-500"
                    placeholder="user@telkomuniversity.ac.id" value="{{ old('email') }}" required autofocus>
            </div>

            {{-- Input: Password --}}
            <div>
                <label for="password" class="block bg-black text-white px-2 py-1 font-bold text-sm uppercase w-fit mb-2">
                    PASSWORD
                </label>
                <input type="password" id="password" name="password"
                    class="w-full border-[3px] border-black p-3 font-mono text-lg focus:outline-none focus:shadow-[4px_4px_0px_0px_#CCFF00] transition-shadow placeholder-gray-500"
                    placeholder="••••••••" required>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-black text-white border-[3px] border-black py-4 text-xl font-bold uppercase tracking-wider hover:bg-[#CCFF00] hover:text-black transition-colors duration-200">
                MASUK
            </button>

            {{-- Register Link --}}
            <div class="text-center pt-2">
                <a href="{{ route('register') }}"
                    class="font-mono text-sm underline hover:bg-[#CCFF00] hover:text-black px-1 transition-colors">
                    BELUM PUNYA AKUN? DAFTAR
                </a>
            </div>
        </form>
    </div>
@endsection
