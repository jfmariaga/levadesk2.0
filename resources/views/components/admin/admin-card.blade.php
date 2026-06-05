@props(['title', 'description', 'icon', 'href' => '#'])

<a href="{{ $href }}"
    class="block rounded-3xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:shadow-xl dark:border-slate-700 dark:bg-slate-900">

    <div class="flex items-start gap-4">

        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10">

            <img src="{{ $icon }}" alt="{{ $title }}" class="h-10 w-10 object-contain">

        </div>

        <div>

            <h3 class="text-lg font-semibold text-slate-800 dark:text-white">

                {{ $title }}

            </h3>

            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">

                {{ $description }}

            </p>

        </div>

    </div>

</a>
