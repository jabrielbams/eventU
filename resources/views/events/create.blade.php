@extends('layouts.app')

@section('title', 'Draft Event')

@section('content')
<style>
    /* Neo-Brutalism Design System for Drafting Table */
    .drafting-table {
        border: 3px solid black;
        box-shadow: 12px 12px 0px 0px #000000;
        background-color: #ffffff;
        max-width: 900px;
        margin: 40px auto;
        padding: 0;
        position: relative;
    }

    .drafting-header {
        background-color: #000000;
        color: #ffffff;
        padding: 20px;
        font-family: 'Inter', sans-serif; /* Fallback */
        text-transform: uppercase;
        font-weight: 900;
        font-size: 2rem;
        border-bottom: 3px solid black;
        letter-spacing: 2px;
    }

    .drafting-body {
        padding: 40px;
    }

    /* Typography & Inputs */
    .neo-input {
        width: 100%;
        border: 3px solid black;
        border-radius: 0;
        padding: 15px;
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        font-size: 1.1rem;
        background: #fff;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .neo-input:focus {
        outline: none;
        background-color: #f0f0f0;
        box-shadow: 4px 4px 0px 0px #000000;
        transform: translate(-4px, -4px);
    }

    .neo-title {
        font-size: 2.5rem;
        margin-bottom: 30px;
        border-width: 4px;
    }

    .neo-label {
        font-family: 'Arial', sans-serif;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
        font-size: 0.9rem;
    }

    /* Grid Layout */
    .drafting-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .drafting-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Image Drop Zone */
    .drop-zone {
        border: 3px dashed black;
        background-color: #f8f8f8;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        margin-bottom: 30px;
        position: relative;
        transition: background-color 0.2s;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .drop-zone:hover {
        background-color: #eee;
    }

    .drop-zone-text {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 900;
        font-size: 1.5rem;
        text-transform: uppercase;
        pointer-events: none;
    }

    .drop-zone-preview {
        max-width: 100%;
        max-height: 300px;
        object-fit: contain;
        margin-top: 15px;
        border: 2px solid black;
        display: none;
    }

    /* Submit Button */
    .btn-launch {
        width: 100%;
        background-color: #000000;
        color: #ffffff;
        border: 3px solid black;
        padding: 20px;
        font-size: 1.5rem;
        font-weight: 900;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Arial Black', sans-serif;
    }

    .btn-launch:hover {
        background-color: #EE2E24; /* Telkom Red */
        color: #ffffff;
        box-shadow: 8px 8px 0px 0px #000000;
        transform: translate(-4px, -4px);
    }

    /* Sticky Note Error */
    .sticky-error {
        background-color: #CCFF00; /* Neon Yellow */
        color: #ff0000;
        padding: 10px 15px;
        font-family: 'Brush Script MT', 'Comic Sans MS', cursive; /* Handwritten feel */
        font-size: 1.2rem;
        position: absolute;
        z-index: 10;
        box-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        transform: rotate(-2deg);
        border: 1px solid #e0e0e0;
        margin-top: 5px;
        max-width: 250px;
        display: none; /* Hidden by default */
    }

    .sticky-error::after {
        content: '';
        position: absolute;
        bottom: -5px;
        right: 5px;
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-top: 10px solid #CCFF00;
        transform: rotate(3deg);
    }
    
    .input-group {
        position: relative;
        margin-bottom: 20px;
    }
</style>

<div class="drafting-table">
    <div class="drafting-header">
        PUBLISH NEW EVENT
    </div>
    
    <div class="drafting-body">
        <form id="create-event-form">
            <!-- Title -->
            <div class="input-group">
                <input type="text" name="title" class="neo-input neo-title" placeholder="ENTER EVENT TITLE..." required>
                <div class="sticky-error" id="error-title"></div>
            </div>

            <!-- Meta Grid -->
            <div class="drafting-grid">
                <!-- Category -->
                <div class="input-group">
                    <label class="neo-label">Category</label>
                    <select name="category_id" class="neo-input" required>
                        <option value="" disabled selected>SELECT TYPE</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="sticky-error" id="error-category_id"></div>
                </div>

                <!-- Date -->
                <div class="input-group">
                    <label class="neo-label">Date</label>
                    <input type="date" name="date" class="neo-input" required>
                    <div class="sticky-error" id="error-date"></div>
                </div>

                <!-- Time -->
                <div class="input-group">
                    <label class="neo-label">Time</label>
                    <input type="time" name="time" class="neo-input" required>
                    <div class="sticky-error" id="error-time"></div>
                </div>
            </div>

            <!-- Location -->
            <div class="input-group">
                <label class="neo-label">Location</label>
                <input type="text" name="location" class="neo-input" placeholder="VENUE OR LINK" required>
                <div class="sticky-error" id="error-location"></div>
            </div>

            <!-- Image Upload -->
            <div class="input-group">
                <label class="neo-label">Event Poster</label>
                <input type="file" name="image" id="image-input" class="hidden" accept="image/*" style="display:none">
                <div class="drop-zone" id="drop-zone">
                    <div class="drop-zone-text">DROP POSTER HERE</div>
                    <img id="image-preview" class="drop-zone-preview" alt="Preview">
                </div>
                <div class="sticky-error" id="error-image"></div>
            </div>

            <!-- Description -->
            <div class="input-group">
                <label class="neo-label">Manifesto (Description)</label>
                <textarea name="description" class="neo-input" rows="8" placeholder="// ENTER EVENT DETAILS..." style="resize: vertical;"></textarea>
                <div class="sticky-error" id="error-description"></div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-launch">LAUNCH EVENT</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('create-event-form');
        const dropZone = document.getElementById('drop-zone');
        const imageInput = document.getElementById('image-input');
        const imagePreview = document.getElementById('image-preview');
        const dropZoneText = document.querySelector('.drop-zone-text');

        // --- Image Upload Logic ---
        dropZone.addEventListener('click', () => imageInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.backgroundColor = '#e0e0e0';
            dropZone.style.borderStyle = 'solid';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.backgroundColor = '#f8f8f8';
            dropZone.style.borderStyle = 'dashed';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.backgroundColor = '#f8f8f8';
            dropZone.style.borderStyle = 'dashed';
            
            if (e.dataTransfer.files.length) {
                imageInput.files = e.dataTransfer.files;
                showPreview(e.dataTransfer.files[0]);
            }
        });

        imageInput.addEventListener('change', () => {
            if (imageInput.files.length) {
                showPreview(imageInput.files[0]);
            }
        });

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                dropZoneText.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        // --- Form Submission Logic ---
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Clear previous errors
            document.querySelectorAll('.sticky-error').forEach(el => {
                el.style.display = 'none';
                el.innerText = '';
            });

            const formData = new FormData(form);
            const submitBtn = document.querySelector('.btn-launch');
            const originalBtnText = submitBtn.innerText;
            
            submitBtn.disabled = true;
            submitBtn.innerText = 'LAUNCHING...';

            try {
                // Determine API endpoint - assuming relative path or explicit absolute
                const response = await fetch('/api/events', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                         // Content-Type not set for FormData
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.status === 201) {
                    alert("MISSION SUCCESS: Event Launched Successfully!");
                    window.location.href = '/dashboard';
                } else if (response.status === 403) {
                    alert("STRICT WARNING: You do not have an Organization Profile yet. Access Denied.");
                } else if (response.status === 422) {
                    // Handle Validation Errors
                    const errors = data.errors || data.message; // Laravel usually returns { message: "...", errors: { ... } }
                    
                    // Specific parsing for Laravel 422 response
                    if (data.errors) {
                         Object.entries(data.errors).forEach(([field, messages]) => {
                            const errorEl = document.getElementById(`error-${field}`);
                            if (errorEl) {
                                errorEl.innerText = messages[0];
                                errorEl.style.display = 'block';
                                
                                // Randomize rotation slightly for "sticky note" effect
                                const rotation = Math.random() * 4 - 2; // -2 to 2 deg
                                errorEl.style.transform = `rotate(${rotation}deg)`;
                            }
                        });
                    } else {
                         alert('Validation Error: ' + JSON.stringify(data));
                    }

                } else {
                    console.error("Unknown error:", data);
                    alert("SYSTEM ERROR: Failed to obtain launch clearance.");
                }
            } catch (error) {
                console.error("Network error:", error);
                alert("NETWORK FAILURE: Communication down.");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
            }
        });
    });
</script>
@endsection
