<div>
    <!-- Header de la página -->
    <x-slot name="header">
        <h2
            class="font-semibold text-xl text-center {{ $recurso && $recurso->estatus != 1 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-500' }} leading-tight uppercase">
            {{ $recurso && $recurso->estatus != 1 ? __('Detalles del Recurso Inactivo') : __('Detalles del Recurso') }}
        </h2>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">

                @if ($recurso)
                            <!-- Grid de información -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <x-input-label value="Nombre del Recurso:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold break-words overflow-hidden">
                                        {{ $recurso->nombre_recurso }}
                                    </p>
                                </div>

                                <div>
                                    <x-input-label value="Estatus:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                        <span
                                            class="{{ $recurso->estatus == 1
                    ? 'px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-600 dark:text-green-100'
                    : 'px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-600 dark:text-red-100' }}">
                                            {{ $recurso->estatus == 1 ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <x-input-label value="Fecha de Creación:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                        {{ \Carbon\Carbon::parse($recurso->fecha_creacion)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No se ha encontrado el Recurso...</p>
                @endif

                <!-- Botón Volver -->
                <div class="flex justify-end mt-6">
                    <x-danger-button type="button" wire:click="cerrar">
                        <link rel="stylesheet"
                            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                        <span class="material-symbols-outlined">arrow_back</span>
                        {{ __('Volver') }}
                    </x-danger-button>
                </div>
            </div>
        </div>
    </div>
</div>