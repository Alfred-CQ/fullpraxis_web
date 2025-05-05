<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        h1,
        h2,
        h3 {
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 20px;
        }

        .attendance-day {
            margin-top: 15px;
        }

        .attendance-record {
            margin-left: 20px;
        }
    </style>
</head>

<body>

    <div class="header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; height: 0%;">
            <img src="{{ public_path('images/main-logo.png') }}" alt="Logo" style="height: 90px;">
        </div>
        <div style="text-align: right; font-size: 12px; display: flex; flex-direction: column; align-items: flex-end;">
            <img src="{{ public_path('qrs/validez-' . $data['student']['student_id'] . '.png') }}" alt="QR Validez" style="height: 60px; margin-bottom: 5px;">
            <p style="margin: 0;"><strong>https://academiafullpraxis.edu.pe </strong></p>
            <p style="margin: 0;"><strong>Fecha de generación: </strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>


    <hr style="margin: 10px 0;">
    <h2 style="text-align: center;">REPORTE DE ASISTENCIAS</h2>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; border: 1px solid #d0d0d0;">
        <thead>
            <tr>
                <th colspan="3" style="text-align: center; background-color: #f0f0f0; padding: 8px; font-size: 14px; border: 1px solid #d0d0d0;">
                    Datos del estudiante
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width: 25%; background-color: #f0f0f0; padding: 6px; border: 1px solid #d0d0d0;"><strong>Código ID:</strong></td>
                <td style="padding: 6px; border: 1px solid #d0d0d0;">{{ $data['student']['student_id'] }}</td>
                <td rowspan="4" style="text-align: center; padding: 6px; border: 1px solid #d0d0d0;">
                    <img src="{{ public_path('storage/' . $data['student']['photo_path']) }}" alt="Foto del estudiante" style="height: 100px; object-fit: cover;">

                </td>
            </tr>
            <tr>
                <td style="background-color: #f0f0f0; padding: 6px; border: 1px solid #d0d0d0;"><strong>DNI:</strong></td>
                <td style="padding: 6px; border: 1px solid #d0d0d0;">{{ $data['student']['doi'] }}</td>
            </tr>
            <tr>
                <td style="background-color: #f0f0f0; padding: 6px; border: 1px solid #d0d0d0;"><strong>Nombre completo:</strong></td>
                <td style="padding: 6px; border: 1px solid #d0d0d0;">{{ $data['student']['first_names'] }} {{ $data['student']['last_names'] }}</td>
            </tr>
            <tr>
                <td style="background-color: #f0f0f0; padding: 6px; border: 1px solid #d0d0d0;"><strong>Teléfono:</strong></td>
                <td style="padding: 6px; border: 1px solid #d0d0d0;">{{ $data['student']['phone_number'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="attendances" style="margin-top: 30px;">

        <table style="width: 100%; border-collapse: collapse; border: 1px solid #d0d0d0; font-size: 12px;">
            <thead>
                <tr>
                    <th style="padding: 8px; border: 1px solid #d0d0d0; text-align: center; background-color: #f4f4f4;" colspan="3">
                        Registros del Auxiliar
                    </th>
                </tr>
                <tr>
                    <th style="padding: 6px; border: 1px solid #d0d0d0; background-color: #f4f4f4;">Fecha</th>
                    <th style="padding: 6px; border: 1px solid #d0d0d0; background-color: #f4f4f4; width: 30%;">Hora</th>
                    <th style="padding: 6px; border: 1px solid #d0d0d0; background-color: #f4f4f4;">Tipo de Registro</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['attendances'] as $date => $records)
                @php
                $rowspan = count($records);
                $formattedDate = \Carbon\Carbon::parse($date)
                ->locale('es')
                ->isoFormat('dddd DD [de] MMMM [del] YYYY');
                @endphp
                @foreach ($records as $index => $record)
                <tr>
                    @if ($index === 0)
                    <td rowspan="{{ $rowspan }}" style="text-align: center; padding: 6px; border: 1px solid #d0d0d0; background-color: #f9f9f9;">
                        <strong>{{ ucfirst($formattedDate) }}</strong>
                    </td>
                    @endif
                    <td style="text-align: center; padding: 6px; border: 1px solid #d0d0d0;">
                        {{ \Carbon\Carbon::parse($record->recorded_at)->format('h:i A') }}
                    </td>
                    <td style="text-align: center; padding: 6px; border: 1px solid #d0d0d0;">
                        @if($record->attendance_type == 'Entry')
                        Registro de Entrada
                        @elseif($record->attendance_type == 'Exit')
                        Registro de Salida
                        @else
                        {{ $record->attendance_type }}
                        @endif
                    </td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>


</body>

</html>