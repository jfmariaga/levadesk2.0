@props([
    'label' => null,
])

<div>

    @if ($label)
        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

            {{ $label }}

        </label>
    @endif

    <textarea {{ $attributes }} rows="4"
        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none dark:border-slate-700 dark:bg-slate-900">

    </textarea>

</div>
