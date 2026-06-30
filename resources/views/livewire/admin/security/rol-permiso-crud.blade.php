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
            'label' => 'Roles y Permisos',
        ],
    ]" />

    <div class="mb-6 flex items-center justify-between">

        <div>

            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                Roles y Permisos

            </h2>

        </div>

        <div class="flex gap-2">

            <button type="button" wire:click="createPermission"
                class="rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700">

                + Nuevo Permiso

            </button>

            <button type="button" wire:click="createRole"
                class="rounded-2xl bg-primary px-4 py-2 text-sm font-medium text-white">

                + Nuevo Rol

            </button>

        </div>

    </div>

    <div class="grid gap-6 xl:grid-cols-2">

        {{-- ROLES --}}
        <div>

            <div class="mb-4 flex items-center justify-between gap-4">

                <input wire:model.live="roleSearch" type="text" placeholder="Buscar rol..."
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

                <select wire:model.live="rolesPerPage"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium shadow-sm">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>

                </select>

            </div>

            <div class="mb-3 text-sm text-slate-500">

                Mostrando
                {{ $roles->count() }}
                de
                {{ $roles->total() }}
                roles

            </div>

            <x-admin.crud-table>

                <thead>

                    <tr class="border-b bg-slate-50">

                        <th wire:click="sortRolesBy('name')" class="cursor-pointer px-4 py-3 text-left">

                            Rol

                        </th>

                        <th wire:click="sortRolesBy('guard_name')" class="cursor-pointer px-4 py-3 text-left">

                            Guard

                        </th>

                        <th class="px-4 py-3 text-left">

                            Uso

                        </th>

                        <th class="px-4 py-3 text-right">

                            Acciones

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($roles as $role)
                        <tr wire:key="role-{{ $role->id }}" class="border-b border-slate-100">

                            <td class="px-4 py-3">

                                <div class="font-medium text-slate-800">

                                    {{ $role->name }}

                                </div>

                            </td>

                            <td class="px-4 py-3">

                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">

                                    {{ $role->guard_name }}

                                </span>

                            </td>

                            <td class="px-4 py-3">

                                <div class="text-sm text-slate-700">

                                    {{ $role->users_count }}
                                    usuarios

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $role->permissions_count }}
                                    permisos

                                </div>

                            </td>

                            <td class="px-4 py-3">

                                <div class="flex justify-end gap-2">

                                    <button wire:click="editRole({{ $role->id }})" type="button"
                                        class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                        ✏️

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="py-12 text-center text-slate-500">

                                No se encontraron roles.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </x-admin.crud-table>

            <div class="mt-4">

                {{ $roles->links() }}

            </div>

        </div>

        {{-- PERMISOS --}}
        <div>

            <div class="mb-4 flex items-center justify-between gap-4">

                <input wire:model.live="permissionSearch" type="text" placeholder="Buscar permiso..."
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">

                <select wire:model.live="permissionsPerPage"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium shadow-sm">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>

                </select>

            </div>

            <div class="mb-3 text-sm text-slate-500">

                Mostrando
                {{ $permissions->count() }}
                de
                {{ $permissions->total() }}
                permisos

            </div>

            <x-admin.crud-table>

                <thead>

                    <tr class="border-b bg-slate-50">

                        <th wire:click="sortPermissionsBy('name')" class="cursor-pointer px-4 py-3 text-left">

                            Permiso

                        </th>

                        <th wire:click="sortPermissionsBy('guard_name')" class="cursor-pointer px-4 py-3 text-left">

                            Guard

                        </th>

                        <th class="px-4 py-3 text-left">

                            Roles

                        </th>

                        <th class="px-4 py-3 text-right">

                            Acciones

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($permissions as $permission)
                        <tr wire:key="permission-{{ $permission->id }}" class="border-b border-slate-100">

                            <td class="px-4 py-3">

                                <div class="font-medium text-slate-800">

                                    {{ $permission->name }}

                                </div>

                            </td>

                            <td class="px-4 py-3">

                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">

                                    {{ $permission->guard_name }}

                                </span>

                            </td>

                            <td class="px-4 py-3 text-sm text-slate-700">

                                {{ $permission->roles_count }}

                            </td>

                            <td class="px-4 py-3">

                                <div class="flex justify-end gap-2">

                                    <button wire:click="editPermission({{ $permission->id }})" type="button"
                                        class="rounded-lg bg-blue-50 px-3 py-2 text-blue-600 transition hover:bg-blue-100">

                                        ✏️

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="py-12 text-center text-slate-500">

                                No se encontraron permisos.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </x-admin.crud-table>

            <div class="mt-4">

                {{ $permissions->links() }}

            </div>

        </div>

    </div>

    <x-admin.crud-drawer :show="$showRoleDrawer" wire:key="role-drawer-{{ $editingRoleId ?? 'new' }}" :title="$editingRoleId ? 'Editar Rol' : 'Nuevo Rol'"
        subtitle="Configuración del rol y sus permisos">

        <div class="space-y-6" wire:key="role-form-{{ $editingRoleId ?? 'new' }}">

            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Información General

                </h3>

                <div class="space-y-4">

                    <x-ui.input wire:model.live="roleName" label="Nombre" />

                    <x-ui.input wire:model.live="roleGuardName" label="Guard" />

                </div>

            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Permisos

                </h3>

                @php
                    $permissionGroups = $permissionsForRole->groupBy(
                        fn($permission) => str_contains($permission->name, '.')
                            ? \Illuminate\Support\Str::before($permission->name, '.')
                            : 'general',
                    );
                @endphp

                <div class="max-h-[28rem] space-y-5 overflow-y-auto pr-1">

                    @forelse ($permissionGroups as $group => $groupPermissions)
                        <div>

                            <div class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">

                                {{ $group }}

                            </div>

                            <div class="grid gap-2">

                                @foreach ($groupPermissions as $permission)
                                    <label
                                        class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 transition hover:bg-slate-50">

                                        <input type="checkbox" wire:model.live="permissionsSelected"
                                            value="{{ $permission->name }}" class="rounded border-slate-300 text-primary">

                                        <div>

                                            <div class="text-sm font-medium text-slate-800">

                                                {{ $permission->name }}

                                            </div>

                                            <div class="text-xs text-slate-500">

                                                {{ $permission->guard_name }}

                                            </div>

                                        </div>

                                    </label>
                                @endforeach

                            </div>

                        </div>
                    @empty
                        <div class="text-sm text-slate-500">

                            No hay permisos configurados.

                        </div>
                    @endforelse

                </div>

                @error('permissionsSelected.*')
                    <div class="mt-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                        {{ $message }}

                    </div>
                @enderror

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4">

                <button wire:click="closeRoleDrawer" type="button"
                    class="rounded-xl border border-slate-300 px-5 py-2">

                    Cancelar

                </button>

                <button wire:click="saveRole" type="button" class="rounded-xl bg-primary px-5 py-2 text-white">

                    Guardar

                </button>

            </div>

        </div>

    </x-admin.crud-drawer>

    <x-admin.crud-drawer :show="$showPermissionDrawer" wire:key="permission-drawer-{{ $editingPermissionId ?? 'new' }}"
        :title="$editingPermissionId ? 'Editar Permiso' : 'Nuevo Permiso'" subtitle="Permiso disponible para asignar a roles">

        <div class="space-y-6" wire:key="permission-form-{{ $editingPermissionId ?? 'new' }}">

            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <h3 class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">

                    Información General

                </h3>

                <div class="space-y-4">

                    <x-ui.input wire:model.live="permissionName" label="Nombre" />

                    <x-ui.input wire:model.live="permissionGuardName" label="Guard" />

                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4">

                <button wire:click="closePermissionDrawer" type="button"
                    class="rounded-xl border border-slate-300 px-5 py-2">

                    Cancelar

                </button>

                <button wire:click="savePermission" type="button" class="rounded-xl bg-primary px-5 py-2 text-white">

                    Guardar

                </button>

            </div>

        </div>

    </x-admin.crud-drawer>

</div>
