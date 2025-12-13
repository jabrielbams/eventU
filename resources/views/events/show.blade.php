@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <button onclick="history.back()" class="mb-8 inline-block bg-white text-black font-bold py-2 px-6 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-black hover:text-white hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
        &larr; BACK TO EVENTS
    </button>

    <div id="loading" class="text-center py-12">
        <p class="text-4xl font-black animate-pulse">LOADING DETAILS...</p>
    </div>

    <div id="event-detail" class="hidden max-w-4xl mx-auto bg-white border-4 border-black shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
        <!-- Poster Image -->
        <div class="w-full h-64 md:h-96 overflow-hidden border-b-4 border-black bg-gray-200">
            <img id="detail-image" src="" alt="" class="w-full h-full object-cover">
        </div>

        <div class="p-8 md:p-12">
            <!-- Title -->
            <h1 id="detail-title" class="text-4xl md:text-6xl font-black mb-6 uppercase leading-none" style="text-shadow: 2px 2px 0px #EE2E24;">
                
            </h1>

            <div class="flex flex-wrap gap-4 mb-8">
                 <span id="detail-category" class="bg-[#EE2E24] text-white font-bold px-4 py-2 border-2 border-black text-lg">
                    
                </span>
                <span id="detail-date" class="bg-black text-white font-bold px-4 py-2 border-2 border-black text-lg">
                    
                </span>
                 <span id="detail-organizer" class="bg-white text-black font-bold px-4 py-2 border-2 border-black text-lg">
                   
                </span>
            </div>

            <!-- Description Box -->
            <div class="border-4 border-black p-6 bg-gray-50 relative">
                <div class="absolute -top-3 left-4 bg-white px-2 font-bold border-2 border-black text-sm">DESCRIPTION</div>
                <p id="detail-description" class="text-lg md:text-xl font-mono leading-relaxed text-justify">
                    
                </p>
            </div>
            
            <div class="mt-8 text-center md:text-left">
                <button class="bg-[#EE2E24] text-white font-black text-2xl py-4 px-12 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:scale-105 hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all">
                    REGISTER NOW
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Get ID from URL (or passed via Blade if we wanted, but let's parse URL for pure client-side feel if served via standard route)
        // Since we are using standard Laravel routing /events/{id}, we can pass ID via Blade variable.
        const eventId = {{ $id ?? 'null' }}; 
        
        if (!eventId) {
            alert('Event ID not found');
            return;
        }

        const loading = document.getElementById('loading');
        const detailContainer = document.getElementById('event-detail');

        fetch(`/api/events/${eventId}`)
            .then(res => res.json())
            .then(data => {
                loading.classList.add('hidden');
                detailContainer.classList.remove('hidden');

                document.getElementById('detail-title').innerText = data.title;
                document.getElementById('detail-image').src = data.image_url;
                document.getElementById('detail-image').alt = data.title;
                document.getElementById('detail-description').innerText = data.description;
                document.getElementById('detail-category').innerText = data.category;
                document.getElementById('detail-organizer').innerText = 'ORG: ' + data.organizer;

                const date = new Date(data.date);
                document.getElementById('detail-date').innerText = date.toLocaleDateString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' 
                });
            })
            .catch(err => {
                console.error(err);
                loading.innerText = "FAILED TO LOAD EVENT.";
            });
    });
</script>
@endsection
