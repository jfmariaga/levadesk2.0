@extends('layouts.app')

@section('body')
    <div class="min-h-screen bg-slate-100 dark:bg-slate-950">

        {{-- HERO --}}
        <section class="relative overflow-hidden bg-gradient-to-r from-[#0B0F3A] via-[#18004D] to-[#0B1E6D]">

            {{-- GLOW --}}
            <div
                class="absolute inset-0 opacity-40 blur-3xl bg-[radial-gradient(circle_at_top_right,#ff4ecd,transparent_35%),radial-gradient(circle_at_bottom_left,#4f46e5,transparent_35%)]">
            </div>

            <div class="relative z-10">

                {{-- TOPBAR --}}
                <x-layouts.app-header />

                {{-- SEARCH --}}
                <x-layouts.portal-hero />

            </div>

        </section>

        {{-- CONTENT --}}
        <main class="relative z-20 mt-12 px-6 pb-12">

            @yield('content')

        </main>

    </div>
@endsection
