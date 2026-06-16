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
            'label' => 'Grupos',
        ],
    ]" />

    <x-admin.crud-toolbar title="Grupos" buttonText="Nuevo Grupo" />

    {{-- BUSCADOR --}}
    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar grupo..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    {{-- CONTADOR --}}
    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $grupos->count() }}
            de
            {{ $grupos->total() }}
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

                <th class="px-4 py-3 text-left">
                    Descripción
                </th>

                <th class="px-4 py-3 text-left">
                    Usuarios
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

            @forelse ($grupos as $grupo)

                <tr wire:key="grupo-{{ $grupo->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3 text-slate-500">

                        {{ $grupo->id }}

                    </td>

                    <td class="px-4 py-3 font-medium">

                        {{ $grupo->nombre }}

                    </td>

                    <td class="px-4 py-3 text-slate-600">

                        {{ $grupo->descripcion }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="space-y-1">

                            @foreach ($grupo->usuarios->take(3) as $usuario)
                                <div class="text-sm">

                                    {{ $usuario->nombre_completo }}

                                </div>
                            @endforeach

                            @if ($grupo->usuarios->count() > 3)
                                <div class="text-xs text-slate-500">

                                    +{{ $grupo->usuarios->count() - 3 }}
                                    usuarios más

                                </div>
                            @endif

                        </div>

                    </td>

                    <td class="px-4 py-3 text-slate-500">

                        {{ $grupo->updated_at?->format('d/m/Y') }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            <button wire:click="edit({{ $grupo->id }})" type="button"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                ✏️

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

        {{ $grupos->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}">

        <div class="space-y-6 p-6" wire:key="form-{{ $editingId ?? 'new' }}">

            {{-- HEADER --}}
            <div class="border-b border-slate-200 pb-4">

                <h2 class="text-2xl font-bold text-slate-800">

                    {{ $editingId ? 'Editar' : 'Nuevo' }}
                    Grupo

                </h2>

                <p class="mt-1 text-sm text-slate-500">

                    Configuración del grupo y asignación de usuarios.

                </p>

            </div>

            {{-- INFORMACIÓN GENERAL --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Información General

                </h3>

                <div class="space-y-4">

                    <x-ui.input wire:model.live="nombre" label="Nombre" />

                    <x-ui.textarea wire:model.live="descripcion" label="Descripción" />

                </div>

            </div>

            {{-- USUARIOS --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Usuarios Asignados

                </h3>

                <div class="max-h-80 space-y-2 overflow-y-auto">

                    @foreach ($usuarios as $usuario)
                        <label
                            class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 transition hover:bg-slate-50">

                            <input type="checkbox" wire:model.live="usuariosSeleccionados" value="{{ $usuario->id }}"
                                class="rounded border-slate-300 text-primary">

                            <div>

                                <div class="text-sm font-medium text-slate-800">

                                    {{ $usuario->nombre_completo }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $usuario->email }}

                                </div>

                            </div>

                        </label>
                    @endforeach

                </div>

            </div>

            {{-- FOOTER --}}
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
