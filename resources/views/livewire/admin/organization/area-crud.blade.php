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
            'label' => 'Áreas',
        ],
    ]" />

    <x-admin.crud-toolbar title="Áreas" buttonText="Nueva Área" />

    {{-- BUSCADOR --}}
    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar área, código o sociedad..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    {{-- FILTROS --}}
    <div class="mb-4 grid gap-4 md:grid-cols-2">

        <div>

            <label class="mb-2 block text-sm font-medium text-slate-700">

                Sociedad

            </label>

            <select wire:model.live="filtroSociedad" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                <option value="">

                    Todas las sociedades

                </option>

                @foreach ($sociedades as $sociedad)
                    <option value="{{ $sociedad->id }}">

                        {{ $sociedad->nombre }}

                    </option>
                @endforeach

            </select>

        </div>

        <div>

            <label class="mb-2 block text-sm font-medium text-slate-700">

                Estado

            </label>

            <select wire:model.live="filtroActivo" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                <option value="">

                    Todos los estados

                </option>

                <option value="1">

                    Activo

                </option>

                <option value="0">

                    Inactivo

                </option>

            </select>

        </div>

    </div>

    {{-- CONTADOR --}}
    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $areas->count() }}
            de
            {{ $areas->total() }}
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

                    Área

                </th>

                <th wire:click="sortBy('codigo')" class="cursor-pointer px-4 py-3 text-left">

                    Código

                </th>

                <th wire:click="sortBy('sociedad_id')" class="cursor-pointer px-4 py-3 text-left">

                    Sociedad

                </th>

                <th wire:click="sortBy('activo')" class="cursor-pointer px-4 py-3 text-left">

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

            @forelse ($areas as $area)
                <tr wire:key="area-{{ $area->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3 text-slate-500">

                        {{ $area->id }}

                    </td>

                    <td class="px-4 py-3 font-medium">

                        {{ $area->nombre }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $area->codigo ?? 'Sin código' }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $area->sociedad?->nombre ?? 'Sin sociedad' }}

                    </td>

                    <td class="px-4 py-3">

                        @if ($area->activo)
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

                        {{ $area->updated_at?->format('d/m/Y') }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            <button wire:click="edit({{ $area->id }})" type="button"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                ✏️

                            </button>

                            <button wire:click="toggle({{ $area->id }})" type="button"
                                title="{{ $area->activo ? 'Desactivar' : 'Activar' }}"
                                class="rounded-lg px-3 py-2 transition
                                {{ $area->activo
                                    ? 'bg-red-50 text-red-600 hover:bg-red-100'
                                    : 'bg-green-50 text-green-600 hover:bg-green-100' }}">

                                {{ $area->activo ? '🔒' : '🔓' }}

                            </button>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="py-12 text-center text-slate-500">

                        No se encontraron registros.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </x-admin.crud-table>

    <div class="mt-4">

        {{ $areas->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}" :title="$editingId ? 'Editar Área' : 'Nueva Área'"
        subtitle="Configuración de áreas por sociedad">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Información General

                </h3>

                <div class="space-y-4">

                    <x-ui.select wire:model.live="sociedad_id" label="Sociedad">

                        <option value="">
                            Sin sociedad
                        </option>

                        @foreach ($sociedades as $sociedad)
                            <option value="{{ $sociedad->id }}">

                                {{ $sociedad->nombre }}

                            </option>
                        @endforeach

                    </x-ui.select>

                    @error('sociedad_id')
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                            {{ $message }}

                        </div>
                    @enderror

                    <x-ui.input wire:model.live="nombre" label="Nombre" />

                    <x-ui.input wire:model.live="codigo" label="Código" />

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">

                            Estado

                        </label>

                        <select wire:model.live="activo" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                            <option value="1">

                                Activo

                            </option>

                            <option value="0">

                                Inactivo

                            </option>

                        </select>

                        @error('activo')
                            <div class="mt-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                                {{ $message }}

                            </div>
                        @enderror

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
