<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Diario de Asistencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #888;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .alumno {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Reporte Diario de Asistencias</h1>
    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</p>

    @forelse($data as $alumno)
        <div class="alumno">
            <table>
                <thead>
                    <tr>
                        <th colspan="3">Alumno: {{ $alumno['nombre_completo'] }} (DNI: {{ $alumno['doi'] }})</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumno['asistencias'] as $index => $asistencia)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($asistencia['tipo'] === 'Entry')
                                    Entrada
                                @elseif($asistencia['tipo'] === 'Exit')
                                    Salida
                                @else
                                    {{ $asistencia['tipo'] }}
                                @endif
                            </td>
                            <td>{{ $asistencia['hora'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p>No hay asistencias registradas para hoy.</p>
    @endforelse

</body>
</html>
    