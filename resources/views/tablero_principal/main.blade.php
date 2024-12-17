@extends('layouts.sbadmin')

@section('container')

{{-- Page Heading --}}
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tablero de seguimiento - D&iacute;a actual</h1>
    {{--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> Generar reporte
    </a>--}}
</div>

{{-- Content Row --}}
<div class="row">
        
  @foreach($citaEstados as $citaEstado)

    @php    
      switch($citaEstado->id){

        case 1:
          $claseColor = 'primary';
          break;
        case 2:
          $claseColor = 'warning';
          break;
        case 3:
          $claseColor = 'danger';
          break;
        case 4:
          $claseColor = 'success';
          break;
      }
    @endphp

    <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $claseColor }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $claseColor }} text-uppercase mb-1">
                                {{ $citaEstado->cita_estado }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($citaEstado->getCitas) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      @endforeach

      <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                En progreso</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $enProgreso }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
       
        

        
        {{--<div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Reprogramado</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                0
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sync-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                         <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">No asisti&oacute;
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Concluido</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                0
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
    </div> 

@endsection