<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

                    <a href="${event.registration_link || '#'}" class="action-btn">Register Now</a>
                </div>
            `;
        }
    </script>
</body>
</html>
