<div {{ $attributes->merge([
    'class' => 'rounded-2xl bg-white p-6 shadow-sm dark:bg-slate-900'
]) }}>
    {{ $slot }}
</div>