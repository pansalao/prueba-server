<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-center text-gray-800 dark:text-gray-200 leading-tight uppercase">
            {{ __('Detalles de la Planificación') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
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
                        <p class="text-base font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide">
                            {{ $planificacion->docente_rol ?? 'Docente' }}: {{ $planificacion->docente_nombre ?? '' }} {{ $planificacion->docente_apellido ?? '' }}
                        </p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-tight break-words overflow-hidden">
                            {{ $planificacion->nombre_unidad_curricular ?? 'Unidad Curricular' }} - Sección {{ $planificacion->nombre_seccion ?? '' }}
                        </h3>
                        <div class="flex flex-col gap-1 mt-1">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                C.I: {{ $planificacion->cedula ?? '' }}
                            </p>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                                Periodo: {{ $planificacion->nombre_lapso ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if (($planificacion->estatus ?? 0) == 1)
                            <a href="{{ route('planificacion.reporte.detalle', $planificacionId) }}" target="_blank"
                                class="flex items-center gap-2 bg-[#f0f0f0] border border-[#767676] text-black text-xs font-bold py-1.5 px-3 rounded shadow transition-all duration-200 hover:bg-gray-200 whitespace-nowrap"
                                title="Ver Plan de Curso en PDF (Nueva pestaña)">
                                <span class="material-icons text-black text-base">picture_as_pdf</span>
                                PDF
                            </a>
                            <a href="{{ route('planificacion.acuerdo', $planificacionId) }}" target="_blank"
                                class="flex items-center gap-2 bg-[#f0f0f0] border border-[#767676] text-black text-xs font-bold py-1.5 px-3 rounded shadow transition-all duration-200 hover:bg-gray-200 whitespace-nowrap"
                                title="Ver Acuerdo de Aprendizaje (Nueva pestaña)">
                                <span class="material-icons text-black text-base">assignment</span>
                                ACUERDO DE APRENDIZAJE
                            </a>
                        @endif
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if (($planificacion->estatus ?? 0) == 1) bg-green-100 text-green-800
                            @elseif(($planificacion->estatus ?? 0) == 2) bg-yellow-100 text-yellow-800
                            @elseif(($planificacion->estatus ?? 0) == 3) bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $estatusTexto ?? 'Desconocido' }}
                        </span>
                    </div>
                </div>

                {{-- Acordeón de Unidades --}}
                <div class="space-y-6" x-data="{ openUnidad: 0 }">
                    <div class="flex items-center justify-between border-b border-gray-300 dark:border-gray-600 pb-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                            Definición de Unidades
                        </h2>
                        <div class="flex gap-2">
                            @if (!empty($planificacion->unidades))
                                @foreach ($planificacion->unidades as $idx => $u)
                                    <button type="button" @click="openUnidad = {{ $idx }}"
                                        :class="openUnidad === {{ $idx }} ? 'bg-[#767676] text-white' : 'bg-[#f0f0f0] text-black border border-[#767676]'"
                                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold transition-all duration-200 text-sm shadow-sm">
                                        {{ $idx + 1 }}
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4">
                        @if (!empty($planificacion->unidades))
                            @foreach ($planificacion->unidades as $idx => $unidad)
                                <div class="border rounded-xl dark:border-gray-700 shadow-sm overflow-hidden transition-all duration-300"
                                    :class="openUnidad === {{ $idx }} ? 'ring-2 ring-blue-500 ring-opacity-50' : ''">
                                    <button @click="openUnidad = openUnidad === {{ $idx }} ? null : {{ $idx }}"
                                        class="w-full p-4 bg-gray-50 dark:bg-gray-700 flex justify-between items-center hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <span class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-tight">
                                            Unidad {{ $unidad->numero }}
                                        </span>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="text-xs px-2 py-0.5 rounded-full
                                                                                                                            @if (($unidad->estatus ?? 0) == 1) bg-green-100 text-green-700
                                                                                                                            @elseif(($unidad->estatus ?? 0) == 2) bg-yellow-100 text-yellow-700
                                                                                                                            @elseif(($unidad->estatus ?? 0) == 3) bg-red-100 text-red-700
                                                                                                                            @else bg-gray-200 text-gray-600 @endif">
                                                {{ $unidad->estatus_texto ?? 'N/A' }}
                                            </span>
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300"
                                                :class="openUnidad === {{ $idx }} ? 'rotate-180' : ''" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </button>

                                    <div x-show="openUnidad === {{ $idx }}" x-collapse
                                        class="bg-white dark:bg-gray-800 p-4 space-y-4">
                                        {{-- Contenidos de la Unidad --}}
                                        @if (!empty($unidad->contenidos))
                                            <div>
                                                <h4
                                                    class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1">
                                                    Contenidos
                                                </h4>
                                                <ul
                                                    class="list-disc list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                                    @foreach ($unidad->contenidos as $contenido)
                                                        <li>
                                                            <div class="mt-1 mb-2 bg-gray-50 dark:bg-gray-900/50 p-2 rounded-lg border border-gray-100 dark:border-gray-700">
                                                                <div>
                                                                    <span class="text-xs font-bold text-gray-500 uppercase">Tema:</span>
                                                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $contenido['titulo_tema'] ?? 'Sin Tema' }}</span>
                                                                </div>
                                                                <div class="mt-1">
                                                                    <span class="text-xs font-bold text-gray-500 uppercase">Objetivo:</span>
                                                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $contenido['titulo_objetivo'] ?? 'Sin Objetivo' }}</span>
                                                                </div>
                                                                <div class="mt-1">
                                                                    <span class="text-xs font-bold text-gray-500 uppercase">Contenido:</span>
                                                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $contenido['titulo_contenido'] ?? 'Sin Título' }}</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            {{-- Recursos --}}
                                            @if (!empty($unidad->recursos))
                                                <div>
                                                    <h4
                                                        class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1">
                                                        Recursos
                                                    </h4>
                                                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                                                        @foreach ($unidad->recursos as $recurso)
                                                            <li>{{ $recurso['recurso'] ?? '' }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            {{-- Estrategias --}}
                                            @if (!empty($unidad->estrategias))
                                                <div>
                                                    <h4
                                                        class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1">
                                                        Estrategias
                                                    </h4>
                                                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                                                        @foreach ($unidad->estrategias as $estrategia)
                                                            <li class="mb-2">
                                                                <span class="text-xs font-bold text-gray-500 uppercase block">Tema: {{ $estrategia['titulo_tema'] ?? 'Sin Tema' }}</span>
                                                                <span class="text-sm text-gray-800 dark:text-gray-200">{{ $estrategia['actividad'] ?? '' }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Bibliografías --}}
                                        @if (!empty($unidad->bibliografias))
                                            <div class="mt-4">
                                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1 text-xs uppercase tracking-wide">
                                                    Referencias Bibliográficas
                                                </h4>
                                                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                                    @foreach ($unidad->bibliografias as $biblio)
                                                        <li>{{ $biblio['bibliografia'] ?? '' }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        {{-- Indicadores de Logros --}}
                                        @if (!empty($unidad->indicadores_logro))
                                            <div>
                                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b dark:border-gray-700 pb-1 uppercase text-xs">
                                                    Indicadores de Logros
                                                </h4>
                                                <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-700 text-sm italic text-gray-600 dark:text-gray-400 break-words overflow-hidden">
                                                    {{ $unidad->indicadores_logro }}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Evaluaciones --}}
                                        @if (!empty($unidad->evaluaciones))
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
                                                                <th scope="col" class="px-3 py-2 border-r dark:border-gray-600" width="15%">Fecha</th>
                                                                <th scope="col" class="px-3 py-2 border-r dark:border-gray-600" width="22%">Evaluación</th>
                                                                <th scope="col" class="px-3 py-2 border-r dark:border-gray-600" width="22%">Técnica</th>
                                                                <th scope="col" class="px-3 py-2 border-r dark:border-gray-600" width="26%">Participación</th>
                                                                <th scope="col" class="px-3 py-2" width="15%">Ponderación</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($unidad->evaluaciones as $eval)
                                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                                    <td class="px-3 py-2 border-r dark:border-gray-700">{{ $eval['fecha_evaluacion'] ?? '' }}</td>
                                                                    <td class="px-3 py-2 font-medium text-gray-900 dark:text-white break-words min-w-[150px] border-r dark:border-gray-700">
                                                                        {{ $eval['evaluacion'] ?? '' }}
                                                                    </td>
                                                                    <td class="px-3 py-2 border-r dark:border-gray-700">{{ $eval['tecnica'] ?? '' }}</td>
                                                                    <td class="px-3 py-2 border-r dark:border-gray-700">
                                                                        @if (($eval['forma_participacion'] ?? '') == '1')
                                                                            Individual
                                                                        @elseif(($eval['forma_participacion'] ?? '') == '2')
                                                                            Grupal ({{ $eval['integrantes'] ?? '2-10' }})
                                                                        @else
                                                                            N/A
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-3 py-2 font-bold">{{ $eval['ponderacion'] ?? 0 }}%</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Area de Motivo de Rechazo (Solo Coordinador) --}}
                                        @if ($mostrarBotonRechazarPlanificacion && (Gate::allows('cambiar-estatus-planificacion') || Gate::allows('aprobacion-vocero-planificacion')))
                                            <div class="mt-4 border-t pt-4 dark:border-gray-600">
                                                {{-- Caso 1: La unidad no está rechazada y no se ha pulsado 'Rechazar' --}}
                                                @if (($unidad->estatus ?? 0) != 3 && empty($mostrarMotivoRechazoCorte[$unidad->detalle_id]))
                                                    <div class="flex justify-end gap-2">
                                                        <button wire:click="mostrarTextareaMotivo({{ $unidad->detalle_id }})"
                                                            class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
                                                            Rechazar unidad
                                                        </button>
                                                        @if (($unidad->estatus ?? 0) != 1)
                                                            <button wire:click="aprobarCorte({{ $unidad->detalle_id }})"
                                                                class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
                                                                Aceptar unidad
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif

                                                {{-- Caso 2: Se está redactando el motivo de rechazo --}}
                                                @if (!empty($mostrarMotivoRechazoCorte[$unidad->detalle_id]) && ($unidad->estatus ?? 0) != 3)
                                                    <div class="space-y-2">
                                                        <x-input-label for="motivo_rechazo_{{ $unidad->detalle_id }}" :value="__('Motivo de Rechazo')" />
                                                        <textarea wire:model.defer="motivosRechazoCortes.{{ $unidad->detalle_id }}"
                                                            id="motivo_rechazo_{{ $unidad->detalle_id }}" rows="3"
                                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                                        <div class="flex justify-end space-x-2">
                                                            <button wire:click="ocultarTextareaMotivo({{ $unidad->detalle_id }})"
                                                                class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
                                                                Cancelar
                                                            </button>
                                                            <button wire:click="confirmarRechazoCorte({{ $unidad->detalle_id }})"
                                                                class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
                                                                Aceptar
                                                            </button>
                                                        </div>
                                                        @error("motivosRechazoCortes.{$unidad->detalle_id}")
                                                            <span class="text-red-600 text-xs">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Caso 3: La unidad ya está rechazada (Mostramos el cuadro rojo premium) --}}
                                        @if (($unidad->estatus ?? null) == 3 && !empty($unidad->ultimo_motivo_rechazo))
                                            <div class="mt-4">
                                                <h4 class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                                    Motivo de Rechazo
                                                </h4>
                                                <div
                                                    class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-md">
                                                    <p class="text-sm text-red-800 dark:text-red-300">
                                                        {{ $unidad->ultimo_motivo_rechazo }}
                                                    </p>
                                                </div>
                                                @if (Gate::allows('cambiar-estatus-planificacion') || Gate::allows('aprobacion-vocero-planificacion'))
                                                    <div class="flex justify-end mt-2">
                                                        <button wire:click="eliminarMotivoRechazo({{ $unidad->detalle_id }})"
                                                            class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
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
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">No hay unidades registradas en esta
                                planificación.</p>
                        @endif
                    </div>



                    {{-- Sección de Carga de Contrato de Estudiantes (Solo para Planificación Aprobada y para el Docente) --}}
                    @if (($planificacion->estatus ?? 0) == 1)
                        <div class="mt-10 p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-2xl">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                    <span class="material-icons">draw</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 uppercase tracking-tight">
                                        Contrato Estudiantil
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Cargue aquí el contrato firmado por los estudiantes (PDF o Imagen).</p>
                                </div>
                            </div>

                            @if(Auth::id() == ($planificacion->docente_id ?? null))
                            <form wire:submit.prevent="saveContrato" class="space-y-4">
                                <div class="flex flex-col sm:flex-row items-center gap-4">
                                    <div class="flex-1 w-full">
                                        <input type="file" wire:model="contratoEstudiantes" id="contratoEstudiantes"
                                            class="block w-full text-sm text-gray-500 dark:text-gray-400
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-xl file:border-0
                                            file:text-sm file:font-black
                                            file:bg-blue-600 file:text-white
                                            hover:file:bg-blue-700
                                            transition-all cursor-pointer"
                                            accept=".pdf,image/*">
                                        @error('contratoEstudiantes') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    @if ($contratoEstudiantes)
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold shadow-lg hover:bg-green-700 transition-all hover:-translate-y-0.5">
                                            <span class="material-icons text-sm">cloud_upload</span>
                                            SUBIR CONTRATO
                                        </button>
                                    @endif
                                </div>

                                <div wire:loading wire:target="contratoEstudiantes" class="text-sm text-blue-600 font-bold">
                                    <span class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Cargando archivo...
                                    </span>
                                </div>
                            </form>
                            @endif

                            @if ($contratoPath)
                                <div class="mt-6 flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center">
                                            <span class="material-icons text-base">check_circle</span>
                                        </div>
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Archivo de contrato ya cargado</span>
                                    </div>
                                    <a href="{{ Storage::url($contratoPath) }}" target="_blank"
                                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-bold text-sm transition-colors">
                                        <span class="material-icons text-sm">visibility</span>
                                        VER CONTRATO CARGADO
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Footer con Botones de Acción --}}
                    <div class="mt-8 flex justify-end space-x-3 pt-4 border-t dark:border-gray-700">
                        <button wire:click="cerrar"
                            class="inline-flex font-bold items-center px-4 py-2 bg-[#f0f0f0] border border-[#767676] rounded-lg text-sm text-black uppercase tracking-widest hover:bg-gray-200 focus:outline-none transition ease-in-out duration-150 shadow-sm gap-2">
                            <span class="material-icons text-black text-base">arrow_back</span>
                            Volver
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

