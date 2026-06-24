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
            'label' => 'Flujos de Terceros',
        ],
    ]" />

    <x-admin.crud-toolbar title="Flujos de Terceros" buttonText="Nuevo Flujo" />

    {{-- BUSCADOR --}}
    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar aplicación o tercero..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    {{-- CONTADOR --}}
    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $flujos->count() }}
            de
            {{ $flujos->total() }}
            registros

        </div>

        <div class="flex items-center gap-2">

            <span class="text-sm text-slate-500">

                Mostrar

            </span>

            <select wire:model.live="perPage" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm">

                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>

            </select>

        </div>

    </div>

    <x-admin.crud-table>

        <thead>

            <tr class="border-b bg-slate-50">

                <th class="px-4 py-3 text-left">
                    Sociedad
                </th>

                <th class="px-4 py-3 text-left">
                    Aplicación
                </th>

                <th class="px-4 py-3 text-left">
                    Tercero
                </th>

                <th class="px-4 py-3 text-left">
                    Responsable
                </th>

                <th class="px-4 py-3 text-left">
                    Destinatarios
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

            @forelse ($flujos as $flujo)

                @php

                    $correos = collect(json_decode($flujo->destinatarios ?? '[]', true));

                @endphp

                <tr wire:key="flujo-{{ $flujo->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3">

                        {{ $flujo->aplicacion?->sociedad?->nombre }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $flujo->aplicacion?->nombre }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $flujo->tercero?->nombre }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $flujo->usuario?->nombre_completo }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex flex-wrap gap-1">

                            @foreach ($correos->take(3) as $correo)
                                <span class="rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-700">

                                    {{ $correo }}

                                </span>
                            @endforeach

                            @if ($correos->count() > 3)
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-700">

                                    +{{ $correos->count() - 3 }}

                                </span>
                            @endif

                        </div>

                    </td>

                    <td class="px-4 py-3">

                        @if ($flujo->activo)
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

                            <button wire:click="edit({{ $flujo->id }})"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600">

                                ✏️

                            </button>

                            <button wire:click="toggle({{ $flujo->id }})"
                                class="
                                    rounded-lg px-3 py-2
                                    {{ $flujo->activo ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}
                                ">

                                {{ $flujo->activo ? '🔒' : '🔓' }}

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

        {{ $flujos->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}" :title="$editingId ? 'Editar Flujo' : 'Nuevo Flujo'"
        subtitle="Configuración de atención con terceros">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Información General

                </h3>

                <div class="space-y-4">

                    <x-ui.select wire:model.live="aplicacion_id" label="Aplicación">

                        <option value="">
                            Seleccione...
                        </option>

                        @foreach ($aplicaciones as $aplicacion)
                            <option value="{{ $aplicacion->id }}">

                                {{ $aplicacion->nombre }}

                            </option>
                        @endforeach

                    </x-ui.select>

                    <x-ui.select wire:model.live="tercero_id" label="Tercero">

                        <option value="">
                            Seleccione...
                        </option>

                        @foreach ($terceros as $tercero)
                            <option value="{{ $tercero->id }}">

                                {{ $tercero->nombre }}

                            </option>
                        @endforeach

                    </x-ui.select>

                    <x-ui.select wire:model.live="usuario_id" label="Responsable Interno">

                        <option value="">
                            Seleccione...
                        </option>

                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">

                                {{ $usuario->nombre_completo }}

                            </option>
                        @endforeach

                    </x-ui.select>

                    {{-- DESTINATARIOS --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">

                            Destinatarios

                        </label>

                        <div class="flex gap-2">

                            <input wire:model="nuevoCorreo" wire:keydown.enter.prevent="agregarCorreo" type="email"
                                placeholder="correo@empresa.com"
                                class="flex-1 rounded-xl border border-slate-200 px-4 py-3">

                            <button wire:click="agregarCorreo" type="button"
                                class="rounded-xl bg-primary px-4 py-3 text-white">

                                Agregar

                            </button>

                        </div>

                        <div wire:key="correos-{{ md5(json_encode($correos)) }}" class="mt-4 flex flex-wrap gap-2">

                        <div wire:key="correos-lista-{{ md5(json_encode($this->correos)) }}"
                            class="mt-4 flex flex-wrap gap-2">

                            @foreach ($this->correos as $correo)
                                <div wire:key="correo-{{ md5($correo) }}"
                                    class="flex items-center gap-2 rounded-full bg-blue-100 px-3 py-2 text-sm text-blue-700">

                                    {{ $correo }}

                                    <button wire:click="eliminarCorreo('{{ base64_encode($correo) }}')" type="button"
                                        class="font-bold">

                                        ✕

                                    </button>

                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>

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
