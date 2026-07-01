{{--
    Pill reutilizable de estado/prioridad (estética Ticketera).
    Uso: @include('3312client::components._pill', ['texto' => 'Abierto', 'kind' => 'estado'])
         @include('3312client::components._pill', ['texto' => 'Alta', 'kind' => 'prioridad'])
--}}
@php
    $__texto = trim((string) ($texto ?? ''));
    $__key = mb_strtolower($__texto);
    $__kind = $kind ?? 'estado';

    if ($__kind === 'prioridad') {
        $__color = match (true) {
            in_array($__key, ['urgente', 'crítica', 'critica', 'alta']) => '#B1005F',
            in_array($__key, ['media', 'normal']) => '#C77D0A',
            in_array($__key, ['baja', 'bajo']) => '#5B8A72',
            default => '#8A8A88',
        };
    } else {
        $__color = match (true) {
            in_array($__key, ['abierto', 'nuevo', 'reabierto']) => '#2F6FDB',
            in_array($__key, ['en progreso', 'en proceso', 'asignado', 'pendiente']) => '#C77D0A',
            in_array($__key, ['en espera', 'esperando']) => '#6B6B78',
            in_array($__key, ['resuelto', 'completado', 'solucionado']) => '#1F8A5B',
            in_array($__key, ['cerrado']) => '#8A8A88',
            default => '#8A8A88',
        };
    }

    $__label = $__texto !== '' ? $__texto : ($__kind === 'prioridad' ? 'Media' : 'Abierto');
@endphp
<span class="pill" style="color: {{ $__color }}; background: {{ $__color }}1A;">
    <span class="pill-dot"></span>{{ $__label }}
</span>
