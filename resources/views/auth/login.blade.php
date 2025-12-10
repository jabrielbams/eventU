@extends('layouts.app')

@section('title', 'Access Your Hub')

@section('content')
<div class="flex justify-center items-center py-10 w-full relative">
    
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter text-telkom-black uppercase drop-shadow-[3px_3px_0px_rgba(238,46,36,1)]">
                Welcome Back
            </h1>
            <p class="mt-2 text-lg font-medium text-gray-600">Continue your journey at TelyuEvents.</p>
        </div>

        <!-- Form Card -->
        <div class="neo-box bg-white p-8 relative overflow-hidden">
            <!-- Decorative Shape -->
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-telkom-red rounded-full border-4 border-telkom-black z-0"></div>

            <form id="loginForm" class="relative z-10 space-y-6">
                <!-- Login Error Alert -->
                <div id="loginError" class="hidden neo-box bg-telkom-red p-4 text-white font-bold mb-4">
                    Invalid credentials. Please try again.
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-lg font-bold mb-2 uppercase tracking-wide">University Email</label>
                    <input type="email" name="email" id="email" 
                        class="neo-input focus:ring-0" 
                        placeholder="student@telkomuniversity.ac.id">
                    <p id="error-email" class="hidden text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1"></p>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-lg font-bold mb-2 uppercase tracking-wide">Password</label>
                    <input type="password" name="password" id="password" 
                        class="neo-input focus:ring-0" 
                        placeholder="********">
                    <p id="error-password" class="hidden text-telkom-red font-bold text-sm mt-1 bg-black text-white px-1"></p>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="w-full neo-button text-xl tracking-widest mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
                    Access Dashboard
                </button>
                
                <p class="text-center text-sm font-medium mt-4">
                    New here? <a href="{{ route('register') }}" class="underline decoration-2 decoration-telkom-red hover:text-telkom-red">Create Account</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Check if loading
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn.disabled) return;

        // Reset UI
        document.getElementById('loginError').classList.add('hidden');
        document.querySelectorAll('[id^="error-"]').forEach(el => {
            el.classList.add('hidden');
            el.innerText = '';
        });

        // Loading State
        const originalBtnText = submitBtn.innerText;
        submitBtn.disabled = true;
        submitBtn.innerText = 'Authenticating...';

        // Gather Data
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (response.status === 200) {
                return response.json().then(data => {
                    if (data.status === 'success') {
                         window.location.href = data.redirect;
                    }
                });
            } else if (response.status === 401) {
                return response.json().then(data => {
                    const errorBox = document.getElementById('loginError');
                    errorBox.innerText = data.message || 'Invalid credentials';
                    errorBox.classList.remove('hidden');
                });
            } else if (response.status === 422) {
                return response.json().then(data => {
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
                 return response.json().then(data => {
                     alert(data.message || 'An error occurred');
                 });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network connection error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = originalBtnText;
        });
    });
</script>
@endsection
