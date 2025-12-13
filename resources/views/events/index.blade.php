<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TelyuEvents - Neo Catalog</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --telkom-red: #EE2E24;
            --black: #000000;
            --white: #FFFFFF;
            --border-thick: 4px solid var(--black);
            --border-med: 3px solid var(--black);
            --shadow-hard: 6px 6px 0px 0px var(--black);
            --shadow-hover: 0px 0px 0px 0px var(--black);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Space Grotesk', sans-serif;
        }

        body {
            background-color: var(--white);
            background-image: radial-gradient(#000000 1px, transparent 1px);
            background-size: 20px 20px;
            color: var(--black);
            padding: 2rem;
            min-height: 100vh;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        header {
            margin-bottom: 3rem;
            padding: 2rem;
            background: var(--white);
            border: var(--border-thick);
            box-shadow: var(--shadow-hard);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        h1 {
            font-size: clamp(2.5rem, 5vw, 5rem);
            font-weight: 900;
            text-transform: uppercase;
            line-height: 0.9;
            letter-spacing: -2px;
        }

        .home-link {
            font-size: 1.2rem;
            font-weight: 900;
            color: var(--black);
            text-decoration: none;
            text-transform: uppercase;
            padding: 0.5rem 1rem;
            border: var(--border-med);
            background: var(--white);
            transition: all 0.1s;
        }
        
        .home-link:hover {
            background: var(--telkom-red);
            color: var(--white);
        }

        /* Control Panel (Filter Bar) */
        .control-panel {
            background: var(--white);
            border: var(--border-thick);
            box-shadow: var(--shadow-hard);
            padding: 1.5rem;
            margin-bottom: 4rem;
            display: flex;
            flex-direction: row; /* Default: Row */
            flex-wrap: wrap;       /* Default: Wrap if needed */
            gap: 1rem;
            align-items: stretch;
        }

        .search-group {
            flex-grow: 1; /* Take available space */
            flex-basis: 300px; /* Minimum width before wrapping, but flexible */
            display: flex;
            gap: 0;
            margin-right: 1rem;
        }

        .neo-input {
            width: 100%;
            border: var(--border-med);
            padding: 1rem;
            font-size: 1.2rem;
            font-weight: 700;
            outline: none;
            border-radius: 0;
            text-transform: uppercase;
            box-sizing: border-box; /* Fix overflow */
        }

        .neo-input::placeholder {
            color: #888;
            opacity: 1;
        }

        .neo-input:focus {
            background: #ffecec;
        }

        .filter-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            flex-grow: 0;
        }

        .filter-btn {
            background: var(--white);
            color: var(--black);
            border: var(--border-med);
            padding: 0.8rem 1.5rem;
            font-weight: 900;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: var(--shadow-hard);
            transition: all 0.1s ease;
            position: relative;
        }

        .filter-btn:hover {
            transform: translate(6px, 6px);
            box-shadow: var(--shadow-hover);
            background: #eee;
        }

        .filter-btn.active {
            background: var(--telkom-red);
            color: var(--white);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            h1 { font-size: 3rem; }
            
            .control-panel { 
                padding: 1rem; 
                flex-direction: column; /* Stack vertically */
                align-items: flex-start;
            }
            
            .search-group { 
                width: 100%; 
                margin-right: 0;
                margin-bottom: 1.5rem; /* Space between search and filters */
                flex-basis: auto;
            }
            
            .filter-group { 
                width: 100%; 
                justify-content: flex-start; /* Align left */
            }
            
            .filter-btn { 
                flex-grow: 1; 
                text-align: center; 
            }
            
            .neo-card-shadow-wrapper:hover { 
                transform: none; 
                box-shadow: var(--shadow-hard); 
            }
        }

        .filter-btn:active {
            transform: translate(6px, 6px);
            box-shadow: var(--shadow-hover);
        }

        /* Event Grid */
        #event-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 3rem;
        }

        /* Neo Card */
        .neo-card {
            background: var(--white);
            border: var(--border-thick);
            display: flex;
            flex-direction: column;
            position: relative;
            transition: all 0.2s;
        }

        .neo-card:hover {
             /* Static card, button interaction handles the 'press' feel, 
                but maybe the card itself can float? 
                User said "Cards & Buttons... Hard Drop Shadows". 
                I will add shadow to the card too. */
            box-shadow: var(--shadow-hard);
            transform: translate(-4px, -4px); /* Pop up effect */
        }
        
        .neo-card-shadow-wrapper {
            /* Wrapper to handle the static shadow vs hover effect properly if needed,
               but standard box-shadow on hover works for "pop up". 
               User asked for "On :hover, ... translate ... to COVER the shadow".
               That usually means the element starts with shadow, and moves DOWN to cover it.
               So: Default State = Shadow + Translated UP. Hover State = No Shadow + Translated DOWN.
               Let's try that for Buttons.
               For Cards, usually we want them to pop up on hover, or strictly follow the rule.
               User said "Cards & Buttons... On :hover, the element should translate... to cover".
               So I will implement that: Default has shadow. Hover removes shadow and translates.
            */
            box-shadow: var(--shadow-hard);
            transform: translate(0, 0);
            transition: all 0.1s;
        }

        .neo-card-shadow-wrapper:hover {
            transform: translate(6px, 6px);
            box-shadow: var(--shadow-hover);
        }

        .card-image-container {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            border-bottom: var(--border-thick);
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Date Badge - Floated */
        .date-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--white);
            border: var(--border-med);
            padding: 0.5rem;
            text-align: center;
            min-width: 60px;
            box-shadow: 4px 4px 0px var(--black);
        }

        .date-badge .month {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            background: var(--black);
            color: var(--white);
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

        .tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .tag-pill {
            background: var(--black);
            color: var(--white);
            padding: 0.2rem 0.8rem;
            border-radius: 999px; /* Pill shape - or blocky? User said "Pills". Standard pills are rounded. */
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            border: 2px solid var(--black);
        }
        
        .tag-pill.red {
            background: var(--telkom-red);
            border-color: var(--black); 
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 900;
            line-height: 1.1;
            text-transform: uppercase;
        }

        .card-details {
            margin-top: auto;
            border-top: 4px solid var(--black);
            padding-top: 1rem;
            font-weight: 700;
            font-size: 0.9rem;
        }

        /* Full Width Button */
        .card-action {
            width: 100%;
            background: var(--telkom-red);
            color: var(--white);
            border: none;
            border-top: var(--border-thick);
            padding: 1rem;
            font-size: 1.2rem;
            font-weight: 900;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        .card-action:hover {
            background: var(--black);
            color: var(--white);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 4rem;
        }

        .page-btn {
            background: var(--white);
            border: var(--border-thick);
            padding: 1rem 2rem;
            font-weight: 900;
            text-transform: uppercase;
            box-shadow: var(--shadow-hard);
            cursor: pointer;
            transition: all 0.1s;
        }

        .page-btn:hover:not(:disabled) {
            transform: translate(6px, 6px);
            box-shadow: var(--shadow-hover);
            background: var(--telkom-red);
            color: var(--white);
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
            background: #ddd;
        }

        /* Mobile */
        @media (max-width: 768px) {
            h1 { font-size: 3rem; }
            .control-panel { padding: 1rem; flex-direction: column; }
            .search-group { width: 100%; }
            .filter-group { width: 100%; justify-content: stretch; }
            .filter-btn { flex-grow: 1; text-align: center; }
            .neo-card-shadow-wrapper:hover { transform: none; box-shadow: var(--shadow-hard); } /* Disable hover move on mobile? */
        }
    </style>
</head>
<body>

    <div class="container">
        <header>
            <h1>Event Catalog</h1>
            <a href="/" class="home-link">&larr; Dashboard</a>
        </header>

        <!-- Control Panel -->
        <div class="control-panel">
            <div class="search-group">
                <input type="text" id="searchInput" class="neo-input" placeholder="SEARCH EVENTS..." onkeyup="handleSearch(event)">
            </div>
            <div class="filter-group">
                <button class="filter-btn active" onclick="setCategory('all')">All</button>
                <button class="filter-btn" onclick="setCategory('workshop')">Workshop</button>
                <button class="filter-btn" onclick="setCategory('seminar')">Seminar</button>
                <button class="filter-btn" onclick="setCategory('competition')">Compete</button>
            </div>
        </div>

        <!-- Event Grid -->
        <div id="event-list">
            <!-- Loaded via JS -->
            <h2 style="grid-column: 1/-1; text-align: center; font-size: 2rem;">LOADING...</h2>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button id="prevBtn" class="page-btn" onclick="changePage(-1)" disabled>Previous</button>
            <button id="nextBtn" class="page-btn" onclick="changePage(1)" disabled>Next</button>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let currentCategory = 'all';
        let searchQuery = '';
        let debounceTimer;

        document.addEventListener('DOMContentLoaded', () => {
            loadEvents();
        });

        function handleSearch(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                searchQuery = e.target.value;
                currentPage = 1;
                loadEvents();
            }, 300);
        }

        function setCategory(cat) {
            currentCategory = cat;
            currentPage = 1;
            
            // Update Active UI
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
                const btnText = btn.innerText.toLowerCase();
                if(btnText === cat || (cat === 'competition' && btnText === 'compete')) {
                    btn.classList.add('active');
                }
            });

            loadEvents();
        }

        function changePage(delta) {
            currentPage += delta;
            loadEvents();
        }

        function loadEvents() {
            const list = document.getElementById('event-list');
            // list.innerHTML = '<h2 style="grid-column: 1/-1; text-align: center;">LOADING...</h2>';
            
            let url = `/api/events?page=${currentPage}&category=${currentCategory}`;
            if(searchQuery) {
                url += `&search=${encodeURIComponent(searchQuery)}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    renderEvents(data.data); // Laravel pagination wraps in data
                    updatePagination(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    list.innerHTML = '<h2 style="grid-column: 1/-1; text-align: center; color: var(--telkom-red);">ERROR LOADING EVENTS</h2>';
                });
        }

        function renderEvents(events) {
            const list = document.getElementById('event-list');
            list.innerHTML = '';

            if(!events || events.length === 0) {
                list.innerHTML = '<h2 style="grid-column: 1/-1; text-align: center;">NO EVENTS FOUND</h2>';
                return;
            }

            events.forEach(event => {
                // Parse Date for Badge
                const dateObj = new Date(event.date);
                const day = dateObj.getDate();
                const month = dateObj.toLocaleString('default', { month: 'short' }).toUpperCase();

                const html = `
                    <div class="neo-card neo-card-shadow-wrapper">
                        <div class="card-image-container">
                            <img src="${event.image || 'https://placehold.co/600x400'}" class="card-image" alt="${event.title}">
                            <div class="date-badge">
                                <span class="month">${month}</span>
                                <span class="day">${day}</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="tags">
                                <span class="tag-pill red">${event.category}</span>
                            </div>
                            <div class="card-title">${event.title}</div>
                            <div class="card-details">
                                <div>📍 ${event.location}</div>
                                <div>🕒 ${event.time}</div>
                            </div>
                        </div>
                        <a href="/events/${event.id}" class="card-action">
                            View Details
                        </a>
                    </div>
                `;
                list.innerHTML += html;
            });
        }

        function updatePagination(data) {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            prevBtn.disabled = !data.prev_page_url;
            nextBtn.disabled = !data.next_page_url;
        }
    </script>
</body>
</html>
