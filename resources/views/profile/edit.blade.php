@extends('layouts.app')

@section('title', 'Identity Card')

@section('content')
<style>
    :root {
        --telkom-red: #EE2E24;
        --neo-black: #000000;
        --neo-white: #FFFFFF;
        --border-thick: 3px solid var(--neo-black);
        --anim-spring: cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* Kinetic Stack Container */
    .profile-stack {
        position: relative;
        width: 100%;
        max-width: 600px; /* Adjust as needed */
        margin: 4rem auto;
        padding: 2rem; 
    }

    /* Common Layer Styles */
    .stack-layer {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        border: var(--border-thick);
        transition: all 0.4s var(--anim-spring);
        will-change: transform;
    }

    /* Layer 1: Bottom (Black Shadow) */
    .layer-bot {
        background-color: var(--neo-black);
        z-index: 1;
        transform: translate(0, 0);
    }

    /* Layer 2: Middle (Telkom Red) */
    .layer-mid {
        background-color: var(--telkom-red);
        z-index: 2;
        transform: translate(0, 0) rotate(0deg);
    }

    /* Layer 3: Top (Content) */
    .layer-top {
        position: relative; 
        background-color: var(--neo-white);
        z-index: 3;
        border: var(--border-thick);
        transform: translate(0, 0);
        
        display: flex;
        flex-direction: column;
    }

    /* Adjusting Absolute layers to match the Relative Top Layer size */
    .profile-stack .layer-bot,
    .profile-stack .layer-mid {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }

    /* --- The Explosion Animation (Hover on Container) --- */
    .profile-stack:hover .layer-top {
        transform: translate(-8px, -8px);
    }

    .profile-stack:hover .layer-mid {
        transform: translate(8px, 0px) rotate(3deg); /* Adjusted y to 0 based on user spec? Spec: 8px Right. */
        /* User Spec: Translates 8px (Right) and rotates 3deg. */
    }

    .profile-stack:hover .layer-bot {
        transform: translate(8px, 8px); /* Spec: 8px Down and 8px Right. */
    }

    /* --- Content Styling --- */
    .profile-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem;
        background: var(--neo-white);
        border-bottom: var(--border-thick);
    }

    .avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin-bottom: 1.5rem;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: var(--border-thick);
        filter: grayscale(100%);
        transition: filter 0.3s ease;
    }

    .avatar-wrapper:hover .avatar-img {
        filter: grayscale(0%);
    }

    .upload-btn {
        position: absolute;
        bottom: -5px;
        right: -5px;
        background-color: var(--telkom-red);
        border: var(--border-thick);
        color: var(--neo-white);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .upload-btn:hover {
        transform: scale(1.1);
    }

    /* File Input hidden */
    #avatarInput {
        display: none;
    }

    .profile-form {
        padding: 2rem;
        background: var(--neo-white);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .neo-input {
        width: 100%;
        padding: 1rem;
        border: var(--border-thick);
        font-family: inherit;
        font-size: 1rem;
        background: var(--neo-white);
        outline: none;
        transition: background 0.2s;
        border-radius: 0; /* Strict Brutalism */
    }

    .neo-input:focus {
        background: #f0f0f0;
    }

    .btn-save {
        display: block;
        width: 100%;
        padding: 1rem;
        background-color: var(--neo-black);
        color: var(--neo-white);
        border: none;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        transition: transform 0.2s, background 0.2s;
        border: var(--border-thick); /* Keep border for consistency? Usually buttons are solid. */
    }

    .btn-save:hover {
        background-color: var(--telkom-red);
        color: var(--neo-black);
        transform: translate(-2px, -2px);
        box-shadow: 4px 4px 0 var(--neo-black);
    }

    .section-divider {
        margin: 2rem 0;
        border-top: var(--border-thick);
        position: relative;
    }
    
    .section-divider span {
        position: absolute;
        top: -0.8rem;
        left: 50%;
        transform: translateX(-50%);
        background: var(--neo-white);
        padding: 0 1rem;
        font-weight: bold;
        text-transform: uppercase;
    }

</style>

<div class="flex justify-center items-center min-h-screen">
    
    <!-- Kinetic Stack Component -->
    <div class="profile-stack">
        <!-- Layer 1: Bottom -->
        <div class="stack-layer layer-bot"></div>
        
        <!-- Layer 2: Middle -->
        <div class="stack-layer layer-mid"></div>
        
        <!-- Layer 3: Top (Main Content) -->
        <div class="layer-top">
            
            <!-- Avatar Section -->
            <div class="profile-header">
                <div class="avatar-wrapper">
                    <!-- Default to a placeholder if no avatar -->
                    <img id="avatarPreview" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=000000&color=ffffff&size=128" alt="User Avatar" class="avatar-img">
                    
                    <label for="avatarInput" class="upload-btn">
                        <!-- Simple Camera Icon SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="butt" stroke-linejoin="miter"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                    </label>
                    <input type="file" id="avatarInput" accept="image/*">
                </div>
                <h2 class="text-2xl font-black uppercase">{{ auth()->user()->name }}</h2>
                <p class="text-sm font-bold opacity-60">IDENTITY CARD RESTRICTED</p>
            </div>

            <!-- Form Section -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="neo-input" value="{{ old('name', auth()->user()->name) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="neo-input" value="{{ old('email', auth()->user()->email) }}" readonly style="opacity: 0.7; cursor: not-allowed;">
                </div>

                <div class="section-divider">
                    <span>Security</span>
                </div>

                <!-- Simple Password Update -->
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="neo-input" placeholder="Leave blank to keep current">
                </div>

                <button type="submit" class="btn-save">
                    Update Identity
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
