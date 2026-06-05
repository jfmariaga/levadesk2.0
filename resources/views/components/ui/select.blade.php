@props([
    'label' => null,
])

<div class="space-y-2">

    @if($label)

        <label
            class="block text-sm font-medium text-slate-700 dark:text-slate-300">

            {{ $label }}

        </label>

    @endif

    <select
        {{ $attributes->merge([
            'class' => '
                w-full
                rounded-2xl
                border
                border-slate-200
                bg-slate-50
                px-4
                py-3
                text-sm
                text-slate-800
                focus:border-primary
                focus:outline-none
                focus:ring-4
                focus:ring-primary/10
                dark:border-slate-700
                dark:bg-slate-800
                dark:text-white
            '
        ]) }}>

        {{ $slot }}

    </select>

</div>