<div class="modal fade" id="soporteModal" tabindex="-1" aria-labelledby="soporteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="soporteModalLabel">Soporte Técnico</h5>
                <a href="{{ route('soporte.index') }}" class="btn btn-link">
                    Ver mis tickets
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form method="POST" action="{{ route('soporte.store') }}" id="soporteForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    @php
                        $__soporteGuard = config('3312client.auth_guard');
                        $__soporteUser = Auth::guard($__soporteGuard)->user();
                        $__soporteFields = config('3312client.user_fields', []);
                    @endphp
                    <input type="hidden" name="name" value="{{ $__soporteUser?->{$__soporteFields['name'] ?? 'name'} ?? '' }}" required>
                    <input type="hidden" name="lastname" value="{{ $__soporteUser?->{$__soporteFields['lastname'] ?? 'lastname'} ?? '' }}" required>
                    <input type="hidden" name="current_url" value="{{ url()->current() }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">✉️ Email</label>
                                <input readonly type="email" class="form-control form-control-sm" id="email" name="email"
                                    value="{{ $__soporteUser?->{$__soporteFields['email'] ?? 'email'} ?? '' }}"
                                    {{ $__soporteUser ? 'readonly' : '' }} required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">📞 Teléfono</label>
                                <input type="number" class="form-control form-control-sm" id="phone" name="phone"
                                    placeholder="Ingrese su teléfono" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_ticket" class="form-label">🏷️ Tipo de Ticket</label>
                                <select class="form-control form-control-sm" id="tipo_ticket" name="tipo_ticket_id" required>
                                    @foreach($tiposTicket['data'] as $tipo)
                                        <option value="{{ $tipo['id'] }}">{{ $tipo['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">❗ Prioridad</label>
                                <select class="form-control form-control-sm" id="prioridad" name="prioridad" required>
                                    <option value="baja">Baja 🟢</option>
                                    <option value="media">Media 🟡</option>
                                    <option value="alta">Alta 🔴</option>
                                    <option value="urgente">Urgente 🚨</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="asunto" class="form-label">🗒️ Asunto</label>
                        <input type="text" class="form-control form-control-sm" id="asunto" name="titulo"
                            placeholder="Escribe el asunto del ticket" required>
                    </div>

                    <input type="hidden" id="url" name="url" readonly>

                    {{-- Editor de texto enriquecido --}}
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">📝 Mensaje</label>
                        <div id="editor" style="height: 200px; background: white;"></div>
                        <textarea name="mensaje" id="mensaje" hidden></textarea>
                    </div>
                    

                    <div class="mb-3">
                        <label for="archivos" class="form-label">📎 Adjuntar archivos</label>
                        <input type="file" class="form-control form-control-sm" id="archivos" name="archivos[]" multiple>
                        <small class="text-muted">Puedes adjuntar varios archivos (máx. 10MB cada uno).</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Escribe tu mensaje aquí...'
        });

        const form = document.getElementById('soporteForm');
        form.addEventListener('submit', function () {
            document.getElementById('mensaje').value = quill.root.innerHTML;

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Enviando...';

            const phone = document.getElementById('phone').value;
            localStorage.setItem('phone', phone);
        });

        const savedPhone = localStorage.getItem('phone');
        if (savedPhone) {
            document.getElementById('phone').value = savedPhone;
        }
    });
</script>
@endpush

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush
