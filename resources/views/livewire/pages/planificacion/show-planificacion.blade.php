<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-center text-gray-800 dark:text-gray-200 leading-tight uppercase">
            {{ __('Detalles de la Planificación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">
                @if (session()->has('message'))
                    <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Header / Información General --}}
                <div class="flex justify-between items-start border-b pb-4 mb-4 dark:border-gray-700">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-tight">
                            {{ $planificacion->nombre_unidad_curricular ?? 'Unidad Curricular' }}
                        </h3>
                        <div class="flex flex-col gap-1 mt-1">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Docente: {{ $planificacion->docente_nombre ?? '' }} {{ $planificacion->docente_apellido ?? '' }} - C.I: {{ $planificacion->cedula ?? '' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Sección: {{ $planificacion->nombre_seccion ?? '' }}
                            </p>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                                Periodo: {{ $planificacion->nombre_lapso ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('planificacion.reporte.detalle', $planificacionId) }}" target="_blank"
                            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1.5 px-3 rounded shadow transition-all duration-200"
                            title="Ver Plan de Curso en PDF (Nueva pestaña)">
                            <span class="material-icons text-white text-base">picture_as_pdf</span>
                            PDF
                        </a>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if (($planificacion->estatus ?? 0) == 1) bg-green-100 text-green-800
                            @elseif(($planificacion->estatus ?? 0) == 2) bg-yellow-100 text-yellow-800
                            @elseif(($planificacion->estatus ?? 0) == 3) bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $estatusTexto ?? 'Desconocido' }}
                        </span>
                    </div>
                </div>

                {{-- Acordeón de Cortes --}}
                <div class="space-y-6" x-data="{ openCorte: 0 }">
                    <div class="flex items-center justify-between border-b border-gray-300 dark:border-gray-600 pb-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                            Definición de Cortes
                        </h2>
                        <div class="flex gap-2">
                            @if (!empty($planificacion->cortes))
                                @foreach ($planificacion->cortes as $idx => $c)
                                    <button type="button" @click="openCorte = {{ $idx }}"
                                        :class="openCorte === {{ $idx }} ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold transition-all duration-200 text-sm shadow-sm">
                                        {{ $idx + 1 }}
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4">
                        @if (!empty($planificacion->cortes))
                            @foreach ($planificacion->cortes as $idx => $corte)
                                <div class="border rounded-xl dark:border-gray-700 shadow-sm overflow-hidden transition-all duration-300"
                                    :class="openCorte === {{ $idx }} ? 'ring-2 ring-blue-500 ring-opacity-50' : ''">
                                    <button @click="openCorte = openCorte === {{ $idx }} ? null : {{ $idx }}"
                                        class="w-full p-4 bg-gray-50 dark:bg-gray-700 flex justify-between items-center hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <span class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-tight">
                                            Corte {{ $corte->corte }}
                                        </span>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="text-xs px-2 py-0.5 rounded-full
                                                                                                                            @if (($corte->estatus ?? 0) == 1) bg-green-100 text-green-700
                                                                                                                            @elseif(($corte->estatus ?? 0) == 2) bg-yellow-100 text-yellow-700
                                                                                                                            @elseif(($corte->estatus ?? 0) == 3) bg-red-100 text-red-700
                                                                                                                            @else bg-gray-200 text-gray-600 @endif">
                                                {{ $corte->estatus_texto ?? 'N/A' }}
                                            </span>
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300"
                                                :class="openCorte === {{ $idx }} ? 'rotate-180' : ''" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </button>

                                    <div x-show="openCorte === {{ $idx }}" x-collapse
                                        class="bg-white dark:bg-gray-800 p-4 space-y-4">
                                        {{-- Contenidos del Corte --}}
                                        @if (!empty($corte->contenidos))
                                            <div>
                                                <h4
                                                    class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1">
                                                    Contenidos
                                                </h4>
                                                <ul
                                                    class="list-disc list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                                    @foreach ($corte->contenidos as $contenido)
                                                        <li>
                                                            <span
                                                                class="font-medium text-gray-800 dark:text-gray-200">{{ $contenido['titulo_contenido'] ?? 'Sin Título' }}</span>
                                                            @if (!empty($contenido['indicadores_logros']))
                                                                <ul class="ml-5 list-circle mt-1 space-y-1 text-xs">
                                                                    @foreach ($contenido['indicadores_logros'] as $indicador)
                                                                        <li>- {{ $indicador['descripcion_indicador'] ?? '' }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            {{-- Recursos --}}
                                            @if (!empty($corte->recursos))
                                                <div>
                                                    <h4
                                                        class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1">
                                                        Recursos
                                                    </h4>
                                                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                                                        @foreach ($corte->recursos as $recurso)
                                                            <li>{{ $recurso['recurso'] ?? '' }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            {{-- Estrategias --}}
                                            @if (!empty($corte->estrategias))
                                                <div>
                                                    <h4
                                                        class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1">
                                                        Estrategias
                                                    </h4>
                                                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                                                        @foreach ($corte->estrategias as $estrategia)
                                                            <li>{{ $estrategia['estrategia'] ?? '' }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Evaluaciones --}}
                                        @if (!empty($corte->evaluaciones))
                                            <div>
                                                <h4
                                                    class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1">
                                                    Plan de Evaluación
                                                </h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full text-xs text-left text-gray-500 dark:text-gray-400">
                                                        <thead
                                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                            <tr>
                                                                <th scope="col" class="px-3 py-2">Evaluación</th>
                                                                <th scope="col" class="px-3 py-2">Técnica</th>
                                                                <th scope="col" class="px-3 py-2">Ponderación</th>
                                                                <th scope="col" class="px-3 py-2">Fecha</th>
                                                                <th scope="col" class="px-3 py-2">Participación</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($corte->evaluaciones as $eval)
                                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                                    <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">
                                                                        {{ $eval['evaluacion'] ?? '' }}
                                                                    </td>
                                                                    <td class="px-3 py-2">{{ $eval['tecnica'] ?? '' }}</td>
                                                                    <td class="px-3 py-2">{{ $eval['ponderacion'] ?? 0 }}%</td>
                                                                    <td class="px-3 py-2">{{ $eval['fecha_evaluacion'] ?? '' }}</td>
                                                                    <td class="px-3 py-2">
                                                                        @if (($eval['forma_participacion'] ?? '') == '1')
                                                                            Individual
                                                                        @elseif(($eval['forma_participacion'] ?? '') == '2')
                                                                            Pareja
                                                                        @elseif(($eval['forma_participacion'] ?? '') == '3')
                                                                            Grupal
                                                                        @else
                                                                            N/A
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Area de Motivo de Rechazo (Solo Coordinador) --}}
                                        @if ($mostrarBotonRechazarPlanificacion && Gate::allows('is-coordinador'))
                                            <div class="mt-4 border-t pt-4 dark:border-gray-600">
                                                {{-- Caso 1: El corte no está rechazado y no se ha pulsado 'Rechazar' --}}
                                                @if (($corte->estatus ?? 0) != 3 && empty($mostrarMotivoRechazoCorte[$corte->detalle_id]))
                                                    <div class="flex justify-end gap-2">
                                                        <button wire:click="mostrarTextareaMotivo({{ $corte->detalle_id }})"
                                                            class="inline-flex items-center gap-1 text-xs bg-red-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-red-700 transition-colors shadow-sm uppercase">
                                                            Rechazar corte
                                                        </button>
                                                        @if (($corte->estatus ?? 0) != 1)
                                                            <button wire:click="aprobarCorte({{ $corte->detalle_id }})"
                                                                class="inline-flex items-center gap-1 text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-blue-700 transition-colors shadow-sm uppercase">
                                                                Aceptar corte
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif

                                                {{-- Caso 2: Se está redactando el motivo de rechazo --}}
                                                @if (!empty($mostrarMotivoRechazoCorte[$corte->detalle_id]) && ($corte->estatus ?? 0) != 3)
                                                    <div class="space-y-2">
                                                        <x-input-label for="motivo_rechazo_{{ $corte->detalle_id }}" :value="__('Motivo de Rechazo')" />
                                                        <textarea wire:model.defer="motivosRechazoCortes.{{ $corte->detalle_id }}"
                                                            id="motivo_rechazo_{{ $corte->detalle_id }}" rows="3"
                                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                                        <div class="flex justify-end space-x-2">
                                                            <button wire:click="ocultarTextareaMotivo({{ $corte->detalle_id }})"
                                                                class="inline-flex items-center gap-1 text-xs bg-red-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-red-700 transition-colors shadow-sm uppercase">
                                                                Cancelar
                                                            </button>
                                                            <button wire:click="confirmarRechazoCorte({{ $corte->detalle_id }})"
                                                                class="inline-flex items-center gap-1 text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-blue-700 transition-colors shadow-sm uppercase">
                                                                Aceptar
                                                            </button>
                                                        </div>
                                                        @error("motivosRechazoCortes.{$corte->detalle_id}")
                                                            <span class="text-red-600 text-xs">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Caso 3: El corte ya está rechazado (Mostramos el cuadro rojo premium) --}}
                                        @if (($corte->estatus ?? null) == 3 && !empty($corte->ultimo_motivo_rechazo))
                                            <div class="mt-4">
                                                <h4 class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                                    Motivo de Rechazo
                                                </h4>
                                                <div
                                                    class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-md">
                                                    <p class="text-sm text-red-800 dark:text-red-300">
                                                        {{ $corte->ultimo_motivo_rechazo }}
                                                    </p>
                                                </div>
                                                @if (Gate::allows('is-coordinador'))
                                                    <div class="flex justify-end mt-2">
                                                        <button wire:click="eliminarMotivoRechazo({{ $corte->detalle_id }})"
                                                            class="inline-flex items-center gap-1 text-xs bg-red-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-red-700 transition-colors shadow-sm uppercase">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Quitar motivo
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">No hay cortes registrados en esta
                                planificación.</p>
                        @endif
                    </div>

                    {{-- Bibliografías --}}
                    @if (!empty($planificacion->bibliografias))
                        <div class="mt-6">
                            <h4
                                class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1">
                                Referencias Bibliográficas
                            </h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                                @foreach ($planificacion->bibliografias as $biblio)
                                    <li>{{ $biblio['bibliografia'] ?? '' }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Footer con Botones de Acción --}}
                    <div class="mt-8 flex justify-end space-x-3 pt-4 border-t dark:border-gray-700">
                        <button wire:click="cerrar"
                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow transition-all duration-200 uppercase">
                            <span class="material-icons text-white text-base">arrow_back</span>
                            Volver
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
