@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-black mb-8 border-b-4 border-black inline-block">TelU Events</h1>

    <!-- Filter Section -->
    <div class="flex flex-wrap gap-4 mb-8" id="filter-container">
        <button class="filter-btn active bg-[#EE2E24] text-white font-bold py-2 px-6 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all" data-category="All">
            All
        </button>
        <button class="filter-btn bg-white text-black font-bold py-2 px-6 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-[#EE2E24] hover:text-white hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all" data-category="Academic">
            Academic
        </button>
        <button class="filter-btn bg-white text-black font-bold py-2 px-6 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-[#EE2E24] hover:text-white hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all" data-category="Non-Academic">
            Non-Academic
        </button>
         <button class="filter-btn bg-white text-black font-bold py-2 px-6 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-[#EE2E24] hover:text-white hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all" data-category="Workshop">
            Workshop
        </button>
         <button class="filter-btn bg-white text-black font-bold py-2 px-6 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-[#EE2E24] hover:text-white hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all" data-category="Seminar">
            Seminar
        </button>
    </div>

    <!-- Event Grid -->
    <div id="event-grid" class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        <!-- Events will be loaded here -->
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center hidden" id="pagination-container">
        <button id="prev-btn" class="bg-white text-black font-bold py-2 px-6 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            &laquo; Previous
        </button>
        <span id="page-info" class="font-bold text-lg">Page 1</span>
        <button id="next-btn" class="bg-white text-black font-bold py-2 px-6 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            Next &raquo;
        </button>
    </div>

    <!-- Loading State -->
    <div id="loading" class="text-center py-12 hidden">
        <p class="text-2xl font-black animate-pulse">LOADING DATA...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let currentPage = 1;
        let currentCategory = 'All';
        const grid = document.getElementById('event-grid');
        const loading = document.getElementById('loading');
        const paginationContainer = document.getElementById('pagination-container');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const pageInfo = document.getElementById('page-info');

        // Helper to format date
        const formatDate = (dateString) => {
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        };

        const fetchEvents = async (page = 1, category = '') => {
            grid.innerHTML = '';
            loading.classList.remove('hidden');
            paginationContainer.classList.add('hidden');

            try {
                let url = `/api/events?page=${page}`;
                if (category && category !== 'All') {
                    url += `&category=${category}`;
                }

                const response = await fetch(url);
                const data = await response.json();
                
                loading.classList.add('hidden');
                
                // Render Cards
                if (data.data.length === 0) {
                    grid.innerHTML = '<p class="text-xl font-bold col-span-3 text-center border-4 border-black p-8 bg-gray-100">NO EVENTS FOUND.</p>';
                    return;
                }

                data.data.forEach(event => {
                    const card = `
                        <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[-2px] hover:translate-y-[-2px] hover:shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] transition-all flex flex-col h-full">
                            <div class="h-48 overflow-hidden border-b-4 border-black relative group">
                                <img src="${event.image_url}" alt="${event.title}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                <span class="absolute top-0 right-0 bg-[#EE2E24] text-white font-bold px-3 py-1 border-l-4 border-b-4 border-black">
                                    ${event.category}
                                </span>
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-xl font-black mb-2 leading-tight line-clamp-2" style="font-size: clamp(1.25rem, 2vw, 1.5rem);">${event.title}</h3>
                                <p class="text-sm font-bold mb-4 text-gray-600 border-b-2 border-dashed border-black pb-2">
                                    📅 ${formatDate(event.date)}
                                </p>
                                <p class="mb-4 line-clamp-3 flex-grow font-mono text-sm">
                                    ${event.description}
                                </p>
                                <a href="/events/${event.id}" class="mt-auto block text-center bg-black text-white font-bold py-3 hover:bg-[#EE2E24] hover:text-white transition-colors border-2 border-black">
                                    VIEW DETAILS ➔
                                </a>
                            </div>
                        </div>
                    `;
                    grid.innerHTML += card;
                });

                // Pagination Logic
                paginationContainer.classList.remove('hidden');
                pageInfo.innerText = `Page ${data.current_page}`;
                
                prevBtn.disabled = !data.prev_page_url;
                nextBtn.disabled = !data.next_page_url;

            } catch (error) {
                console.error('Error fetching events:', error);
                loading.classList.add('hidden');
                grid.innerHTML = '<p class="text-red-600 font-bold">ERROR LOADING EVENTS.</p>';
            }
        };

        // Initial Fetch
        fetchEvents(currentPage, currentCategory);

        // Filter Click Handlers
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                // Update UI
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('bg-[#EE2E24]', 'text-white');
                    b.classList.add('bg-white', 'text-black');
                });
                e.target.classList.remove('bg-white', 'text-black');
                e.target.classList.add('bg-[#EE2E24]', 'text-white');

                currentCategory = e.target.getAttribute('data-category');
                currentPage = 1; // Reset to page 1 on filter
                fetchEvents(currentPage, currentCategory);
            });
        });

        // Pagination Click Handlers
        prevBtn.addEventListener('click', () => {
             if (currentPage > 1) {
                 currentPage--;
                 fetchEvents(currentPage, currentCategory);
             }
        });

        nextBtn.addEventListener('click', () => {
             currentPage++;
             fetchEvents(currentPage, currentCategory);
        });
    });
</script>
@endsection
