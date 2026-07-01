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
        <div class="stat-card h-100">
            <div class="stat-label"><span class="stat-dot" style="background:#6B6B78"></span>Totales</div>
            <div class="stat-value">{{ $totalTickets }}</div>
            <div class="stat-hint">Tickets en total</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-label"><span class="stat-dot" style="background:#C77D0A"></span>Pendientes</div>
            <div class="stat-value">{{ $pendientes }}</div>
            <div class="stat-hint">Requieren atención</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-label"><span class="stat-dot" style="background:#1F8A5B"></span>Resueltos</div>
            <div class="stat-value">{{ $resueltos }}</div>
            <div class="stat-hint">Cerrados o resueltos</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-label"><span class="stat-dot" style="background:#B1005F"></span>Críticos</div>
            <div class="stat-value">{{ $criticos }}</div>
            <div class="stat-hint">Prioridad alta o urgente</div>
        </div>
    </div>
</div>

<!-- Card Principal con Tabla -->
<div class="card overflow-hidden">
    <div class="card-header bg-white border-0 py-3 d-flex flex-wrap justify-content-between align-items-center gap-3" style="border-bottom:1px solid var(--border-softer) !important;">
        <div class="d-flex align-items-center gap-2">
            <h5 class="mb-0 fw-bold font-display">Lista de Tickets</h5>
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
                    <th width="90">ID</th>
                    <th>Asunto</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th class="text-end" width="120">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ticketsData as $ticket)
                    <tr class="ticket-row" onclick="window.location='{{ route('soporte.show', $ticket['id']) }}'">
                        <td class="ticket-id">TK-{{ $ticket['id'] }}</td>
                        <td>
                            <div class="fw-semibold">{{ $ticket['titulo'] ?? 'Sin título' }}</div>
                            @if(isset($ticket['mensaje']) && strlen($ticket['mensaje']) > 0)
                                <div class="small text-truncate" style="max-width: 320px; color: var(--text-faint);">
                                    {{ Str::limit(strip_tags($ticket['mensaje']), 60) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @include('3312client::components._pill', ['texto' => $ticket['prioridad']['texto'] ?? 'Media', 'kind' => 'prioridad'])
                        </td>
                        <td>
                            @include('3312client::components._pill', ['texto' => $ticket['estado']['texto'] ?? 'Abierto', 'kind' => 'estado'])
                        </td>
                        <td class="small" style="color: var(--text-muted);">
                            {{ \Carbon\Carbon::parse($ticket['created_at'] ?? now())->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('soporte.show', $ticket['id']) }}"
                               onclick="event.stopPropagation()"
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
                                <div class="rounded-circle p-4" style="background: var(--brand-tint);">
                                    <i class="bi bi-inbox fs-1" style="color: var(--primary-color);"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1" style="color: var(--text-main);">No hay tickets registrados</h6>
                                    <p class="small mb-0" style="color: var(--text-muted);">Crea tu primer ticket para comenzar</p>
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

@endsection
