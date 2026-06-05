<div
    {{ $attributes->merge([
        'class' => 'rounded-3xl border border-white/10 bg-white/90 p-8 shadow-2xl backdrop-blur-xl dark:bg-slate-900/90'
    ]) }}>

    {{ $slot }}

</div>