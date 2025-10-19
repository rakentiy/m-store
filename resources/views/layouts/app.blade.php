<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite([
        'resources/css/app.css',
        'resources/sass/main.sass',
        'resources/js/app.js'
    ])
</head>
<body class="antialiased">
<x-messages.flash-message></x-messages.flash-message>
@yield('content')
</body>
</html>
