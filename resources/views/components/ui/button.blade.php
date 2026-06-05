@props([
    'variant' => 'primary'
])

@php
$classes = match($variant) {
    'secondary' => 'bg-slate-200 text-slate-800 hover:bg-slate-300',
    'danger' => 'bg-red-600 text-white hover:bg-red-700',
    default => 'bg-primary text-white hover:bg-primary-dark',
};
@endphp

<button {{
    $attributes->merge([
        'class' => "rounded-xl px-4 py-2 text-sm font-medium transition {$classes}"
    ])
}}>
    {{ $slot }}
</button>