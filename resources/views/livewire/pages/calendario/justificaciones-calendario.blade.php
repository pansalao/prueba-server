<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-center text-gray-800 dark:text-gray-500 leading-tight uppercase">
            {{ __('Atenuantes del Calendario') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">

                @if ($calendario && count($justificaciones) > 0)
                    @php
                        // Orden personalizado: Lapso 1 -> Intensivo -> Lapso 2
                        $ordenLapso = ['1' => 0, '' => 1, '2' => 2];
                        $etiquetasLapso = ['1' => 'Lapso 1', '' => 'Intensivo', '2' => 'Lapso 2'];
                        
                        // Agrupar por lapso
                        $grupos = collect($justificaciones)->groupBy(function($j) {
                            return $j['lapso'] ?? '';
                        });
                    @endphp

                    <div class="mt-2 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-300 mb-4 flex items-center gap-2">
                            Atenuantes Registrados
                        </h3>

                        @foreach($ordenLapso as $lapsoVal => $priority)
                            @if($grupos->has($lapsoVal))
                                <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/50 p-5 rounded-xl shadow-sm mb-6">
                                    <h4 class="text-md font-bold text-gray-700 dark:text-gray-300 mb-4 border-b border-orange-200 dark:border-orange-800/50 pb-2">
                                        {{ $etiquetasLapso[$lapsoVal] ?? 'Otros' }}
                                    </h4>
                                    
                                    <div class="space-y-4">
                                        @foreach($grupos[$lapsoVal] as $j)
                                            <div class="bg-white dark:bg-gray-800/50 border border-orange-200/60 dark:border-orange-700/40 rounded-lg p-4 shadow-sm">
                                                <p class="text-sm text-orange-700 dark:text-orange-400 mb-2 font-bold">{{ $j['nombre_campo'] ?? $j['periodo'] ?? 'N/A' }}</p>

                                                @if(!empty($j['dato_colocado']) || !empty($j['dato_esperado']))
                                                    <div class="flex flex-wrap gap-3 mb-3 text-xs text-gray-500 dark:text-gray-400">
                                                        <span class="inline-flex items-center gap-1 bg-orange-50/80 dark:bg-gray-900 border border-orange-200 dark:border-orange-700/50 rounded-md px-2.5 py-1 shadow-sm">
                                                            <span class="font-semibold text-orange-600 dark:text-orange-400">Semanas colocadas:</span>
                                                            {{ $j['dato_colocado'] ?? '—' }}
                                                        </span>
                                                        <span class="inline-flex items-center gap-1 bg-orange-50/80 dark:bg-gray-900 border border-orange-200 dark:border-orange-700/50 rounded-md px-2.5 py-1 shadow-sm">
                                                            <span class="font-semibold text-orange-600 dark:text-orange-400">Semanas esperadas:</span>
                                                            {{ $j['dato_esperado'] ?? '—' }}
                                                        </span>
                                                    </div>
                                                @endif

                                                <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-md p-3 shadow-sm">
                                                    <p class="text-gray-800 dark:text-gray-200 text-sm italic">
                                                        "{{ $j['texto'] ?? 'Sin texto registrado.' }}"
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">
                            Este calendario no tiene atenuantes registrados.
                        </p>
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
