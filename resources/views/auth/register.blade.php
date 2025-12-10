@extends('layouts.app')

@section('title', 'Join the Movement')

@section('content')
<div class="flex justify-center items-center py-10 w-full">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter text-telkom-black uppercase drop-shadow-[3px_3px_0px_rgba(238,46,36,1)]">
                Create Account
            </h1>
            <p class="mt-2 text-lg font-medium text-gray-600">Start your journey at Telkom University.</p>
        </div>

        <!-- Form Card -->
        <div class="neo-box bg-white p-8 relative overflow-hidden">
            <!-- Decorative Shape -->
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-telkom-red rounded-full border-4 border-telkom-black z-0"></div>

            <form action="{{ route('register.post') }}" method="POST" class="relative z-10 space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-lg font-bold mb-2 uppercase tracking-wide">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                        class="neo-input focus:ring-0 @error('name') border-red-500 @enderror" 
                        placeholder="John Doe">
                    @error('name')
                        <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white inline-block px-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-lg font-bold mb-2 uppercase tracking-wide">University Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                        class="neo-input focus:ring-0 @error('email') border-red-500 @enderror" 
                        placeholder="student@telkomuniversity.ac.id">
                    @error('email')
                         <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white inline-block px-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-lg font-bold mb-2 uppercase tracking-wide">I am a...</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="student" class="peer sr-only" {{ old('role') == 'student' ? 'checked' : '' }}>
                            <div class="neo-box p-3 text-center hover:bg-gray-100 peer-checked:bg-telkom-black peer-checked:text-white transition-all">
                                Student
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="organizer" class="peer sr-only" {{ old('role') == 'organizer' ? 'checked' : '' }}>
                            <div class="neo-box p-3 text-center hover:bg-gray-100 peer-checked:bg-telkom-black peer-checked:text-white transition-all">
                                Organizer
                            </div>
                        </label>
                    </div>
                    @error('role')
                         <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white inline-block px-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-lg font-bold mb-2 uppercase tracking-wide">Password</label>
                    <input type="password" name="password" id="password" 
                        class="neo-input focus:ring-0" 
                        placeholder="********">
                    @error('password')
                         <p class="text-telkom-red font-bold text-sm mt-1 bg-black text-white inline-block px-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-lg font-bold mb-2 uppercase tracking-wide">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                        class="neo-input focus:ring-0" 
                        placeholder="********">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full neo-button text-xl tracking-widest mt-4">
                    Register Access
                </button>
                
                <p class="text-center text-sm font-medium mt-4">
                    Already have an account? <a href="#" class="underline decoration-2 decoration-telkom-red hover:text-telkom-red">Login Here</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
