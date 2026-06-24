<div>

    <x-admin.breadcrumb :items="[
        [
            'label' => 'Portal',
            'route' => route('dashboard'),
        ],
        [
            'label' => 'Administración',
            'route' => route('admin.dashboard'),
        ],
        [
            'label' => 'Impactos',
        ],
    ]" />

    <x-admin.crud-toolbar title="Impactos" buttonText="Nueva Impacto" />

    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar Impacto..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $impactos->count() }}
            de
            {{ $impactos->total() }}
            registros

        </div>

        <div class="flex items-center gap-2">

            <span class="text-sm text-slate-500">
                Mostrar
            </span>

            <select wire:model.live="perPage"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium shadow-sm">

                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>

            </select>

            <span class="text-sm text-slate-500">
                registros
            </span>

        </div>

    </div>

    <x-admin.crud-table>

        <thead>

            <tr class="border-b bg-slate-50">

                <th class="w-20 px-4 py-3 text-left">
                    ID
                </th>

                <th wire:click="sortBy('nombre')" class="cursor-pointer px-4 py-3 text-left">

                    Nombre

                </th>

                <th wire:click="sortBy('puntuacion')" class="cursor-pointer px-4 py-3 text-left">

                    Puntuación

                </th>

                <th wire:click="sortBy('updated_at')" class="cursor-pointer px-4 py-3 text-left">

                    Actualizado

                </th>

                <th class="px-4 py-3 text-right">
                    Acciones
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse ($impactos as $impacto)
                <tr wire:key="Impacto-{{ $impacto->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3 text-slate-500">
                        {{ $impacto->id }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $impacto->nombre }}
                    </td>

                    <td class="px-4 py-3">

                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">

                            {{ $impacto->puntuacion }}

                        </span>

                    </td>

                    <td class="px-4 py-3 text-slate-500">
                        {{ $impacto->updated_at?->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            <button wire:click="edit({{ $impacto->id }})" type="button"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                ✏️

                            </button>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="py-12 text-center text-slate-500">

                        No se encontraron registros.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </x-admin.crud-table>

    <div class="mt-4">

        {{ $impactos->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}" :title="$editingId ? 'Editar Impacto' : 'Nuevo Impacto'"
        subtitle="Configuración de niveles de impacto">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Información General

                </h3>

                <div class="space-y-4">

                    <x-ui.input wire:model.live="nombre" label="Nombre" />

                    <x-ui.input wire:model.live="puntuacion" type="number" label="Puntuación" />

                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4">

                <button wire:click="closeDrawer" type="button" class="rounded-xl border border-slate-300 px-5 py-2">

                    Cancelar

                </button>

                <button wire:click="save" type="button" class="rounded-xl bg-primary px-5 py-2 text-white">

                    Guardar

                </button>

            </div>

        </div>

    </x-admin.crud-drawer>

</div>
