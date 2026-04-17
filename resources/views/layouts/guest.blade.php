<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style>
            @font-face {
                font-family: 'Verdana';
                src: local('Verdana');
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Vanilla Calendar Pro -->
        <link href="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro/build/vanilla-calendar.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro/build/vanilla-calendar.min.js"></script>
    </head>
    <body class="font-sogat text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <img src="{{ asset('assets/img/android-chrome-512x512.png') }}" alt="Mi Logo" class="rounded-full w-20 h-20 fill-current text-gray-500">
            </div>
            <div class="w-full sm:max-w-7xl px-4 sm:px-6 lg:px-8 mt-6 bg-transparent overflow-hidden">
                <div class="px-6 py-4 bg-white dark:bg-gray-800 shadow-[0px_0px_15px_#000000] sm:rounded-[25px] transition-all duration-500">
                    {{ $slot }}

                    @if(!Route::is('seleccionar-rol'))
                        <div class="mt-4 text-sm text-gray-700 dark:text-gray-300">
                            Los campos con <span class="text-red-500 font-bold">*</span> son obligatorios
                        </div>
                    @endif
                </div>

                <footer class="bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 py-3 text-center transition-colors duration-300">
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-tighter">
                        Todos los derechos reservados © 2026 UPTP - CRÉDITOS UNIDAD DE SISTEMAS / DESARROLLO DE
                        SOFTWARE.
                    </p>
                </footer>
            </div>
        </div>
    </body>
</html>
