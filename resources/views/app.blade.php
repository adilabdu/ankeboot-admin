<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Ankeboot Admin</title>

        <link rel="apple-touch-icon" sizes="180x180" href="{{ Vite::asset('resources/assets/favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ Vite::asset('resources/assets/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ Vite::asset('resources/assets/favicon/favicon-16x16.png')  }}">
        <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/assets/favicon/favicon.ico') }}">
        <link rel="manifest" href="{{ Vite::asset('resources/assets/favicon/site.webmanifest') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite('resources/js/app.js')
    </head>

    <body>
        <div id="app"></div>
    </body>

</html>
