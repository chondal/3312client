@extends(config('3312client.layoutpath'))

@section('content')


@include('flash::message')

<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title mb-0 d-flex align-items-center">
                        📩 Mis Tickets de Soporte
                    </h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#soporteModal">
                        ➕ Nuevo Ticket
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>🔢 ID</th>
                                    <th>📝 Título</th>
                                    <th>📍 Estado</th>
                                    <th>⚠️ Prioridad</th>
                                    <th>📅 Creado</th>
                                    <th>📌 Cierre</th>
                                    <th>🔧 Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets['data'] ?? [] as $ticket)
                                    <tr>
                                        <td class="fw-bold">#{{ $ticket['id'] }}</td>
                                        <td>{{ $ticket['titulo'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $ticket['estado']['color'] }}">
                                                {{ $ticket['estado']['texto'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $ticket['prioridad']['color'] }}">
                                                {{ $ticket['prioridad']['texto'] }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($ticket['created_at'])->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            @if (!empty($ticket['cerrado_en']))
                                                ✅ {{ \Carbon\Carbon::parse($ticket['cerrado_en'])->format('d/m/Y H:i') }}
                                            @else
                                                ⏳ En proceso
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('soporte.show', $ticket['id']) }}"
                                                class="btn btn-sm btn-info">
                                                👁️ Ver Detalles
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            📭 No hay tickets registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @isset($tickets['pagination'])
                        @if ($tickets['pagination']['total'] > 0)
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <p class="text-muted mb-0">
                                    Mostrando {{ count($tickets['data']) }} de {{ $tickets['pagination']['total'] }}
                                    tickets
                                </p>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        @if ($tickets['pagination']['prev_page_url'])
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $tickets['pagination']['prev_page_url'] }}" rel="prev">
                                                    ◀️ Anterior
                                                </a>
                                            </li>
                                        @endif

                                        @if ($tickets['pagination']['next_page_url'])
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $tickets['pagination']['next_page_url'] }}" rel="next">
                                                    Siguiente ▶️
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        @endif
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>

<x-formulario-soporte />


@push('styles')
<style>
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
    }
</style>
@endpush

@endsection
