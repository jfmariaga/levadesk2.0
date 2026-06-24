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
            'label' => 'Subcategorías',
        ],
    ]" />

    <x-admin.crud-toolbar title="Subcategorías" buttonText="Nueva Subcategoría" />

    {{-- BUSCADOR --}}
    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar subcategoría..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    {{-- CONTADOR --}}
    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $subcategorias->count() }}
            de
            {{ $subcategorias->total() }}
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

                <th class="px-4 py-3 text-left">
                    Categoría
                </th>

                <th class="px-4 py-3 text-left">
                    Nombre
                </th>

                <th class="px-4 py-3 text-left">
                    Código
                </th>

                <th class="px-4 py-3 text-left">
                    Estado
                </th>

                <th class="px-4 py-3 text-left">
                    Actualizado
                </th>

                <th class="px-4 py-3 text-right">
                    Acciones
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse ($subcategorias as $subcategoria)
                <tr wire:key="subcategoria-{{ $subcategoria->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3 text-slate-500">

                        {{ $subcategoria->id }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="font-medium">

                            {{ $subcategoria->categoria?->nombre }}

                        </div>

                        <div class="text-xs text-slate-500">

                            {{ $subcategoria->categoria?->tipoSolicitud?->nombre }}

                        </div>

                    </td>

                    <td class="px-4 py-3">

                        {{ $subcategoria->nombre }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $subcategoria->codigo }}

                    </td>

                    <td class="px-4 py-3">

                        @if ($subcategoria->estado == 0)
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

                        {{ $subcategoria->updated_at?->format('d/m/Y') }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            <button wire:click="edit({{ $subcategoria->id }})" type="button"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                ✏️

                            </button>

                            <button wire:click="toggle({{ $subcategoria->id }})" type="button"
                                title="{{ $subcategoria->estado == 0 ? 'Desactivar' : 'Activar' }}"
                                class="rounded-lg px-3 py-2 transition
                                {{ $subcategoria->estado == 0
                                    ? 'bg-red-50 text-red-600 hover:bg-red-100'
                                    : 'bg-green-50 text-green-600 hover:bg-green-100' }}">

                                {{ $subcategoria->estado == 0 ? '🔒' : '🔓' }}

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

        {{ $subcategorias->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}" :title="$editingId ? 'Editar Subcategoría' : 'Nueva Subcategoría'"
        subtitle="Configuración de subcategorías y categorías asociadas">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            {{-- INFORMACIÓN GENERAL --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Información General

                </h3>

                <div class="space-y-4">

                    <x-ui.select wire:model.live="categoria_id" label="Categoría">

                        <option value="">
                            Seleccione una categoría
                        </option>

                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">

                                [{{ $categoria->tipoSolicitud?->nombre }}]
                                {{ $categoria->nombre }}

                            </option>
                        @endforeach

                    </x-ui.select>

                    <x-ui.input wire:model.live="nombre" label="Nombre" />

                    <x-ui.input wire:model.live="codigo" label="Código" />

                    <x-ui.textarea wire:model.live="descripcion" label="Descripción" />

                </div>

            </div>

            {{-- ACCIONES --}}
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
