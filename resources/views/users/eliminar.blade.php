@extends('layouts.sbadmin')

@section('styles')

  <style type="text/css">
      .btns {
          text-decoration: none !important;
      }
  </style>
@endsection

@section('container')
    
  <h3 class="mt-5"><i class="fa-solid fa-user"></i> Borrar registro de usuario</h3>
  <hr>

  <div class="row">
    <div class="col-sm-6">
        <div class="alert alert-info" role="alert">
            <i class="fa-solid fa-exclamation-triangle"></i> <b>Atento aviso:</b> Est&aacute; intentando eliminar el registro de usuario <b><i class="fa-solid fa-user"></i> {{ $user->name }}</b>, desea proceder? Esta acci&oacute;n no podr&aacute; deshacerse.
            <br><br>

            <form method="POST" action="/usuarios/delete">
                @csrf

                <input type="hidden" name="user_id" value="{{ $user->id }}">
                
                @if($link == 'usuario')

                    <a href="/usuarios/editar/{{ $user->id }}" class="btn btn-dark btns" role="button">
                        <i class="fa-solid fa-arrow-left"></i> No, regresar
                    </a>
                @else
                    <a href="/usuarios" class="btn btn-dark btns" role="button">
                        <i class="fa-solid fa-arrow-left"></i> No, regresar al listado de usuarios
                    </a>
                @endif

                <a class="btn btn-danger btns" href="/usuarios/delete"
                      onclick="event.preventDefault();
                      this.closest('form').submit();">
                    Si, proceder a eliminar el registro de usuario <i class="fa-solid fa-trash-can"></i>
                </a>
            </form>
        </div>
    </div>
  </div>

@endsection