@extends('layouts.sbadmin')

@section('container')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-user"></i> Aviso: Eliminaci&oacute;n de registro
        </h6>
    </div>
    <div class="card-body">
        
        <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <b>Atento aviso:</b> Est&aacute; intentando eliminar el registro del paciente <b><i class="fas fa-user"></i> {{ $paciente->nombre_s }}</b>, desea proceder? Esta acci&oacute;n no podr&aacute; deshacerse.
                <br><br>

                <form method="POST" action="/paciente/delete">
                    @csrf

                    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                    
                    <a href="/pacientes" class="btn btn-dark" role="button">
                        <i class="fas fa-arrow-left"></i> No, regresar al listado de pacientes
                    </a>
                    
                    <a class="btn btn-danger" href="/paciente/delete"
                          onclick="event.preventDefault();
                          this.closest('form').submit();">
                        Si, proceder a eliminar el registro <i class="fas fa-trash-can"></i>
                    </a>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection