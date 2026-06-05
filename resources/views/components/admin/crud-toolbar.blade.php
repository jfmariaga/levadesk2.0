@props(['title', 'buttonText' => 'Nuevo'])

<div class="mb-6 flex items-center justify-between">

    <div>

        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

            {{ $title }}

        </h2>

    </div>

    <button type="button" wire:click="create" class="rounded-2xl bg-primary px-4 py-2 text-sm font-medium text-white">

        + {{ $buttonText }}

    </button>

</div>
