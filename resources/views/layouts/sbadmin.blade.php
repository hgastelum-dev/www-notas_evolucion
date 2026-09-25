<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name') }}</title>
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('fonts/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    @yield('styles')
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-light sidebar accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/tablero">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('img/geialogo.jpg') }}" height="50">
                </div>
                <div class="sidebar-brand-text mx-3">GEIA Med.<sup></sup></div>
            </a>

            <hr class="sidebar-divider my-0">

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

                    <i class="fas fa-clock"></i>
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

                    
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Buscar" aria-label="Buscar"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    {{ Auth::user()->email }}
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('sbadmin/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesi&oacute;n
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                
                <div class="container-fluid">

                    @yield('container')
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>GEIA Med. &copy; Derechos Reservados, 2024</span>
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