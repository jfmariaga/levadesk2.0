@extends('layouts.auth')

@section('content')

<x-ui.auth-card>

    <div class="text-center">

        {{-- ICON --}}
        <div
            class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-3xl bg-primary/10 text-5xl">

            📩

        </div>

        {{-- TITLE --}}
        <h1
            class="text-3xl font-bold text-slate-800 dark:text-white">

            Revisa tu correo corporativo

        </h1>

        {{-- DESCRIPTION --}}
        <p
            class="mt-4 text-sm leading-relaxed text-slate-500 dark:text-slate-400">

            Tu cuenta fue creada correctamente.

            Hemos enviado un enlace de verificación a:

        </p>

        {{-- EMAIL --}}
        <div
            class="mt-4 rounded-2xl border border-primary/20 bg-primary/5 px-4 py-4 text-sm font-medium text-primary">

            {{ auth()->user()?->email }}

        </div>

        {{-- ALERT --}}
        <div
            class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-700">

            Debes verificar tu correo antes de iniciar sesión en LevaDesk.

        </div>

        {{-- EXTRA --}}
        <p
            class="mt-6 text-xs text-slate-400">

            Si no encuentras el correo revisa:
            spam, promociones o correo no deseado.

        </p>

        {{-- LOGIN --}}
        <div class="mt-8">

            <a
                href="{{ route('login') }}"
                class="inline-flex rounded-2xl bg-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-primary-dark">

                Ir al login

            </a>

        </div>

    </div>

</x-ui.auth-card>

@endsection