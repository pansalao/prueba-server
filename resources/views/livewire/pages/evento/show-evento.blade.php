<div>
    <!-- Header de la página -->
    <x-slot name="header">
        <h2
            class="font-semibold text-xl text-center {{ $evento && $evento->estatus != 1 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-500' }} leading-tight uppercase">
            {{ $evento && $evento->estatus != 1 ? __('Detalles del Evento Inactivo') : __('Detalles del Evento') }}
        </h2>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">

                @if ($evento)
                    <!-- Grid de información -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

                        {{-- Descripción --}}
                        <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                            <x-input-label value="Nombre del Evento:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold break-words overflow-hidden">
                                {{ $evento->nombre_evento }}
                            </p>
                        </div>

                        {{-- Tipo de Evento --}}
                        <div>
                            <x-input-label value="Tipo de Evento:" />
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                {{ $evento->tipo_evento_nombre }}
                            </p>
                        </div>

                        {{-- Color --}}
                        <div>
                            <x-input-label value="Color Asignado:" />
                            <div class="flex items-center gap-3">
                                @if($evento->color_rel)
                                    <div class="w-7 h-7 rounded-full border border-gray-400 shadow-sm" style="background-color: {{ $evento->color_rel->codigo_color }}"></div>
                                    <p class="text-gray-700 dark:text-gray-300 font-semibold text-lg">{{ $evento->color_rel->nombre_color }}</p>
                                @else
                                    <div class="w-7 h-7 rounded-full border border-gray-400 shadow-sm" style="background-color: {{ $evento->color }}"></div>
                                    <p class="text-gray-700 dark:text-gray-300 font-semibold italic">Color Predeterminado</p>
                                @endif
                            </div>
                        </div>

                        {{-- Fechas --}}
                        @if($evento->detalles->first())
                            <div>
                                <x-input-label value="Fecha de Inicio:" />
                                <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                    {{ \Carbon\Carbon::parse($evento->detalles->first()->dia_inicio_detalle_evento)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div>
                                <x-input-label value="Fecha de Fin:" />
                                <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                    {{ \Carbon\Carbon::parse($evento->detalles->first()->dia_fin_detalle_evento)->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif

                        {{-- Estatus --}}
                        <div>
                            <x-input-label value="Estatus:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                <span
                                    class="{{ $evento->estatus == 1
                                        ? 'px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-600 dark:text-green-100'
                                        : 'px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-600 dark:text-red-100' }}">
                                    {{ $evento->estatus == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No se ha encontrado el Evento...</p>
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