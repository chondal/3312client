@php
    $baseUrl = rtrim(config('3312client.url'), '/');
    $archivos = $ticket['ticket']['archivos'] ?? [];
@endphp

<div class="detail-title mt-4 mb-2">Adjuntos</div>

@forelse ($archivos as $archivo)
    @php
        $icono = match ($archivo['mime_type'] ?? '') {
            'application/pdf' => 'bi-file-earmark-pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'bi-file-earmark-word',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'bi-file-earmark-spreadsheet',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'bi-file-earmark-slides',
            default => str_starts_with($archivo['mime_type'] ?? '', 'image/') ? 'bi-file-earmark-image' : 'bi-file-earmark-text',
        };
    @endphp
    <a href="{{ $baseUrl . '/storage/' . $archivo['file_name'] }}" target="_blank" class="attach-row">
        <span class="attach-icon"><i class="bi {{ $icono }}"></i></span>
        <span class="min-w-0">
            <span class="d-block text-truncate" style="font-size:13px;font-weight:500;color:var(--text-main);">{{ $archivo['file_name'] }}</span>
            <span class="d-block" style="font-size:11.5px;color:var(--text-faint);">Ver archivo</span>
        </span>
    </a>
@empty
    <div style="font-size: 13px; color: #C0C0BE;">Sin archivos adjuntos.</div>
@endforelse
