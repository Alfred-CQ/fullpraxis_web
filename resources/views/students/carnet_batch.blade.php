<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnets</title>
    <style>
        body {
            margin: 0;
            padding: 0px;
        }

        .page-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            page-break-inside: avoid;
        }

        .carnet-container {
            width: 600px;
            margin-bottom: 5px;
            position: relative;
        }

        .carnet-image {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        @media print {
            body {
                padding: 0;
            }
            
            .page-container {
                gap: 0;
            }
            
            .carnet-container {
                margin-bottom: 0;
                page-break-after: avoid;
                page-break-before: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        @foreach($students as $student)
            <div class="carnet-container">
                <img class="carnet-image" src="data:image/jpeg;base64,{{ base64_encode($student['imageData']) }}" alt="Carnet">
            </div>
        @endforeach
    </div>
</body>
</html>