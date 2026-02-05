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

    <script>
        // Check localStorage first for user preference
        const storedTheme = localStorage.getItem('theme');

        if (storedTheme === 'dark' || (storedTheme === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            // If theme is explicitly dark or no preference is stored and system prefers dark
            document.documentElement.classList.add('dark');
        } else {
            // Otherwise, ensure dark class is removed (for light mode)
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sogat antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-950 py-8 px-4 sm:px-6">
        {{-- Contenedor Principal Unificado (Estilo Card de la imagen) --}}
        <div class="max-w-[1200px] mx-auto bg-white dark:bg-gray-900 rounded-[2rem] overflow-hidden border border-gray-200 dark:border-gray-800 flex flex-col transition-all duration-300"
             style="box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);">
            
            <!-- Logo Header (Sintillo) -->
            <div class="bg-white dark:bg-gray-800 px-8 py-2 flex items-center transition-colors duration-300">
                <div class="flex items-center w-full">
                    {{-- Botón Hamburguesa para móviles --}}
                    <button @click="Livewire.dispatch('toggle-sidebar')" aria-label="Abrir menú"
                        class="lg:hidden p-2 mr-4 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <img src="{{ asset('img/logo_viejo.jpg') }}" alt="SOGAT Sintillo" class="w-full h-auto max-h-[100px] object-contain">
                </div>
            </div>

            <!-- Línea SOGAT (decoración) -->
            <div class="flex justify-center">
                <div class="sogat-hr !my-0"></div>
            </div>

            {{-- Alpine.js wrapper para el estado de la sidebar --}}
            <div x-data="{ alpineSidebarOpen: false }"
                class="flex flex-1"
                @sidebar-state-changed.window="alpineSidebarOpen = $event.detail.isOpen">
                
                {{-- SideBar --}}
                <livewire:side-bar />

                {{-- Área de Contenido Principal --}}
                <div id="main-content-wrapper" class="flex-1 transition-all duration-300 ease-in-out bg-white dark:bg-gray-900 min-h-[600px] mt-[15px]"
                    :class="{ 'ml-[234px]': alpineSidebarOpen && window.innerWidth < 1024, 'ml-0': !alpineSidebarOpen && window.innerWidth < 1024 }">

                    <livewire:notificaciones />

                    <!-- Page Heading (si existe) -->
                    @if (isset($header))
                        <header class="sogat-header shadow-sm border-b border-gray-100 dark:border-gray-800">
                            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                                <h1 class="font-bold text-xl text-center leading-tight tracking-wide">
                                    {{ $header }}
                                </h1>
                            </div>
                        </header>
                    @endif

                    <!-- Contenido de la Página ($slot) -->
                    <main class="p-4 sm:p-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>

            <!-- Footer al estilo de la imagen -->
            <footer class="bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 py-3 text-center transition-colors duration-300">
                <p class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-tighter">
                    Todos los derechos reservados © {{ date('Y') }} UPTP - CRÉDITOS UNIDAD DE SISTEMAS / DESARROLLO DE SOFTWARE.
                </p>
            </footer>
        </div>
    </div>
    @livewireScripts
    @livewire('livewire-ui-modal')
</body>

</html>