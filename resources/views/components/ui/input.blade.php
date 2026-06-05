@props([
    'label' => null,
    'type' => 'text',
])

<div class="space-y-2">

    {{-- LABEL --}}
    @if($label)

        <label
            class="block text-sm font-medium text-slate-700 dark:text-slate-300">

            {{ $label }}

        </label>

    @endif

    {{-- INPUT --}}
    <input
        type="{{ $type }}"

        {{ $attributes->merge([
            'class' => "
                w-full
                rounded-2xl
                border
                px-4
                py-3
                text-sm
                transition
                focus:outline-none
                focus:ring-4
                dark:text-white

                ".(
                    $errors->has($attributes->wire('model')->value())
                    ? '
                        border-red-300
                        bg-red-50
                        focus:ring-red-100
                    '
                    : '
                        border-slate-200
                        bg-slate-50
                        focus:border-primary
                        focus:ring-primary/10
                        dark:border-slate-700
                        dark:bg-slate-800
                    '
                )
        ]) }}
    >

    {{-- ERROR --}}
    @error($attributes->wire('model')->value())

        <div
            class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

            {{ $message }}

        </div>

    @enderror

</div>