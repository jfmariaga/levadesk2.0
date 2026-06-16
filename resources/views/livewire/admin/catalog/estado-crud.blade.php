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
            'label' => 'Estados',
        ],
    ]" />

    <x-admin.crud-toolbar title="Estados" buttonText="Nuevo Estado" />

    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar estado..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $estados->count() }}
            de
            {{ $estados->total() }}
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

                <th wire:click="sortBy('updated_at')" class="cursor-pointer px-4 py-3 text-left">

                    Actualizado

                </th>

                <th class="px-4 py-3 text-right">
                    Acciones
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse ($estados as $estado)
                <tr wire:key="estado-{{ $estado->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3 text-slate-500">

                        {{ $estado->id }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $estado->nombre }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $estado->descripcion }}

                    </td>

                    <td class="px-4 py-3 text-slate-500">

                        {{ $estado->updated_at?->format('d/m/Y') }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            <button wire:click="edit({{ $estado->id }})" type="button"
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

        {{ $estados->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            <h2 class="text-xl font-bold">

                {{ $editingId ? 'Editar' : 'Nuevo' }}
                Estado

            </h2>

            <x-ui.input wire:model.live="nombre" label="Nombre" />

            <x-ui.textarea wire:model.live="descripcion" label="Descripción" />

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
