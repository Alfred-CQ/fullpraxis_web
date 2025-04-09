<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnets de Estudiantes</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .page {
            page-break-after: always;
            text-align: center;
        }

        .carnet-container {
            width: 600px;
            position: relative;
            top: 0;
            left: 0;
            margin: 0 auto;
        }

        .carnet-image {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    {{ /* HELP */ }}
    @foreach ($students as $student) 
        <div class="page">
            <div class="carnet-container">
                <img class="carnet-image" src="data:image/jpeg;base64,{{ base64_encode($student['imageData']) }}" alt="Carnet">
            </div>
        </div>
    @endforeach
</body>
</html>
