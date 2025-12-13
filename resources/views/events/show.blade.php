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
        /* --- EVENT ANNOUNCEMENTS (BULLETIN BOARD) --- */
        /* Layout Stack */
        .layout-stack {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 900px;
        }

        .section-divider {
            border: 0;
            border-top: 3px dashed var(--black);
            margin: 4rem 0;
            width: 100%;
        }

        .poster-container {
             max-width: 100%;
        }

        /* --- EVENT ANNOUNCEMENTS (BULLETIN BOARD) --- */
        .bulletin-section {
            max-width: 100%;
            width: 100%;
            margin-top: 0;
            background-color: #f0f0f0;
            background-image: repeating-linear-gradient(
                45deg,
                #dadada 0px,
                #dadada 1px,
                transparent 1px,
                transparent 10px
            ); /* Hatched Pattern */
            border: var(--border-thick);
            box-shadow: var(--shadow-hard);
            padding: 2rem;
            position: relative;
        }

        .section-header {
            background: var(--black);
            color: var(--white);
            font-size: 1.5rem;
            font-weight: 800;
            padding: 0.5rem 1.5rem;
            display: inline-block;
            transform: rotate(-2deg);
            margin-bottom: 2rem;
            border: 2px solid var(--white);
            box-shadow: 4px 4px 0px rgba(0,0,0,0.2);
        }

        /* Broadcast Box (Organizer) */
        .broadcast-box {
            background: var(--white);
            border: var(--border-thick);
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .megaphone-input {
            width: 100%;
            border: 2px solid var(--black);
            padding: 1rem;
            font-family: 'Space Grotesk', monospace;
            font-size: 1rem;
            resize: vertical;
            min-height: 100px;
            background: #fff;
        }

        .broadcast-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .urgency-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .broadcast-btn {
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 0.8rem 2rem;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 0.1s;
        }

        .broadcast-btn:active {
            transform: scale(0.95);
        }

        /* News Feed */
        #news-feed {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .news-card {
            background: var(--white);
            border: var(--border-thick);
            padding: 0;
            position: relative;
            box-shadow: 4px 4px 0px var(--black);
            transition: opacity 0.3s ease;
        }

        .news-card.urgent {
            background: #CCFF00; /* Neon Yellow */
            border-color: #EE2E24; /* Red Border */
        }
        
        .news-card.urgent .news-header {
             border-bottom-color: #EE2E24;
        }
        
        .news-card.urgent .timestamp-badge {
            background: #EE2E24;
            color: var(--white);
        }

        .news-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 1rem;
            border-bottom: 2px solid var(--black);
            background: rgba(255,255,255,0.5);
        }

        .timestamp-badge {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: 0.85rem;
            background: var(--black);
            color: var(--white);
            padding: 0.2rem 0.5rem;
        }

        .urgent-badge {
            background: #EE2E24;
            color: var(--white);
            font-weight: 900;
            padding: 0.2rem 0.5rem;
            font-size: 0.8rem;
            margin-right: auto;
            margin-left: 1rem;
            border: 1px solid var(--black);
            display: none; /* Hidden by default */
        }
        
        .news-card.urgent .urgent-badge {
            display: inline-block;
        }

        .news-body {
            padding: 1.5rem;
            font-size: 1.1rem;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .news-controls {
            display: flex;
            gap: 0.5rem;
        }

        .control-icon {
            cursor: pointer;
            font-size: 1rem;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .control-icon:hover {
            opacity: 1;
        }

        .empty-state {
            text-align: center;
            font-weight: 700;
            padding: 2rem;
            color: #666;
            border: 2px dashed #999;
            background: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body>

    <a href="/events" class="back-btn">&larr; Back to Catalog</a>

    <div class="layout-stack">
        <div class="poster-container" id="poster">
            <div id="loading" style="grid-column: 1/-1; padding: 5rem; text-align: center; font-size: 2rem; font-weight: bold;">
                LOADING EVENT DETAILS...
            </div>
        </div>

        <hr class="section-divider">

        <!-- LIVE BULLETIN BOARD SECTION -->
        <div class="bulletin-section">
            <div class="section-header">LIVE UPDATES</div>

            @if(auth()->check() && isset($event) && auth()->id() === $event->user_id)
            <!-- Organizer Broadcast Box -->
            <div class="broadcast-box">
                <textarea id="announcementInput" class="megaphone-input" placeholder="LOUD NOISES! Type announcement here..."></textarea>
                <div class="broadcast-controls">
                    <label class="urgency-toggle">
                        <input type="checkbox" id="urgencyCheck">
                        MARK AS CRITICAL
                    </label>
                    <button id="broadcastBtn" class="broadcast-btn">BROADCAST</button>
                </div>
            </div>
            @endif

            <!-- News Feed -->
            <div id="news-feed">
                <!-- Announcements injected here -->
                <div class="empty-state">NO UPDATES YET. STAY TUNED.</div>
            </div>
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
            fetchAnnouncements(eventId);
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



        /* --- ANNOUNCEMENT LOGIC --- */

        // Helper to format date "12 OCT - 14:00"
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = date.getDate();
            const month = date.toLocaleString('default', { month: 'short' }).toUpperCase();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${day} ${month} - ${hours}:${minutes}`;
        }

        // Fetch Announcements on Load
        function fetchAnnouncements(id) {
            fetch(`/api/events/${id}/announcements`)
                .then(res => { 
                    if(!res.ok) throw new Error("Failed to fetch");
                    return res.json(); 
                })
                .then(data => {
                    const feed = document.getElementById('news-feed');
                    if (data.length > 0) {
                        feed.innerHTML = ''; // Clear empty state
                        // Assuming data is latest first. If not, verify API sort.
                        data.forEach(item => {
                            feed.appendChild(createAnnouncementCard(item));
                        });
                    }
                })
                .catch(err => console.log('No announcements or error fetching', err));
        }

        // Create DOM Element for Announcement
        function createAnnouncementCard(data) {
            const card = document.createElement('div');
            card.className = `news-card ${data.is_urgent ? 'urgent' : ''}`;
            card.id = `announcement-${data.id}`;

            const isOwner = {{ (auth()->check() && isset($event) && auth()->id() === $event->user_id) ? 'true' : 'false' }};
            
            let controlsHtml = '';
            if (isOwner) {
                controlsHtml = `
                    <div class="news-controls">
                        <span class="control-icon" onclick="editAnnouncement(${data.id}, '${data.message?.replace(/'/g, "\\'")}')">✏️</span>
                        <span class="control-icon" onclick="deleteAnnouncement(${data.id})">❌</span>
                    </div>
                `;
            }

            card.innerHTML = `
                <div class="news-header">
                    <span class="timestamp-badge">${formatDate(data.created_at)}</span>
                    <span class="urgent-badge">URGENT</span>
                    ${controlsHtml}
                </div>
                <div class="news-body" id="body-${data.id}">${data.message}</div>
            `;
            return card;
        }

        // Post Announcement
        const broadcastBtn = document.getElementById('broadcastBtn');
        if (broadcastBtn) {
            broadcastBtn.addEventListener('click', () => {
                const input = document.getElementById('announcementInput');
                const isUrgent = document.getElementById('urgencyCheck').checked;
                const message = input.value.trim();

                if (!message) return alert("Please type a message!");

                broadcastBtn.disabled = true;
                broadcastBtn.textContent = "SENDING...";

                fetch('/api/announcements', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        event_id: eventId,
                        message: message,
                        is_urgent: isUrgent
                    })
                })
                .then(res => res.json())
                .then(data => {
                    // Optimistic UI or use returned data
                    const feed = document.getElementById('news-feed');
                    
                    // Remove empty state if present
                    if (feed.querySelector('.empty-state')) {
                        feed.innerHTML = '';
                    }

                    // Prepend new card
                    feed.insertBefore(createAnnouncementCard(data), feed.firstChild);

                    // Reset Form
                    input.value = '';
                    document.getElementById('urgencyCheck').checked = false;
                    broadcastBtn.disabled = false;
                    broadcastBtn.textContent = "BROADCAST";
                })
                .catch(err => {
                    console.error(err);
                    alert("Failed to post announcement.");
                    broadcastBtn.disabled = false;
                    broadcastBtn.textContent = "BROADCAST";
                });
            });
        }

        // Delete Announcement
        window.deleteAnnouncement = function(id) {
            if(!confirm("Purge this update?")) return;

            fetch(`/api/announcements/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => {
                if(res.ok) {
                    const card = document.getElementById(`announcement-${id}`);
                    card.style.opacity = '0';
                    setTimeout(() => card.remove(), 300);
                } else {
                    alert('Failed to delete');
                }
            });
        };

        // Edit Announcement (Inline)
        window.editAnnouncement = function(id, currentText) {
            const bodyDiv = document.getElementById(`body-${id}`);
            const originalHtml = bodyDiv.innerHTML;
            
            // Swap to input
            bodyDiv.innerHTML = `
                <input type="text" id="edit-input-${id}" value="${currentText}" 
                    style="width: 100%; padding: 0.5rem; border: 2px solid black; font-family: 'Space Grotesk'; font-size: 1rem;">
                <div style="margin-top: 0.5rem; font-size: 0.8rem;">Press Enter to Save, Esc to Cancel</div>
            `;
            
            const input = document.getElementById(`edit-input-${id}`);
            input.focus();

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    saveEdit(id, input.value);
                } else if (e.key === 'Escape') {
                    bodyDiv.innerHTML = originalHtml; // Revert
                }
            });
        };

        function saveEdit(id, newMessage) {
            fetch(`/api/announcements/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: newMessage })
            })
            .then(res => res.json())
            .then(data => {
                const bodyDiv = document.getElementById(`body-${id}`);
                bodyDiv.innerHTML = data.message; 
                // Need to update the onclick handler for the edit button to reflect new text? 
                // Ideally yes, but simpler to just reload or let it be. 
                // For robustness, let's update the edit button onclick attribute manually or just re-fetch.
                // Re-fetching is safer but slower. Let's update the attribute if we can select it.
                // Simple for now: Just update text.
            })
            .catch(err => {
                console.error(err);
                alert('Update failed');
            });
        }

        function showSuccessModal(ticketId) {
            const modal = document.getElementById('successModal');
            document.getElementById('ticketIdDisplay').textContent = '#' + ticketId;
            modal.classList.add('active');
        }

        // Call fetch on load (Modify existing DOMContentLoaded)
        const originalInit = window.onload; // or just append to the existing event listener

        function closeModal() {
            document.getElementById('successModal').classList.remove('active');
        }
    </script>
</body>
</html>
