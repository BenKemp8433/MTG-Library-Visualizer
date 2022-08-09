<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>MTG Library Visualizer</title>

        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="{{ mix('css/icons.css') }}" rel="stylesheet">

        {{--Ziggy routes--}}
        @routes

        {{--Testing with TailwindCSS full library--}}
        @if (env('APP_ENV') == 'local')
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="antialiased">
        <div id="app"
             class="grid grid-cols-12 h-screen"
             style="grid-template-rows: 51px auto;"
        >
            <v-app></v-app>
        </div>

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
