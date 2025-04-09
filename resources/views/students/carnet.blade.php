<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnet</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .carnet-container {
            width: 600px;  
            position: relative;
            top: 0;
            left: 0;
        }

        .carnet-image {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="carnet-container">
        <img class="carnet-image" src="data:image/jpeg;base64,{{ base64_encode($imageData) }}" alt="Carnet">
    </div>
</body>
</html>
