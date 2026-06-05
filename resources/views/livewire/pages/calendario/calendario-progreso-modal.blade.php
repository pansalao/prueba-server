<div x-data="{ showProgresoModal: false, searchQuery: '', expanded: {} }"
    @open-progreso-modal.window="showProgresoModal = true" x-show="showProgresoModal" style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="showProgresoModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
            @click="showProgresoModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div x-show="showProgresoModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

            <!-- Cabecera del modal -->
            <div
                class="bg-white dark:bg-gray-800 px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2"
                    id="modal-title">
                    <span class="material-icons text-green-500">analytics</span>
                    Eventos Asignados y Progreso
                </h3>
                <button type="button" @click="showProgresoModal = false"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <!-- Contenido del modal -->
            <div class="px-4 py-5 sm:p-6 max-h-[60vh] overflow-y-auto">
                <div class="mb-4 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons text-gray-400 text-sm">search</span>
                    </span>
                    <input type="text" x-model="searchQuery"
                        class="pl-10 w-full block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Buscar evento asignado...">
                </div>

                <div class="space-y-4">
                    @foreach($this->progresoEventos as $index => $progreso)
                        <div x-show="!searchQuery || '{{ strtolower($progreso['nombre']) }}'.includes(searchQuery.toLowerCase())"
                            class="flex flex-col p-3 rounded-lg border {{ $progreso['completado'] ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : 'bg-gray-50 border-gray-200 dark:bg-gray-700/50 dark:border-gray-600' }}">

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full flex-shrink-0"
                                        style="background-color: {{ $progreso['color'] }}"></div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                            {{ $progreso['nombre'] }}</h4>
                                        @if(!($progreso['is_por_lapso'] ?? false))
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                @if($progreso['limite'] === null)
                                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Ilimitado</span>
                                                @elseif($progreso['completado'])
                                                    <span class="text-green-600 dark:text-green-400 font-medium">Completado</span>
                                                @else
                                                    Faltan {{ $progreso['restantes'] }}
                                                    {{ ($progreso['is_dias'] ?? false) ? 'días' : 'instancias' }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @if(!($progreso['is_por_lapso'] ?? false))
                                    <div class="text-right flex items-center gap-4">
                                        <div>
                                            <div
                                                class="text-sm font-bold {{ $progreso['completado'] ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                                @if($progreso['limite'] === null)
                                                    Asignados {{ $progreso['agregados'] }}
                                                    {{ ($progreso['is_dias'] ?? false) ? 'días' : 'instancias' }}
                                                @else
                                                    Asignados {{ $progreso['agregados'] }} de {{ $progreso['limite'] }}
                                                    {{ ($progreso['is_dias'] ?? false) ? 'días' : 'instancias' }}
                                                @endif
                                            </div>
                                            @if($progreso['limite'] !== null)
                                                <div
                                                    class="w-24 h-2 mt-1 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden ml-auto">
                                                    <div class="h-full {{ $progreso['completado'] ? 'bg-green-500' : 'bg-blue-500' }}"
                                                        style="width: {{ min(100, ($progreso['agregados'] / $progreso['limite']) * 100) }}%">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" @click="expanded[{{ $index }}] = !expanded[{{ $index }}]"
                                            class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <span class="material-icons transition-transform duration-200"
                                                :class="{'rotate-180': expanded[{{ $index }}]}">expand_more</span>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            @if($progreso['is_por_lapso'] ?? false)
                                <div class="mt-3 space-y-2">
                                    @foreach($progreso['progreso_por_lapso'] as $lapsoIndex => $lapso)
                                        @php 
                                            $lapsoCompletado = $lapso['agregados'] >= $progreso['limite_por_lapso'];
                                            $lapsoKey = $index . '_lapso_' . $lapsoIndex;
                                        @endphp
                                        <div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 p-2">
                                            <div class="flex items-center justify-between cursor-pointer" @click="expanded['{{ $lapsoKey }}'] = !expanded['{{ $lapsoKey }}']">
                                                <div>
                                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $lapso['nombre'] }}</span>
                                                    <p class="text-[11px] text-gray-500">
                                                        @if($lapsoCompletado)
                                                            <span class="text-green-600">Completado</span>
                                                        @else
                                                            Faltan {{ max(0, $progreso['limite_por_lapso'] - $lapso['agregados']) }} inst.
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <div class="text-right">
                                                        <span class="text-xs font-bold {{ $lapsoCompletado ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                                            Asignados {{ $lapso['agregados'] }} de {{ $progreso['limite_por_lapso'] }}
                                                        </span>
                                                        <div class="w-20 h-1.5 mt-0.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden ml-auto">
                                                            <div class="h-full {{ $lapsoCompletado ? 'bg-green-500' : 'bg-blue-500' }}" style="width: {{ min(100, ($lapso['agregados'] / $progreso['limite_por_lapso']) * 100) }}%"></div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                                        <span class="material-icons transition-transform duration-200 text-[18px]" :class="{'rotate-180': expanded['{{ $lapsoKey }}']}">expand_more</span>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Panel Fechas Lapso -->
                                            <div x-show="expanded['{{ $lapsoKey }}']" x-collapse class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                                @if(count($lapso['fechas_asignadas']) > 0)
                                                    <ul class="space-y-1">
                                                        @foreach($lapso['fechas_asignadas'] as $fecha)
                                                            <li class="text-[11px] text-gray-600 dark:text-gray-400 flex items-center justify-between bg-gray-50 dark:bg-gray-900/50 p-1.5 rounded">
                                                                <div class="flex items-center gap-1.5">
                                                                    <span class="material-icons text-[12px] text-gray-400">event</span>
                                                                    <span>
                                                                        {{ \Carbon\Carbon::parse($fecha['inicio'])->format('d/m/Y') }}
                                                                        @if($fecha['inicio'] !== $fecha['fin'])
                                                                            - {{ \Carbon\Carbon::parse($fecha['fin'])->format('d/m/Y') }}
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-[11px] text-gray-500 italic">No hay fechas asignadas registradas en el componente.</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    {{-- Fuera de lapso --}}
                                    @if(count($progreso['fechas_asignadas']) > 0)
                                        <div class="bg-orange-50 dark:bg-orange-900/10 rounded border border-orange-200 dark:border-orange-800/30 p-2">
                                            <div class="flex items-center justify-between cursor-pointer" @click="expanded['{{ $index }}_fuera'] = !expanded['{{ $index }}_fuera']">
                                                <div>
                                                    <span class="text-sm font-bold text-orange-700 dark:text-orange-400 flex items-center gap-1">
                                                        <span class="material-icons text-[16px]">warning</span> Fuera de Lapso
                                                    </span>
                                                    <p class="text-[11px] text-orange-500">
                                                        Fechas asignadas incorrectamente
                                                    </p>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs font-bold text-orange-600 dark:text-orange-400">
                                                        {{ count($progreso['fechas_asignadas']) }} instancias
                                                    </span>
                                                    <button type="button" class="text-orange-400 hover:text-orange-600 focus:outline-none">
                                                        <span class="material-icons transition-transform duration-200 text-[18px]" :class="{'rotate-180': expanded['{{ $index }}_fuera']}">expand_more</span>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div x-show="expanded['{{ $index }}_fuera']" x-collapse class="mt-2 pt-2 border-t border-orange-200 dark:border-orange-800/30">
                                                <ul class="space-y-1">
                                                    @foreach($progreso['fechas_asignadas'] as $fecha)
                                                        <li class="text-[11px] text-orange-700 dark:text-orange-300 flex items-center justify-between bg-white dark:bg-gray-800 p-1.5 rounded">
                                                            <div class="flex items-center gap-1.5">
                                                                <span class="material-icons text-[12px] text-orange-400">event</span>
                                                                <span>
                                                                    {{ \Carbon\Carbon::parse($fecha['inicio'])->format('d/m/Y') }}
                                                                    @if($fecha['inicio'] !== $fecha['fin'])
                                                                        - {{ \Carbon\Carbon::parse($fecha['fin'])->format('d/m/Y') }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Panel de Fechas Asignadas (Original) -->
                                <div x-show="expanded[{{ $index }}]" x-collapse
                                    class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    @if(count($progreso['fechas_asignadas']) > 0)
                                        <ul class="space-y-1">
                                            @foreach($progreso['fechas_asignadas'] as $fecha)
                                                <li
                                                    class="text-[11px] text-gray-600 dark:text-gray-300 flex items-center justify-between bg-white dark:bg-gray-800 p-1.5 rounded border border-gray-100 dark:border-gray-700">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="material-icons text-[12px] text-gray-400">event</span>
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($fecha['inicio'])->format('d/m/Y') }}
                                                            @if($fecha['inicio'] !== $fecha['fin'])
                                                                - {{ \Carbon\Carbon::parse($fecha['fin'])->format('d/m/Y') }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @if(isset($fecha['dias']))
                                                        <span class="font-medium text-[10px] text-gray-500">{{ $fecha['dias'] }} días</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-[11px] text-gray-500 italic">No hay fechas asignadas registradas en el componente.</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach

                    @if(count($this->progresoEventos) === 0)
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <span class="material-icons text-4xl mb-2 opacity-50">info</span>
                            <p>No hay eventos asignados o con límites de repetición en la biblioteca actual.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pie del modal -->
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <x-secondary-button type="button" @click="showProgresoModal = false"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Cerrar
                </x-secondary-button>
            </div>
        </div>
    </div>
</div>