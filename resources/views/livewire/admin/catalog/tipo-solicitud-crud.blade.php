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
            'label' => 'Tipos de Solicitud',
        ],
    ]" />

    <x-admin.crud-toolbar title="Tipos de Solicitud" buttonText="Nuevo Tipo" />

    {{-- BUSCADOR --}}
    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar tipo de solicitud..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    {{-- CONTADOR --}}
    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $tipos->count() }}
            de
            {{ $tipos->total() }}
            registros

        </div>

        <div class="flex items-center gap-2">

            <span class="text-sm text-slate-500">
                Mostrar
            </span>

            <select wire:model.live="perPage"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium shadow-sm transition focus:border-primary focus:ring-2 focus:ring-primary/20">

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

                <th wire:click="sortBy('codigo')" class="cursor-pointer px-4 py-3 text-left">

                    Código

                </th>

                <th class="px-4 py-3 text-left">
                    Estado
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

            @forelse ($tipos as $tipo)
                <tr wire:key="tipo-{{ $tipo->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3 text-slate-500">
                        {{ $tipo->id }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $tipo->nombre }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $tipo->codigo }}
                    </td>

                    <td class="px-4 py-3">

                        @if ($tipo->estado == 0)
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">

                                Activo

                            </span>
                        @else
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs text-red-700">

                                Inactivo

                            </span>
                        @endif

                    </td>

                    <td class="px-4 py-3 text-slate-500">

                        {{ $tipo->updated_at?->format('d/m/Y') }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            <button wire:click="edit({{ $tipo->id }})"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                ✏️

                            </button>

                            <button wire:click="toggle({{ $tipo->id }})"
                                title="{{ $tipo->estado == 0 ? 'Desactivar' : 'Activar' }}"
                                class="rounded-lg px-3 py-2 transition
                                {{ $tipo->estado == 0
                                    ? 'bg-red-50 text-red-600 hover:bg-red-100'
                                    : 'bg-green-50 text-green-600 hover:bg-green-100' }}">

                                {{ $tipo->estado == 0 ? '🔒' : '🔓' }}

                            </button>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="py-12 text-center text-slate-500">

                        No se encontraron registros.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </x-admin.crud-table>

    <div class="mt-4">

        {{ $tipos->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            <h2 class="text-xl font-bold">

                {{ $editingId ? 'Editar' : 'Nuevo' }}
                Tipo de Solicitud

            </h2>

            <x-ui.input wire:model.live="nombre" label="Nombre" />

            <x-ui.input wire:model.live="codigo" label="Código" />

            <div class="flex gap-3">

                <button wire:click="save" type="button" class="rounded-xl bg-primary px-4 py-2 text-white">

                    Guardar

                </button>

                <button wire:click="closeDrawer" type="button" class="rounded-xl border px-4 py-2">

                    Cancelar

                </button>

            </div>

        </div>

    </x-admin.crud-drawer>

</div>
