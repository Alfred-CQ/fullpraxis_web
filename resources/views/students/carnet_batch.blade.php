<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Planchas de Carnets</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }
        .page {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            height: 100%;
            page-break-after: always;
        }
        .carnet-container {
            width: 50%; /* 2 columnas */
            padding: 10px;
            box-sizing: border-box;
        }
        .carnet-image {
            width: 100%;
            height: auto;
            max-height: 150px;
            object-fit: contain;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

@foreach(collect($students)->chunk(6) as $group)
    <div class="page">
        @foreach($group as $student)
            <div class="carnet-container">
                <img class="carnet-image" src="data:image/png;base64,{{ base64_encode($student['imageData']) }}" alt="Carnet">
            </div>
        @endforeach
    </div>
@endforeach

</body>
</html>
