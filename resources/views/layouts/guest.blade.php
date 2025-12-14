<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EventU - @yield('title', 'Guest')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Courier+Prime:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                        mono: ['Courier Prime', 'monospace'],
                    },
                    colors: {
                        'industrial-red': '#b02120', // Telkom University Maroon
                        'telkom-red': '#b02120',
                        'industrial-black': '#000000',
                        'industrial-white': '#FFFFFF',
                        'telkom-gray': '#333333',
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans text-industrial-black overflow-x-hidden m-0 p-0">

    <!-- Background: Technical Graph Paper -->
    <div
        class="min-h-screen w-full bg-[#f2f2f2] bg-[radial-gradient(#000000_1px,transparent_1px)] [background-size:20px_20px] flex items-center justify-center p-6">

        <!-- MAIN LAYOUT CONTENT -->
        <div class="w-full flex justify-center">
            @yield('content')
        </div>
    </div>

</body>

</html>
