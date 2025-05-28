<div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-paperclip me-2"></i>
            Archivos Adjuntos
        </h5>
    </div>

    <div class="card-body">
        @php
            $baseUrl = rtrim(config('3312client.url'), '/');
        @endphp

        @forelse ($ticket['ticket']['archivos'] ?? [] as $archivo)
            <div class="d-flex mb-3 align-items-center">
                <div class="file-thumbnail me-3">
                    @switch($archivo['mime_type'])
                        @case('application/pdf')
                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                            @break
                        @case('application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                            <i class="fas fa-file-word fa-2x text-primary"></i>
                            @break
                        @case('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                            <i class="fas fa-file-excel fa-2x text-success"></i>
                            @break
                        @case('application/vnd.openxmlformats-officedocument.presentationml.presentation')
                            <i class="fas fa-file-powerpoint fa-2x text-warning"></i>
                            @break
                        @default
                            <i class="fas fa-file-alt fa-2x text-secondary"></i>
                    @endswitch
                </div>

                <div>
                    <h6 class="mb-1">{{ $archivo['file_name'] }}</h6>
                    <a href="{{ $baseUrl . '/storage/' . $archivo['file_name'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i> Ver
                    </a>
                </div>
            </div>
        @empty
            <p class="text-center text-muted mb-0">No hay archivos adjuntos</p>
        @endforelse
    </div>
</div>
