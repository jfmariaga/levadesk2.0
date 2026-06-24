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
            'label' => 'ANS',
        ],
    ]" />

    <x-admin.crud-toolbar title="ANS" buttonText="Nuevo ANS" />

    {{-- BUSCADOR --}}
    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar ANS..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    {{-- CONTADOR --}}
    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $ans->count() }}
            de
            {{ $ans->total() }}
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

                    Tipo Solicitud

                </th>

                <th class="px-4 py-3 text-left">

                    Nivel

                </th>

                <th class="px-4 py-3 text-left">

                    Horario

                </th>

                <th class="px-4 py-3 text-left">

                    Asignación

                </th>

                <th class="px-4 py-3 text-left">

                    Aceptación

                </th>

                <th class="px-4 py-3 text-left">

                    Resolución

                </th>

                <th class="px-4 py-3 text-right">

                    Acciones

                </th>

            </tr>

        </thead>

        <tbody>

            @forelse ($ans as $item)
                <tr wire:key="ans-{{ $item->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3 text-slate-500">

                        {{ $item->id }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $item->tipoSolicitud?->nombre }}

                    </td>

                    <td class="px-4 py-3">

                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs text-blue-700">

                            {{ $item->nivel }}

                        </span>

                    </td>

                    <td class="px-4 py-3">

                        {{ $item->h_atencion }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $this->formatearTiempo($item->t_asignacion_segundos) }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $this->formatearTiempo($item->t_aceptacion_segundos) }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $this->formatearTiempo($item->t_resolucion_segundos) }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            <button wire:click="edit({{ $item->id }})" type="button"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                ✏️

                            </button>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="py-12 text-center text-slate-500">

                        No se encontraron registros.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </x-admin.crud-table>

    <div class="mt-4">

        {{ $ans->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}" :title="$editingId ? 'Editar ANS' : 'Nuevo ANS'"
        subtitle="Configuración de acuerdos de nivel de servicio">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Configuración ANS

                </h3>

                <div class="space-y-4">

                    <x-ui.select wire:model.live="solicitud_id" label="Tipo Solicitud">

                        <option value="">
                            Seleccione...
                        </option>

                        @foreach ($tiposSolicitud as $tipo)
                            <option value="{{ $tipo->id }}">

                                {{ $tipo->nombre }}

                            </option>
                        @endforeach

                    </x-ui.select>

                    <x-ui.select wire:model.live="nivel" label="Nivel">

                        <option value="">
                            Seleccione...
                        </option>

                        @foreach ($niveles as $nivel)
                            <option value="{{ $nivel }}">

                                {{ $nivel }}

                            </option>
                        @endforeach

                    </x-ui.select>

                    <x-ui.select wire:model.live="h_atencion" label="Horario">

                        @foreach ($horarios as $horario)
                            <option value="{{ $horario }}">

                                {{ $horario }}

                            </option>
                        @endforeach

                    </x-ui.select>

                    <x-ui.input wire:model.live="t_asignacion" type="number" label="Tiempo Asignación (minutos)" />

                    <x-ui.input wire:model.live="t_aceptacion" type="number" label="Tiempo Aceptación (minutos)" />

                    <x-ui.input wire:model.live="t_resolucion" type="number" label="Tiempo Resolución (minutos)" />

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
