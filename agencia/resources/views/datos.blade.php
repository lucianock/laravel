<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <main class="container">
        <h1>Impresion de datos</h1>
        @if($nombre == 'Luciano')
            <h2>Bienvenido, {{ $nombre }}</h2>
        @else
            <h2>Usuario desconocido</h2>
        @endif
        <ul>
            @foreach($marcas as $marca)
            <li>{{ $marca }}</li>
            @endforeach
        </ul>
    </main>
    
    <footer>
        <p>&copy; 2025 Agencia. Todos los derechos reservados.</p>  
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
