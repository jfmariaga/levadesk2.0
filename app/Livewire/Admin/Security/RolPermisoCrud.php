<?php

namespace App\Livewire\Admin\Security;

use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolPermisoCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $rolesPerPage = 10;

    public int $permissionsPerPage = 10;

    public string $roleSearch = '';

    public string $permissionSearch = '';

    public bool $showRoleDrawer = false;

    public bool $showPermissionDrawer = false;

    public ?int $editingRoleId = null;

    public ?int $editingPermissionId = null;

    public string $roleName = '';

    public string $roleGuardName = 'web';

    public array $permissionsSelected = [];

    public string $permissionName = '';

    public string $permissionGuardName = 'web';

    public string $roleSortField = 'name';

    public string $roleSortDirection = 'asc';

    public string $permissionSortField = 'name';

    public string $permissionSortDirection = 'asc';

    protected function roleRules(): array
    {
        return [

            'roleName' => [
                'required',
                'min:3',
                Rule::unique(
                    'roles',
                    'name'
                )
                    ->where(
                        'guard_name',
                        $this->roleGuardName
                    )
                    ->ignore(
                        $this->editingRoleId
                    ),
            ],

            'roleGuardName' => [
                'required',
                'max:255',
            ],

            'permissionsSelected' => [
                'array',
            ],

            'permissionsSelected.*' => [
                'exists:permissions,name',
            ],

        ];
    }

    protected function permissionRules(): array
    {
        return [

            'permissionName' => [
                'required',
                'min:3',
                Rule::unique(
                    'permissions',
                    'name'
                )
                    ->where(
                        'guard_name',
                        $this->permissionGuardName
                    )
                    ->ignore(
                        $this->editingPermissionId
                    ),
            ],

            'permissionGuardName' => [
                'required',
                'max:255',
            ],

        ];
    }

    public function updatedRoleSearch(): void
    {
        $this->resetPage('rolesPage');
    }

    public function updatedPermissionSearch(): void
    {
        $this->resetPage('permissionsPage');
    }

    public function updatedRolesPerPage(): void
    {
        $this->resetPage('rolesPage');
    }

    public function updatedPermissionsPerPage(): void
    {
        $this->resetPage('permissionsPage');
    }

    public function sortRolesBy(string $field): void
    {
        if ($this->roleSortField === $field) {

            $this->roleSortDirection =
                $this->roleSortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {

            $this->roleSortField = $field;

            $this->roleSortDirection = 'asc';
        }
    }

    public function sortPermissionsBy(string $field): void
    {
        if ($this->permissionSortField === $field) {

            $this->permissionSortDirection =
                $this->permissionSortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {

            $this->permissionSortField = $field;

            $this->permissionSortDirection = 'asc';
        }
    }

    public function createRole(): void
    {
        $this->resetRoleForm();

        $this->showRoleDrawer = true;
    }

    public function editRole(int $id): void
    {
        $role = Role::query()

            ->with('permissions')

            ->findOrFail($id);

        $this->editingRoleId = $role->id;

        $this->roleName = $role->name;

        $this->roleGuardName = $role->guard_name;

        $this->permissionsSelected =
            $role->permissions
            ->pluck('name')
            ->toArray();

        $this->showRoleDrawer = true;
    }

    public function saveRole(): void
    {
        $this->validate(
            $this->roleRules()
        );

        $role = Role::updateOrCreate(

            ['id' => $this->editingRoleId],

            [

                'name' => trim($this->roleName),

                'guard_name' => trim($this->roleGuardName),

            ]

        );

        $role->syncPermissions(
            $this->permissionsSelected
        );

        $this->forgetPermissionCache();

        $this->resetRoleForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Rol guardado correctamente.'
        );
    }

    public function createPermission(): void
    {
        $this->resetPermissionForm();

        $this->showPermissionDrawer = true;
    }

    public function editPermission(int $id): void
    {
        $permission = Permission::findOrFail($id);

        $this->editingPermissionId = $permission->id;

        $this->permissionName = $permission->name;

        $this->permissionGuardName = $permission->guard_name;

        $this->showPermissionDrawer = true;
    }

    public function savePermission(): void
    {
        $this->validate(
            $this->permissionRules()
        );

        Permission::updateOrCreate(

            ['id' => $this->editingPermissionId],

            [

                'name' => trim($this->permissionName),

                'guard_name' => trim($this->permissionGuardName),

            ]

        );

        $this->forgetPermissionCache();

        $this->resetPermissionForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Permiso guardado correctamente.'
        );
    }

    public function closeRoleDrawer(): void
    {
        $this->resetRoleForm();
    }

    public function closePermissionDrawer(): void
    {
        $this->resetPermissionForm();
    }

    private function resetRoleForm(): void
    {
        $this->editingRoleId = null;

        $this->roleName = '';

        $this->roleGuardName = 'web';

        $this->permissionsSelected = [];

        $this->showRoleDrawer = false;

        $this->resetValidation();
    }

    private function resetPermissionForm(): void
    {
        $this->editingPermissionId = null;

        $this->permissionName = '';

        $this->permissionGuardName = 'web';

        $this->showPermissionDrawer = false;

        $this->resetValidation();
    }

    private function forgetPermissionCache(): void
    {
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }

    public function render()
    {
        return view(
            'livewire.admin.security.rol-permiso-crud',
            [

                'roles' => Role::query()

                    ->withCount([
                        'permissions',
                        'users',
                    ])

                    ->when(
                        $this->roleSearch,
                        fn($query) =>
                        $query->where(
                            'name',
                            'like',
                            "%{$this->roleSearch}%"
                        )
                            ->orWhere(
                                'guard_name',
                                'like',
                                "%{$this->roleSearch}%"
                            )
                    )

                    ->orderBy(
                        $this->roleSortField,
                        $this->roleSortDirection
                    )

                    ->paginate(
                        $this->rolesPerPage,
                        ['*'],
                        'rolesPage'
                    ),

                'permissions' => Permission::query()

                    ->withCount('roles')

                    ->when(
                        $this->permissionSearch,
                        fn($query) =>
                        $query->where(
                            'name',
                            'like',
                            "%{$this->permissionSearch}%"
                        )
                            ->orWhere(
                                'guard_name',
                                'like',
                                "%{$this->permissionSearch}%"
                            )
                    )

                    ->orderBy(
                        $this->permissionSortField,
                        $this->permissionSortDirection
                    )

                    ->paginate(
                        $this->permissionsPerPage,
                        ['*'],
                        'permissionsPage'
                    ),

                'permissionsForRole' => Permission::query()

                    ->orderBy('name')

                    ->get(),

            ]
        )->layout('layouts.admin');
    }
}
