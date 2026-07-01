@extends(config('3312client.layoutpath'))

@section('title', 'Detalle del Ticket')
@section('page-title', 'Detalle del Ticket')
@section('page-subtitle', 'Conversación y seguimiento de la incidencia')

@php
    // Helper de iniciales (máx. 2)
    if (!function_exists('soporte_initials')) {
        function soporte_initials($name) {
            $name = trim((string) $name);
            if ($name === '') return '·';
            $parts = preg_split('/\s+/', $name);
            $ini = '';
            foreach (array_slice($parts, 0, 2) as $p) {
                $ini .= mb_strtoupper(mb_substr($p, 0, 1));
            }
            return $ini ?: '·';
        }
    }
@endphp

@section('content')
@if (isset($ticket['ticket']))
    @php
        $t = $ticket['ticket'];
        $estadoTexto = $t['estado']['texto'] ?? 'Abierto';
        $prioridadTexto = $t['prioridad']['texto'] ?? 'Media';
        $agenteNombre = trim(($t['admin']['name'] ?? '') . ' ' . ($t['admin']['lastname'] ?? ''));
        $agenteNombre = $agenteNombre !== '' ? $agenteNombre : 'Sin asignar';
        $esCerrado = mb_strtolower($estadoTexto) === 'cerrado';
    @endphp

    {{-- Cabecera del ticket --}}
    <div class="card mb-4">
        <div class="card-body p-4">
            <a href="{{ route('soporte.index') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none fw-semibold mb-3" style="color: var(--primary-color); font-size: 13px;">
                <i class="bi bi-chevron-left"></i> Mis tickets
            </a>
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                <div class="min-w-0">
                    <div class="d-flex align-items-center gap-2 mb-1" style="color: var(--text-faint); font-size: 13px;">
                        <span class="ticket-id">TK-{{ $t['id'] ?? 'N/A' }}</span>
                        <span style="width:4px;height:4px;border-radius:50%;background:#D9D9D9;"></span>
                        <span>Creado el {{ isset($t['created_at']) ? \Carbon\Carbon::parse($t['created_at'])->format('d/m/Y H:i') : '—' }}</span>
                    </div>
                    <h1 class="font-display mb-0" style="font-size: 22px; font-weight: 600; color: var(--text-main);">
                        {{ $t['titulo'] ?? 'Sin título' }}
                    </h1>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @include('3312client::components._pill', ['texto' => $prioridadTexto, 'kind' => 'prioridad'])
                    @include('3312client::components._pill', ['texto' => $estadoTexto, 'kind' => 'estado'])
                    @unless($esCerrado)
                        <button class="btn btn-primary btn-sm" onclick="scrollToResponse()">
                            <i class="bi bi-reply me-1"></i> Responder
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalCerrarTicket">
                            Cerrar
                        </button>
                    @endunless
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Columna izquierda: hilo de conversación + composer --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <div class="chat-thread mb-4">
                        @forelse($t['mensajes'] ?? [] as $mensaje)
                            @continue($mensaje['es_interno'] ?? false)
                            @php
                                $esAgente = isset($mensaje['admin']);
                                $autor = $esAgente
                                    ? trim(($mensaje['admin']['name'] ?? '') . ' ' . ($mensaje['admin']['lastname'] ?? ''))
                                    : ($mensaje['user']['name'] ?? 'Tú');
                                $autor = $autor !== '' ? $autor : ($esAgente ? 'Agente' : 'Tú');
                            @endphp
                            <div class="chat-row {{ $esAgente ? '' : 'mine' }}">
                                @if($esAgente)
                                    <span class="avatar-initials" style="width:34px;height:34px;font-size:12px;">{{ soporte_initials($autor) }}</span>
                                @endif
                                <div class="chat-col">
                                    <div class="chat-meta">
                                        <span class="chat-name">{{ $esAgente ? $autor : 'Tú' }}</span>
                                        <span class="chat-time">{{ isset($mensaje['created_at']) ? \Carbon\Carbon::parse($mensaje['created_at'])->format('d/m/Y H:i') : '' }}</span>
                                    </div>
                                    <div class="chat-bubble {{ $esAgente ? 'agent' : 'mine' }}">
                                        {!! $mensaje['mensaje'] !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4" style="color: var(--text-faint); font-size: 14px;">
                                Aún no hay mensajes en esta conversación.
                            </div>
                        @endforelse
                    </div>

                    @unless($esCerrado)
                        {{-- Composer de respuesta --}}
                        <form action="{{ route('soporte.responder', $t['id']) }}" method="POST" enctype="multipart/form-data" id="formResponder">
                            @csrf
                            <div class="composer-box">
                                <textarea name="respuesta" rows="2" placeholder="Escribe una respuesta…" required></textarea>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <label class="btn btn-sm btn-light d-inline-flex align-items-center gap-2 mb-0" style="color: var(--text-muted);">
                                        <i class="bi bi-paperclip"></i>
                                        <span>Adjuntar</span>
                                        <input type="file" name="archivos[]" multiple hidden onchange="updateAdjuntoLabel(this)">
                                    </label>
                                    <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2">
                                        Enviar <i class="bi bi-send"></i>
                                    </button>
                                </div>
                                <div id="adjuntoLabel" class="small mt-1" style="color: var(--text-muted);"></div>
                            </div>
                        </form>
                    @endunless
                </div>
            </div>
        </div>

        {{-- Columna derecha: detalles + adjuntos --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <div class="detail-title mb-3">Detalles del ticket</div>

                    <div class="detail-row">
                        <span class="detail-key">Estado</span>
                        @include('3312client::components._pill', ['texto' => $estadoTexto, 'kind' => 'estado'])
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">Prioridad</span>
                        @include('3312client::components._pill', ['texto' => $prioridadTexto, 'kind' => 'prioridad'])
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">Categoría</span>
                        <span class="detail-val">{{ $t['tipo']['nombre'] ?? 'No definida' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">Área</span>
                        <span class="detail-val">{{ $t['area']['nombre'] ?? 'Sin asignar' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">Creado</span>
                        <span class="detail-val">{{ isset($t['created_at']) ? \Carbon\Carbon::parse($t['created_at'])->format('d/m/Y H:i') : '—' }}</span>
                    </div>
                    <div class="detail-row" style="border-bottom:none;">
                        <span class="detail-key">Agente asignado</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="avatar-initials" style="width:26px;height:26px;font-size:11px;">{{ soporte_initials($agenteNombre === 'Sin asignar' ? '' : $agenteNombre) }}</span>
                            <span class="detail-val">{{ $agenteNombre }}</span>
                        </span>
                    </div>

                    {{-- Adjuntos --}}
                    @includeIf('3312client::components._adjuntos', ['ticket' => $ticket])
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body">
            <div class="alert alert-warning mb-0">No se encontró información del ticket.</div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    function scrollToResponse() {
        const form = document.getElementById('formResponder');
        if (form) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const ta = form.querySelector('textarea');
            if (ta) ta.focus();
        }
    }

    function updateAdjuntoLabel(input) {
        const label = document.getElementById('adjuntoLabel');
        if (!label) return;
        const files = Array.from(input.files || []).map(f => f.name);
        label.textContent = files.length ? files.join(', ') : '';
    }
</script>
@endpush

@endsection
