@extends('pacientes.paciente_header')

@section('styles')

  <link href="{{ asset('summernote-0.8.18-dist/summernote.min.css') }}" rel="stylesheet">

@endsection

@section('paciente')

  @if($paciente->getPadecimientos)
  	
  	@php
  		$padecimientos = $paciente->getPadecimientos->padecimiento;
  	@endphp

  @else 
  	
  	@php
  		$padecimientos = '';
  	@endphp
  	
  	<div class="alert alert-warning" role="alert">
  	  <i class="fas fa-exclamation-circle"></i> Sin registro de <b>padecimiento actual</b>...
  	</div>
  @endif

  @if( session('userAlerts') )
  	
  	<div class="alert alert-{{ session('userAlerts')['icono'] }} alert-dismissible fade show" role="alert">
        <strong>
        	{{ session('userAlerts')['titulo'] }}
        </strong> {{ session('userAlerts')['mensaje'] }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
  @endif

  <form method="post" action="/paciente/padecimientos/update">
  	@csrf
  	<input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
  	<h5>
  		<b>Padecimientos:</b>
  	</h5>
  	<textarea class="form-control" rows="10" name="padecimiento" id="padecimiento">{{ $padecimientos }}</textarea>
  	<br>
  	<button type="submit" class="btn btn-success btn-block">
  		Guardar
  	</button>
  </form>

@endsection

@section('scripts')

  <script type="text/javascript" src="{{ asset('summernote-0.8.18-dist/summernote.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#padecimiento').summernote({
        tabsize: 2,
        height: 500
      });
    });
  </script>
@endsection