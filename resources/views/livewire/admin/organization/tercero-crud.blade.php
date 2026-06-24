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
            'label' => 'Terceros',
        ],
    ]" />

    <x-admin.crud-toolbar title="Terceros" buttonText="Nuevo Tercero" />

    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar tercero..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    <x-admin.crud-table>

        <thead>

            <tr class="border-b bg-slate-50">

                <th class="px-4 py-3 text-left">
                    Nombre
                </th>

                <th class="px-4 py-3 text-left">
                    Descripción
                </th>

                <th class="px-4 py-3 text-left">
                    Estado
                </th>

                <th class="px-4 py-3 text-right">
                    Acciones
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach ($terceros as $tercero)
                <tr wire:key="tercero-{{ $tercero->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3">

                        {{ $tercero->nombre }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $tercero->descripcion }}

                    </td>

                    <td class="px-4 py-3">

                        @if ($tercero->activo)
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">

                                Activo

                            </span>
                        @else
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs text-red-700">

                                Inactivo

                            </span>
                        @endif

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            <button wire:click="edit({{ $tercero->id }})"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600">

                                ✏️

                            </button>

                            <button wire:click="toggle({{ $tercero->id }})"
                                class="rounded-lg px-3 py-2
                                {{ $tercero->activo ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">

                                {{ $tercero->activo ? '🔒' : '🔓' }}

                            </button>

                        </div>

                    </td>

                </tr>
            @endforeach

        </tbody>

    </x-admin.crud-table>

    <div class="mt-4">

        {{ $terceros->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}" :title="$editingId ? 'Editar Tercero' : 'Nuevo Tercero'"
        subtitle="Configuración de proveedores externos">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Información General

                </h3>

                <div class="space-y-4">

                    <x-ui.input wire:model.live="nombre" label="Nombre" />

                    <x-ui.textarea wire:model.live="descripcion" label="Descripción" />

                    <x-ui.select wire:model.live="activo" label="Estado">

                        <option value="1">
                            Activo
                        </option>

                        <option value="0">
                            Inactivo
                        </option>

                    </x-ui.select>

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
