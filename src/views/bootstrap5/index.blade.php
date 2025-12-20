@extends(config('3312client.layoutpath'))

@section('title', 'Mis Tickets')
@section('page-title', 'Mis Tickets')
@section('page-subtitle', 'Gestión y seguimiento de incidencias')

@section('content')

@php
    // Calcular estadísticas desde los tickets
    $ticketsData = $tickets['data'] ?? [];
    $totalTickets = $tickets['pagination']['total'] ?? count($ticketsData);
    $pendientes = collect($ticketsData)->filter(function($ticket) {
        return isset($ticket['estado']['texto']) && 
               !in_array(strtolower($ticket['estado']['texto']), ['cerrado', 'resuelto', 'completado']);
    })->count();
    $resueltos = collect($ticketsData)->filter(function($ticket) {
        return isset($ticket['estado']['texto']) && 
               in_array(strtolower($ticket['estado']['texto']), ['cerrado', 'resuelto', 'completado']);
    })->count();
    $criticos = collect($ticketsData)->filter(function($ticket) {
        return isset($ticket['prioridad']['texto']) && 
               in_array(strtolower($ticket['prioridad']['texto']), ['urgente', 'crítica', 'alta']);
    })->count();
@endphp

<!-- Cards de Estadísticas -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Totales</p>
                    <h3 class="fw-bold mb-0">{{ $totalTickets }}</h3>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-layers-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Pendientes</p>
                    <h3 class="fw-bold mb-0">{{ $pendientes }}</h3>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Resueltos</p>
                    <h3 class="fw-bold mb-0">{{ $resueltos }}</h3>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Críticos</p>
                    <h3 class="fw-bold mb-0">{{ $criticos }}</h3>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card Principal con Tabla -->
<div class="card">
    <div class="card-header bg-white border-0 py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
            <h5 class="mb-0 fw-bold">Lista de Tickets</h5>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#soporteModal">
                <i class="bi bi-plus-lg"></i>
                <span class="d-none d-sm-inline">Nuevo Ticket</span>
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0">
            <thead>
                <tr>
                    <th width="80">#ID</th>
                    <th>Asunto</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th class="text-end" width="120">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ticketsData as $ticket)
                    <tr class="ticket-row">
                        <td class="fw-bold text-primary">#{{ $ticket['id'] }}</td>
                        <td>
                            <div class="fw-bold">{{ $ticket['titulo'] ?? 'Sin título' }}</div>
                            @if(isset($ticket['mensaje']) && strlen($ticket['mensaje']) > 0)
                                <div class="small text-muted text-truncate" style="max-width: 300px;">
                                    {{ Str::limit(strip_tags($ticket['mensaje']), 60) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @php
                                $prioridadTexto = strtolower($ticket['prioridad']['texto'] ?? 'media');
                                $prioridadColor = 'primary';
                                if (in_array($prioridadTexto, ['urgente', 'crítica', 'alta'])) {
                                    $prioridadColor = 'danger';
                                } elseif (in_array($prioridadTexto, ['media', 'normal'])) {
                                    $prioridadColor = 'warning';
                                } elseif (in_array($prioridadTexto, ['baja', 'bajo'])) {
                                    $prioridadColor = 'success';
                                }
                            @endphp
                            <span class="priority-dot bg-{{ $prioridadColor }}"></span>
                            <span class="small">{{ $ticket['prioridad']['texto'] ?? 'Media' }}</span>
                        </td>
                        <td>
                            @php
                                $estadoTexto = strtolower($ticket['estado']['texto'] ?? 'abierto');
                                $badgeClass = 'badge-soft-primary';
                                if (in_array($estadoTexto, ['cerrado', 'resuelto', 'completado'])) {
                                    $badgeClass = 'badge-soft-success';
                                } elseif (in_array($estadoTexto, ['en proceso', 'pendiente', 'asignado'])) {
                                    $badgeClass = 'badge-soft-warning';
                                } elseif (in_array($estadoTexto, ['crítico', 'urgente'])) {
                                    $badgeClass = 'badge-soft-danger';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }} rounded-pill px-3">
                                {{ $ticket['estado']['texto'] ?? 'Abierto' }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ \Carbon\Carbon::parse($ticket['created_at'] ?? now())->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('soporte.show', $ticket['id']) }}" 
                               class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1">
                                <i class="bi bi-eye"></i>
                                <span class="d-none d-md-inline">Ver</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center gap-3">
                                <div class="bg-light rounded-circle p-4">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">No hay tickets registrados</h6>
                                    <p class="text-muted small mb-0">Crea tu primer ticket para comenzar</p>
                                </div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#soporteModal">
                                    <i class="bi bi-plus-lg me-1"></i> Crear Ticket
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    @isset($tickets['pagination'])
        @if (($tickets['pagination']['total'] ?? 0) > 0)
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="text-muted mb-0 small">
                        Mostrando {{ count($ticketsData) }} de {{ $tickets['pagination']['total'] }} tickets
                    </p>
                    <nav>
                        <ul class="pagination justify-content-end mb-0">
                            @if (isset($tickets['pagination']['prev_page_url']) && $tickets['pagination']['prev_page_url'])
                                <li class="page-item">
                                    <a class="page-link border-0" href="{{ $tickets['pagination']['prev_page_url'] }}" rel="prev">
                                        <i class="bi bi-chevron-left"></i> Anterior
                                    </a>
                                </li>
                            @endif

                            @if (isset($tickets['pagination']['next_page_url']) && $tickets['pagination']['next_page_url'])
                                <li class="page-item">
                                    <a class="page-link border-0" href="{{ $tickets['pagination']['next_page_url'] }}" rel="next">
                                        Siguiente <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    @endisset
</div>

<!-- Componente de Formulario de Soporte -->
<x-formulario-soporte />

@push('styles')
<style>
    /* Estilos adicionales para la vista de tickets */
    .ticket-row {
        transition: all 0.2s ease;
    }
    
    .ticket-row:hover {
        background-color: #fafbfc;
        cursor: pointer;
    }
    
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
    }
    
    .priority-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        vertical-align: middle;
    }
</style>
@endpush

@endsection
