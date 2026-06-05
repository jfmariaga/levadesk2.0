@extends('layouts.portal')

@section('content')
    <div class="mx-auto max-w-6xl">

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

            <x-portal.module-card title="Soporte TI"
                description="Solicita soporte técnico, acceso a sistemas o reporta un incidente."
                icon="{{ asset('images/modules/soporte-ti.png') }}" />

            <x-portal.module-card title="Mis Tickets"
                description="Consulta el estado de tus solicitudes y realiza seguimiento en tiempo real."
                icon="{{ asset('images/modules/mis-tickets.png') }}" />

            <x-portal.module-card title="Tutoriales y guías"
                description="Encuentra tutoriales, preguntas frecuentes y manuales paso a paso."
                icon="{{ asset('images/modules/tutoriales.png') }}" />

            <x-portal.module-card title="Gestión de Tickets"
                description="Consulta, atiende y resuelve tickets asignados a tus grupos."
                icon="{{ asset('images/modules/gestion-tickets.png') }}" />

            <x-portal.module-card title="Gestión Presupuestal" description="Monitorea el plan presupuestal del área TI."
                icon="{{ asset('images/modules/presupuesto.png') }}" />

            <x-portal.module-card title="Arquitectura Tecnológica"
                description="Gestión integral del ecosistema tecnológico."
                icon="{{ asset('images/modules/arquitectura.png') }}" />

            <x-portal.module-card title="Administración LevaDesk"
                description="Control centralizado de la estructura funcional y técnica de la plataforma."
                icon="{{ asset('images/modules/administracion.png') }}" href="{{ route('admin.dashboard') }}" />

        </div>

    </div>
@endsection
