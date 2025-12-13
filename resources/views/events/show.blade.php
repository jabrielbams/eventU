<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Event Detail</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
         :root {
            --telkom-red: #EE2E24;
            --black: #000000;
            --white: #FFFFFF;
            --border-thick: 4px solid var(--black);
            --shadow-hard: 8px 8px 0px var(--black);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Space Grotesk', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: var(--black);
            padding: 2rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn {
            position: absolute;
            top: 2rem;
            left: 2rem;
            background: var(--white);
            border: var(--border-thick);
            padding: 1rem 2rem;
            text-decoration: none;
            color: var(--black);
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: var(--shadow-hard);
            transition: transform 0.2s;
        }

        .back-btn:active {
            transform: translate(4px, 4px);
            box-shadow: 4px 4px 0px var(--black);
        }

        .poster-container {
            background: var(--white);
            border: var(--border-thick);
            box-shadow: var(--shadow-hard);
            max-width: 900px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            overflow: hidden;
        }

        .poster-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-right: var(--border-thick);
            background-color: #ddd;
            min-height: 400px;
        }

        .poster-content {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .category-badge {
            background: var(--telkom-red);
            color: var(--white);
            padding: 0.5rem 1rem;
            border: 2px solid var(--black);
            display: inline-block;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 1rem;
            align-self: flex-start;
        }

        h1 {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 700;
            line-height: 1;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
            border-top: 2px solid var(--black);
            border-bottom: 2px solid var(--black);
            padding: 1.5rem 0;
        }

        .meta-item strong {
            display: block;
            text-transform: uppercase;
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.2rem;
        }

        .meta-item span {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .description {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .action-btn {
            background: var(--black);
            color: var(--white);
            text-align: center;
            padding: 1rem;
            font-size: 1.2rem;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: none;
            border: var(--border-thick);
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: var(--telkom-red);
            transform: scale(1.02);
        }

            /* Neo-Brutalism Stack Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .stack-card {
            position: relative;
            width: 400px;
            height: 250px;
        }

        .stack-layer {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 3px solid var(--black);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Layer 1 (Bottom): Solid Black */
        .layer-1 {
            background-color: var(--black);
            bottom: 0px;
            right: 0px;
            z-index: 1;
        }

        /* Layer 2 (Middle): Neon Yellow */
        .layer-2 {
            background-color: #CCFF00;
            bottom: 0px;
            right: 0px;
            z-index: 2;
        }

        /* Layer 3 (Top): White */
        .layer-3 {
            background-color: var(--white);
            bottom: 0px;
            right: 0px;
            z-index: 3;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
        }

        /* Animation States */
        .modal-overlay.active .layer-1 {
            transform: translate(15px, 15px);
        }

        .modal-overlay.active .layer-2 {
            transform: translate(8px, 8px);
        }
        
        /* Utility */
        .hidden {
            display: none !important;
        }

        /* Button States */
        .action-btn:disabled {
            background-color: #999;
            cursor: not-allowed;
            border-color: #666;
            color: #ccc;
            transform: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .poster-container {
                grid-template-columns: 1fr;
                border: var(--border-thick);
            }
            .poster-image {
                border-right: none;
                border-bottom: var(--border-thick);
                height: 300px;
            }
            .poster-content {
                padding: 1.5rem;
            }
            .back-btn {
                position: static;
                margin-bottom: 2rem;
                display: inline-block;
            }
            body {
                flex-direction: column;
                justify-content: flex-start;
            }
            .stack-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <a href="/events" class="back-btn">&larr; Back to Catalog</a>

    <div class="poster-container" id="poster">
        <div id="loading" style="grid-column: 1/-1; padding: 5rem; text-align: center; font-size: 2rem; font-weight: bold;">
            LOADING EVENT DETAILS...
        </div>
    </div>

    <!-- Deconstructed Stack Modal -->
    <div class="modal-overlay" id="successModal">
        <div class="stack-card">
            <div class="stack-layer layer-1"></div>
            <div class="stack-layer layer-2"></div>
            <div class="stack-layer layer-3">
                <h2 style="font-weight: 900; font-size: 1.5rem; margin-bottom: 1rem; text-transform: uppercase;">Registration Successful</h2>
                <p style="font-size: 0.9rem; margin-bottom: 0.5rem; color: #666;">TICKET ID</p>
                <div id="ticketIdDisplay" style="font-family: monospace; font-size: 1.5rem; font-weight: bold; border: 2px dashed black; padding: 0.5rem 1rem;">
                    #TICKET-12345
                </div>
                <button onclick="closeModal()" style="margin-top: 1.5rem; background: transparent; border: none; text-decoration: underline; font-weight: bold; cursor: pointer;">CLOSE</button>
            </div>
        </div>
    </div>

    <script>
        // Get ID from URL or Blade injection (We used Blade to pass ID in view route, but API calls need ID)
        // Since we are using strictly Vanilla JS inside Blade, we can grab the ID from the URL path
        const pathSegments = window.location.pathname.split('/');
        const eventId = pathSegments[pathSegments.length - 1];

        document.addEventListener('DOMContentLoaded', () => {
            fetchEventDetails(eventId);
        });

        function fetchEventDetails(id) {
            fetch(`/api/events/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Event not found');
                    return response.json();
                })
                .then(data => {
                    renderDetail(data);
                })
                .catch(error => {
                    document.getElementById('loading').innerText = "EVENT NOT FOUND";
                });
        }

        function renderDetail(event) {
            const container = document.getElementById('poster');
            container.innerHTML = `
                <img src="${event.image || 'https://placehold.co/800x1200'}" class="poster-image" alt="Event Poster">
                <div class="poster-content">
                    <span class="category-badge">${event.category}</span>
                    <h1>${event.title}</h1>
                    
                    <div class="meta-grid">
                        <div class="meta-item">
                            <strong>Date</strong>
                            <span>${event.date}</span>
                        </div>
                        <div class="meta-item">
                            <strong>Time</strong>
                            <span>${event.time}</span>
                        </div>
                        <div class="meta-item">
                            <strong>Location</strong>
                            <span>${event.location}</span>
                        </div>
                         <div class="meta-item">
                            <strong>Organizer</strong>
                            <span>${event.organizer}</span>
                        </div>
                    </div>

                    <p class="description">
                        ${event.description}
                    </p>

                    <button id="registerBtn" class="action-btn" style="width: 100%; border: none; cursor: pointer; border: var(--border-thick);">
                        REGISTER NOW
                    </button>
                </div>
            `;

            checkRegistrationStatus(event.id);

            document.getElementById('registerBtn').addEventListener('click', () => {
                registerForEvent(event.id);
            });
        }

        function checkRegistrationStatus(eventId) {
            const btn = document.getElementById('registerBtn');
            fetch(`/api/events/${eventId}/status`)
                .then(res => res.json())
                .then(data => {
                    if (data.registered) {
                        btn.textContent = "ALREADY REGISTERED";
                        btn.disabled = true;
                        btn.style.backgroundColor = '#ccc';
                        btn.style.color = '#666';
                        btn.style.borderColor = '#999';
                        btn.style.transform = 'none';
                    } else {
                        btn.textContent = "REGISTER NOW";
                        btn.disabled = false;
                        btn.style.backgroundColor = 'var(--telkom-red)'; // Red for active
                        btn.style.color = 'var(--white)';
                    }
                })
                .catch(err => console.error('Failed to check status', err));
        }

        function registerForEvent(eventId) {
            const btn = document.getElementById('registerBtn');
            const originalText = btn.textContent;
            
            btn.textContent = "Processing...";
            btn.disabled = true;

            fetch(`/api/events/${eventId}/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // Add CSRF token if needed, but this is an API route, usually stateless or token based.
                    // Assuming API doesn't need CSRF or it's handled via cookie if same-origin.
                    // For typical Laravel API in web context:
                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                }
            })
            .then(async response => {
                const data = await response.json();
                if (response.status === 201) {
                    showSuccessModal(data.ticket_id || 'ID-UNKNOWN');
                    btn.textContent = "ALREADY REGISTERED";
                    btn.style.backgroundColor = '#ccc';
                    btn.style.color = '#666';
                    btn.style.borderColor = '#999';
                } else {
                    alert(data.message || 'Registration failed');
                    btn.textContent = originalText;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred');
                btn.textContent = originalText;
                btn.disabled = false;
            });
        }

        function showSuccessModal(ticketId) {
            const modal = document.getElementById('successModal');
            document.getElementById('ticketIdDisplay').textContent = '#' + ticketId;
            modal.classList.add('active');
        }

        function closeModal() {
            document.getElementById('successModal').classList.remove('active');
        }
    </script>
</body>
</html>
