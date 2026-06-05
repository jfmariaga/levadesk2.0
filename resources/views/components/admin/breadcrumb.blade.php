@props([
    'items' => []
])

<nav
    class="mb-6 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">

    @foreach($items as $item)

        @if(!$loop->first)

            <span>/</span>

        @endif

        @if(isset($item['route']))

            <a
                href="{{ $item['route'] }}"
                class="transition hover:text-primary">

                {{ $item['label'] }}

            </a>

        @else

            <span
                class="font-medium text-slate-700 dark:text-slate-200">

                {{ $item['label'] }}

            </span>

        @endif

    @endforeach

</nav>