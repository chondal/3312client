<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Centro de Soporte')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bs-body-font-family: 'Inter', sans-serif;
            --sidebar-width: 260px;
            --primary-color: #435ebe;
            --light-bg: #f2f7ff;
        }

        body {
            background-color: var(--light-bg);
            font-family: var(--bs-body-font-family);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            background: #fff;
            transition: all 0.3s;
            border-right: 1px solid #eef2f6;
            overflow-y: auto;
        }

        #main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: all 0.3s;
            min-height: 100vh;
        }

        .nav-link {
            color: #607080;
            padding: 0.8rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
            margin: 0 1rem 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background-color: #f0f4ff;
        }

        .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(67, 94, 190, 0.3);
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Table Styling */
        .table-custom th {
            font-weight: 600;
            color: #8898aa;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-custom td {
            vertical-align: middle;
            padding: 1rem 1.5rem;
            color: #444;
            border-bottom: 1px solid #f8f9fa;
        }

        .ticket-row:hover {
            background-color: #fafbfc;
            cursor: pointer;
        }

        /* Priority Indicators */
        .priority-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        /* Mobile Adjustments */
        @media (max-width: 991px) {
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }
            #sidebar.active {
                margin-left: 0;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }
            #main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }

        /* Avatar Group */
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }
        
        .badge-soft-success { 
            background: #d1e7dd; 
            color: #0f5132; 
        }
        .badge-soft-warning { 
            background: #fff3cd; 
            color: #664d03; 
        }
        .badge-soft-danger { 
            background: #f8d7da; 
            color: #842029; 
        }
        .badge-soft-primary { 
            background: #cfe2ff; 
            color: #084298; 
        }

        /* Alertas flash personalizadas */
        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .alert-danger {
            background: #f8d7da;
            color: #842029;
        }

        .alert-warning {
            background: #fff3cd;
            color: #664d03;
        }

        .alert-info {
            background: #cfe2ff;
            color: #084298;
        }

        /* Mensajes de ticket */
        .ticket-message {
            padding: 1.25rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .ticket-message.user-message {
            background: #f0f4ff;
            border-left: 4px solid var(--primary-color);
        }

        .ticket-message.admin-message {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
        }

        .ticket-message .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .ticket-message .message-author {
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ticket-message .message-time {
            font-size: 0.8125rem;
            color: #64748b;
        }

        .ticket-message .message-content {
            color: #1e293b;
            line-height: 1.7;
        }

        /* Formularios */
        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 94, 190, 0.15);
        }

        /* Botones */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #3b51a3;
            border-color: #3b51a3;
            transform: translateY(-1px);
        }

        /* Offcanvas personalizado */
        .offcanvas {
            border-left: 1px solid #eef2f6;
        }

        .offcanvas-header {
            background: #fff;
        }

        .offcanvas-body {
            background: var(--light-bg);
        }

        /* Paginación personalizada */
        .pagination .page-link {
            border: none;
            color: #607080;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Scrollbar personalizado */
        #sidebar::-webkit-scrollbar {
            width: 6px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        #sidebar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="d-flex flex-column justify-content-between pb-4">
        <div>
            <!-- Logo -->
            <div class="d-flex align-items-center justify-content-center py-4 mb-2">
                <a href="{{ route('soporte.index') }}" class="d-flex align-items-center gap-2 text-primary fw-bold fs-4 text-decoration-none">
                    <i class="bi bi-ticket-perforated-fill"></i> 
                    <span>DVS<span class="text-dark">360</span></span>
                </a>
            </div>

            <!-- Navegación -->
            <div class="nav flex-column">
                <a href="{{ route('soporte.index') }}" class="nav-link {{ request()->routeIs('soporte.index') ? 'active' : '' }}">
                    <i class="bi bi-inbox-fill"></i> 
                    <span>Mis Tickets</span>
                </a>
                @php
                    // Intentar obtener la ruta de retorno al sistema principal
                    $backRoute = config('3312client.back_route', null);
                    if (!$backRoute) {
                        // Intentar usar la ruta dashboard si existe
                        try {
                            $backRoute = route('dashboard');
                        } catch (\Exception $e) {
                            // Si no existe, usar la raíz
                            $backRoute = url('/');
                        }
                    } else {
                        // Si es una ruta con nombre, intentar resolverla
                        try {
                            $backRoute = route($backRoute);
                        } catch (\Exception $e) {
                            // Si falla, usar como URL directa
                            $backRoute = url($backRoute);
                        }
                    }
                @endphp
                <a href="{{ $backRoute }}" class="nav-link">
                    <i class="bi bi-arrow-left-circle-fill"></i> 
                    <span>Volver al Sistema</span>
                </a>
            </div>
        </div>

        <!-- Footer del Sidebar -->
        <div class="px-4">
            <div class="card bg-primary text-white p-3 border-0" style="background: linear-gradient(45deg, #435ebe, #3b51a3); cursor: pointer;" 
                 data-bs-toggle="modal" 
                 data-bs-target="#whatsappSupportModal">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 p-2 rounded">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.372a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.893c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </div>
                    <div>
                        <small class="d-block opacity-75">Soporte</small>
                        <span class="fw-bold">WhatsApp</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content">
        <!-- Top Navbar -->
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none shadow-sm" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h4 class="fw-bold mb-0">@yield('page-title', 'Tickets')</h4>
                    <p class="text-muted mb-0 small">@yield('page-subtitle', 'Gestión y seguimiento de incidencias')</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                @auth
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Usuario') }}&background=435ebe&color=fff" 
                             class="avatar shadow-sm" 
                             alt="{{ auth()->user()->name ?? 'Usuario' }}">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                        <li><h6 class="dropdown-header">Hola, {{ auth()->user()->name ?? 'Usuario' }}</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger w-100 text-start border-0 bg-transparent">
                                    <i class="bi bi-box-arrow-right me-2"></i> Salir
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </header>

        <!-- Alertas Flash -->
        @if(session('flash_notification'))
            <div class="alert alert-{{ session('flash_notification.level') }} alert-dismissible fade show" role="alert">
                {!! session('flash_notification.message') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @include('flash::message')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Contenido Principal -->
        @yield('content')
    </main>

    <!-- Modal de Soporte WhatsApp -->
    <div class="modal fade" id="whatsappSupportModal" tabindex="-1" aria-labelledby="whatsappSupportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="#25D366" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.372a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.893c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="whatsappSupportModalLabel">Soporte por WhatsApp</h5>
                            <p class="text-muted small mb-0">Contacto directo y rápido</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4" role="alert">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <div>
                                <strong>Importante:</strong> Este canal de <strong>WhatsApp</strong> está disponible únicamente para <strong>urgencias</strong> o situaciones que no puedan ser explicadas adecuadamente a través de un ticket.
                            </div>
                        </div>
                    </div>
                    
                    <p class="mb-3">
                        Para consultas regulares, te recomendamos utilizar nuestro <strong>sistema de tickets</strong>. 
                        Nuestro equipo responderá muy pronto y te contactará si necesitamos alguna información adicional para resolver tu consulta.
                    </p>
                    
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/5491137470915" 
                           target="_blank" 
                           class="btn btn-success btn-lg d-flex align-items-center justify-content-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.372a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.893c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span>Contactar por WhatsApp (Urgencias)</span>
                        </a>
                        <p class="text-center text-muted small mb-0 mt-2">
                            <i class="bi bi-telephone-fill"></i> +54 9 11 3747-0915
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle para móviles
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }

        // Cerrar sidebar al hacer clic fuera en móviles
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 992) {
                if (sidebar && !sidebar.contains(e.target) && sidebarToggle && !sidebarToggle.contains(e.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Auto-cerrar alertas después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }, 5000);
            });
        });

        // Configuración global de AJAX
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar token CSRF para todas las peticiones AJAX
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.axios = window.axios || {};
                window.axios.defaults = window.axios.defaults || {};
                window.axios.defaults.headers = window.axios.defaults.headers || {};
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
