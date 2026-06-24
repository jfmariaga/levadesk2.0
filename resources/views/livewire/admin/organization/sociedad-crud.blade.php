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
            'label' => 'Sociedades',
        ],
    ]" />

    <x-admin.crud-toolbar title="Sociedades" buttonText="Nueva Sociedad" />

    {{-- BUSCADOR --}}
    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar sociedad..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    {{-- CONTADOR --}}
    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $sociedades->count() }}
            de
            {{ $sociedades->total() }}
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

            @forelse ($sociedades as $sociedad)
                <tr wire:key="sociedad-{{ $sociedad->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3 text-slate-500">

                        {{ $sociedad->id }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $sociedad->nombre }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $sociedad->codigo }}

                    </td>

                    <td class="px-4 py-3">

                        @if ($sociedad->estado == 0)
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

                        {{ $sociedad->updated_at?->format('d/m/Y') }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            {{-- EDITAR --}}
                            <button wire:click="edit({{ $sociedad->id }})" type="button"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                ✏️

                            </button>

                            {{-- ESTADO --}}
                            <button wire:click="toggle({{ $sociedad->id }})" type="button"
                                title="{{ $sociedad->estado == 0 ? 'Desactivar' : 'Activar' }}"
                                class="rounded-lg px-3 py-2 transition
                                {{ $sociedad->estado == 0
                                    ? 'bg-red-50 text-red-600 hover:bg-red-100'
                                    : 'bg-green-50 text-green-600 hover:bg-green-100' }}">

                                {{ $sociedad->estado == 0 ? '🔒' : '🔓' }}

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

        {{ $sociedades->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}" :title="$editingId ? 'Editar Sociedad' : 'Nueva Sociedad'"
        subtitle="Información general de la sociedad">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Información General

                </h3>

                <div class="space-y-4">

                    <x-ui.input wire:model.live="nombre" label="Nombre" />

                    <x-ui.input wire:model.live="codigo" label="Código" />

                    <x-ui.textarea wire:model.live="descripcion" label="Descripción" />

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">

                            Estado

                        </label>

                        <select wire:model.live="estado" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                            <option value="0">
                                Activo
                            </option>

                            <option value="1">
                                Inactivo
                            </option>

                        </select>

                    </div>

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
