@extends('layouts.app')

@section('title', 'Profil Organisasi')

@section('content')
<div class="py-10 px-4">
    <!-- Back Button -->
    <div class="max-w-6xl mx-auto mb-8">
        <a href="{{ route('dashboard') }}" class="neo-button-default inline-block">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Loading State -->
    <div id="loading" class="max-w-6xl mx-auto text-center py-20">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-4 border-b-4 border-black"></div>
        <p class="mt-4 font-bold uppercase">Memuat data organisasi...</p>
    </div>

    <!-- Error State -->
    <div id="error" class="hidden max-w-6xl mx-auto">
        <div class="bg-industrial-red text-white border-[3px] border-black shadow-[8px_8px_0px_#000] p-8">
            <h2 class="text-2xl font-black uppercase mb-2">⚠️ Error</h2>
            <p id="error-message" class="font-bold"></p>
        </div>
    </div>

    <!-- Organization Profile Container -->
    <div id="organization-content" class="hidden max-w-6xl mx-auto">
        <!-- Organization Header -->
        <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] overflow-hidden relative mb-8">
            <!-- Rivets -->
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2 z-10"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2 z-10"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 left-2 z-10"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full bottom-2 right-2 z-10"></div>

            <div class="p-8 md:p-12">
                <!-- Logo and Name -->
                <div class="flex flex-col md:flex-row items-start md:items-center gap-8 mb-8 pb-8 border-b-[3px] border-black">
                    <div id="org-logo-container" class="flex-shrink-0">
                        <!-- Logo will be inserted here -->
                    </div>
                    <div class="flex-1">
                        <h1 id="org-name" class="text-3xl md:text-5xl font-black uppercase leading-[0.95] mb-4 tracking-tight">
                            <!-- Organization name -->
                        </h1>
                        <p id="org-description" class="text-lg text-gray-700 font-medium leading-relaxed">
                            <!-- Description -->
                        </p>
                    </div>
                </div>

                <!-- Contact Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div id="org-email-container" class="hidden bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">📧</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Email</strong>
                        </div>
                        <span id="org-email" class="text-sm font-bold block font-mono break-all"></span>
                    </div>
                    <div id="org-phone-container" class="hidden bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">📞</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Telepon</strong>
                        </div>
                        <span id="org-phone" class="text-sm font-bold block font-mono"></span>
                    </div>
                    <div id="org-address-container" class="hidden bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">📍</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Alamat</strong>
                        </div>
                        <span id="org-address" class="text-sm font-bold block"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Section -->
        <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] overflow-hidden relative">
            <!-- Rivets -->
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2 z-10"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2 z-10"></div>

            <div class="p-8 md:p-12">
                <h2 class="text-2xl md:text-3xl font-black uppercase mb-6 tracking-tight">Event yang Diselenggarakan</h2>

                <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Events will be inserted here -->
                </div>

                <div id="no-events" class="hidden text-center py-12">
                    <p class="text-xl font-bold uppercase text-gray-500">Belum ada event yang diselenggarakan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const organizationId = {{ $organizationId }};
    const loading = document.getElementById('loading');
    const error = document.getElementById('error');
    const content = document.getElementById('organization-content');
    const errorMessage = document.getElementById('error-message');

    // Fetch organization data using the API
    fetch(`/api/organizations/${organizationId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Organisasi tidak ditemukan');
            }
            return response.json();
        })
        .then(result => {
            const org = result.data;

            // Hide loading, show content
            loading.classList.add('hidden');
            content.classList.remove('hidden');

            // Populate organization details
            document.getElementById('org-name').textContent = org.name;
            document.getElementById('org-description').textContent = org.description || 'Tidak ada deskripsi';

            // Logo
            const logoContainer = document.getElementById('org-logo-container');
            if (org.logo_url) {
                logoContainer.innerHTML = `
                    <div class="w-32 h-32 border-[3px] border-black shadow-[4px_4px_0px_#000] overflow-hidden bg-white">
                        <img src="${org.logo_url}" alt="${org.name}" class="w-full h-full object-cover">
                    </div>
                `;
            } else {
                logoContainer.innerHTML = `
                    <div class="w-32 h-32 border-[3px] border-black shadow-[4px_4px_0px_#000] bg-gray-200 flex items-center justify-center">
                        <span class="text-4xl font-black text-gray-400">${org.name.charAt(0).toUpperCase()}</span>
                    </div>
                `;
            }

            // Contact info
            if (org.email) {
                document.getElementById('org-email').textContent = org.email;
                document.getElementById('org-email-container').classList.remove('hidden');
            }
            if (org.phone) {
                document.getElementById('org-phone').textContent = org.phone;
                document.getElementById('org-phone-container').classList.remove('hidden');
            }
            if (org.address) {
                document.getElementById('org-address').textContent = org.address;
                document.getElementById('org-address-container').classList.remove('hidden');
            }

            // Populate events
            const eventsContainer = document.getElementById('events-container');
            const noEvents = document.getElementById('no-events');

            if (org.events && org.events.length > 0) {
                org.events.forEach(event => {
                    const eventCard = createEventCard(event);
                    eventsContainer.appendChild(eventCard);
                });
            } else {
                noEvents.classList.remove('hidden');
            }
        })
        .catch(err => {
            loading.classList.add('hidden');
            error.classList.remove('hidden');
            errorMessage.textContent = err.message;
        });

    function createEventCard(event) {
        const card = document.createElement('div');
        card.className = 'bg-white border-[3px] border-black shadow-[6px_6px_0px_#000] overflow-hidden hover:shadow-[8px_8px_0px_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] transition-all duration-100';

        const imageUrl = event.image_url || 'https://placehold.co/400x300/333333/FFFFFF?text=Event';
        const statusColors = {
            'draft': 'bg-gray-400',
            'published': 'bg-green-500',
            'cancelled': 'bg-red-500',
            'completed': 'bg-blue-500'
        };
        const statusColor = statusColors[event.status] || 'bg-gray-400';

        // Format date
        const eventDate = new Date(event.date);
        const dateFormatted = eventDate.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        card.innerHTML = `
            <div class="relative h-48 bg-gray-100">
                <img src="${imageUrl}" alt="${event.title}" class="w-full h-full object-cover">
                <span class="${statusColor} text-white px-3 py-1 text-xs font-black uppercase absolute top-2 right-2 border-[2px] border-black">
                    ${event.status}
                </span>
            </div>
            <div class="p-4">
                <h3 class="font-black uppercase text-lg mb-2 leading-tight">${event.title}</h3>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">${event.description || ''}</p>
                <div class="text-xs font-bold mb-3">
                    <div class="flex items-center gap-2 mb-1">
                        <span>📅</span>
                        <span class="font-mono">${dateFormatted}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span>📍</span>
                        <span class="font-mono">${event.location}</span>
                    </div>
                </div>
                <a href="/events/${event.id}" class="block w-full text-center bg-black text-white py-2 px-4 border-[2px] border-black font-black uppercase text-sm hover:bg-industrial-red transition-colors">
                    Lihat Detail
                </a>
            </div>
        `;

        return card;
    }
});
</script>
@endsection
