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
            'label' => 'Usuarios',
        ],
    ]" />

    <x-admin.crud-toolbar title="Usuarios" buttonText="Nuevo Usuario" />

    {{-- BUSCADOR --}}
    <div class="mb-6">

        <input wire:model.live="search" type="text" placeholder="Buscar usuario, correo, sociedad o área..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

    </div>

    {{-- FILTROS --}}
    <div class="mb-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">

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

                Área

            </label>

            <select wire:model.live="filtroArea" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                <option value="">

                    Todas las áreas

                </option>

                @foreach ($areasFiltro as $area)
                    <option value="{{ $area->id }}">

                        {{ $area->nombre }}

                    </option>
                @endforeach

            </select>

        </div>

        <div>

            <label class="mb-2 block text-sm font-medium text-slate-700">

                Rol

            </label>

            <select wire:model.live="filtroRol" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                <option value="">

                    Todos los roles

                </option>

                @foreach ($roles as $rol)
                    <option value="{{ $rol->name }}">

                        {{ $rol->name }}

                    </option>
                @endforeach

            </select>

        </div>

        <div>

            <label class="mb-2 block text-sm font-medium text-slate-700">

                Estado

            </label>

            <select wire:model.live="filtroEstado" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

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

        <div>

            <label class="mb-2 block text-sm font-medium text-slate-700">

                Vacaciones

            </label>

            <select wire:model.live="filtroVacaciones" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                <option value="">

                    Todos

                </option>

                <option value="1">

                    En vacaciones

                </option>

                <option value="0">

                    Disponible

                </option>

            </select>

        </div>

        <div>

            <label class="mb-2 block text-sm font-medium text-slate-700">

                Aprobador TI

            </label>

            <select wire:model.live="filtroAprobador" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                <option value="">

                    Todos

                </option>

                <option value="1">

                    Sí

                </option>

                <option value="0">

                    No

                </option>

            </select>

        </div>

    </div>

    {{-- CONTADOR --}}
    <div class="mb-4 flex items-center justify-between">

        <div class="text-sm text-slate-500">

            Mostrando
            {{ $usuarios->count() }}
            de
            {{ $usuarios->total() }}
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

                <th wire:click="sortBy('name')" class="cursor-pointer px-4 py-3 text-left">

                    Usuario

                </th>

                <th class="px-4 py-3 text-left">

                    Roles

                </th>

                <th class="px-4 py-3 text-left">

                    Organización

                </th>

                <th wire:click="sortBy('estado')" class="cursor-pointer px-4 py-3 text-left">

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

            @forelse ($usuarios as $usuario)
                <tr wire:key="usuario-{{ $usuario->id }}" class="border-b border-slate-100">

                    <td class="px-4 py-3">

                        <div class="flex items-center gap-3">

                            <img src="{{ $usuario->foto_perfil }}" class="h-10 w-10 rounded-full object-cover">

                            <div>

                                <div class="font-medium text-slate-800">

                                    {{ $usuario->nombre_completo }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $usuario->email }}

                                </div>

                                @if (!$usuario->hasVerifiedEmail())
                                    <div class="mt-1 inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">

                                        Correo sin verificar

                                    </div>
                                @endif

                            </div>

                        </div>

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex flex-wrap gap-1">

                            @forelse ($usuario->roles as $rol)
                                <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs text-primary">

                                    {{ $rol->name }}

                                </span>
                            @empty
                                <span class="text-sm text-slate-400">

                                    Sin rol

                                </span>
                            @endforelse

                        </div>

                    </td>

                    <td class="px-4 py-3">

                        <div class="text-sm font-medium text-slate-800">

                            {{ $usuario->sociedad?->nombre ?? 'Sin sociedad' }}

                        </div>

                        <div class="text-xs text-slate-500">

                            {{ $usuario->area_nombre }}

                        </div>

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex flex-wrap gap-1">

                            @if ($usuario->estado)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">

                                    Activo

                                </span>
                            @else
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs text-red-700">

                                    Inactivo

                                </span>
                            @endif

                            @if ($usuario->en_vacaciones)
                                <span class="rounded-full bg-sky-100 px-3 py-1 text-xs text-sky-700">

                                    Vacaciones

                                </span>
                            @endif

                            @if ($usuario->aprobador_ti)
                                <span class="rounded-full bg-violet-100 px-3 py-1 text-xs text-violet-700">

                                    Aprobador TI

                                </span>
                            @endif

                        </div>

                    </td>

                    <td class="px-4 py-3 text-slate-500">

                        {{ $usuario->updated_at?->format('d/m/Y') }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            <button wire:click="edit({{ $usuario->id }})" type="button"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                ✏️

                            </button>

                            <button wire:click="toggle({{ $usuario->id }})" type="button"
                                title="{{ $usuario->estado ? 'Desactivar' : 'Activar' }}"
                                class="rounded-lg px-3 py-2 transition
                                {{ $usuario->estado
                                    ? 'bg-red-50 text-red-600 hover:bg-red-100'
                                    : 'bg-green-50 text-green-600 hover:bg-green-100' }}">

                                {{ $usuario->estado ? '🔒' : '🔓' }}

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

        {{ $usuarios->links() }}

    </div>

    <x-admin.crud-drawer :show="$showDrawer" wire:key="drawer-{{ $editingId ?? 'new' }}" :title="$editingId ? 'Editar Usuario' : 'Nuevo Usuario'"
        subtitle="Datos, permisos y asignaciones del usuario">

        <div class="space-y-6" wire:key="form-{{ $editingId ?? 'new' }}">

            {{-- DATOS PERSONALES --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Datos Personales

                </h3>

                <div class="space-y-4">

                    <div class="grid gap-4 md:grid-cols-2">

                        <x-ui.input wire:model.live="name" label="Nombres" />

                        <x-ui.input wire:model.live="last_name" label="Apellidos" />

                    </div>

                    <x-ui.input wire:model.live="email" type="email" label="Correo" />

                    <x-ui.input wire:model.live="profile_photo" label="Foto de perfil (ruta storage)" />

                </div>

            </div>

            {{-- ORGANIZACION --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Organización

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

                    <x-ui.select wire:model.live="area_id" label="Área relacionada">

                        <option value="">
                            Sin área relacionada
                        </option>

                        @foreach ($areas as $areaItem)
                            <option value="{{ $areaItem->id }}">

                                {{ $areaItem->nombre }}

                            </option>
                        @endforeach

                    </x-ui.select>

                    @error('area_id')
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                            {{ $message }}

                        </div>
                    @enderror

                    <x-ui.input wire:model.live="area" label="Área legacy" />

                </div>

            </div>

            {{-- SEGURIDAD --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Seguridad y Estado

                </h3>

                <div class="space-y-4">

                    <div class="grid gap-4 md:grid-cols-2">

                        <x-ui.input wire:model.live="password" type="password"
                            label="{{ $editingId ? 'Nueva contraseña' : 'Contraseña' }}" />

                        <x-ui.input wire:model.live="password_confirmation" type="password"
                            label="Confirmar contraseña" />

                    </div>

                    <div class="grid gap-4 md:grid-cols-2">

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Estado

                            </label>

                            <select wire:model.live="estado" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                                <option value="1">

                                    Activo

                                </option>

                                <option value="0">

                                    Inactivo

                                </option>

                            </select>

                        </div>

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Correo

                            </label>

                            <select wire:model.live="correo_verificado"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                                <option value="1">

                                    Verificado

                                </option>

                                <option value="0">

                                    Sin verificar

                                </option>

                            </select>

                        </div>

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Vacaciones

                            </label>

                            <select wire:model.live="en_vacaciones"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                                <option value="0">

                                    Disponible

                                </option>

                                <option value="1">

                                    En vacaciones

                                </option>

                            </select>

                        </div>

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Aprobador TI

                            </label>

                            <select wire:model.live="aprobador_ti"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                                <option value="0">

                                    No

                                </option>

                                <option value="1">

                                    Sí

                                </option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ROLES --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Roles

                </h3>

                <div class="grid gap-2 md:grid-cols-2">

                    @forelse ($roles as $rol)
                        <label
                            class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 transition hover:bg-slate-50">

                            <input type="checkbox" wire:model.live="rolesSeleccionados" value="{{ $rol->name }}"
                                class="rounded border-slate-300 text-primary">

                            <span class="text-sm font-medium text-slate-800">

                                {{ $rol->name }}

                            </span>

                        </label>
                    @empty
                        <div class="text-sm text-slate-500">

                            No hay roles configurados.

                        </div>
                    @endforelse

                </div>

                @error('rolesSeleccionados.*')
                    <div class="mt-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                        {{ $message }}

                    </div>
                @enderror

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

    @if ($showReasignacionModal)
        <div class="fixed inset-0 z-[60]">

            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div class="relative flex min-h-screen items-center justify-center p-4">

                <div
                    class="max-h-[90vh] w-full max-w-6xl overflow-y-auto rounded-3xl bg-white shadow-2xl dark:bg-slate-900">

                    <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-6 py-5 dark:border-slate-800 dark:bg-slate-900">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <h2 class="text-xl font-bold text-slate-800 dark:text-white">

                                    Reasignar tickets pendientes

                                </h2>

                                <p class="mt-1 text-sm text-slate-500">

                                    Este usuario tiene tickets abiertos. Debes reasignarlos antes de inactivarlo.

                                </p>

                            </div>

                            <button wire:click="cerrarReasignacion" type="button"
                                class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-50">

                                Cerrar

                            </button>

                        </div>

                    </div>

                    <div class="space-y-6 p-6">

                        @if (count($ticketsComoUsuario) > 0)
                            <section class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">

                                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">

                                        Tickets donde es solicitante

                                    </h3>

                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">

                                        {{ count($ticketsComoUsuario) }}
                                        pendientes

                                    </span>

                                </div>

                                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

                                    <table class="min-w-full">

                                        <thead>

                                            <tr class="border-b bg-slate-50 text-xs uppercase tracking-wider text-slate-500">

                                                <th class="px-4 py-3 text-left">
                                                    Ticket
                                                </th>

                                                <th class="px-4 py-3 text-left">
                                                    Título
                                                </th>

                                                <th class="px-4 py-3 text-left">
                                                    Estado
                                                </th>

                                                <th class="px-4 py-3 text-left">
                                                    Agente actual
                                                </th>

                                                <th class="px-4 py-3 text-left">
                                                    Nuevo solicitante
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach ($ticketsComoUsuario as $ticket)
                                                <tr wire:key="ticket-usuario-{{ $ticket['id'] }}"
                                                    class="border-b border-slate-100">

                                                    <td class="px-4 py-3 text-sm font-medium text-slate-800">

                                                        {{ $ticket['nomenclatura'] }}

                                                    </td>

                                                    <td class="px-4 py-3 text-sm text-slate-600">

                                                        {{ $ticket['titulo'] ?? '-' }}

                                                    </td>

                                                    <td class="px-4 py-3">

                                                        <span
                                                            class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700">

                                                            {{ $ticket['estado'] }}

                                                        </span>

                                                    </td>

                                                    <td class="px-4 py-3 text-sm text-slate-600">

                                                        {{ $ticket['agente'] }}

                                                    </td>

                                                    <td class="px-4 py-3">

                                                        <div class="min-w-64 space-y-2">

                                                            <input
                                                                wire:model.live.debounce.300ms="busquedasUsuarioReasignacion.{{ $ticket['id'] }}"
                                                                type="search" placeholder="Buscar usuario..."
                                                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">

                                                            <select
                                                                wire:model.live="reasignacionesUsuario.{{ $ticket['id'] }}"
                                                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">

                                                                <option value="">
                                                                    Seleccionar usuario
                                                                </option>

                                                                @forelse (($usuariosReasignacionPorTicket[$ticket['id']] ?? collect()) as $usuarioActivo)
                                                                    <option value="{{ $usuarioActivo->id }}">

                                                                        {{ $usuarioActivo->nombre_completo }}
                                                                        -
                                                                        {{ $usuarioActivo->email }}

                                                                    </option>
                                                                @empty
                                                                    <option value="" disabled>
                                                                        Sin resultados
                                                                    </option>
                                                                @endforelse

                                                            </select>

                                                        </div>

                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                                @error('reasignacionesUsuario')
                                    <div class="mt-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                                        {{ $message }}

                                    </div>
                                @enderror

                            </section>
                        @endif

                        @if (count($ticketsComoAgente) > 0)
                            <section class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">

                                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">

                                        Tickets donde es agente

                                    </h3>

                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">

                                        {{ count($ticketsComoAgente) }}
                                        pendientes

                                    </span>

                                </div>

                                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

                                    <table class="min-w-full">

                                        <thead>

                                            <tr class="border-b bg-slate-50 text-xs uppercase tracking-wider text-slate-500">

                                                <th class="px-4 py-3 text-left">
                                                    Ticket
                                                </th>

                                                <th class="px-4 py-3 text-left">
                                                    Título
                                                </th>

                                                <th class="px-4 py-3 text-left">
                                                    Estado
                                                </th>

                                                <th class="px-4 py-3 text-left">
                                                    Solicitante
                                                </th>

                                                <th class="px-4 py-3 text-left">
                                                    Nuevo agente
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach ($ticketsComoAgente as $ticket)
                                                <tr wire:key="ticket-agente-{{ $ticket['id'] }}"
                                                    class="border-b border-slate-100">

                                                    <td class="px-4 py-3 text-sm font-medium text-slate-800">

                                                        {{ $ticket['nomenclatura'] }}

                                                    </td>

                                                    <td class="px-4 py-3 text-sm text-slate-600">

                                                        {{ $ticket['titulo'] ?? '-' }}

                                                    </td>

                                                    <td class="px-4 py-3">

                                                        <span
                                                            class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700">

                                                            {{ $ticket['estado'] }}

                                                        </span>

                                                    </td>

                                                    <td class="px-4 py-3 text-sm text-slate-600">

                                                        {{ $ticket['solicitante'] }}

                                                    </td>

                                                    <td class="px-4 py-3">

                                                        <div class="min-w-64 space-y-2">

                                                            <input
                                                                wire:model.live.debounce.300ms="busquedasAgenteReasignacion.{{ $ticket['id'] }}"
                                                                type="search" placeholder="Buscar agente..."
                                                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">

                                                            <select
                                                                wire:model.live="reasignacionesAgente.{{ $ticket['id'] }}"
                                                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">

                                                                <option value="">
                                                                    Seleccionar agente
                                                                </option>

                                                                @forelse (($agentesReasignacionPorTicket[$ticket['id']] ?? collect()) as $agenteActivo)
                                                                    <option value="{{ $agenteActivo->id }}">

                                                                        {{ $agenteActivo->nombre_completo }}
                                                                        -
                                                                        {{ $agenteActivo->email }}

                                                                    </option>
                                                                @empty
                                                                    <option value="" disabled>
                                                                        Sin resultados
                                                                    </option>
                                                                @endforelse

                                                            </select>

                                                        </div>

                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                                @error('reasignacionesAgente')
                                    <div class="mt-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                                        {{ $message }}

                                    </div>
                                @enderror

                            </section>
                        @endif

                        @if (
                            (count($ticketsComoUsuario) > 0 && $usuariosActivosReasignacion->isEmpty()) ||
                                (count($ticketsComoAgente) > 0 && $agentesActivosReasignacion->isEmpty()))
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                                No hay candidatos activos suficientes para completar la reasignación.

                            </div>
                        @endif

                    </div>

                    <div class="sticky bottom-0 flex flex-wrap justify-end gap-3 border-t border-slate-200 bg-white px-6 py-4">

                        <button wire:click="cerrarReasignacion" type="button"
                            class="rounded-xl border border-slate-300 px-5 py-2 text-sm font-medium">

                            Cancelar

                        </button>

                        <button wire:click="confirmarReasignacion" type="button"
                            @disabled(
                                (count($ticketsComoUsuario) > 0 && $usuariosActivosReasignacion->isEmpty()) ||
                                    (count($ticketsComoAgente) > 0 && $agentesActivosReasignacion->isEmpty()))
                            class="rounded-xl px-5 py-2 text-sm font-medium text-white
                            {{ (count($ticketsComoUsuario) > 0 && $usuariosActivosReasignacion->isEmpty()) ||
                            (count($ticketsComoAgente) > 0 && $agentesActivosReasignacion->isEmpty())
                                ? 'cursor-not-allowed bg-slate-400'
                                : 'bg-primary' }}">

                            Reasignar e inactivar

                        </button>

                    </div>

                </div>

            </div>

        </div>
    @endif

</div>
