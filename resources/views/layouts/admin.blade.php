@extends('layouts.app')

@section('body')
    <div class="min-h-screen bg-slate-100 dark:bg-slate-950">

        <div class="bg-gradient-to-r from-[#0B0F3A] via-[#18004D] to-[#0B1E6D]">

            <x-layouts.app-header />

        </div>

        <div class="mx-auto max-w-7xl px-6 py-8">

            <div class="mb-8 flex items-center justify-between">

                <div>

                    <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                        Administración LevaDesk
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Configuración y parametrización del sistema
                    </p>

                </div>

            </div>

            {{ $slot }}

        </div>

    </div>
@endsection
