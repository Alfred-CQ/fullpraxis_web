<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnets</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 595px; /* Ancho de A4 en píxeles */
            height: 842px; /* Alto de A4 en píxeles */
        }

        .carnet-container {
            width: {{ $carnetWidth }}px;
            height: {{ $carnetHeight }}px;
            position: absolute;
        }

        .carnet-image {
            width: 100%;
            height: auto;
        }

        @foreach ($imageDataArray as $index => $imageData)
            .carnet-{{ $index }} {
                top: {{ floor($index / $carnetsPerRow) * ($carnetHeight + $verticalMargin) + $verticalMargin }}px;
                left: {{ ($index % $carnetsPerRow) * ($carnetWidth + $horizontalMargin) + $horizontalMargin }}px;
            }
        @endforeach
    </style>
</head>
<body>
    @foreach ($imageDataArray as $index => $imageData)
        <div class="carnet-container carnet-{{ $index }}">
            <img class="carnet-image" src="data:image/jpeg;base64,{{ base64_encode($imageData) }}" alt="Carnet">
        </div>
    @endforeach
</body>
</html>
