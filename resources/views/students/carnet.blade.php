<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnet de Estudiante</title>
</head>
<body>
    <h1>Carnet de Estudiante</h1>
    <p><strong>DNI:</strong> {{ $data['doi'] }}</p>
    <p><strong>Nombre:</strong> {{ $data['name'] }}</p>
    <p><strong>Teléfono:</strong> {{ $data['mobile_number'] }}</p>
    <p><strong>Fecha de Nacimiento:</strong> {{ $data['birth_date'] }}</p>
    <p><strong>Teléfono del Apoderado:</strong> {{ $data['guardian_mobile_number'] }}</p>
    <p><strong>Colegio de Egreso:</strong> {{ $data['graduated_high_school'] }}</p>
</body>
</html>