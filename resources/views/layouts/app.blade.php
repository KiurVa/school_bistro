<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Bistroo Menüü')</title>

    @vite(['resources/js/app.js'])
    
    {{-- jQuery (AJAX jaoks) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    @stack('styles')
</head>
<body>
    @yield('content')
    
    @stack('scripts')
</body>
</html>