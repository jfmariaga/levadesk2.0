<div>
    <x-admin.breadcrumb :items="[
        [
            'label' => 'Portal',
            'route' => route('dashboard'),
        ],
        [
            'label' => 'Administración',
        ],
    ]" />
    <div class="space-y-10">

        {{-- CATÁLOGOS --}}
        <div>

            <h2 class="mb-6 text-xl font-bold text-slate-800 dark:text-white">
                Catálogos
            </h2>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

                <x-admin.admin-card title="Tipos de Solicitud"
                    description="Incidentes, requerimientos, cambios y accesos."
                    href="{{ route('admin.tipos-solicitud.index') }}"
                    icon="{{ asset('images/modules/tipo-solicitud.png') }}" />

                <x-admin.admin-card title="Categorías" description="Clasificación principal de servicios."
                    href="{{ route('admin.categorias.index') }}" icon="{{ asset('images/modules/categoria.png') }}" />

                <x-admin.admin-card title="Subcategorías" description="Detalle operativo de cada categoría."
                    href="{{ route('admin.subcategorias.index') }}"
                    icon="{{ asset('images/modules/subcategoria.png') }}" />

                <x-admin.admin-card title="Estados" description="Estados disponibles para workflows."
                    href="{{ route('admin.estados.index') }}" icon="{{ asset('images/modules/estado.png') }}" />

                <x-admin.admin-card title="Urgencias" description="Niveles de urgencia y puntuación."
                    href="{{ route('admin.urgencias.index') }}" icon="{{ asset('images/modules/urgencia.png') }}" />

                <x-admin.admin-card title="Impactos" description="Niveles de impacto y puntuación."
                    href="{{ route('admin.impactos.index') }}" icon="{{ asset('images/modules/impacto.png') }}" />

                <x-admin.admin-card title="Sociedades" description="Gestión de sociedades y compañías."
                    href="{{ route('admin.sociedades.index') }}" icon="{{ asset('images/modules/sociedad.png') }}" />

                <x-admin.admin-card title="Áreas" description="Gestión de áreas por sociedad."
                    href="{{ route('admin.areas.index') }}" icon="{{ asset('images/modules/sociedad.png') }}" />

                <x-admin.admin-card title="Usuarios" description="Administración de usuarios, roles y grupos."
                    href="{{ route('admin.usuarios.index') }}" icon="{{ asset('images/modules/grupo.png') }}" />

                <x-admin.admin-card title="Roles y Permisos" description="Administración de accesos del sistema."
                    href="{{ route('admin.roles-permisos.index') }}" icon="{{ asset('images/modules/administracion.png') }}" />

                <x-admin.admin-card title="Grupos" description="Equipos responsables de atención y soporte."
                    href="{{ route('admin.grupos.index') }}" icon="{{ asset('images/modules/grupo.png') }}" />

                <x-admin.admin-card title="Aplicaciones" description="Gestión de aplicaciones y responsables."
                    href="{{ route('admin.aplicaciones.index') }}"
                    icon="{{ asset('images/modules/aplicacion.png') }}" />

                <x-admin.admin-card title="Asignación Automática"
                    description="Configuración de grupos responsables por subcategoría."
                    href="{{ route('admin.asignaciones.index') }}"
                    icon="{{ asset('images/modules/asignacion.png') }}" />

                <x-admin.admin-card title="ANS" description="Acuerdos de Nivel de Servicio."
                    href="{{ route('admin.ans.index') }}" icon="{{ asset('images/modules/ans.png') }}" />

                <x-admin.admin-card title="Terceros" description="Proveedores y aliados externos."
                    href="{{ route('admin.terceros.index') }}" icon="{{ asset('images/modules/tercero.png') }}" />

                <x-admin.admin-card title="Flujos de Terceros" description="Escalamiento y atención con proveedores."
                    href="{{ route('admin.flujos-terceros.index') }}"
                    icon="{{ asset('images/modules/flujo-tercero.png') }}" />

            </div>

        </div>

    </div>
</div>
