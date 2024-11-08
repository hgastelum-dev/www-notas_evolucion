@extends('layouts.app')

@section('content')
    
<h3 class="mt-5"><i class="fa-solid fa-gauge-simple-high"></i> Tablero</h3>
<hr>

<div class="dropdown">
  
  <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownModulos" data-bs-toggle="dropdown" aria-expanded="false">
    Seleccione un modulo
  </button>
  
  <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownModulos">
    <li>
        <a class="dropdown-item active" href="#">
            <i class="fas fa-temperature-low"></i> Temperatura
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="/dashboard/reedswitch">
            <i class="fas fa-magnet"></i> Magneticos
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fa-solid fa-grip-lines"></i> Humedad del suelo
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fa-solid fa-lightbulb"></i> Nivel de luz
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fa-solid fa-bolt"></i> Relevador
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fas fa-running"></i> PIR
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fas fa-video"></i> Camara
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fas fa-fire"></i> Deteccion de gas y humo
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fas fa-tint"></i> Nivel de agua
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fab fa-pagelines"></i> Riego automatico
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fas fa-map-marker-alt"></i> GPS
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="far fa-id-card"></i> RFID
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fas fa-fingerprint"></i> Biometricos
        </a>
    </li>
    <li>
        <hr class="dropdown-divider">
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fas fa-desktop"></i> Equipo de computo
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fas fa-server"></i> Servidores
        </a>
    </li>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fas fa-ethernet"></i> Switches
        </a>
    </li>
  </ul>
</div>

<br>

@yield('modulo')

@endsection