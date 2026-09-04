@extends('layouts.sbadmin')

@section('styles')

  <link rel="stylesheet" type="text/css" href="{{ asset('lib/js/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">

@endsection

@section('container')

  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-user"></i> Pacientes registrados
        </h6>
    </div>
    <div class="card-body">
        
        <p>
          <a href="/pacientes/alta" class="btn btn-info">
            <i class="fas fa-user"></i>  Registrar paciente
          </a>
        </p>

        @if(session('userAlerts'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>{{ session('userAlerts')['titulo'] }}</strong> {{ session('userAlerts')['mensaje'] }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        @endif
        
        <table class="table table-hover" id="table-pacientes">
          <thead>
            <tr>
              <th>Num. exp.</th>
              <th>Nombre completo</th>
              <th>Correo electronico</th>
              <th></th>
            </tr>  
          </thead>
          <tbody>
            @foreach($pacientes as $paciente)
              <tr>
                <td>
                  <b>{{ $paciente->numero_expediente }}</b>
                </td>
                <td>{{ $paciente->nombre_s }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}</td>
                <td>{{ $paciente->email }}</td>
                <td>
                  <a class="btn btn-info" href="/paciente/editar/{{ $paciente->id }}">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                  <a class="btn btn-danger" href="/paciente/borrar/{{ $paciente->id }}">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
    </div>
  </div>

@endsection

@section('scripts')

  <script type="text/javascript" src="{{ asset('lib/js/jquery/dist/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('lib/js/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('lib/js/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function () {
        $('#table-pacientes').DataTable({
            order: [[0, 'asc']],
            language: {
              url: '{{ asset('js/dataTables.es-MX.json') }}'
            }
        });
    }); 
  </script>

@endsection