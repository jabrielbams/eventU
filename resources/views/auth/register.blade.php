@extends('layouts.app')

@section('title', 'Join the Movement')

@section('content')
<div class="flex justify-center items-center py-10 w-full relative">
    
    <!-- Success Modal (Hidden by default) -->
    <div id="successModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="neo-box bg-green-400 p-8 max-w-sm w-full relative animate-bounce-in">
            <h2 class="text-3xl font-black uppercase text-telkom-black mb-4">Success!</h2>
            <p class="font-bold text-lg mb-6">Account created successfully.</p>
            <button onclick="window.location.reload()" class="neo-button w-full bg-white text-telkom-black hover:bg-gray-100">
                Continue
            </button>
        </div>
    </div>

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

            <form id="registerForm" class="relative z-10 space-y-6">
                
                <!-- Name -->
                <div>
                    <label for="name" class="block text-lg font-bold mb-2 uppercase tracking-wide">Full Name</label>
                    <input type="text" name="name" id="name" 
                        class="neo-input focus:ring-0" 
                        placeholder="John Doe">
                    <p id="error-name" class="hidden text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1"></p>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-lg font-bold mb-2 uppercase tracking-wide">University Email</label>
                    <input type="email" name="email" id="email" 
                        class="neo-input focus:ring-0" 
                        placeholder="student@telkomuniversity.ac.id">
                    <p id="error-email" class="hidden text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1"></p>
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-lg font-bold mb-2 uppercase tracking-wide">I am a...</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="student" class="peer sr-only" checked>
                            <div class="neo-box p-3 text-center hover:bg-gray-100 peer-checked:bg-telkom-black peer-checked:text-white transition-all">
                                Student
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="organizer" class="peer sr-only">
                            <div class="neo-box p-3 text-center hover:bg-gray-100 peer-checked:bg-telkom-black peer-checked:text-white transition-all">
                                Organizer
                            </div>
                        </label>
                    </div>
                    <p id="error-role" class="hidden text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1"></p>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-lg font-bold mb-2 uppercase tracking-wide">Password</label>
                    <input type="password" name="password" id="password" 
                        class="neo-input focus:ring-0" 
                        placeholder="Password">
                    <p id="error-password" class="hidden text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1"></p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-lg font-bold mb-2 uppercase tracking-wide">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                        class="neo-input focus:ring-0" 
                        placeholder="Password">
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="w-full neo-button text-xl tracking-widest mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
                    Register Access
                </button>
                
                <p class="text-center text-sm font-medium mt-4">
                    Already have an account? <a href="{{ route('login') }}" class="underline decoration-2 decoration-telkom-red hover:text-telkom-red">Login Here</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('[id^="error-"]').forEach(el => {
            el.classList.add('hidden');
            el.innerText = '';
        });

        // UI Loading State
        const submitBtn = document.getElementById('submitBtn');
        const originalBtnText = submitBtn.innerText;
        submitBtn.disabled = true;
        submitBtn.innerText = 'Processing...';

        // Gather Data
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (response.status === 201) {
                return response.json().then(data => {
                    // Success State
                    document.getElementById('successModal').classList.remove('hidden');
                });
            } else if (response.status === 422) {
                return response.json().then(data => {
                    // Validation Errors
                    Object.keys(data.errors).forEach(field => {
                        const errorEl = document.getElementById(`error-${field}`);
                        if (errorEl) {
                            errorEl.innerText = data.errors[field][0];
                            errorEl.classList.remove('hidden', 'inline-block');
                            errorEl.classList.add('inline-block');
                        }
                    });
                });
            } else {
                // General Error
                alert('Something went wrong. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        })
        .finally(() => {
            // Reset Button
            submitBtn.disabled = false;
            submitBtn.innerText = originalBtnText;
        });
    });
</script>
@endsection
