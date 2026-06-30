<div class="mx-auto max-w-6xl">

    <x-admin.breadcrumb :items="[
        [
            'label' => 'Portal',
            'route' => route('dashboard'),
        ],
        [
            'label' => 'Perfil',
        ],
    ]" />

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- RESUMEN --}}
        <aside class="lg:col-span-1">

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                <div class="h-3 bg-primary"></div>

                <div class="p-6 text-center">

                    <img src="{{ $profile_photo ? $profile_photo->temporaryUrl() : $usuario->foto_perfil }}"
                        class="mx-auto h-32 w-32 rounded-full border-4 border-white object-cover shadow-lg">

                    <h2 class="mt-5 text-xl font-bold text-slate-800 dark:text-white">

                        {{ $usuario->nombre_completo }}

                    </h2>

                    <div class="mt-2 text-sm text-slate-500">

                        {{ $usuario->email }}

                    </div>

                    <div class="mt-4 flex flex-wrap justify-center gap-2">

                        @foreach ($usuario->roles as $rol)
                            <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary">

                                {{ $rol->name }}

                            </span>
                        @endforeach

                        @if ($usuario->en_vacaciones)
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">

                                En vacaciones

                            </span>
                        @endif

                    </div>

                    <div class="mt-6 space-y-3 rounded-2xl bg-slate-50 p-4 text-left text-sm dark:bg-slate-800">

                        <div>

                            <div class="text-xs uppercase tracking-wider text-slate-400">

                                Sociedad

                            </div>

                            <div class="mt-1 font-medium text-slate-700 dark:text-slate-200">

                                {{ $usuario->sociedad?->nombre ?? 'Sin sociedad' }}

                            </div>

                        </div>

                        <div>

                            <div class="text-xs uppercase tracking-wider text-slate-400">

                                Área

                            </div>

                            <div class="mt-1 font-medium text-slate-700 dark:text-slate-200">

                                {{ $usuario->area_nombre }}

                            </div>

                        </div>

                        <div>

                            <div class="text-xs uppercase tracking-wider text-slate-400">

                                Grupos

                            </div>

                            <div class="mt-1 font-medium text-slate-700 dark:text-slate-200">

                                {{ $usuario->grupos->pluck('nombre')->join(', ') ?: 'Sin grupos' }}

                            </div>

                        </div>

                    </div>

                    @if ($usuario->en_vacaciones)
                        <button wire:click="volverDelTrabajo" type="button"
                            class="mt-5 w-full rounded-2xl border border-green-300 px-4 py-3 text-sm font-medium text-green-700 transition hover:bg-green-50">

                            Regresar al trabajo

                        </button>
                    @endif

                </div>

            </div>

        </aside>

        {{-- CONTENIDO --}}
        <section class="lg:col-span-2">

            <div class="mb-6 flex flex-wrap gap-2">

                <button wire:click="setActiveSection('profile')" type="button"
                    class="rounded-2xl px-4 py-2 text-sm font-medium transition
                    {{ $activeSection === 'profile'
                        ? 'bg-primary text-white'
                        : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">

                    Perfil

                </button>

                <button wire:click="setActiveSection('password')" type="button"
                    class="rounded-2xl px-4 py-2 text-sm font-medium transition
                    {{ $activeSection === 'password'
                        ? 'bg-primary text-white'
                        : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">

                    Contraseña

                </button>

                @if ($puedeGestionarVacaciones)
                    <button wire:click="setActiveSection('vacaciones')" type="button"
                        class="rounded-2xl px-4 py-2 text-sm font-medium transition
                        {{ $activeSection === 'vacaciones'
                            ? 'bg-primary text-white'
                            : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">

                        Vacaciones

                    </button>
                @endif

            </div>

            @if ($activeSection === 'profile')
                <form wire:submit.prevent="updateProfile" class="space-y-6">

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

                        <h3 class="mb-5 text-lg font-semibold text-slate-800 dark:text-white">

                            Datos Personales

                        </h3>

                        <div class="space-y-4">

                            <div class="grid gap-4 md:grid-cols-2">

                                <x-ui.input wire:model.live="name" label="Nombres" />

                                <x-ui.input wire:model.live="last_name" label="Apellidos" />

                            </div>

                            <x-ui.input wire:model.live="email" type="email" label="Correo corporativo" />

                            <div class="grid gap-4 md:grid-cols-2">

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700">

                                        Área relacionada

                                    </label>

                                    <select wire:model.live="area_id"
                                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm">

                                        <option value="">
                                            Sin área relacionada
                                        </option>

                                        @foreach ($areas as $areaItem)
                                            <option value="{{ $areaItem->id }}">

                                                {{ $areaItem->nombre }}

                                            </option>
                                        @endforeach

                                    </select>

                                    @error('area_id')
                                        <div class="mt-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>

                                <x-ui.input wire:model.live="area" label="Área legacy" />

                            </div>

                            <div>

                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                    Foto de perfil

                                </label>

                                <input wire:model="profile_photo" type="file" accept="image/png,image/jpeg"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white">

                                @error('profile_photo')
                                    <div class="mt-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                                        {{ $message }}

                                    </div>
                                @enderror

                                <div wire:loading wire:target="profile_photo" class="mt-2 text-sm text-slate-500">

                                    Cargando imagen...

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="flex justify-end">

                        <button type="submit" class="rounded-2xl bg-primary px-6 py-3 text-sm font-medium text-white">

                            Guardar Perfil

                        </button>

                    </div>

                </form>
            @endif

            @if ($activeSection === 'password')
                <form wire:submit.prevent="updatePassword" class="space-y-6">

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

                        <h3 class="mb-5 text-lg font-semibold text-slate-800 dark:text-white">

                            Cambiar Contraseña

                        </h3>

                        <div class="space-y-4">

                            <x-ui.input wire:model.live="current_password" type="password" label="Contraseña actual" />

                            <div class="grid gap-4 md:grid-cols-2">

                                <x-ui.input wire:model.live="password" type="password" label="Nueva contraseña" />

                                <x-ui.input wire:model.live="password_confirmation" type="password"
                                    label="Confirmar contraseña" />

                            </div>

                        </div>

                    </div>

                    <div class="flex justify-end">

                        <button type="submit" class="rounded-2xl bg-primary px-6 py-3 text-sm font-medium text-white">

                            Actualizar Contraseña

                        </button>

                    </div>

                </form>
            @endif

            @if ($activeSection === 'vacaciones' && $puedeGestionarVacaciones)
                <div class="space-y-6">

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

                        <h3 class="mb-5 text-lg font-semibold text-slate-800 dark:text-white">

                            Backups

                        </h3>

                        <p class="mb-5 text-sm leading-relaxed text-slate-500">

                            Selecciona primero un <strong>agente backup global</strong> y luego verifica si deseas
                            mantenerlo para todos los flujos y aplicaciones. Puedes cambiar algunos manualmente antes de
                            guardar.

                        </p>

                        <div class="mb-5 grid gap-3 md:grid-cols-[1fr_auto]">

                            <select wire:model.live="backupGlobalId"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm">

                                <option value="">
                                    Seleccionar backup global
                                </option>

                                @foreach ($agentes as $agente)
                                    <option value="{{ $agente->id }}">

                                        {{ $agente->nombre_completo }}

                                    </option>
                                @endforeach

                            </select>

                            <button wire:click="asignarBackupGlobal" type="button"
                                class="rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium">

                                Aplicar

                            </button>

                        </div>

                        <div class="mb-4 flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 text-sm">

                            <span class="text-slate-600">

                                Cobertura

                            </span>

                            <span
                                class="rounded-full {{ $totalItemsBackup === $itemsCubiertos ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }} px-3 py-1 text-xs font-medium">

                                {{ $itemsCubiertos }}
                                /
                                {{ $totalItemsBackup }}
                                cubiertos

                            </span>

                        </div>

                        <div class="overflow-hidden rounded-2xl border border-slate-200">

                            <table class="min-w-full">

                                <thead>

                                    <tr class="border-b bg-slate-50 text-xs uppercase tracking-wider text-slate-500">

                                        <th class="px-4 py-3 text-left">
                                            Tipo
                                        </th>

                                        <th class="px-4 py-3 text-left">
                                            Flujo / Aplicación
                                        </th>

                                        <th class="px-4 py-3 text-left">
                                            Grupo
                                        </th>

                                        <th class="px-4 py-3 text-left">
                                            Agente Backup
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse ($itemsBackup as $item)
                                        <tr wire:key="backup-item-{{ $item['key'] }}" class="border-b border-slate-100">

                                            <td class="px-4 py-3">

                                                <span
                                                    class="rounded-full {{ $item['type'] === 'flujo' ? 'bg-slate-100 text-slate-700' : 'bg-green-100 text-green-700' }} px-3 py-1 text-xs font-medium">

                                                    {{ $item['type'] === 'flujo' ? 'Flujo' : 'Aplicación' }}

                                                </span>

                                            </td>

                                            <td class="px-4 py-3">

                                                <div class="text-sm font-medium text-slate-800">

                                                    {{ $item['nombre'] }}

                                                </div>

                                                <div class="text-xs text-slate-500">

                                                    {{ $item['contexto'] }}

                                                </div>

                                            </td>

                                            <td class="px-4 py-3 text-sm text-slate-600">

                                                {{ $item['grupo'] }}

                                            </td>

                                            <td class="px-4 py-3">

                                                <select wire:model.live="backupAsignaciones.{{ $item['key'] }}"
                                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">

                                                    <option value="">
                                                        Seleccionar backup
                                                    </option>

                                                    @foreach ($agentes as $agente)
                                                        <option value="{{ $agente->id }}">

                                                            {{ $agente->nombre_completo }}

                                                        </option>
                                                    @endforeach

                                                </select>

                                            </td>

                                        </tr>
                                    @empty
                                        <tr>

                                            <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">

                                                No tienes flujos ni aplicaciones relacionadas.

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        @error('backupAsignaciones')
                            <div class="mt-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                                {{ $message }}

                            </div>
                        @enderror

                        @if ($totalItemsBackup > $itemsCubiertos)
                            <div class="mt-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">

                                Debes cubrir todos los flujos y aplicaciones antes de marcar vacaciones.

                            </div>
                        @endif

                    </div>

                    <div class="flex flex-wrap justify-end gap-3">

                        <button wire:click="guardarBackups" type="button"
                            class="rounded-2xl border border-slate-300 px-6 py-3 text-sm font-medium">

                            Guardar Backups

                        </button>

                        @if ($en_vacaciones)
                            <button wire:click="volverDelTrabajo" type="button"
                                class="rounded-2xl bg-green-600 px-6 py-3 text-sm font-medium text-white">

                                Regresar al Trabajo

                            </button>
                        @else
                            <button wire:click="marcarVacaciones" type="button"
                                @disabled($totalItemsBackup > $itemsCubiertos)
                                class="rounded-2xl px-6 py-3 text-sm font-medium text-white {{ $totalItemsBackup > $itemsCubiertos ? 'cursor-not-allowed bg-slate-400' : 'bg-primary' }}">

                                Marcar Vacaciones

                            </button>
                        @endif

                    </div>

                </div>
            @endif

        </section>

    </div>

</div>
