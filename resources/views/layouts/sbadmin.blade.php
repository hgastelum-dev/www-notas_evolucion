<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('img/geialogo.jpg') }}">
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('fonts/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --geia-primary: #2563eb;
            --geia-primary-dark: #1d4ed8;
            --geia-accent: #0d9488;
            --geia-bg: #f3f5f9;
            --geia-sidebar-bg: #ffffff;
            --geia-text-muted: #6b7280;
            --geia-border: #e5e7eb;

            --geia-sidebar-green: #1f7a4d;
            --geia-sidebar-green-dark: #145c38;
            --geia-sidebar-text: #ffffff;
            --geia-sidebar-text-muted: rgba(255, 255, 255, 0.65);
            --geia-sidebar-hover: rgba(255, 255, 255, 0.10);
            --geia-sidebar-active: rgba(255, 255, 255, 0.18);
        }

        body {
            background-color: var(--geia-bg);
        }

        #accordionSidebar {
            background: var(--geia-sidebar-bg) !important;
            border-right: 1px solid var(--geia-border);
        }

        .sidebar-brand {
            color: var(--geia-primary-dark) !important;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .sidebar .nav-item .nav-link {
            color: #374151;
            border-left: 3px solid transparent;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .sidebar .nav-item .nav-link:hover {
            background-color: #eef2ff;
        }

        .sidebar .nav-item.active > .nav-link,
        .sidebar .nav-item .nav-link[aria-expanded="true"] {
            border-left-color: var(--geia-primary);
            background-color: #eef2ff;
            color: var(--geia-primary-dark);
            font-weight: 600;
        }

        .sidebar .collapse-item.active {
            background-color: var(--geia-primary) !important;
            color: #fff !important;
        }

        .sidebar-heading {
            color: #9ca3af;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
        }

        .sidebar-role-badge {
            margin: 0.5rem 1rem 1rem;
            padding: 0.6rem 0.8rem;
            background: #f8fafc;
            border: 1px solid var(--geia-border);
            border-radius: 0.5rem;
            text-align: left;
        }

        .sidebar-role-badge .usuario-nombre {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-role-badge .usuario-rol {
            display: inline-block;
            margin-top: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.15rem 0.5rem;
            border-radius: 1rem;
            background: var(--geia-accent);
            color: #fff;
        }

        .topbar {
            border-bottom: 1px solid var(--geia-border);
        }

        .topbar-fecha {
            font-size: 0.8rem;
            color: var(--geia-text-muted);
        }

        .geia-breadcrumb-bar {
            background: #fff;
            border-bottom: 1px solid var(--geia-border);
            padding: 0.6rem 1.5rem;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
            font-size: 0.85rem;
        }

        .geia-breadcrumb-bar a {
            color: var(--geia-primary);
            text-decoration: none;
        }

        .geia-breadcrumb-bar a:hover {
            text-decoration: underline;
        }

        .geia-breadcrumb-bar .separador {
            color: #cbd5e1;
            margin: 0 0.4rem;
        }

        .geia-breadcrumb-bar .actual {
            color: #1f2937;
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--geia-primary);
            border-color: var(--geia-primary);
        }

        .btn-primary:hover {
            background-color: var(--geia-primary-dark);
            border-color: var(--geia-primary-dark);
        }
    </style>
    @yield('styles')
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-light sidebar accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/tablero">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('img/geialogo.jpg') }}" height="50">
                </div>
                <div class="sidebar-brand-text mx-3">GEIA Med.</div>
            </a>

            <hr class="sidebar-divider my-0">

            @php
                $rolEtiqueta = null;
                if (Auth::check()) {
                    if (Auth::user()->can('CitaGestionarTodas')) {
                        $rolEtiqueta = 'Recepción';
                    } elseif (Auth::user()->can('CitaAtender')) {
                        $rolEtiqueta = 'Doctor(a)';
                    } elseif (Auth::user()->can('CitaGestionar')) {
                        $rolEtiqueta = 'Agenda';
                    }
                }
            @endphp

            @if($rolEtiqueta)
                <div class="sidebar-role-badge">
                    <span class="usuario-nombre">{{ Auth::user()->name }}</span>
                    <span class="usuario-rol">{{ $rolEtiqueta }}</span>
                </div>
            @endif

            <li class="nav-item {{ request()->is('tablero') ? 'active' : '' }}">
                <a class="nav-link" href="/tablero">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tablero</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Modulos
            </div>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('pacientes*') || request()->is('paciente*') ? '' : 'collapsed' }}"
                   href="#"
                   data-toggle="collapse"
                   data-target="#collapsePacientes"
                   aria-expanded="{{ request()->is('pacientes*') || request()->is('paciente*') ? 'true' : 'false' }}"
                   aria-controls="collapsePacientes">

                    <i class="fas fa-users"></i>
                    <span>Pacientes</span>
                </a>

                <div id="collapsePacientes"
                     class="collapse {{ request()->is('pacientes*') || request()->is('paciente*') ? 'show' : '' }}"
                     aria-labelledby="headingTwo"
                     data-parent="#accordionSidebar">

                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Opciones:</h6>

                        <a class="collapse-item {{ request()->is('pacientes/alta') ? 'active' : '' }}"
                           href="/pacientes/alta">
                            Alta
                        </a>

                        <a class="collapse-item {{ request()->is('pacientes') ? 'active' : '' }}"
                           href="/pacientes">
                            Listado
                        </a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('agenda*') || request()->is('citas*') || request()->is('cita*') ? '' : 'collapsed' }}"
                   href="#"
                   data-toggle="collapse"
                   data-target="#collapseAgenda"
                   aria-expanded="{{ request()->is('agenda*') || request()->is('citas*') || request()->is('cita*') ? 'true' : 'false' }}"
                   aria-controls="collapseAgenda">

                    <i class="fas fa-clock"></i>
                    <span>Agenda</span>
                </a>

                <div id="collapseAgenda"
                     class="collapse {{ request()->is('agenda*') || request()->is('citas*') || request()->is('cita*') ? 'show' : '' }}"
                     aria-labelledby="headingUtilities"
                     data-parent="#accordionSidebar">

                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Opciones:</h6>

                        <a class="collapse-item {{ request()->is('agenda') ? 'active' : '' }}"
                           href="/agenda">
                            Calendario
                        </a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('usuarios*') || request()->is('usuario*') ? '' : 'collapsed' }}"
                   href="#"
                   data-toggle="collapse"
                   data-target="#collapseUsuarios"
                   aria-expanded="{{ request()->is('usuarios*') ? 'true' : 'false' }}"
                   aria-controls="collapseUsuarios">

                    <i class="fas fa-user-shield"></i>
                    <span>Usuarios</span>
                </a>

                <div id="collapseUsuarios"
                     class="collapse {{ request()->is('usuarios*') ? 'show' : '' }}"
                     aria-labelledby="headingUsuarios"
                     data-parent="#accordionSidebar">

                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Opciones:</h6>

                        <a class="collapse-item {{ request()->is('usuarios') ? 'active' : '' }}"
                           href="/usuarios">
                            Gestionar usuarios
                        </a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <span class="topbar-fecha d-none d-md-inline">
                        <i class="far fa-calendar-alt"></i>
                        {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}
                    </span>

                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-700 small">
                                    <b>{{ Auth::user()->name }}</b>
                                    @if($rolEtiqueta)
                                        &middot; {{ $rolEtiqueta }}
                                    @endif
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('sbadmin/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <span class="dropdown-item-text small text-gray-500">
                                    {{ Auth::user()->email }}
                                </span>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesi&oacute;n
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>

                <div class="container-fluid">

                    @php
                        $etiquetasRuta = [
                            'tablero' => 'Tablero',
                            'pacientes' => 'Pacientes',
                            'paciente' => 'Pacientes',
                            'alta' => 'Alta de paciente',
                            'editar' => 'Editar',
                            'antecedentes' => 'Antecedentes',
                            'padecimientos' => 'Padecimiento actual',
                            'exp-fisica' => 'Exploración física',
                            'plan' => 'Plan inicial',
                            'notas-hist' => 'Histórico de notas',
                            'nota-hist' => 'Nota histórica',
                            'agenda' => 'Agenda',
                            'citas' => 'Agenda',
                            'cita' => 'Agenda',
                            'atender' => 'Atención de cita',
                            'soap01' => 'Subjetivo',
                            'subjetivo' => 'Subjetivo',
                            'soap02' => 'Objetivo',
                            'objetivo' => 'Objetivo',
                            'soap03' => 'Análisis',
                            'analisis' => 'Análisis',
                            'soap04' => 'Planeación',
                            'planeacion' => 'Planeación',
                            'usuarios' => 'Usuarios',
                            'registrar' => 'Registrar',
                        ];

                        $segmentos = collect(request()->segments())
                            ->reject(fn($s) => is_numeric($s))
                            ->map(fn($s) => $etiquetasRuta[$s] ?? ucfirst(str_replace('-', ' ', $s)))
                            ->unique()
                            ->values();
                    @endphp

                    @if($segmentos->count() > 0)
                        <div class="geia-breadcrumb-bar">
                            <a href="/tablero"><i class="fas fa-home"></i> Inicio</a>
                            @foreach($segmentos as $i => $segmento)
                                <span class="separador">/</span>
                                @if($i === $segmentos->count() - 1)
                                    <span class="actual">{{ $segmento }}</span>
                                @else
                                    <span>{{ $segmento }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @yield('container')
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>GEIA Med. &copy; Derechos Reservados, {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Est&aacute; seguro de querer finalizar su sesi&oacute;n?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Seleccione <b>Cerrar sesi&oacute;n</b> para proceder.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a class="btn btn-primary" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesi&oacute;n
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script>

    @yield('scripts')

</body>
</html>