<header class="flex items-center justify-between px-6 py-5">

    {{-- LEFT --}}
    <div class="flex items-center gap-3">

        {{-- LOGO --}}
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-500/10">

            <img src="{{ asset('images/branding/logo-icon.ico') }}">

        </div>

        {{-- TITLE --}}
        <div>

            <h1 class="text-4xl font-bold tracking-tight text-white">

                Bienvenido,
                {{ auth()->user()->nombre_completo }}

            </h1>

            <p class="text-sm text-slate-300">

                {{ auth()->user()->sociedad?->nombre }}
                ·
                {{ auth()->user()->area_id
                    ? auth()->user()->areaRelacion?->nombre
                    : auth()->user()->area }}

            </p>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-4">

        {{-- DARK MODE --}}
        <button
            x-data
            @click="document.documentElement.classList.toggle('dark')"
            class="rounded-xl p-2 text-yellow-400 transition hover:bg-white/10">

            🌙

        </button>

        {{-- NOTIFICATIONS --}}
        <button class="relative text-yellow-400">

            🔔

            <span
                class="absolute -right-1 -top-1 h-2 w-2 rounded-full bg-red-500">
            </span>

        </button>

        {{-- USER MENU --}}
        <div
            class="relative"
            x-data="{ open: false }">

            <button
                @click="open = !open"
                class="rounded-full border-2 border-white/20 transition hover:border-white/50">

                <img
                    src="{{ auth()->user()->foto_perfil }}"
                    class="h-12 w-12 rounded-full object-cover">

            </button>

            <div
                x-show="open"
                @click.outside="open = false"
                x-transition
                class="absolute right-0 z-50 mt-3 w-72 overflow-hidden rounded-3xl border border-white/10 bg-white/95 shadow-2xl backdrop-blur-xl dark:bg-slate-900/95">

                <div class="px-5 py-5">

                    <div>

                        <div class="text-sm font-semibold text-slate-800 dark:text-white">

                            {{ auth()->user()->nombre_completo }}

                        </div>

                        <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">

                            {{ auth()->user()->email }}

                        </div>

                        <div class="mt-3 flex items-center justify-between">

                            <span
                                class="inline-flex rounded-full bg-primary/10 px-3 py-1 text-[11px] font-medium text-primary">

                                {{ auth()->user()->roles->first()?->name }}

                            </span>

                            <a href="{{ route('perfil') }}"
                                class="rounded-full bg-white px-3 py-1 text-[11px] font-medium text-primary transition hover:bg-primary hover:text-white">

                                Mi perfil

                            </a>

                        </div>

                        <div class="mt-3 flex justify-end">

                            <form
                                method="POST"
                                action="{{ route('logout') }}">

                                @csrf

                                <button
                                    type="submit"
                                    class="rounded-full bg-red-500/10 px-3 py-1 text-[11px] font-medium text-red-500 transition hover:bg-red-500 hover:text-white">

                                    Cerrar sesión

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</header>
