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
        h1, h2, h3 {
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
    <div class="header">
        <h1>Reporte de Asistencias</h1>
        <p><strong>DNI:</strong> {{ $data['student']['doi'] }}</p>
        <p><strong>Nombre:</strong> {{ $data['student']['first_names'] }} {{ $data['student']['last_names'] }}</p>
        <p><strong>Teléfono:</strong> {{ $data['student']['phone_number'] }}</p>
    </div>

    <div class="attendances">
        <h2>Asistencias</h2>
        @foreach ($data['attendances'] as $date => $records)
            <div class="attendance-day">
                <h3>{{ $date }}</h3>
                <ul>
                    @foreach ($records as $record)
                        <li class="attendance-record">
                            {{ $record->recorded_at }} - {{ $record->attendance_type }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</body>
</html>