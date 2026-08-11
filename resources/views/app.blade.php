<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Task-Board</title>

        {{-- Setzt das gespeicherte Theme vor dem ersten Paint, sonst blitzt der Dark-Mode-Default auf. --}}
        <script>
            (function () {
                try {
                    var storedTheme = localStorage.getItem('sl-theme');
                    if (storedTheme === 'light' || storedTheme === 'dark') {
                        document.documentElement.setAttribute('data-theme', storedTheme);
                    }
                } catch (error) {}
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>
