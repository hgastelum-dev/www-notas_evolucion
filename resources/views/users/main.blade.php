@extends('layouts.sbadmin')

@section('styles')

  <link rel="stylesheet" type="text/css" href="{{ asset('lib/js/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
  <style type="text/css">
  	
  	.form-switch {
  		margin-top: 7px;
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
  		border: #dc3545 !important;
  		background-color: #dc3545 !important;
  	}
  </style>
@endsection

@section('container')
    
  <h3 class="mt-5"><i class="fa-solid fa-users"></i> Usuarios</h3>
  <hr>

  <p class="text-end">
  	<div class="btn-group text-end" role="group" aria-label="Acciones para gestion de usuarios">
  	  <div class="btn-group" role="group">
  	    <button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
  	      <i class="fa-solid fa-file-arrow-down"></i> Descargar listado
  	    </button>
  	    <ul class="dropdown-menu">
  	      <li>
  	      	<a class="dropdown-item" href="usuarios/exportar">
  	      		<i class="fa-solid fa-file-excel"></i> Excel
  	      	</a>
  	      </li>
  	      <li>
  	      	<a class="dropdown-item" href="usuarios/exportar/pdf">
  	      		<i class="fa-solid fa-file-pdf"></i> PDF
  	      	</a>
  	      </li>
  	    </ul>
  	  </div>
  	  @can('Usuarios_Eliminar')
  	  <button type="button" class="btn btn-danger" id="btn-delete-multi">
  		<i class="fa-solid fa-trash-can"></i>
  	  </button>
  	  @endcan
  	</div>
  </p>

  <div class="table-responsive">
  	<br>
    <table class="table table-sm align-middle text-nowrap table-hover" id="table-users">
      <thead>
        <tr>
        	<th>Nombre(s) y apellido(s)</th>
          <th>Correo electronico</th>
          <th>Estado</th>
          @can('Usuarios_Eliminar')
          <th></th>
          <th></th>
          @endcan
          @if(Auth::user()->can('Usuarios_Eliminar') || Auth::user()->can('Usuarios_Editar') || Auth::user()->can('Usuarios_Gestionar_Permisos'))
          <th></th>
          @endif
          <th>Fecha de registro</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)

        	<tr>
        		<td>{{ $user->name }}</td>
        		<td>{{ $user->email }}</td>
        		<td>
        			@if($user->activo)
        				<i class="fa-solid fa-circle text-success"></i> Activo
        			@else
        				<i class="fa-solid fa-circle text-warning"></i> Inactivo
        			@endif
        			
        		</td>
        		@if(Auth::user()->can('Usuarios_Eliminar') || Auth::user()->can('Usuarios_Editar') || Auth::user()->can('Usuarios_Gestionar_Permisos'))
        		<td>
        			<a class="btn btn-dark" href="/usuarios/editar/{{ $user->id }}" role="button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Modificar usuario">
        				<i class="fa-solid fa-wrench"></i>
        			</a>
        		</td>
        		@endif
        		@can('Usuarios_Eliminar')
        		<td>
        			<a class="btn btn-danger" href="/usuarios/confirm/delete/{{ $user->id }}/usuarios" role="button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Borrar usuario">
                  <i class="fa-solid fa-trash-can"></i>
              </a>
            </td>
            <td>
        			<div class="form-check form-switch form-switch-md">
  							<input class="form-check-input checkbox-users" type="checkbox" role="switch" id="check-{{ $user->id }}" value="{{ $user->id }}" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Seleccionar para eliminar">
  							<label class="form-check-label" for="check-{{ $user->id }}"></label>
  						</div>
        		</td>
        		@endcan
        		<td>
        			@php
        				$fechaCreacion = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at);
        			@endphp
        			{{ $fechaCreacion->toDayDateTimeString() }}
        		</td>
        	</tr>
        @endforeach
      </tbody>
    </table>

    @can('Usuarios_Eliminar')
      {{-- modal para eliminar varios usuarios --}}
    	<div class="modal fade" id="modal-borrar-multiple" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="multipleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="multipleModalLabel">
                <i class="fa-solid fa-circle-info"></i> Est&aacute; seguro de querer eliminar los siguientes registros de usuario?
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-nowrap">
            <div id="users-info" class="text-center"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="fa-solid fa-arrow-left"></i> No, regresar
            </button>
            <form method="post" action="/usuarios/delete/multi" onsubmit="document.getElementById('btn-multiple-delete').disabled = true;">

                @csrf
                <input type="hidden" id="users_ids" name="users_ids">

                <button type="submit" class="btn btn-danger" id="btn-multiple-delete">
                	Si, borrar usuarios del sistema <i class="fa-solid fa-trash-can"></i>
                </button>
            </form>
          </div>
        </div>
      </div>
    	</div>

    	{{-- modal para mostrar error cuando no se seleccione ningun usuario con los checkboxes --}}
    	<div class="modal fade" id="modal-error" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="errorSeleccionModalLabel" aria-hidden="true">
    	  <div class="modal-dialog modal-lg">
    	    <div class="modal-content">
    	      <div class="modal-header">
    	        <h5 class="modal-title" id="errorSeleccionModalLabel">
    	            <i class="fa-solid fa-xmark text-danger"></i> Error
    	        </h5>
    	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    	      </div>
    	      <div class="modal-body text-center">
    	        <i class="fa-solid fa-circle-info"></i> Favor de seleccionar minimo <b>1 usuario</b> del listado para poder proceder a eliminar registros
    	      </div>
    	    </div>
    	  </div>
  	  </div>
  	@endcan

@endsection

@section('scripts')

  <script type="text/javascript" src="{{ asset('lib/js/jquery/dist/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('lib/js/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('lib/js/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('lib/js/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('lib/js/moment/min/moment.min.js') }}"></script>

  <script type="text/javascript">

  	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  	
  	$(document).ready(function () {
  	    $('#table-users').DataTable({
  	        order: [[0, 'asc']],
  	        language: {
  	        	url: '{{ asset('js/dataTables.es-MX.json') }}'
  	        }
  	    });
  	});	

  	@can('Usuarios_Eliminar')
    	document.getElementById('btn-delete-multi').addEventListener('click', function(event){

        document.getElementById('users_ids').value = '';

        var inputsCheckbox = document.querySelectorAll('.checkbox-users');   
        var usersIds = [];

        for (var i = 0; i < inputsCheckbox.length; i++) {   
          if ( inputsCheckbox[i].checked ) {
             usersIds.push( inputsCheckbox[i].value );
          } 
        }

        if (usersIds <= 0){
           	
          var errorModal = new bootstrap.Modal(document.getElementById('modal-error'), {
            keyboard: false
          });

          errorModal.show();
          return;
        }

        document.getElementById('users_ids').value = JSON.stringify(usersIds);
            
        var modalMultiple = new bootstrap.Modal(document.getElementById('modal-borrar-multiple'), {
          keyboard: false
        });

        modalMultiple.show();
      });
    @endcan

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