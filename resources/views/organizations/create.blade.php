@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f0f0f0] p-8 font-sans flex items-center justify-center relative overflow-hidden">
    <!-- Dot Grid Background -->
    <div class="absolute inset-0 z-0 opacity-20" style="background-image: radial-gradient(#000 2px, transparent 2px); background-size: 20px 20px;"></div>

    <div class="relative z-10 w-full max-w-2xl">
        <!-- Card Container -->
        <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8 relative">
            
            <!-- Header -->
            <h1 class="text-4xl font-black uppercase mb-8 tracking-tighter border-b-4 border-black pb-4">
                Create Organization Profile
            </h1>

            <!-- Form -->
            <form id="createOrgForm" class="space-y-6">
                <!-- Name Input -->
                <div class="space-y-2">
                    <label for="name" class="block font-bold text-xl uppercase">Organization Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full bg-white border-4 border-black p-4 text-lg font-bold focus:outline-none focus:translate-x-1 focus:translate-y-1 focus:shadow-none transition-all placeholder-gray-500 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                </div>
                
                <!-- Email Input -->
                <div class="space-y-2">
                    <label for="email" class="block font-bold text-xl uppercase">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full bg-white border-4 border-black p-4 text-lg font-bold focus:outline-none focus:translate-x-1 focus:translate-y-1 focus:shadow-none transition-all placeholder-gray-500 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                </div>
                
                <!-- Phone Input -->
                <div class="space-y-2">
                    <label for="phone" class="block font-bold text-xl uppercase">Phone</label>
                    <input type="text" id="phone" name="phone" required
                        class="w-full bg-white border-4 border-black p-4 text-lg font-bold focus:outline-none focus:translate-x-1 focus:translate-y-1 focus:shadow-none transition-all placeholder-gray-500 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                </div>
                
                <!-- Address Input -->
                <div class="space-y-2">
                    <label for="address" class="block font-bold text-xl uppercase">Address</label>
                    <input type="text" id="address" name="address" required
                        class="w-full bg-white border-4 border-black p-4 text-lg font-bold focus:outline-none focus:translate-x-1 focus:translate-y-1 focus:shadow-none transition-all placeholder-gray-500 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                </div>
                
                <!-- Description Input -->
                <div class="space-y-2">
                    <label for="description" class="block font-bold text-xl uppercase">Description</label>
                    <textarea id="description" name="description" rows="4" required
                        class="w-full bg-white border-4 border-black p-4 text-lg font-mono focus:outline-none focus:translate-x-1 focus:translate-y-1 focus:shadow-none transition-all placeholder-gray-500 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]"></textarea>
                </div>

                <!-- Logo Upload -->
                <div class="space-y-2">
                    <label class="block font-bold text-xl uppercase">Upload Logo</label>
                    <div class="relative group cursor-pointer">
                        <input type="file" id="logo" name="logo" required accept=".jpg,.jpeg,.png"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                        <div class="w-full border-4 border-dashed border-black bg-gray-50 p-8 text-center group-hover:bg-red-50 transition-colors">
                            <span class="font-black text-2xl uppercase text-gray-400 group-hover:text-red-600 block mb-2">
                                Drop Image Here
                            </span>
                            <span class="font-mono text-sm block" id="fileName">or click to browse</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-red-600 text-white font-black text-2xl uppercase py-4 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:translate-x-1 hover:translate-y-1 hover:shadow-none active:bg-red-700 transition-all">
                    Create Profile
                </button>
            </form>
        
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('createOrgForm');
        const logoInput = document.getElementById('logo');
        const fileNameDisplay = document.getElementById('fileName');

        // File Input Style Update
        logoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileNameDisplay.textContent = e.target.files[0].name;
                fileNameDisplay.classList.add('font-bold', 'text-black');
            } else {
                fileNameDisplay.textContent = 'or click to browse';
                fileNameDisplay.classList.remove('font-bold', 'text-black');
            }
        });

        // Form Submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // 1. Construct FormData
            const formData = new FormData(form);

            // 2. Get CSRF Token
            // Assumes standard Laravel meta tag or simple cookie read is usually needed if not using auth headers directly.
            // But strict requirement: "Add the X-CSRF-TOKEN header"
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch('/api/organizations', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        // 'Content-Type': 'multipart/form-data', // DO NOT SET THIS! Browser sets it with boundary.
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    alert('Success! Organization created.');
                    // Redirect to the new profile or dashboard
                    window.location.href = `/organizations/${data.data.id}`;
                } else {
                    const error = await response.json();
                    alert('Error: ' + (error.message || 'Validation failed'));
                    console.error('Error details:', error);
                }
            } catch (err) {
                console.error('Network Error:', err);
                alert('Network error occurred.');
            }
        });
    });
</script>
@endsection
