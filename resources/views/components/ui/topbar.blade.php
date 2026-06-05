<header
    class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-6 dark:border-slate-800 dark:bg-slate-900">

    {{-- LEFT --}}
    <div class="flex items-center gap-4">

        <button
            class="rounded-xl p-2 transition hover:bg-slate-100 dark:hover:bg-slate-800">

            ☰

        </button>

        <h1
            class="text-lg font-semibold text-slate-800 dark:text-white">

            LevaDesk 2.0

        </h1>

    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-4">

        {{-- SEARCH --}}
        <div class="hidden lg:block">

            <input
                type="text"
                placeholder="Buscar..."
                class="w-72 rounded-2xl border border-slate-200 bg-slate-100 px-4 py-2 text-sm focus:border-primary focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >

        </div>

        {{-- DARK MODE --}}
        <button
            x-data
            @click="document.documentElement.classList.toggle('dark')"
            class="rounded-xl p-2 transition hover:bg-slate-100 dark:hover:bg-slate-800">

            🌙

        </button>

        {{-- USER MENU --}}
        <div class="relative" x-data="{ open: false }">

            {{-- BUTTON --}}
            <button
                @click="open = !open"
                class="flex items-center gap-3 rounded-2xl px-3 py-2 transition hover:bg-slate-100 dark:hover:bg-slate-800">

                {{-- AVATAR --}}
                <img
                    src="{{ auth()->user()->foto_perfil }}"
                    class="h-10 w-10 rounded-full object-cover"
                >

                {{-- INFO --}}
                <div class="hidden text-left md:block">

                    <div
                        class="text-sm font-semibold text-slate-800 dark:text-white">

                        {{ auth()->user()->nombre_completo }}

                    </div>

                    <div
                        class="text-xs text-slate-500 dark:text-slate-400">

                        {{ auth()->user()->roles->first()?->name }}

                    </div>

                </div>

            </button>

            {{-- DROPDOWN --}}
            <div
                x-show="open"
                @click.outside="open = false"
                x-transition
                class="absolute right-0 z-50 mt-2 w-64 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl dark:border-slate-700 dark:bg-slate-900">

                {{-- USER --}}
                <div
                    class="border-b border-slate-200 px-4 py-3 dark:border-slate-700">

                    <div
                        class="font-semibold text-slate-800 dark:text-white">

                        {{ auth()->user()->nombre_completo }}

                    </div>

                    <div
                        class="text-sm text-slate-500 dark:text-slate-400">

                        {{ auth()->user()->email }}

                    </div>

                </div>

                {{-- LINKS --}}
                <div class="py-2">

                    <a
                        href="#"
                        class="flex items-center rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">

                        Mi perfil

                    </a>

                    <a
                        href="#"
                        class="flex items-center rounded-xl px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">

                        Configuración

                    </a>

                </div>

                {{-- LOGOUT --}}
                <div
                    class="border-t border-slate-200 pt-2 dark:border-slate-700">

                    <form
                        method="POST"
                        action="{{ route('logout') }}">

                        @csrf

                        <button
                            type="submit"
                            class="flex w-full items-center rounded-xl px-4 py-3 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:hover:bg-red-500/10">

                            Cerrar sesión

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</header>