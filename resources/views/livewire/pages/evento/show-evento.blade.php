<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-center {{ $evento && $evento->estatus != 1 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-500' }} leading-tight uppercase">
            {{ $evento && $evento->estatus != 1 ? __('Detalles del Evento Inactivo') : __('Detalles del Evento') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">

                @if ($evento)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

                        {{-- Descripción --}}
                        <div>
                            <x-input-label value="Nombre del Evento:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold break-words">
                                {{ $evento->nombre_evento }}
                            </p>
                        </div>

                        {{-- Fechas --}}
                        @if($evento->detalles->first())
                            <div>
                                <x-input-label value="Fecha de Inicio:" />
                                <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                    {{ \Carbon\Carbon::parse($evento->detalles->first()->dia_inicio_detalle_evento)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div>
                                <x-input-label value="Fecha de Fin:" />
                                <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                    {{ \Carbon\Carbon::parse($evento->detalles->first()->dia_fin_detalle_evento)->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif

                        {{-- Tipo de Evento --}}
                        <div>
                            <x-input-label value="Tipo de Evento:" />
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                {{ $evento->tipo_evento == '6' ? 'Feriado Mundial' : $evento->tipo_evento_nombre }}
                            </p>
                        </div>

                        {{-- Evento Especial --}}
                        <div>
                            <x-input-label value="Evento Especial:" />
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                @if($evento->especial_evento == 1) Vacaciones Colectivas
                                @elseif($evento->especial_evento == 2) Inicio del Lapso Académico
                                @elseif($evento->especial_evento == 3) Fin del Lapso Académico
                                @elseif($evento->especial_evento == 4) Semana Santa
                                @elseif($evento->especial_evento == 5) Carnaval
                                @elseif($evento->especial_evento == 7) Inicio del Lapso Académico Trayecto Inicial
                                @elseif($evento->especial_evento == 8) Fin del Lapso Académico Trayecto Inicial
                                @elseif($evento->especial_evento == 9) Inicio del Curso Intensivo
                                @elseif($evento->especial_evento == 10) Fin del Curso Intensivo
                                @else Ninguno @endif
                            </p>
                        </div>

                        {{-- Cantidad de Días de Vacaciones --}}
                        @if($evento->especial_evento == 1 && $evento->cantidad_dias_evento > 0)
                            <div>
                                <x-input-label value="Días de Vacaciones:" />
                                <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                    {{ $evento->cantidad_dias_evento }} días
                                </p>
                            </div>
                        @endif

                        {{-- Color --}}
                        <div>
                            <x-input-label value="Color Asignado:" />
                            <div class="flex items-center gap-3 mt-1">
                                <div class="w-7 h-7 rounded-full border border-gray-400 shadow-sm" style="background-color: {{ $evento->codigo_color_evento ?? $evento->color }}"></div>
                            </div>
                        </div>

                        {{-- Independiente --}}
                        <div>
                            <x-input-label value="¿Es Independiente?:" />
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-icons {{ $evento->is_independiente_evento ? 'text-green-500' : 'text-red-500' }} text-3xl">
                                    {{ $evento->is_independiente_evento ? 'check' : 'close' }}
                                </span>
                            </div>
                        </div>

                        {{-- Laborable --}}
                        <div>
                            <x-input-label value="Laborable:" />
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-icons {{ $evento->is_laborable_evento ? 'text-green-500' : 'text-red-500' }} text-3xl">
                                    {{ $evento->is_laborable_evento ? 'check' : 'close' }}
                                </span>
                            </div>
                        </div>

                        {{-- Repetible --}}
                        <div>
                            <x-input-label value="Repetible:" />
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-icons {{ $evento->is_repetible_evento ? 'text-green-500' : 'text-red-500' }} text-3xl">
                                    {{ $evento->is_repetible_evento ? 'check' : 'close' }}
                                </span>
                            </div>
                        </div>

                        {{-- Cantidad de Días --}}
                        @if($evento->is_cantidad_dias_evento)
                            <div>
                                <x-input-label value="Cantidad de días de duración:" />
                                <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                    {{ $evento->cantidad_dias_evento }} días
                                </p>
                            </div>
                        @endif

                        {{-- Estatus --}}
                        <div>
                            <x-input-label value="Estatus:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                <span class="{{ $evento->estatus == 1 ? 'px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-600 dark:text-green-100' : 'px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-600 dark:text-red-100' }}">
                                    {{ $evento->estatus == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No se ha encontrado el Evento...</p>
                @endif

                <div class="flex justify-end mt-6">
                    <x-danger-button type="button" wire:click="cerrar">
                        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                        <span class="material-symbols-outlined">arrow_back</span>
                        {{ __('Volver') }}
                    </x-danger-button>
                </div>
            </div>
        </div>
    </div>
</div>