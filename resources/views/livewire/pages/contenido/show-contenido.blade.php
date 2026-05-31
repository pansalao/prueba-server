<div>
    <!-- Header de la página -->
    <x-slot name="header">
        <h2
            class="font-semibold text-xl text-center {{ $contenido && $contenido->estatus != 1 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-500' }} leading-tight uppercase">
            {{ $contenido && $contenido->estatus != 1 ? __('Detalles del Contenido Inactivo') : __('Detalles del Contenido') }}
        </h2>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">

                @if ($contenido)
                            <!-- Grid de información -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <x-input-label value="Título del Contenido:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold break-words overflow-hidden">
                                        {{ $contenido->titulo_contenido }}
                                    </p>
                                </div>

                                <div>
                                    <x-input-label value="Unidad Curricular:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold break-words overflow-hidden">
                                        {{ $contenido->nombre_unidad_curricular }}
                                    </p>
                                </div>

                                <!-- Objetivos Asociados -->
                                <div class="lg:col-span-3 mt-4">
                                    <x-input-label value="Objetivos Asociados:" class="mb-2" />
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($contenido->objetivos as $objetivo)
                                            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                                <p class="text-gray-700 dark:text-gray-300 font-bold uppercase text-xs">
                                                    {{ $objetivo->titulo_objetivo }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if(auth()->user()?->esCoordinadorOVicerrector())
                                <div class="mt-4">
                                    <x-input-label value="Estatus:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                        <span
                                            class="{{ $contenido->estatus == 1
                    ? 'px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-600 dark:text-green-100'
                    : 'px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-600 dark:text-red-100' }}">
                                            {{ $contenido->estatus == 1 ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </p>
                                </div>
                                @endif

                            </div>

                @else
                    <p class="text-gray-500 dark:text-gray-400">No se ha encontrado el Contenido...</p>
                @endif

                <!-- Botón Volver -->
                <div class="flex justify-end mt-6">
                    <a href="{{ route('contenido/listar') }}">
                        <x-danger-button type="button">
                            <link rel="stylesheet"
                                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                            <span class="material-symbols-outlined">arrow_back</span>
                            {{ __('Volver') }}
                        </x-danger-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

