@extends('layouts.app')

@section('content')
<style>
    /* Event Grid Styles (Neo-Brutalist) */
    .events-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 2rem;
        margin-top: 2rem;
    }
    @media (min-width: 768px) {
        .events-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Neo Card */
    .neo-card {
        background: white;
        border: 4px solid black;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: all 0.2s;
        box-shadow: 6px 6px 0px black;
    }
    .neo-card:hover {
        transform: translate(-4px, -4px);
        box-shadow: 10px 10px 0px black;
    }

    .card-image-container {
        position: relative;
        width: 100%;
        aspect-ratio: 16/9;
        border-bottom: 4px solid black;
    }
    .card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .date-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: white;
        border: 3px solid black;
        padding: 0.5rem;
        text-align: center;
        min-width: 60px;
        box-shadow: 4px 4px 0px black;
    }
    .date-badge .month {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        background: black;
        color: white;
        padding: 2px 4px;
    }
    .date-badge .day {
        display: block;
        font-size: 1.5rem;
        font-weight: 900;
        line-height: 1;
        padding: 4px 0;
    }

    .card-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        flex-grow: 1;
    }
    .card-title {
        font-size: 1.5rem;
        font-weight: 900;
        line-height: 1;
        text-transform: uppercase;
    }
    .card-details {
        font-weight: 700;
        font-size: 0.9rem;
        font-family: monospace;
    }

    .card-action {
        width: 100%;
        background: #EE2E24;
        color: white;
        border: none;
        border-top: 4px solid black;
        padding: 1rem;
        font-size: 1.2rem;
        font-weight: 900;
        text-transform: uppercase;
        text-align: center;
        display: block;
        text-decoration: none;
        transition: background 0.2s;
    }
    .card-action:hover {
        background: black;
        color: white;
    }
</style>

<div class="min-h-screen bg-[#f0f0f0] p-8 font-sans flex flex-col items-center relative overflow-hidden">
    <!-- Dot Grid Background -->
    <div class="absolute inset-0 z-0 opacity-20" style="background-image: radial-gradient(#000 2px, transparent 2px); background-size: 20px 20px;"></div>

    <div class="relative z-10 w-full max-w-3xl">
        <!-- Profile Card -->
        <div id="profileCard" class="bg-white border-4 border-black shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] flex flex-col md:flex-row overflow-hidden min-h-[400px] hidden">
            
            <!-- Logo Section -->
            <div class="w-full md:w-1/3 bg-black p-8 flex items-center justify-center border-b-4 md:border-b-0 md:border-r-4 border-black">
                <div class="w-48 h-48 bg-white border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center relative">
                    <img id="orgLogo" src="" alt="Organization Logo" class="w-full h-full object-contain p-2">
                    <div id="noLogo" class="hidden absolute text-4xl font-black">?</div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="w-full md:w-2/3 p-8 flex flex-col">
                <!-- Name -->
                <h1 id="orgName" class="text-5xl font-black uppercase tracking-tighter mb-6 bg-red-600 text-white inline-block px-4 py-2 self-start border-4 border-black transform -rotate-1 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    Loading...
                </h1>

                <!-- Dossier Details -->
                <div class="flex-grow space-y-4 font-mono text-lg border-t-4 border-black pt-6 mt-2 relative">
                    <!-- Decor: Typewriter Label -->
                    <span class="absolute -top-4 left-0 bg-[#f0f0f0] px-2 text-xs font-bold border-2 border-black">DESCRIPTION</span>
                    
                    <p id="orgDescription" class="whitespace-pre-wrap leading-relaxed">
                        Fetching data...
                    </p>
                </div>

                <!-- Contact Details -->
                <div class="space-y-2 font-bold font-mono text-sm border-t-4 border-black pt-4 mt-6 relative">
                    <span class="absolute -top-3 right-0 bg-[#f0f0f0] px-2 text-xs font-bold border-2 border-black">CONTACT</span>
                    
                    <div class="flex items-center gap-2">
                        <span class="uppercase">Email:</span>
                        <span id="orgEmail" class="font-normal"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="uppercase">Phone:</span>
                        <span id="orgPhone" class="font-normal"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="uppercase">Addr:</span>
                        <span id="orgAddress" class="font-normal"></span>
                    </div>
                </div>

                <!-- Footer / ID -->
                <div class="mt-8 pt-4 border-t-2 border-dashed border-gray-400 text-xs font-mono text-gray-500 flex justify-between">
                    <span>ID: <span id="orgId">#000</span></span>
                    <span>STATUS: ACTIVE</span>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading" class="text-4xl font-black text-center animate-pulse">
            LOADING DATA...
        </div>

        <!-- Events Section -->
        <div id="eventsSection" class="hidden mt-12 w-full">
            <hr class="border-t-4 border-black mb-8">
            <h2 class="text-3xl font-black uppercase mb-8">
                EVENTS BY <span id="orgNameEvents" class="text-red-600">...</span>
            </h2>

            <div id="eventsGrid" class="events-grid">
                <!-- Events will be rendered here -->
            </div>
            
            <div id="noEvents" class="hidden text-center border-4 border-black p-8 bg-white shadow-[8px_8px_0px_0px_black] font-black text-2xl uppercase">
                NO ACTIVE EVENTS
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        // Get ID from URL or Blade injection. Using Blade injection for ID variable since the route passes it.
        const orgId = "{{ $id }}"; 
        const loading = document.getElementById('loading');
        const profileCard = document.getElementById('profileCard');

        try {
            const response = await fetch(`/api/organizations/${orgId}`);
            
            if (!response.ok) {
                throw new Error('Failed to fetch profile');
            }

            const data = await response.json();

            // Populate Data
            document.getElementById('orgName').textContent = data.name;
            document.getElementById('orgDescription').textContent = data.description;
            document.getElementById('orgId').textContent = '#' + data.id.toString().padStart(4, '0');
            document.getElementById('orgEmail').textContent = data.email;
            document.getElementById('orgPhone').textContent = data.phone;
            document.getElementById('orgAddress').textContent = data.address;

            // Handle Logo
            const logoImg = document.getElementById('orgLogo');
            if (data.logo_url || data.logo) {
                logoImg.src = data.logo_url || `/storage/${data.logo.replace('public/', '')}`;

            } else {
                document.getElementById('noLogo').classList.remove('hidden');
            }

            // --- Render Events ---
            document.getElementById('orgNameEvents').textContent = data.name;
            const eventsSection = document.getElementById('eventsSection');
            const eventsGrid = document.getElementById('eventsGrid');
            const noEvents = document.getElementById('noEvents');

            eventsSection.classList.remove('hidden');
            eventsGrid.innerHTML = '';

            // Helper function for image logic
            const getEventImage = (event) => {
                // Priority 1: Backend prepared image_url (now reliable)
                if (event.image_url) {
                    return event.image_url;
                }
                // Priority 2: Raw path (rarely needed if backend is good, but safety net)
                if (event.image_path) {
                    return '/storage/' + event.image_path.replace(/^public\//, '');
                }
                // Priority 3: Banner field if leaked
                if (event.banner) {
                    if(event.banner.startsWith('http')) return event.banner;
                    return '/storage/' + event.banner.replace(/^public\//, '');
                }
                
                // Fallback
                return 'https://placehold.co/600x400?text=No+Image';
            };

            if (data.events && data.events.length > 0) {
                data.events.forEach(event => {
                    const dateObj = new Date(event.event_date || event.created_at);
                    const day = dateObj.getDate();
                    const month = dateObj.toLocaleString('default', { month: 'short' }).toUpperCase();
                    
                    const eventHtml = `
                        <div class="neo-card">
                            <div class="card-image-container">
                                <img src="${getEventImage(event)}" class="card-image" alt="${event.title}">
                                <div class="date-badge">
                                    <span class="month">${month}</span>
                                    <span class="day">${day}</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-title">${event.title}</div>
                                <div class="card-details">
                                    <div>📍 ${event.location || 'TBA'}</div>
                                    <div>🕒 ${event.event_time || 'TBA'}</div>
                                </div>
                            </div>
                            <a href="/events/${event.id}" class="card-action">
                                View Details
                            </a>
                        </div>
                    `;
                    eventsGrid.innerHTML += eventHtml;
                });
            } else {
                noEvents.classList.remove('hidden');
            }

            // Show Card
            loading.classList.add('hidden');
            profileCard.classList.remove('hidden');

        } catch (error) {
            console.error(error);
            loading.textContent = "ERROR: PROFILE NOT FOUND";
            loading.classList.add('text-red-600');
        }
    });
</script>
@endsection
