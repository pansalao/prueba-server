<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-center text-gray-800 dark:text-gray-200 leading-tight uppercase">
            {{ __('Detalles de la Planificación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
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
                                                @if (empty($motivosRechazoCortes[$corte->detalle_id]) && empty($mostrarMotivoRechazoCorte[$corte->detalle_id]))
                                                    <div class="flex justify-end">
                                                        <button wire:click="mostrarTextareaMotivo({{ $corte->detalle_id }})"
                                                            class="inline-flex items-center gap-1 text-xs bg-red-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-red-700 transition-colors shadow-sm uppercase">
                                                            Rechazar corte
                                                        </button>
                                                    </div>
                                                @endif

                                                @if (!empty($mostrarMotivoRechazoCorte[$corte->detalle_id]) || !empty($motivosRechazoCortes[$corte->detalle_id]))
                                                    <div class="space-y-2">
                                                        <x-input-label for="motivo_rechazo_{{ $corte->detalle_id }}" :value="__('Motivo de Rechazo')" />
                                                        <textarea wire:model.defer="motivosRechazoCortes.{{ $corte->detalle_id }}"
                                                            id="motivo_rechazo_{{ $corte->detalle_id }}" rows="3"
                                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                                        <div class="flex justify-end space-x-2">
                                                            @if (empty($motivosRechazoCortes[$corte->detalle_id]))
                                                                <button wire:click="ocultarTextareaMotivo({{ $corte->detalle_id }})"
                                                                    class="inline-flex items-center gap-1 text-xs bg-red-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-red-700 transition-colors shadow-sm uppercase">
                                                                    Cancelar
                                                                </button>
                                                            @else
                                                                <button wire:click="eliminarMotivoRechazo({{ $corte->detalle_id }})"
                                                                    class="text-red-500 hover:text-red-700 text-xs flex items-center">
                                                                    <svg class="w-3 h-3 mr-1" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                    Quitar motivo
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- **NUEVO: Motivo de Rechazo para este Corte** --}}
                                        @if (($corte->estatus ?? null) == 3 && !empty($corte->ultimo_motivo_rechazo))
                                            <div
                                                class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-md">
                                                <p class="text-sm font-medium text-red-800 dark:text-red-300">
                                                    Motivo de Rechazo: <span
                                                        class="font-normal">{{ $corte->ultimo_motivo_rechazo }}</span>
                                                </p>
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
                        <x-secondary-button wire:click="cerrar">
                            {{ __('Volver') }}
                        </x-secondary-button>

                        @if ($mostrarBotonRechazarPlanificacion && Gate::allows('is-coordinador'))
                            <div x-data="{ confirmingRechazo: false }">
                                <x-danger-button @click="confirmingRechazo = true" x-show="!confirmingRechazo">
                                    {{ __('Rechazar Planificación') }}
                                </x-danger-button>

                                <div x-show="confirmingRechazo" class="flex items-center space-x-2">
                                    <span class="text-sm text-red-600">¿Confirmar rechazo?</span>
                                    <button wire:click="rechazarPlanificacion"
                                        class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">
                                        Sí, Rechazar
                                    </button>
                                    <button @click="confirmingRechazo = false"
                                        class="px-2 py-1 bg-gray-300 text-gray-700 rounded text-xs hover:bg-gray-400">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if (($planificacion->estatus ?? null) != 1 && Gate::allows('is-coordinador'))
                            <x-primary-button wire:click="aprobarPlanificacion" class="bg-green-600 hover:bg-green-700">
                                {{ __('Aprobar Planificación') }}
                            </x-primary-button>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>