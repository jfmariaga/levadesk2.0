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
            'label' => 'Asignación Automática',
        ],
    ]" />

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-slate-800 dark:text-white">

            Asignación Automática

        </h1>

        <p class="mt-2 text-sm text-slate-500">

            Configura qué grupo atiende cada subcategoría
            por sociedad.

        </p>

    </div>

    {{-- SOCIEDAD --}}
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5">

        <x-ui.select wire:model.live="sociedadId" label="Sociedad">

            <option value="">

                Seleccione una sociedad

            </option>

            @foreach ($sociedades as $sociedad)
                <option value="{{ $sociedad->id }}">

                    {{ $sociedad->nombre }}

                </option>
            @endforeach

        </x-ui.select>

    </div>

    {{-- LAYOUT --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- ÁRBOL --}}
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="font-semibold text-slate-800">

                    Catálogo de Atención

                </h2>

            </div>

            <div class="max-h-[700px] overflow-y-auto p-5">

                @foreach ($categorias as $categoria)
                    <div class="mb-6">

                        <div class="mb-1 text-xs uppercase tracking-wider text-slate-400">

                            {{ $categoria->tipoSolicitud?->nombre }}

                        </div>

                        <div class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-700">

                            {{ $categoria->nombre }}

                        </div>

                        <div class="space-y-2">

                            @foreach ($categoria->subcategorias as $subcategoria)
                                <button wire:click="seleccionarSubcategoria({{ $subcategoria->id }})"
                                    class="
                                        flex w-full items-center justify-between rounded-xl border px-4 py-3 text-left transition
                                        {{ $subcategoriaId == $subcategoria->id ? 'border-primary bg-primary/5' : 'border-slate-200 hover:bg-slate-50' }}
                                    ">

                                    <span>

                                        {{ $subcategoria->nombre }}

                                    </span>

                                    @if ($subcategoriaId == $subcategoria->id)
                                        <span class="text-primary">

                                            ●

                                        </span>
                                    @endif

                                </button>
                            @endforeach

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        {{-- CONFIGURACIÓN --}}
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="font-semibold text-slate-800">

                    Configuración de Atención

                </h2>

            </div>

            <div class="p-5">

                @if (!$subcategoriaId)

                    <div class="py-20 text-center">

                        <div class="mb-4 text-5xl">

                            ⚙️

                        </div>

                        <div class="text-slate-500">

                            Seleccione una subcategoría
                            para comenzar.

                        </div>

                    </div>
                @else
                    <div class="space-y-6">

                        <x-ui.select wire:key="grupo-{{ $subcategoriaId }}" wire:model.live="grupoId"
                            label="Grupo Responsable">

                            <option value="">
                                Seleccione un grupo
                            </option>

                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->id }}">

                                    {{ $grupo->nombre }}

                                </option>
                            @endforeach

                        </x-ui.select>

                        <x-ui.select wire:key="supervisor-{{ $subcategoriaId }}" wire:model.live="supervisorId"
                            label="Supervisor Principal">

                            <option value="">
                                Seleccione supervisor
                            </option>

                            @foreach ($supervisores as $usuario)
                                <option value="{{ $usuario->id }}">

                                    {{ $usuario->nombre_completo }}

                                </option>
                            @endforeach

                        </x-ui.select>

                        <x-ui.select wire:key="supervisor2-{{ $subcategoriaId }}"
                            wire:model.live="supervisorSecundarioId" label="Supervisor Secundario">

                            <option value="">
                                Seleccione supervisor
                            </option>

                            @foreach ($supervisores as $usuario)
                                <option value="{{ $usuario->id }}">

                                    {{ $usuario->nombre_completo }}

                                </option>
                            @endforeach

                        </x-ui.select>

                        @if ($grupoId)

                            @php

                                $grupo = $grupos->firstWhere('id', $grupoId);

                            @endphp

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                                <div class="mb-3 text-sm font-semibold">

                                    Agentes del Grupo

                                </div>

                                <div class="space-y-2">

                                    @foreach ($grupo?->usuarios ?? [] as $usuario)
                                        <div class="flex items-center gap-3">

                                            <img src="{{ $usuario->foto_perfil }}" class="h-8 w-8 rounded-full">

                                            <div>

                                                <div class="text-sm font-medium">

                                                    {{ $usuario->nombre_completo }}

                                                </div>

                                                <div class="text-xs text-slate-500">

                                                    {{ $usuario->email }}

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        @endif

                        <div class="border-t border-slate-200 pt-4">

                            <button wire:click="guardar" class="rounded-xl bg-primary px-6 py-3 text-white">

                                Guardar Configuración

                            </button>

                        </div>

                    </div>

                @endif

            </div>

        </div>

        {{-- RESUMEN --}}
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="font-semibold text-slate-800">

                    Resumen

                </h2>

            </div>

            <div class="space-y-6 p-5">

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                    <div class="text-sm font-semibold">

                        Configuración

                    </div>

                    <div class="mt-3 text-sm">

                        Configuradas:

                        <span class="font-semibold text-green-600">

                            {{ $configuradas }}

                        </span>

                    </div>

                </div>

                @if ($subcategoriaSeleccionada)

                    <div class="rounded-2xl border border-slate-200 p-4">

                        <div class="mb-4 text-sm font-semibold">

                            Contexto

                        </div>

                        <div class="space-y-4">

                            <div>

                                <div class="text-xs uppercase text-slate-500">

                                    Tipo Solicitud

                                </div>

                                <div class="font-medium">

                                    {{ $subcategoriaSeleccionada->categoria?->tipoSolicitud?->nombre }}

                                </div>

                            </div>

                            <div>

                                <div class="text-xs uppercase text-slate-500">

                                    Categoría

                                </div>

                                <div class="font-medium">

                                    {{ $subcategoriaSeleccionada->categoria?->nombre }}

                                </div>

                            </div>

                            <div>

                                <div class="text-xs uppercase text-slate-500">

                                    Subcategoría

                                </div>

                                <div class="font-medium">

                                    {{ $subcategoriaSeleccionada->nombre }}

                                </div>

                            </div>

                            <div>

                                <div class="text-xs uppercase text-slate-500">

                                    Sociedad

                                </div>

                                <div class="font-medium">

                                    {{ $sociedades->firstWhere('id', $sociedadId)?->nombre }}

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="rounded-2xl border border-slate-200 p-4">

                        <div class="mb-3 text-sm font-semibold">

                            Estado

                        </div>

                        @if ($grupoId)
                            <div class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm text-green-700">

                                🟢 Configurado

                            </div>
                        @else
                            <div class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm text-red-700">

                                🔴 Pendiente

                            </div>
                        @endif

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>
