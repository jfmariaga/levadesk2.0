@extends('layouts.portal')

@section('content')
    @php
        $usuario = auth()->user();

        $esAdmin = $usuario?->hasRole('Admin') ?? false;

        $esAgente = $usuario?->hasRole('Agente') ?? false;

        $puedeVerBasicos =
            $usuario?->hasAnyRole([
                'Usuario',
                'Agente',
                'Admin',
            ]) ?? false;

        $puedeVerGestionTi = $esAgente || $esAdmin;
    @endphp

    <div class="mx-auto max-w-6xl">

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

            @if ($puedeVerBasicos)
                <x-portal.module-card title="Soporte TI"
                    description="Solicita soporte técnico, acceso a sistemas o reporta un incidente."
                    icon="{{ asset('images/modules/soporte-ti.png') }}" />

                <x-portal.module-card title="Mis Tickets"
                    description="Consulta el estado de tus solicitudes y realiza seguimiento en tiempo real."
                    icon="{{ asset('images/modules/mis-tickets.png') }}" />

                <x-portal.module-card title="Tutoriales y guías"
                    description="Encuentra tutoriales, preguntas frecuentes y manuales paso a paso."
                    icon="{{ asset('images/modules/tutoriales.png') }}" />
            @endif

            @if ($puedeVerGestionTi)
                <x-portal.module-card title="Gestión de TI"
                    description="Consulta, atiende y resuelve tickets asignados a tus grupos."
                    icon="{{ asset('images/modules/gestion-tickets.png') }}" />
            @endif

            @if ($esAdmin)
                <x-portal.module-card title="Gestión Presupuestal" description="Monitorea el plan presupuestal del área TI."
                    icon="{{ asset('images/modules/presupuesto.png') }}" />

                <x-portal.module-card title="Arquitectura Tecnológica"
                    description="Gestión integral del ecosistema tecnológico."
                    icon="{{ asset('images/modules/arquitectura.png') }}" />

                <x-portal.module-card title="Administración LevaDesk"
                    description="Control centralizado de la estructura funcional y técnica de la plataforma."
                    icon="{{ asset('images/modules/administracion.png') }}" href="{{ route('admin.dashboard') }}" />
            @endif

        </div>

    </div>
@endsection
