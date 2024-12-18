<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="HGG">
    <title>{{ config('app.name') }}</title>

    <link href="lib/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="lib/css/fontawesome-free/css/all.min.css">
  </head>

  <body class="text-center">
    
    <main class="form-signin w-100 m-auto">
        <form method="POST" action="{{ route('login') }}" autocomplete="off">

            @csrf
            
            <div class="text-center">
              <img src="{{ asset('img/geialogo.jpg') }}" height="150">
            </div>
            <br><br>
            <h4 class="mb-3 fw-normal text-info">
              Sistema de Expediente el&eacute;ctronico
            </h4>
            
            <h4 class="mb-3 fw-normal" style="color: gray;">
              Inicio de sesi&oacute;n
            </h4>

            @if(session('alerta-bloqueo'))
              <h4>
                <span class="badge bg-danger">
                  <i class="fa-solid fa-ban"></i> {{ session('alerta-bloqueo') }}
                </span>
              </h4>
            @endif

            @if ($errors->any())
              <h5 class="text-center">
                @foreach ($errors->all() as $error)
                  <span class="badge bg-danger">
                    <i class="fa-solid fa-xmark"></i> {{ $error }}
                  </span>
                @endforeach
              </h5>
            @endif

            <div class="form-floating">
              <input type="email" class="form-control" id="email" name="email" placeholder="usuario@ejemplo.com" value="{{ old('email') }}" autofocus required>
              <label for="email">Correo electronico</label>
            </div>

            <div class="form-floating">
              <input type="password" class="form-control" id="password" name="password" placeholder="Clave de acceso" required>
              <label for="password">Contrase&ntilde;a</label>
            </div>

            <div class="checkbox mb-3 d-none">
              <label>
                <input type="checkbox" value="remember-me"> Recordar credenciales
              </label>
            </div>

            <button class="w-100 btn btn-lg btn-success" type="submit">
                Ingresar <i class="fa-solid fa-right-to-bracket"></i>
            </button>

            @if (Route::has('password.request'))
                
                <p class="mt-5 mb-3">
                    <a class="text-dark" href="{{ route('password.request') }}">
                        Recuperar contrase&ntilde;a
                    </a>
                </p>
            @endif

            <p class="mt-5 mb-3 text-muted">NCBC &copy; 2023</p>
        </form>
    </main>  
  </body>
</html>
            
                
                