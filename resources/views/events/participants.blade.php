@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <!-- Main Container -->
    <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_#000000]">
        
        <!-- Header Bar -->
        <div class="bg-black text-white p-4 border-b-4 border-black">
            <h2 class="text-2xl font-bold font-mono tracking-widest text-center md:text-left">MISSION ROSTER</h2>
        </div>

        <div class="p-6 md:p-8">
            <!-- Event Title Section -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-5xl font-black uppercase mb-4 leading-tight">
                    PARTICIPANTS FOR: <br class="md:hidden">
                    <span id="eventName" class="text-gray-400 animate-pulse">LOADING PROFILE...</span>
                </h1>
                <div class="h-1 bg-black w-full"></div>
            </div>

            <!-- Action Bar -->
            <div class="flex flex-col md:flex-row gap-4 mb-8 items-stretch md:items-center justify-between">
                <!-- Search Input -->
                <div class="w-full md:w-1/2 relative group">
                    <input type="text" id="searchInput" placeholder="SEARCH NAME OR NIM..." 
                        class="w-full border-2 border-black p-4 font-mono text-lg placeholder-gray-500 focus:outline-none focus:bg-yellow-50 focus:shadow-[4px_4px_0px_0px_#000000] focus:-translate-y-1 focus:-translate-x-1 transition-all uppercase">
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Export Button -->
                <button id="exportBtn" onclick="exportToCSV()" class="w-full md:w-auto bg-[#FFD700] text-black border-2 border-black px-8 py-4 font-black text-xl uppercase shadow-[4px_4px_0px_0px_#000000] hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[6px_6px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-[2px_2px_0px_0px_#000000] transition-all flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    DOWNLOAD CSV
                </button>
            </div>

            <!-- Data Display Area -->
            <div id="rosterContainer" class="min-h-[300px] relative">
                
                <!-- Loading Overlay -->
                <div id="loadingState" class="absolute inset-0 bg-white bg-opacity-90 z-10 flex flex-col items-center justify-center">
                    <div class="animate-spin h-12 w-12 border-4 border-black border-t-[#EE2E24] rounded-full mb-4"></div>
                    <p class="font-mono font-bold text-xl uppercase animate-pulse">RETRIEVING INTEL...</p>
                </div>

                <!-- Desktop Table (Hidden on Mobile) -->
                <div class="hidden md:block overflow-hidden border-2 border-black">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black text-white">
                                <th class="p-4 font-mono text-sm uppercase border-r-2 border-white w-16 text-center">#</th>
                                <th class="p-4 font-mono text-sm uppercase border-r-2 border-white">Student Name</th>
                                <th class="p-4 font-mono text-sm uppercase border-r-2 border-white">NIM</th>
                                <th class="p-4 font-mono text-sm uppercase border-r-2 border-white">Email</th>
                                <th class="p-4 font-mono text-sm uppercase border-r-2 border-white">Reg. Date</th>
                                <th class="p-4 font-mono text-sm uppercase text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="font-mono text-sm">
                            <!-- Rows Injected Here -->
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards (Hidden on Desktop) -->
                <div id="mobileCards" class="md:hidden space-y-4">
                    <!-- Cards Injected Here -->
                </div>
                
                <!-- Empty State -->
                <div id="emptyState" class="hidden flex flex-col items-center justify-center py-16 text-center border-2 border-dashed border-black bg-gray-50">
                    <div class="border-4 border-black p-6 rounded-none mb-4 bg-white shadow-[4px_4px_0px_0px_#000000]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black uppercase tracking-tight">NO RECRUITS YET</h3>
                    <p class="font-mono text-gray-600 mt-2 max-w-xs">Waiting for participants to join this mission.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Neo-Brutalist Hover Effect for Desktop Rows */
    .roster-row {
        border-bottom: 2px solid black;
        transition: all 0.1s ease-in-out;
    }
    .roster-row:hover {
        background-color: #CCFF00; /* Neon Yellow */
        transform: scale(1.005);
        box-shadow: 2px 2px 0px 0px rgba(0,0,0,0.1);
        z-index: 10;
        position: relative;
        font-weight: 700;
    }
    
    .roster-row td {
        padding: 1rem;
        border-right: 2px solid black;
    }
    .roster-row td:last-child {
        border-right: none;
    }
    .roster-row:last-child {
        border-bottom: none;
    }

    /* Mobile Card Styles */
    .roster-card {
        background: white;
        border: 2px solid black;
        padding: 1.5rem;
        box-shadow: 6px 6px 0px 0px #000000;
        position: relative;
    }
    
    .roster-card-header {
        border-bottom: 2px solid black;
        padding-bottom: 0.75rem;
        margin-bottom: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<script>
    // Configuration
    const API_BASE = '/api/events'; // Adjust as needed
    // Get ID from URL path segment (assuming /events/{id}/participants)
    const pathSegments = window.location.pathname.split('/');
    // Check if the last segment is 'participants', if so, grab the one before it
    const eventId = pathSegments[pathSegments.length - 1] === 'participants' 
        ? pathSegments[pathSegments.length - 2]
        : "{{ $id ?? '' }}"; // Fallback to Blade variable if available

    let allParticipants = [];

    document.addEventListener('DOMContentLoaded', () => {
        if (!eventId) {
            alert('SYSTEM ERROR: MISSING EVENT ID');
            return;
        }
        fetchData();
        setupSearch();
    });

    async function fetchData() {
        showLoading(true);
        try {
            // Parallel Fetch: Event Details & Participants
            // If the backend API for participants includes event title, we could skip one.
            // But usually they are separate.
            
            const [eventRes, participantsRes] = await Promise.all([
                fetch(`${API_BASE}/${eventId}`),
                fetch(`${API_BASE}/${eventId}/participants`)
            ]);

            if (!eventRes.ok) throw new Error('Failed to load event details');
            if (!participantsRes.ok) throw new Error('Failed to load participants');

            const eventData = await eventRes.json();
            const participantsData = await participantsRes.json(); // Assuming returns { data: [...] } or [...]

            // Handle response format variations (Laravel Resource vs direct array)
            const participants = Array.isArray(participantsData) ? participantsData : (participantsData.data || []);
            allParticipants = participants;

            updateUI(eventData, participants);

        } catch (error) {
            console.error('Data Fetch Error:', error);
            document.getElementById('eventName').innerText = "ERROR_LOADING_DATA";
            document.getElementById('eventName').classList.add('text-red-600');
            document.getElementById('rosterContainer').innerHTML = `
                <div class="p-8 text-center text-red-600 font-mono font-bold border-2 border-black">
                    CONNECTION LOST. RETRY MISSION.
                </div>
            `;
        } finally {
            showLoading(false);
        }
    }

    function updateUI(event, participants) {
        // Update Header
        document.getElementById('eventName').innerText = event.title || "UNKNOWN EVENT";
        document.getElementById('eventName').classList.remove('text-gray-400', 'animate-pulse');
        document.getElementById('eventName').classList.add('text-black');

        renderRoster(participants);
    }

    function renderRoster(list) {
        const tableBody = document.getElementById('tableBody');
        const mobileCards = document.getElementById('mobileCards');
        const emptyState = document.getElementById('emptyState');
        const tableContainer = tableBody.parentElement.parentElement; // div wrapping table

        tableBody.innerHTML = '';
        mobileCards.innerHTML = '';

        if (list.length === 0) {
            emptyState.classList.remove('hidden');
            tableContainer.classList.add('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        tableContainer.classList.remove('hidden');

        list.forEach((p, index) => {
            // Format Date
            const dateStr = new Date(p.pivot?.created_at || p.created_at).toLocaleDateString('en-GB', {
                day: 'numeric', month: 'short', year: 'numeric'
            });

            const indexNum = index + 1;
            const name = p.name || 'Unknown Soldier';
            const nim = p.nim || 'N/A';
            const email = p.email || '-';

            // Desktop Row
            const row = `
                <tr class="roster-row">
                    <td class="font-bold text-center">${indexNum}</td>
                    <td class="font-bold">${name}</td>
                    <td class="font-mono text-gray-700">${nim}</td>
                    <td class="font-mono text-sm">${email}</td>
                    <td class="font-mono">${dateStr}</td>
                    <td class="text-center">
                        <button class="bg-gray-200 p-2 border-2 border-black hover:bg-[#EE2E24] hover:text-white transition-colors" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;

            // Mobile Card
            const card = `
                <div class="roster-card">
                    <div class="roster-card-header">
                        <span class="font-black text-xl">#${indexNum}</span>
                        <span class="font-mono text-sm bg-black text-white px-2 py-1">${dateStr}</span>
                    </div>
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs font-bold uppercase text-gray-500">Name</p>
                            <p class="text-lg font-black leading-none">${name}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                             <div>
                                <p class="text-xs font-bold uppercase text-gray-500">NIM</p>
                                <p class="font-mono text-lg">${nim}</p>
                            </div>
                        </div>
                        <div>
                             <p class="text-xs font-bold uppercase text-gray-500">Contact</p>
                             <p class="font-mono text-sm break-all">${email}</p>
                        </div>
                    </div>
                     <div class="mt-4 pt-3 border-t-2 border-dashed border-gray-300">
                        <button class="w-full border-2 border-black py-2 font-bold uppercase hover:bg-[#EE2E24] hover:text-white transition-colors flex items-center justify-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-9 6v1h18v-1a6 6 0 00-9-6z" />
                            </svg>
                            View Profile
                        </button>
                     </div>
                </div>
            `;
            mobileCards.innerHTML += card;
        });
    }

    function setupSearch() {
        const input = document.getElementById('searchInput');
        input.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const filtered = allParticipants.filter(p => {
                return (p.name && p.name.toLowerCase().includes(term)) || 
                       (p.nim && p.nim.toLowerCase().includes(term));
            });
            renderRoster(filtered);
        });
    }

    function showLoading(show) {
        const el = document.getElementById('loadingState');
        if (show) el.classList.remove('hidden');
        else el.classList.add('hidden');
    }

    function exportToCSV() {
        if (!allParticipants.length) {
            alert("NO DATA TO EXPORT");
            return;
        }
        
        // Simple CSV generation
        const headers = ["No", "Name", "NIM", "Email", "Registration Date"];
        const rows = allParticipants.map((p, i) => [
            i + 1,
            p.name,
            p.nim,
            p.email,
            new Date(p.pivot?.created_at || p.created_at).toLocaleDateString()
        ]);

        let csvContent = "data:text/csv;charset=utf-8," 
            + headers.join(",") + "\n" 
            + rows.map(e => e.join(",")).join("\n");

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `participants_event_${eventId}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endsection
