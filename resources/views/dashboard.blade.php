<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Tere tulemast, {{ auth()->user()->name }}!</h1>

    <p>Siia saame hiljem lisada admin ja kasutaja lingid / vaated.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logi välja</button>
    </form>
</body>
</html>
