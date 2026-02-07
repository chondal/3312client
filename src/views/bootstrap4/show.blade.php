@extends(config('3312client.layoutpath'))

@section('content')
<div class="md-4">
    <div class="row">
        <div class="col-lg-10 col-xl-9">
            <div class="card shadow-sm border-0">
                @if (isset($ticket['ticket']))
                    <div class="card-header bg-light d-flex justify-content-between align-items-center border-bottom">
                        <div>
                            <h5 class="mb-1">
                                Ticket #{{ $ticket['ticket']['id'] ?? 'N/A' }} -
                                <span class="text-muted">{{ $ticket['ticket']['titulo'] ?? 'Sin título' }}</span>
                            </h5>
                            <div class="mt-1">
                                @if (isset($ticket['ticket']['estado']))
                                    <span class="badge badge-{{ $ticket['ticket']['estado']['color'] ?? 'secondary' }}">
                                        {{ $ticket['ticket']['estado']['texto'] ?? 'Estado desconocido' }}
                                    </span>
                                @endif
                                @if (isset($ticket['ticket']['prioridad']))
                                    <span class="badge badge-{{ $ticket['ticket']['prioridad']['color'] ?? 'secondary' }} ml-2">
                                        Prioridad: {{ $ticket['ticket']['prioridad']['texto'] ?? 'No definida' }}
                                    </span>
                                @endif
                                @if (isset($ticket['ticket']['tipo']))
                                    <span class="badge badge-secondary ml-2">
                                        {{ $ticket['ticket']['tipo']['nombre'] ?? 'Tipo no definido' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('soporte.index') }}" class="btn btn-sm btn-outline-secondary">Volver</a>
                            @if ($ticket['ticket']['estado']['texto'] !== 'Cerrado')
                                <button class="btn btn-sm btn-primary ml-2" onclick="scrollToResponse()">Responder</button>
                                <button class="btn btn-sm btn-danger ml-2" data-toggle="modal" data-target="#modalCerrarTicket">
                                    Cerrar Ticket
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row text-muted mb-3">
                            <div class="col-md-4">
                                <strong>Creación:</strong><br>
                                {{ \Carbon\Carbon::parse($ticket['ticket']['created_at'])->format('d/m/Y H:i') ?? '' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Área:</strong><br>
                                {{ $ticket['ticket']['area']['nombre'] ?? 'Sin asignar' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Asignado a:</strong><br>
                                {{ $ticket['ticket']['admin']['name'] ?? 'N/D' }} {{ $ticket['ticket']['admin']['lastname'] ?? '' }}
                            </div>
                        </div>

                        <div class="ticket-messages mb-4">
                            @forelse($ticket['ticket']['mensajes'] ?? [] as $mensaje)
                                @if (!($mensaje['es_interno'] ?? false))
                                    <div class="message {{ isset($mensaje['admin']) ? 'admin-message' : 'user-message' }}">
                                        <div class="message-header d-flex justify-content-between">
                                            <strong>
                                                {{ $mensaje['admin']['name'] ?? $mensaje['user']['name'] ?? 'Desconocido' }}
                                            </strong>
                                            <span class="text-muted">
                                                {{ \Carbon\Carbon::parse($mensaje['created_at'])->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        <div class="message-content mt-2">
                                            {!! $mensaje['mensaje'] !!}
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="alert alert-info">No hay mensajes disponibles.</div>
                            @endforelse
                        </div>

                        {{-- Adjuntos --}}
                        @includeIf('3312client::components._adjuntos', ['ticket' => $ticket])

                        {{-- Formulario Respuesta --}}
                        <form action="{{ route('soporte.responder', $ticket['ticket']['id']) }}" method="POST" enctype="multipart/form-data" id="formResponder">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Responder</label>
                                <textarea name="respuesta" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Archivos adjuntos</label>
                                <input type="file" class="form-control" name="archivos[]" multiple>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar respuesta</button>
                        </form>
                    </div>
                @else
                    <div class="card-body">
                        <div class="alert alert-warning">No se encontró información del ticket.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function scrollToResponse() {
        const form = document.getElementById('formResponder');
        if (form) {
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
</script>
@endpush

@endsection
