<!DOCTYPE html>
<html lang="es" class="h-full scroll-smooth">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>LevaDesk 2.0</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @livewireStyles

</head>

<body class="h-full">

<div
    class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-100 dark:bg-slate-950">

    {{-- BACKGROUND --}}
    <div
        class="absolute inset-0 bg-gradient-to-r from-[#0B0F3A] via-[#18004D] to-[#0B1E6D]">
    </div>

    {{-- GLOW --}}
    <div
        class="absolute inset-0 opacity-30 blur-3xl bg-[radial-gradient(circle_at_top_right,#ff4ecd,transparent_35%),radial-gradient(circle_at_bottom_left,#4f46e5,transparent_35%)]">
    </div>

    {{-- CONTENT --}}
    <div class="relative z-10 w-full max-w-md px-6">

        @yield('content')

        {{ $slot ?? '' }}

    </div>

</div>

@livewireScripts

</body>
</html>