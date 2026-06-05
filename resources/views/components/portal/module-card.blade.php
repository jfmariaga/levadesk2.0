@props([
    'title',
    'description',
    'icon',
    'href' => null,
])

@if($href)

<a
    href="{{ $href }}"
    class="group block rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-2xl dark:border-slate-800 dark:bg-slate-900">

@else

<div
    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-2xl dark:border-slate-800 dark:bg-slate-900">

@endif

    <div class="flex items-start gap-5">

        {{-- ICON --}}
        <div
            class="flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">

            <img
                src="{{ $icon }}"
                alt="{{ $title }}"
                class="h-14 w-14 object-contain"
            >

        </div>

        {{-- CONTENT --}}
        <div class="flex-1">

            <h3
                class="text-lg font-semibold text-slate-800 dark:text-white">

                {{ $title }}

            </h3>

            <p
                class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">

                {{ $description }}

            </p>

        </div>

    </div>

@if($href)

</a>

@else

</div>

@endif