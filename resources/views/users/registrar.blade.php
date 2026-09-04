@extends('layouts.app')

@section('styles')

  <link rel="stylesheet" type="text/css" href="{{ asset('lib/js/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
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
    
  <h3 class="mt-5"><i class="fa-solid fa-user"></i> Crear usuario</h3>
  <hr>

  <br>

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

  <form method="POST" action="/usuarios/insert" class="row g-3" autocomplete="off">

    <div class="row">
    <div class="col-7">

    <div class="row g-3">
      @csrf

      <div class="col-md-7">
        <label for="name" class="form-label">Nombre(s) y apellido(s)</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" autofocus required>
      </div>

      <div class="col-md-5">
        <label for="email" class="form-label">Correo electronico</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
      </div>

      <div class="col-md-5">
        <label for="password" class="form-label">Contrase&ntilde;a</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="col-md-12">
        <div class="form-check form-switch form-switch-md">
    		  <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" @if(old('activo') == 'on') {{ 'checked' }} @endif>
    		  <label class="form-check-label" for="activo">
    		  	&nbsp; Activar acceso de usuario
    		  </label>
    		</div>
      </div>
    
      <div class="col-md-12">
        <button type="submit" class="btn btn-dark">
        	Guardar <i class="fa-solid fa-floppy-disk"></i>
        </button>
      </div>

      <div class="col-md-12">
      	<h6><i class="fa-solid fa-unlock-keyhole"></i> Permisos de usuario:</h6>
      	<p>
      		-
      	</p>
      </div>
    </div>

    </div>

    @if(Auth::user()->can('Usuarios_Gestionar_Permisos'))
      <div class="col-5">
      	<table class="table align-middle table-sm text-nowrap" id="table-permisos">
      		<thead>
      			<tr>
      				<th>Permiso</th>
      			</tr>
      		</thead>
      		<tbody>
      		@foreach( $permisos as $permiso )
      			<tr>
      				<td>
      					<div class="form-check form-switch form-switch-md">
      					  <input class="form-check-input" type="checkbox" role="switch" id="check-{{ $permiso->id }}" name="permisos[]" value="{{ $permiso->name }}">
      					  <label class="form-check-label" for="check-{{ $permiso->id }}">
      					  	&nbsp; {{ $permiso->name }}
      					  </label>
      					</div>
      				</td>
      			</tr>
      		@endforeach
      		</tbody>
      	</table>
      </div>
    @endcan

    </div>

  </form>

@endsection

@section('scripts')

  <script type="text/javascript" src="{{ asset('lib/js/jquery/dist/jquery.min.js') }}"></script>

  @if(Auth::user()->can('Usuarios_Gestionar_Permisos'))

    <script type="text/javascript" src="{{ asset('lib/js/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/js/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script type="text/javascript">
    	$(document).ready(function () {
    	    $('#table-permisos').DataTable({
    	        scrollY: '500px',
    	        scrollCollapse: true,
    	        paging: false,
    	        searching: false,
    	        language: {
    	        	url: '{{ asset('js/dataTables.es-MX.json') }}'
    	        }
    	    });
    	});	
    </script>
  @endcan

@endsection