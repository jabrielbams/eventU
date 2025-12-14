@extends('layouts.app')

@section('title', 'Update Profile')

@section('content')
<div class="flex justify-center items-center min-h-screen py-12">

    <!-- Kinetic Stack Component -->
    <div class="relative w-full max-w-2xl mx-auto px-4 group">
        <!-- Layer 1: Bottom (Black Shadow) -->
        <div class="absolute w-full h-full top-0 left-0 bg-telkom-black border-[3px] border-telkom-black transition-all duration-500 ease-out group-hover:translate-x-2 group-hover:translate-y-2"></div>

        <!-- Layer 2: Middle (Telkom Red) -->
        <div class="absolute w-full h-full top-0 left-0 bg-telkom-red border-[3px] border-telkom-black transition-all duration-500 ease-out group-hover:translate-x-2 group-hover:rotate-2"></div>

        <!-- Layer 3: Top (Main Content) -->
        <div class="relative bg-telkom-white border-[3px] border-telkom-black transition-all duration-500 ease-out group-hover:-translate-x-2 group-hover:-translate-y-2">

            <!-- Avatar Section -->
            <div class="flex flex-col items-center p-8 bg-telkom-white border-b-[3px] border-telkom-black">
                <div class="relative w-32 h-32 mb-6">
                    <img id="avatarPreview"
                         src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=000000&color=ffffff&size=128"
                         alt="User Avatar"
                         class="w-full h-full object-cover border-[3px] border-telkom-black grayscale hover:grayscale-0 transition-all duration-300">

                    <label for="avatarInput" class="absolute -bottom-1 -right-1 bg-telkom-red border-[3px] border-telkom-black text-telkom-white w-10 h-10 flex items-center justify-center cursor-pointer hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="butt" stroke-linejoin="miter">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                    </label>
                    <input type="file" id="avatarInput" accept="image/*" class="hidden">
                </div>
                <h2 class="text-2xl font-black uppercase">{{ auth()->user()->name }}</h2>
                <p class="text-sm font-bold opacity-60">Profile Kamu</p>
            </div>

            <!-- Form Section -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 bg-telkom-white">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block font-extrabold uppercase mb-2 text-sm">Nama Lengkap</label>
                    <input type="text"
                           name="name"
                           class="w-full p-4 border-[3px] border-telkom-black font-medium text-base bg-telkom-white outline-none focus:bg-gray-100 transition-colors"
                           value="{{ old('name', auth()->user()->name) }}">
                </div>

                <div class="mb-6">
                    <label class="block font-extrabold uppercase mb-2 text-sm">Alamat Email</label>
                    <input type="email"
                           name="email"
                           class="w-full p-4 border-[3px] border-telkom-black font-medium text-base bg-telkom-white outline-none opacity-70 cursor-not-allowed"
                           value="{{ old('email', auth()->user()->email) }}"
                           readonly>
                </div>

                @if(auth()->user()->role === 'organizer')
                <div class="my-8 border-t-[3px] border-telkom-black relative">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-telkom-white px-4 font-bold uppercase text-sm">Organization</span>
                </div>

                <div class="mb-6">
                    <label class="block font-extrabold uppercase mb-2 text-sm">Gabung Organisasi</label>
                    @if(auth()->user()->organization_id)
                        @php
                            $userOrg = auth()->user()->organizations()->wherePivot('organization_id', auth()->user()->organization_id)->first();
                            $status = $userOrg ? $userOrg->pivot->status : null;
                        @endphp
                        @if($status === 'approved')
                            <div class="w-full p-4 border-[3px] border-green-600 bg-green-100 text-green-800 font-bold">
                                ✓ Approved - {{ \App\Models\Organization::find(auth()->user()->organization_id)->name }}
                            </div>
                        @elseif($status === 'pending')
                            <div class="w-full p-4 border-[3px] border-yellow-500 bg-yellow-100 text-yellow-800 font-bold">
                                ⏳ Pending Approval - {{ \App\Models\Organization::find(auth()->user()->organization_id)->name }}
                            </div>
                        @elseif($status === 'rejected')
                            <div class="w-full p-4 border-[3px] border-red-600 bg-red-100 text-red-800 font-bold mb-2">
                                ✗ Rejected - {{ \App\Models\Organization::find(auth()->user()->organization_id)->name }}
                            </div>
                            <input type="number"
                                   name="organization_id"
                                   class="w-full p-4 border-[3px] border-telkom-black font-medium text-base bg-telkom-white outline-none focus:bg-gray-100 transition-colors"
                                   placeholder="Masukkan ID Organisasi untuk mencoba bergabung kembali">
                        @endif
                    @else
                        <input type="number"
                               name="organization_id"
                               class="w-full p-4 border-[3px] border-telkom-black font-medium text-base bg-telkom-white outline-none focus:bg-gray-100 transition-colors"
                               placeholder="Masukkan ID Organisasi untuk mencoba bergabung"
                               value="{{ old('organization_id') }}">
                        <small class="block mt-2 text-sm opacity-70">Masukkan ID Organisasi</small>
                    @endif
                </div>
                @endif

                <div class="my-8 border-t-[3px] border-telkom-black relative">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-telkom-white px-4 font-bold uppercase text-sm">Security</span>
                </div>

                <div class="mb-6">
                    <label class="block font-extrabold uppercase mb-2 text-sm">Kata Sandi Baru</label>
                    <input type="password"
                           name="password"
                           class="w-full p-4 border-[3px] border-telkom-black font-medium text-base bg-telkom-white outline-none focus:bg-gray-100 transition-colors"
                           placeholder="Kosongkan jika tidak ingin mengubah kata sandi">
                </div>

                <button type="submit" class="w-full p-4 bg-telkom-black text-telkom-white font-black uppercase tracking-widest border-[3px] border-telkom-black hover:bg-telkom-red hover:text-telkom-black hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#1A1A1A] transition-all">
                    Update Profile
                </button>
            </form>
        </div>
    </div>

</div>

<!-- Image Preview Script -->
<script>
    document.getElementById('avatarInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
