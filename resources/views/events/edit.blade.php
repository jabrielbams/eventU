@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="drafting-table max-w-4xl mx-auto bg-white p-8 relative">
        <!-- Header -->
        <h1 class="text-5xl font-black uppercase mb-8 border-b-4 border-black pb-4 tracking-tighter">
            Edit Event Manifesto
        </h1>

        <form id="editEventForm" class="space-y-6" onsubmit="handleUpdate(event)">
            @csrf
            
            <!-- Row 1: Title -->
            <div class="form-group relative">
                <label for="title" class="block font-mono font-bold mb-1 uppercase text-sm">Event Title_</label>
                <input type="text" id="title" name="title" 
                    class="w-full border-2 border-black p-4 font-mono text-xl focus:outline-none focus:bg-yellow-50 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all placeholder-gray-400"
                    placeholder="ENTER_EVENT_TITLE_v2.0" required>
            </div>

            <!-- Row 2: Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Category -->
                <div class="form-group relative">
                    <label for="category_id" class="block font-mono font-bold mb-1 uppercase text-sm">Cat_Select</label>
                    <select id="category_id" name="category_id" 
                        class="w-full border-2 border-black p-4 font-mono appearance-none bg-white focus:outline-none focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all">
                        <option value="" disabled>SELECT_CATEGORY</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ strtoupper($category->name) }}</option>
                        @endforeach
                    </select>
                    <!-- Custom Arrow -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-blackTop-10">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <!-- Date -->
                <div class="form-group relative">
                    <label for="date" class="block font-mono font-bold mb-1 uppercase text-sm">Launch_Date</label>
                    <input type="date" id="date" name="date" 
                        class="w-full border-2 border-black p-4 font-mono focus:outline-none focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all" required>
                </div>

                <!-- Time -->
                <div class="form-group relative">
                    <label for="time" class="block font-mono font-bold mb-1 uppercase text-sm">T-Minus</label>
                    <input type="time" id="time" name="time" 
                        class="w-full border-2 border-black p-4 font-mono focus:outline-none focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all" required>
                </div>
            </div>

            <!-- Row 3: Location -->
            <div class="form-group relative">
                <label for="location" class="block font-mono font-bold mb-1 uppercase text-sm">Coordinates / Venue</label>
                <input type="text" id="location" name="location" 
                    class="w-full border-2 border-black p-4 font-mono focus:outline-none focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all"
                    placeholder="LOCATION_DATA_INPUT" required>
            </div>

            <!-- Row 4: Description -->
            <div class="form-group relative">
                <label for="description" class="block font-mono font-bold mb-1 uppercase text-sm">Mission_Brief</label>
                <textarea id="description" name="description" rows="8"
                    class="w-full border-2 border-black p-4 font-mono focus:outline-none focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSA0MCAwIEwgMCAwIDAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iI2UzZTNlMyIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIiAvPjwvc3ZnPg==')] resize-y"
                    placeholder="DESCRIPTION_SEQUENCE..." required></textarea>
            </div>

            <!-- Smart Image Drop Zone -->
            <div class="form-group relative">
                <label class="block font-mono font-bold mb-1 uppercase text-sm">Visual_Schematic</label>
                <div id="dropZone" 
                    class="relative border-4 border-dashed border-black h-64 flex items-center justify-center cursor-pointer overflow-hidden transition-all hover:bg-gray-50 group">
                    
                    <input type="file" id="image" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(this)">
                    
                    <!-- Default Content -->
                    <div id="dropZoneContent" class="text-center z-10 pointer-events-none p-4 bg-white/80 backdrop-blur-sm border-2 border-black transform transition-transform group-hover:scale-105">
                        <p class="font-mono font-bold uppercase text-lg">DROP NEW FILE OR CLICK</p>
                        <p class="text-sm font-mono mt-2 text-gray-600">[supports .jpg, .png]</p>
                    </div>

                    <!-- Background Image (Dynamic) -->
                    <div id="activeImageBg" class="absolute inset-0 bg-cover bg-center opacity-50 z-0 hidden"></div>
                    
                    <!-- Active Poster Label -->
                    <div id="activePosterLabel" class="absolute top-4 left-4 bg-black text-white px-3 py-1 font-mono text-xs z-10 hidden">
                        CURRENT POSTER ACTIVE
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row gap-4 pt-6 border-t-4 border-black border-dashed">
                <button type="submit" 
                    class="flex-1 bg-black text-white font-black uppercase py-4 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-red-600 hover:border-red-600 transition-colors text-xl tracking-wider">
                    Save Changes
                </button>
                <a href="{{ route('events.show', $id) }}" 
                    class="flex-1 text-center bg-white text-black font-black uppercase py-4 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-yellow-400 transition-colors text-xl tracking-wider">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .drafting-table {
        border: 3px solid black;
        box-shadow: 12px 12px 0px 0px #000000;
    }
    .sticky-note {
        font-family: 'Permanent Marker', cursive, sans-serif; /* Fallback to cursive if not available */
        transform: rotate(-2deg);
        box-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        z-index: 50;
        animation: stick 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    @keyframes stick {
        from { transform: scale(0) rotate(15deg); opacity: 0; }
        to { transform: scale(1) rotate(-2deg); opacity: 1; }
    }
</style>

<script>
    const eventId = "{{ $id }}";
    
    // Load existing data
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const response = await fetch(`/api/events/${eventId}`);
            if (!response.ok) throw new Error('Failed to fetch event data');
            
            const event = await response.json();
            populateForm(event);
        } catch (error) {
            console.error('Error loading event:', error);
            // Optional: Show error sticky note globally
            showGlobalError("CONNECTION_FAILURE: UNABLE TO RETRIEVE MANIFESTO");
        }
    });

    function populateForm(event) {
        document.getElementById('title').value = event.title;
        document.getElementById('category_id').value = event.category_id;
        document.getElementById('date').value = event.date;
        document.getElementById('time').value = event.time; // Ensure format match (HH:MM:SS or HH:MM)
        document.getElementById('location').value = event.location;
        document.getElementById('description').value = event.description;

        if (event.image) {
            const dropZone = document.getElementById('dropZone');
            const bgDiv = document.getElementById('activeImageBg');
            const label = document.getElementById('activePosterLabel');
            const content = document.getElementById('dropZoneContent');
            
            // Adjust path if needed, assuming /storage/ or full URL
            // If API returns relative path 'images/foo.jpg', prepend asset url logic or just try
            // Assuming API returns a usable URL or relative string.
            // Let's assume standard Laravel storage link for now.
             
            // Note: Since this is JS, we might use the raw string. 
            // If the backend returns "storage/images/..." 
            const imageUrl = event.image.startsWith('http') ? event.image : `/${event.image}`;

            bgDiv.style.backgroundImage = `url('${imageUrl}')`;
            bgDiv.classList.remove('hidden');
            label.classList.remove('hidden');
            content.querySelector('p:first-child').innerText = "DROP NEW FILE TO REPLACE";
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const bgDiv = document.getElementById('activeImageBg');
                const label = document.getElementById('activePosterLabel');
                const content = document.getElementById('dropZoneContent');
                
                bgDiv.style.backgroundImage = `url('${e.target.result}')`;
                bgDiv.classList.remove('hidden');
                label.innerText = "NEW SCHEMATIC DETECTED";
                label.classList.remove('hidden');
                content.querySelector('p:first-child').innerText = "FILE STAGED FOR UPLOAD";
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    async function handleUpdate(e) {
        e.preventDefault();
        clearErrors();
        
        const form = document.getElementById('editEventForm');
        const formData = new FormData(form);
        formData.append('_method', 'PUT'); // Method spoofing

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerText;
        submitBtn.disabled = true;
        submitBtn.innerText = "OVERWRITING...";

        try {
            const response = await fetch(`/api/events/${eventId}`, {
                method: 'POST', // POST required for FormData/File upload in Laravel with spoofing
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (response.ok) {
                window.location.href = `/events/${eventId}`;
            } else if (response.status === 422) {
                const data = await response.json();
                showValidationErrors(data.errors);
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
            } else {
                throw new Error('Update failed');
            }
        } catch (error) {
            console.error('Update error:', error);
            showGlobalError("SYSTEM_ERROR: UPDATE SEQUENCE FAILED");
            submitBtn.disabled = false;
            submitBtn.innerText = originalBtnText;
        }
    }

    function showValidationErrors(errors) {
        for (const [field, messages] of Object.entries(errors)) {
            const input = document.getElementById(field);
            if (input) {
                // Create Sticky Note
                const note = document.createElement('div');
                note.className = 'sticky-note absolute -top-4 -right-2 bg-yellow-300 text-black p-2 w-48 border border-yellow-600 shadow-lg';
                note.innerHTML = `
                    <p class="font-bold text-xs uppercase underline">Error_Log:</p>
                    <p class="text-sm font-handwriting leading-tight mt-1">${messages[0]}</p>
                    <div class="h-2 w-2 bg-yellow-400 absolute top-1 left-1/2 -mt-1 transform -rotate-45"></div> 
                `; // 'font-handwriting' should be defined or fallback to sticky note font family
                
                // Ensure parent is relative
                input.parentElement.appendChild(note);
                
                // Highlight input
                input.classList.add('border-red-500', 'bg-red-50');
            }
        }
    }

    function clearErrors() {
        document.querySelectorAll('.sticky-note').forEach(el => el.remove());
        document.querySelectorAll('input, select, textarea').forEach(el => {
            el.classList.remove('border-red-500', 'bg-red-50');
        });
    }

    function showGlobalError(msg) {
        alert(msg); // Fallback for active development
    }
</script>
@endsection
