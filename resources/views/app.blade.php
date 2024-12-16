<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <link href="{{ mix('css/app.css') }}" rel="stylesheet"> -->
        <link rel="icon" href="{{ asset('icons8-calendar-94.png') }}" type="image/png">

        @vite('resources/js/app.js', 'resources/css/app.css')
        @inertiaHead
    </head>
    <body>
        @inertia
    </body>
</html>