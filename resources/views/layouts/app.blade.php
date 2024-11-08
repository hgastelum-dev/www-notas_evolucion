<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="HGG">
    <title>{{ config('app.name') }}</title>

    <link href="{{ asset('lib/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/css/fontawesome-free/css/all.min.css') }}">
    @yield('styles')
  </head>
  
  <body class="d-flex flex-column h-100">
    
    <header>
        {{-- Fixed navbar --}}
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/dashboard">
                    <i class="fa-solid fa-gauge-simple-high"></i> Tablero
                </a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarCollapse">
                    
                    {{-- menu de la aplicacion --}}
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                      @can('Usuarios_Ver')
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('usuarios') || request()->is('usuarios/*') ? 'active' : '' }}" aria-current="{{ request()->is('usuarios') || request()->is('usuarios/*') ? 'page' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-users"></i> Usuarios
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            @can('Usuarios_Crear')
                            <li><a class="dropdown-item" href="/usuarios/registrar">
                                <i class="fa-solid fa-user-plus"></i> Crear usuario</a>
                            </li>
                            @endcan
                            <li><a class="dropdown-item" href="/usuarios">
                                <i class="fa-solid fa-users"></i> Gestionar</a>
                            </li>
                        </ul>
                      </li>
                      @endcan

                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('sensores') || request()->is('sensores/*') ? 'active' : '' }}" aria-current="{{ request()->is('sensores') || request()->is('sensores/*') ? 'page' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-network-wired"></i> Dispositivos
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item" href="/sensores/reedswitch">
                                    <i class="fa-solid fa-microchip"></i> Sensores
                                </a>
                            </li>
                        </ul>
                      </li>
                    </ul>
                    
                    {{-- gestion de cuenta de usuario y cierre de sesion --}}
                    <div class="d-flex btn-group">
                      <button type="button" class="btn btn-sm text-white border border-white dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i> {{ Auth::user()->email }}
                      </button>
                      <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-lg-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fa-solid fa-user"></i> Mi perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesi&oacute;n
                                </a>
                            </form>
                        </li>
                      </ul>
                    </div>
                  </div>
            </div>
        </nav>
    </header>

    {{-- Begin page content --}}
    <main class="flex-shrink-0">
      <div class="container">
        @yield('content')
      </div>
    </main>

    <footer class="footer mt-auto py-3 bg-black">
      <div class="container text-center">
        <i class="fa-solid fa-window-restore"></i> <span class="text-muted">{{ config('app.name') }}</span>
      </div>
    </footer>

    <script src="{{ asset('lib/js/bootstrap.bundle.min.js') }}"></script>    
    @yield('scripts')  
  </body>
</html>