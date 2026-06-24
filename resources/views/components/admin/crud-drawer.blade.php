@props([
    'show' => false,
    'title' => null,
    'subtitle' => null,
])

<div class="
        fixed inset-0 z-50
        transition
        {{ $show ? '' : 'pointer-events-none' }}
    ">

    {{-- BACKDROP --}}
    <div
        class="
            absolute inset-0 bg-black/40 backdrop-blur-sm
            transition-opacity
            {{ $show ? 'opacity-100' : 'opacity-0' }}
        ">
    </div>

    {{-- PANEL --}}
    <div
        class="
            absolute right-0 top-0
            h-full w-full max-w-2xl
            overflow-y-auto
            bg-slate-50
            shadow-2xl
            transition-transform
            dark:bg-slate-900
            {{ $show ? 'translate-x-0' : 'translate-x-full' }}
        ">

        {{-- HEADER --}}
        @if ($title)

            <div
                class="sticky top-0 z-10 border-b border-slate-200 bg-white px-6 py-5 dark:border-slate-800 dark:bg-slate-900">

                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                    {{ $title }}

                </h2>

                @if ($subtitle)
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                        {{ $subtitle }}

                    </p>
                @endif

            </div>

        @endif

        {{-- BODY --}}
        <div class="p-6">

            {{ $slot }}

        </div>

    </div>

</div>
