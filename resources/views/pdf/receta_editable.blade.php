<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Receta Médica</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 16px;
            /* dinamico: viene del editor, para cuadrar con la hoja membretada */
            margin-top: {{ $margenTop ?? 200 }}px;
        }
        .header-table { width: 100%; }
        .header-table td { padding: 2px; }
        .right { text-align: right; }
        .rx { margin-top: 20px; font-weight: bold; font-size: 56px; }
        .contenido-editable p { margin: 6px 0; }
        .contenido-editable ul,
        .contenido-editable ol { margin: 4px 0 10px 24px; }
    </style>
  </head>
  <body>

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
            <td>{{ $cita->getPaciente->fecha_nacimiento ?? '' }}</td>
            <td class="right">
                @if(!empty($cita->getPaciente->fecha_nacimiento))
                    {{ \Carbon\Carbon::parse($cita->getPaciente->fecha_nacimiento)->age }} años
                @endif
            </td>
        </tr>
    </table>

    <div class="rx">Rx</div>

    <div class="contenido-editable">
      {!! $contenidoHtml !!}
    </div>

  </body>
</html>