@extends('citas.atender')

@section('styles')

  <link href="{{ asset('summernote-0.8.18-dist/summernote.min.css') }}" rel="stylesheet">

@endsection

@section('seccion-cita')

  <form method="post" action="/cita/soap01/subjetivo/update">
    @csrf
    <input type="hidden" name="cita_paciente_id" value="{{ $cita->id }}">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('userAlerts'))
      <div class="alert alert-{{ session('userAlerts')['icono'] }} alert-dismissible fade show" role="alert">
        <strong>{{ session('userAlerts')['titulo'] }}</strong> {{ session('userAlerts')['mensaje'] }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="mb-3">
      <label for="subjetivo" class="form-label">
        <h3>
            <b>S</b>ubjetivo:
        </h3>
      </label>
      <textarea class="form-control border border-info rounded" id="subjetivo" name="subjetivo" rows="15" autofocus>@if($cita->getSubjetivo) {{ $cita->getSubjetivo->subjetivo }} @endif</textarea>
    </div>
    <p>
      <button type="submit" class="btn btn-success btn-lg btn-block" id="btn-s">
        Guardar datos de apartado <b>Subjetivo</b> 
        @if(session('userAlerts'))
          <span class="badge badge-secondary">
            <i class="fas fa-check-circle"></i> Actualizado exitosamente
          </span>
        @endif
      </button>
    </p>
  </form>

@endsection

@section('scripts')

  <script type="text/javascript" src="{{ asset('summernote-0.8.18-dist/summernote.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#subjetivo').summernote({
        tabsize: 2,
        height: 200,
        focus: true
      });
    });
  </script>
@endsection