@props([
    'show' => false,
])

<div class="
        fixed inset-0 z-50
        transition
        {{ $show ? '' : 'pointer-events-none' }}
    ">

    {{-- BACKDROP --}}
    <div
        class="
            absolute inset-0 bg-black/40
            transition-opacity
            {{ $show ? 'opacity-100' : 'opacity-0' }}
        ">
    </div>

    {{-- PANEL --}}
    <div
        class="
            absolute right-0 top-0 h-full w-full max-w-xl
            bg-white shadow-2xl
            transition-transform
            dark:bg-slate-900
            {{ $show ? 'translate-x-0' : 'translate-x-full' }}
        ">

        {{ $slot }}

    </div>

</div>
