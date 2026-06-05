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
                                {{ $evento->especialEvento()->first() ? $evento->especialEvento()->first()->especial_evento_name : 'Ninguno' }}
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
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                {{ $evento->is_independiente_evento ? 'Sí' : 'No' }}
                            </p>
                        </div>

                        {{-- Laborable --}}
                        <div>
                            <x-input-label value="Laborable:" />
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                {{ $evento->is_laborable_evento ? 'Sí' : 'No' }}
                            </p>
                        </div>

                        {{-- Repetible --}}
                        <div>
                            <x-input-label value="Repetible:" />
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                {{ $evento->is_repetible_evento ? 'Sí' : 'No' }}
                            </p>
                        </div>

                        {{-- Superponible --}}
                        <div>
                            <x-input-label value="Superponible a vacaciones:" />
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                {{ $evento->is_superponible_evento ? 'Sí' : 'No' }}
                            </p>
                        </div>

                        {{-- Día Específico --}}
                        <div>
                            <x-input-label value="Ocurre en un Día Específico:" />
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                {{ $evento->is_dia_evento ? 'Sí' : 'No' }}
                            </p>
                        </div>

                        @if($evento->is_dia_evento && $evento->dia_evento)
                        <div>
                            <x-input-label value="Fecha Específica:" />
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                {{ \Carbon\Carbon::parse($evento->dia_evento)->format('d/m/Y') }}
                            </p>
                        </div>
                        @endif

                        {{-- Semana Específica --}}
                        <div>
                            <x-input-label value="Ocurre en Semanas Específicas:" />
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">
                                {{ $evento->is_semana_evento ? 'Sí' : 'No' }}
                            </p>
                        </div>

                        @if($evento->is_semana_evento && $evento->semana_evento)
                        <div>
                            <x-input-label value="Semanas Configuradas:" />
                            <p class="text-gray-700 dark:text-gray-300 text-sm font-medium">
                                @php
                                    $semanas = is_array($evento->semana_evento) ? $evento->semana_evento : (json_decode($evento->semana_evento, true) ?? []);
                                @endphp
                                @foreach($semanas as $s)
                                    Lapso {{ $s['lapso'] ?? '?' }} - Semana {{ $s['semana'] ?? '?' }}<br>
                                @endforeach
                            </p>
                        </div>
                        @endif

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

                {{-- Sección de Atenuantes --}}
                @if($evento && !empty($evento->justificativo_evento))
                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-300 mb-4 flex items-center gap-2">
                            Atenuantes Registrados
                        </h3>
                        
                        @php
                            $justificativos = is_string($evento->justificativo_evento) 
                                ? json_decode($evento->justificativo_evento, true) 
                                : $evento->justificativo_evento;
                            if (!is_array($justificativos)) $justificativos = [];
                            
                            $lapsos = collect($justificativos)->pluck('lapso')->unique()->sort();
                        @endphp

                        @foreach($lapsos as $lapso)
                            <div class="mt-4 mb-6">
                                <h4 class="text-md font-bold text-gray-700 dark:text-gray-300 mb-3 border-b pb-1">Lapso {{ $lapso ?? 1 }}</h4>
                                <div class="space-y-4">
                                    @foreach($justificativos as $j)
                                        @if(($j['lapso'] ?? 1) == $lapso)
                                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/50 p-4 rounded-md shadow-sm">
                                                <p class="text-sm text-orange-700 dark:text-orange-400 mb-2 font-bold">{{ $j['periodo'] ?? 'N/A' }}</p>
                                                
                                                <div class="bg-white dark:bg-gray-900 border border-orange-300 dark:border-orange-700 rounded-md p-3 shadow-sm mb-3">
                                                    <p class="text-gray-800 dark:text-gray-200 text-sm italic">
                                                        "{{ $j['texto'] ?? 'Sin texto registrado.' }}"
                                                    </p>
                                                </div>


                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
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