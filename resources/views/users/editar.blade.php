@extends('layouts.app')

@section('styles')

<style type="text/css">
  .form-check-label{
    margin-top: 3px;
  }
  
  .form-switch.form-switch-md {
    margin-bottom: 1rem;
  }

  .form-switch.form-switch-md .form-check-input {
    height: 1.5rem;
    width: calc(2rem + 0.75rem);
    border-radius: 3rem;
  }

  input[type='checkbox']:focus {
    box-shadow: none;
  }
  
  input[type='checkbox']:checked {
    border: #198754 !important;
    background-color: #198754 !important;
  }
</style>
@endsection

@section('content')
    
<h3 class="mt-5"><i class="fa-solid fa-pen-to-square"></i> Editar usuario</h3>
<hr>

<p>
  <a href="/usuarios" class="btn btn-dark">
    <i class="fa-solid fa-left-long"></i> Ir al listado de usuarios
  </a>
</p>

<p>
  <h4>
    <i class="fa-solid fa-arrow-right"></i> Visualizando registro de <span class="badge bg-success"><i class="fa-solid fa-user"></i> {{ $user->name }}</span>
  </h4>
</p>

@can('Usuarios_Eliminar')
  <p>
    <a href="/usuarios/confirm/delete/{{ $user->id }}/usuario" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Eliminar registro de usuario">
      <i class="fa-solid fa-trash-can"></i>
    </a>
  </p>
@endcan

@if(Auth::user()->can('Usuarios_Editar'))

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show col-sm-6" role="alert">
        <b>
          <i class="fa-solid fa-circle-exclamation"></i> Error:
        </b> Favor de capturar correctamente los siguientes campos <br><br>
        
        @foreach ($errors->all() as $error)
          <i class="fa-solid fa-xmark"></i> {{ $error }} <br>
        @endforeach
        
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <br>
@endif

<div class="col-8">

<form method="POST" action="/usuarios/update" class="row g-3" autocomplete="off">
  
  @csrf
  <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
  
  <div class="col-6">
    <label for="name" class="form-label">Nombre(s) y apellido(s)</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" autofocus required>
  </div>

  <div class="col-md-4">
    <label for="email" class="form-label">Correo electronico</label>
    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
  </div>

  <div class="col-md-5">
    <label for="password" class="form-label">Nueva contrase&ntilde;a</label>
    <input type="password" class="form-control" id="password" name="password" value="**********" required>
  </div>

  {{--<div class="col-md-5">
    <label for="repeat_password" class="form-label">Favor de confirmar su nueva contrase&ntilde;a</label>
    <input type="password" class="form-control" id="repeat_password" name="repeat_password">
  </div>

  <div class="col-md-5">
    <label for="telefono_movil" class="form-label">Telefono movil</label>
    <input type="text" class="form-control" id="telefono_movil" name="telefono_movil">
  </div>--}}

  <div class="col-md-12">
    <div class="form-check form-switch form-switch-md">
      <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" @if($user->activo) {{ 'checked' }} @endif>
      <label class="form-check-label" for="activo">
        &nbsp; Activar acceso de usuario
      </label>
    </div>
  </div>
  
  <div class="col-12">
    <button type="submit" class="btn btn-dark">
    	Actualizar <i class="fa-solid fa-arrows-rotate"></i>
    </button>
  </div>
</form>
</div>

@endcan

@if(Auth::user()->can('Usuarios_Gestionar_Permisos'))

<div class="row">

<div class="col-4">
  <br>
  <table class="table align-middle">
    <thead>
      <tr>
        <th><i class="fa-solid fa-lock"></i> Permisos sin asignar</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    @foreach($permisosNoAsignados as $permiso)
      <tr>
        <td>{{ $permiso->name }}</td>
        <td>
          <form method="POST" action="/usuarios/permiso/asignar">
            
            @csrf
            <input type="hidden" name="permiso" value="{{ $permiso->name }}">
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <a class="btn btn-success" href="/usuarios/permiso/asignar"
                  onclick="event.preventDefault();
                  this.closest('form').submit();" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Asignar permiso">
              <i class="fa-solid fa-check"></i>
            </a>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>

<div class="col-4">
  <br>
  <table class="table align-middle">
    <thead>
      <tr>
        <th><i class="fa-solid fa-lock-open"></i> Permisos asignados</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    @foreach($user->permissions as $permiso)
      <tr>
        <td>{{ $permiso->name }}</td>
        <td>
          <form method="POST" action="/usuarios/permiso/denegar">
            
            @csrf
            <input type="hidden" name="permiso" value="{{ $permiso->name }}">
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <a class="btn btn-danger" href="/usuarios/permiso/denegar"
                  onclick="event.preventDefault();
                  this.closest('form').submit();" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Revocar permiso">
              <i class="fa-solid fa-ban"></i>
            </a>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
</div>

@endcan
<p>
  <div class="alert alert-secondary" role="alert">
    <b><i class="fa-regular fa-clock"></i> Fecha de registro</b>: {{ $user->created_at }}<br>
    <b><i class="fa-solid fa-arrows-rotate"></i> Fecha de ultima actualizaci&oacute;n</b>: {{ $user->updated_at }}
  </div>
</p>

@endsection

@section('scripts')

<script type="text/javascript" src="{{ asset('lib/js/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script type="text/javascript">

  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

  @if(session('userAlerts'))
    Swal.fire({
      title: "{{ session('userAlerts')['titulo'] }}",
      text: "{{ session('userAlerts')['mensaje'] }}",
      icon: "{{ session('userAlerts')['icono'] }}",
      confirmButtonText: 'Cerrar aviso'
    })
  @endif
</script>

@endsection