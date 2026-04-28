<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Receta Médica</title>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 16px;
        margin-top: 200px; /* ajusta aquí */
    }

    .header {
        width: 100%;
        margin-bottom: 20px;
    }

    .header-table {
        width: 100%;
    }

    .header-table td {
        padding: 2px;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    .rx {
        margin-top: 20px;
        font-weight: bold;
        font-size: 56px;
    }

    .medicamento {
        margin-top: 10px;
    }

    .subpunto {
        margin-left: 20px;
    }

    .plan {
        margin-top: 20px;
    }

</style>
</head>

<body>

{{-- HEADER --}}
<table class="header-table" style="font-size: 19px;">
    <tr>
        <td>
          <b><i>
            {{ $cita->getPaciente->nombre_s ?? '' }} 
            {{ $cita->getPaciente->apellido_paterno ?? '' }} 
            {{ $cita->getPaciente->apellido_materno ?? '' }}</i>
          </b>
        </td>
        <td class="right"><i>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/M/y') }}</i></td>
    </tr>
    <tr>
        <td>
            {{ $cita->getPaciente->fecha_nacimiento ?? '' }}
        </td>
        <td class="right">
            @if(!empty($cita->getPaciente->fecha_nacimiento))
                {{ \Carbon\Carbon::parse($cita->getPaciente->fecha_nacimiento)->age }} años
            @endif
        </td>
    </tr>
</table>

{{-- RX --}}
<div class="rx">Rx</div>

@php $i = 1; @endphp
@foreach($tratamientos as $t)

    <div class="medicamento">
        <b>{{ $i }}. {{ $t->plan }}</b>
    </div>

    {{-- Subpuntos --}}
    @php $letra = 'a'; @endphp
    @foreach($t->getTipoPlanAnidado ?? [] as $sub)
        <div class="subpunto">
            {{ $letra }}. {{ $sub->plan }}
        </div>
        @php $letra++; @endphp
    @endforeach

    @php $i++; @endphp

@endforeach

{{-- PLAN --}}
@if(!empty($planesAgrupado['Plan']))
<div class="plan">
    <b>PLAN:</b>

    @php $i = 1; @endphp
    @foreach($planesAgrupado['Plan'] as $p)
        <div>
            {{ $i }}. {{ $p->plan }}
        </div>
        @php $i++; @endphp
    @endforeach
</div>
@endif

<br><br>

{{-- FIRMA --}}
{{--<div class="center">
    ___________________________<br>
    Firma del médico
</div>--}}

</body>
</html>