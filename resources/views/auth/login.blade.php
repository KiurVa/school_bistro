<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Sisselogimine</title>
</head>
<body>
    <h1>Logi sisse</h1>

    @if ($errors->any())
        <div style="color: red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.perform') }}">
        @csrf

        <div>
            <label for="email">E-post:</label><br>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Parool:</label><br>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label>
                <input type="checkbox" name="remember">
                Jäta mind meelde
            </label>
        </div>

        <button type="submit">Logi sisse</button>
    </form>
</body>
</html>
