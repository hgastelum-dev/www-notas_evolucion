@extends('layouts.sbadmin')

@section('container')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-user"></i> Alta de paciente
        </h6>
    </div>
    <div class="card-body">
        <p>
          <a href="/pacientes" class="btn btn-info">
            <i class="fas fa-arrow-left"></i> Regresar al listado de pacientes
          </a>
        </p>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="/paciente/insert">
          
          @csrf

          <div class="form-group row">
            <label for="nombre_s" class="col-sm-2 col-form-label">
                Nombre(s) <b class="text-danger">*</b>
            </label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="nombre_s" name="nombre_s" required value="{{ old('nombre_s') }}">
            </div>
          </div>

          <div class="form-group row">
            <label for="apellido_paterno" class="col-sm-2 col-form-label">
                Apellido paterno <b class="text-danger">*</b>
            </label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required value="{{ old('apellido_paterno') }}">
            </div>
          </div>

          <div class="form-group row">
            <label for="apellido_materno" class="col-sm-2 col-form-label">
                Apellido materno <b class="text-danger">*</b>
            </label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required value="{{ old('apellido_materno') }}">
            </div>
          </div>

          <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Correo electronico</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            </div>
          </div>
                     
          <div class="form-group row">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-info">
                  Guardar
              </button>
            </div>
          </div>

        </form>
    </div>
</div>

@endsection