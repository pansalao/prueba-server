<div>
    <style>
        /* Estilos para inputs de fecha en modo oscuro */
        .date-input-dark::-webkit-calendar-picker-indicator {
            background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23d1d5db'%3e%3cpath fill-rule='evenodd' d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' clip-rule='evenodd'/%3e%3c/svg%3e") no-repeat center;
            background-size: 20px 20px;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .date-input-dark::-webkit-datetime-edit-text {
            color: #d1d5db !important;
        }

        .date-input-dark::-webkit-datetime-edit-month-field,
        .date-input-dark::-webkit-datetime-edit-day-field,
        .date-input-dark::-webkit-datetime-edit-year-field {
            color: #d1d5db !important;
        }

        /* Estilos para el date picker desplegable */
        .date-input-dark::-webkit-datetime-edit {
            color: #d1d5db !important;
        }

        /* Estilos para el calendario desplegable */
        .date-input-dark::-webkit-calendar-picker-indicator:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        /* Forzar tema oscuro en el date picker */
        .date-input-dark {
            color-scheme: dark;
        }

        /* Estilos adicionales para el date picker */
        .date-input-dark::-webkit-datetime-edit-fields-wrapper {
            background-color: transparent;
        }

        .date-input-dark::-webkit-datetime-edit-text {
            color: #d1d5db !important;
            background-color: transparent;
        }

        .date-input-dark::-webkit-datetime-edit-month-field,
        .date-input-dark::-webkit-datetime-edit-day-field,
        .date-input-dark::-webkit-datetime-edit-year-field {
            color: #d1d5db !important;
            background-color: transparent;
        }

        /* Fallback para otros navegadores */
        .date-input-dark::-moz-calendar-picker-indicator {
            filter: invert(1);
        }

        .date-input-dark::-ms-calendar-picker-indicator {
            filter: invert(1);
        }
    </style>

    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight uppercase text-center">
            {{ __('Crear Planificación Académica') }}
        </h2>
    </x-slot>

    <div class="sogat-card planificacion-module">
        <form wire:submit.prevent="savePlanificacion">
            <div class="space-y-4">

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4
                                                                                                                                                         dark:bg-green-700 dark:border-green-800 dark:text-green-100"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4
                                                                                                                                                         dark:bg-red-700 dark:border-red-800 dark:text-red-100"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-6 mb-6">
                    {{-- Selección de Asignación (Materia y Sección) --}}
                    <div class="min-w-0">
                        <x-select label="Asignación (Materia y Sección)" :options="$asignaciones"
                            valueField="id_detalle_profesor_asignado" textField="descripcion_completa"
                            wire:model.live="id_profesor_asignado" placeholder="Seleccione una asignación"
                            required />
                    </div>

                    {{-- Propósito de la Unidad Curricular --}}
                    @if($id_profesor_asignado && $proposito)
                        <div class="p-4 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 rounded-lg shadow-sm">
                            <div class="flex items-start gap-3">
                                <span class="material-icons text-blue-600 dark:text-blue-400">info</span>
                                <div>
                                    <h4 class="text-sm font-bold text-blue-800 dark:text-blue-200 uppercase tracking-tight">Propósito de la Unidad Curricular</h4>
                                    <p class="mt-1 text-sm text-blue-700 dark:text-blue-300 leading-relaxed italic">
                                        "{{ $proposito }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sección de Distribución Académica (Cortes) -->
                @if($id_profesor_asignado)
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                        <div class="flex items-center gap-2 mb-2 text-gray-700 dark:text-gray-300">
                            <span class="material-icons text-sm">event_note</span>
                            <span class="text-xs font-bold uppercase tracking-wider">Distribución: 4 Unidades Académicas (25% cada una)</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                @endif
                <div class="space-y-6" x-data="{ openUnidad: 0 }">
                    <div class="flex items-center justify-between border-b border-gray-300 dark:border-gray-600 pb-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                            Planificación de Unidades
                        </h2>
                        <div class="flex gap-2">
                            @foreach ($unidades as $idx => $u)
                                <button type="button" @click="openUnidad = {{ $idx }}"
                                    :class="openUnidad === {{ $idx }} ? 'bg-[#767676] text-white' : 'bg-[#f0f0f0] text-black border border-[#767676]'"
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold transition-all duration-200 text-sm shadow-sm">
                                    {{ $idx + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @foreach ($unidades as $index => $unidad)
                        @php
                            $totalPonderacion = $this->getTotalPonderacionForUnidad($index);
                            $validPonderacion = abs($totalPonderacion - 25) < 0.01;

                            // Temas disponibles para esta unidad específica (Renamed for clarity)
                            $temasUnidad = isset($temasPorUnidad[$index + 1]) ? $temasPorUnidad[$index + 1] : [];

                            // Opciones para la forma de participación
                            $formasParticipacion = collect([
                                (object) ['id' => '1', 'nombre' => 'Individual'],
                                (object) ['id' => '2', 'nombre' => 'Pareja'],
                                (object) ['id' => '3', 'nombre' => 'Grupal'],
                            ]);
                        @endphp

                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm transition-all duration-300"
                            :class="openUnidad === {{ $index }} ? 'ring-2 ring-blue-500 ring-opacity-50' : ''">

                            <!-- Cabecera del Accordion -->
                            <button type="button" @click="openUnidad = openUnidad === {{ $index }} ? null : {{ $index }}"
                                class="w-full flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                        Unidad {{ $unidad['numero'] }}
                                    </h3>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Ponderación:</span>
                                        <span
                                            class="text-sm font-bold {{ $validPonderacion ? 'text-green-600 dark:text-green-400' : 'text-red-600' }}">
                                            {{ $totalPonderacion }}% / 25%
                                        </span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300"
                                        :class="openUnidad === {{ $index }} ? 'rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            <!-- Contenido del Accordion -->
                            <div x-show="openUnidad === {{ $index }}" x-collapse>
                                <div class="p-6 bg-white dark:bg-gray-800 space-y-8">
                                    {{-- Objetivos de la Unidad --}}
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <h4
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                Objetivos de la unidad
                                            </h4>
                                            <button type="button" wire:click="addItem({{ $index }}, 'objetivos')"
                                                class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm">
                                                <span class="material-icons text-sm">add</span>
                                                AÑADIR OBJETIVO
                                            </button>
                                        </div>

                                        <div class="space-y-3">
                                            @foreach ($unidad['objetivos'] as $objetivoIndex => $objetivo)
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-grow">
                                                        <x-text-input placeholder="Escriba el objetivo de la unidad..."
                                                            wire:model.live.debounce.500ms="unidades.{{ $index }}.objetivos.{{ $objetivoIndex }}.nombre_objetivo"
                                                            class="w-full text-sm" required />
                                                        @error("unidades.$index.objetivos.$objetivoIndex.nombre_objetivo")
                                                            <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    @if (count($unidad['objetivos']) > 1)
                                                        <button type="button"
                                                            wire:click="removeItem({{ $index }}, 'objetivos', {{ $objetivoIndex }})"
                                                            class="text-gray-400 hover:text-red-500 transition-colors p-1">
                                                            <span class="material-icons text-sm">delete</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Contenidos --}}
                                    <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <h4
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                Temática general
                                            </h4>
                                            <button type="button" wire:click="addItem({{ $index }}, 'contenidos')"
                                                class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm">
                                                <span class="material-icons text-sm">add</span>
                                                AÑADIR CONTENIDO
                                            </button>
                                        </div>

                                        @foreach ($unidad['contenidos'] as $contenidoIndex => $contenido)
                                            <div
                                                class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 space-y-4">
                                                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                                    {{-- Columna de Contenido --}}
                                                    <div class="space-y-4">
                                                        <div class="flex items-center justify-between">
                                                            <label
                                                                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Tema
                                                                y Contenido</label>
                                                            @if (count($unidad['contenidos']) > 1)
                                                                <button type="button"
                                                                    wire:click="removeItem({{ $index }}, 'contenidos', {{ $contenidoIndex }})"
                                                                    class="text-red-500 hover:text-red-700 text-[10px] font-bold uppercase flex items-center gap-1">
                                                                    <span class="material-icons text-xs">delete</span> ELIMINAR
                                                                </button>
                                                            @endif
                                                        </div>

                                                        {{-- Select de Tema --}}
                                                        <div>
                                                            <x-select :options="$temasUnidad" valueField="id_tema"
                                                                textField="titulo_tema"
                                                                wire:model.live.debounce.250ms="unidades.{{ $index }}.contenidos.{{ $contenidoIndex }}.tema_id"
                                                                placeholder="Seleccione un tema" class="text-sm w-full" required />
                                                        </div>

                                                        {{-- Calcular contenidos filtrados --}}
                                                        @php
                                                            $selectedTemaId = $unidad['contenidos'][$contenidoIndex]['tema_id'] ?? null;
                                                            $opcionesContenido = $todosLosContenidos->where('id_tema', $selectedTemaId);
                                                        @endphp

                                                        {{-- Select de Contenido --}}
                                                        <div>
                                                            <x-select :options="$opcionesContenido" valueField="id_contenido"
                                                                textField="titulo_contenido"
                                                                wire:model.live.debounce.250ms="unidades.{{ $index }}.contenidos.{{ $contenidoIndex }}.contenido_id"
                                                                placeholder="Seleccione un contenido" class="text-sm w-full"
                                                                :disabled="empty($selectedTemaId)" required />
                                                        </div>
                                                    </div>

                                                    {{-- Columna de Indicadores --}}
                                                    <div class="space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <label
                                                                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Indicadores
                                                                de Logro</label>
                                                            <button type="button"
                                                                wire:click="addItem({{ $index }}, 'indicadores_logros', {{ $contenidoIndex }})"
                                                                class="text-black dark:text-gray-300 hover:underline text-[10px] font-bold uppercase flex items-center gap-1">
                                                                <span class="material-icons text-xs">add_circle</span> AÑADIR
                                                            </button>
                                                        </div>

                                                        <div class="space-y-2">
                                                            @forelse ($contenido['indicadores_logros'] as $indicadorIndex => $indicador)
                                                                <div class="flex items-center gap-2">
                                                                    <div class="flex-grow">
                                                                        <x-select :options="$indicadores"
                                                                            valueField="id_indicador_logro"
                                                                            textField="nombre_indicador_logro"
                                                                            wire:model.live.debounce.250ms="unidades.{{ $index }}.contenidos.{{ $contenidoIndex }}.indicadores_logros.{{ $indicadorIndex }}.indicador_id"
                                                                            placeholder="Seleccione un indicador"
                                                                             class="text-sm w-full" required />
                                                                     </div>
                                                                     @if (count($contenido['indicadores_logros']) > 1)
                                                                         <button type="button"
                                                                             wire:click="removeItem({{ $index }}, 'indicadores_logros', {{ $indicadorIndex }}, {{ $contenidoIndex }})"
                                                                             class="text-gray-400 hover:text-red-500 transition-colors">
                                                                             <span
                                                                                 class="material-icons text-sm">remove_circle_outline</span>
                                                                         </button>
                                                                     @endif
                                                                 </div>
                                                            @empty
                                                                <p class="text-xs text-gray-500 dark:text-gray-400 italic">No hay
                                                                    indicadores añadidos.</p>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Recursos y Estrategias --}}
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <h4
                                                    class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                    Recursos
                                                </h4>
                                                <button type="button" wire:click="addItem({{ $index }}, 'recursos')"
                                                    class="text-black dark:text-gray-300 hover:underline text-[10px] font-bold uppercase flex items-center gap-1">
                                                    <span class="material-icons text-xs">add_circle</span> AÑADIR
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 gap-2">
                                                @foreach ($unidad['recursos'] as $recursoIndex => $recurso)
                                                    <div class="flex items-center gap-2">
                                                        <div class="flex-grow">
                                                            <x-select :options="$recursosMaestros" valueField="id_recurso"
                                                                textField="nombre_recurso"
                                                                wire:model.live.debounce.250ms="unidades.{{ $index }}.recursos.{{ $recursoIndex }}.recurso_id"
                                                                 placeholder="Seleccione un recurso" class="text-sm w-full" required />
                                                        </div>
                                                        @if (count($unidad['recursos']) > 1)
                                                            <button type="button"
                                                                wire:click="removeItem({{ $index }}, 'recursos', {{ $recursoIndex }})"
                                                                class="text-gray-400 hover:text-red-500 transition-colors">
                                                                <span class="material-icons text-sm">delete</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <h4
                                                    class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                    Estrategias
                                                </h4>
                                                <button type="button" wire:click="addItem({{ $index }}, 'estrategias')"
                                                    class="text-black dark:text-gray-300 hover:underline text-[10px] font-bold uppercase flex items-center gap-1">
                                                    <span class="material-icons text-xs">add_circle</span> AÑADIR
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 gap-2">
                                                @foreach ($unidad['estrategias'] as $estrategiaIndex => $estrategia)
                                                    <div class="flex items-center gap-2">
                                                        <div class="flex-grow">
                                                            <x-select :options="$estrategiasMaestras"
                                                                valueField="id_estrategia_pedagogica"
                                                                textField="nombre_estrategia_pedagogica"
                                                                wire:model.live.debounce.250ms="unidades.{{ $index }}.estrategias.{{ $estrategiaIndex }}.estrategia_id"
                                                                placeholder="Seleccione una estrategia"
                                                                 class="text-sm w-full" required />
                                                        </div>
                                                        @if (count($unidad['estrategias']) > 1)
                                                            <button type="button"
                                                                wire:click="removeItem({{ $index }}, 'estrategias', {{ $estrategiaIndex }})"
                                                                class="text-gray-400 hover:text-red-500 transition-colors">
                                                                <span class="material-icons text-sm">delete</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Plan de Evaluación --}}
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <h4
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                Plan de Evaluación
                                            </h4>
                                            <button type="button" wire:click="addItem({{ $index }}, 'evaluaciones')"
                                                class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm">
                                                <span class="material-icons text-sm">add</span>
                                                AÑADIR EVALUACIÓN
                                            </button>
                                        </div>

                                        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                            <table class="w-full text-xs text-left table-fixed min-w-[850px]">
                                                <thead
                                                    class="bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 uppercase font-bold">
                                                    <tr>
                                                        <th class="px-2 py-3" width="20%">Fecha</th>
                                                        <th class="px-2 py-3" width="22%">Evaluación</th>
                                                        <th class="px-2 py-3" width="22%">Técnica</th>
                                                        <th class="px-2 py-3" width="10%">Pond. (%)</th>
                                                        <th class="px-2 py-3" width="21%">Participación</th>
                                                        <th class="px-2 py-3" width="5%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                                    @foreach ($unidad['evaluaciones'] as $evaluacionIndex => $evaluacion)
                                                        <tr>
                                                            <td class="px-2 py-3">
                                                                <x-text-input type="date"
                                                                    wire:model.live.debounce.250ms="unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.fecha_evaluacion"
                                                                    class="w-full text-xs" required />
                                                            </td>
                                                            <td class="px-2 py-3">
                                                                <x-select :options="$evaluaciones" valueField="id_evaluacion"
                                                                    textField="nombre_evaluacion"
                                                                    wire:model.live.debounce.250ms="unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.evaluacion_id"
                                                                    placeholder="Seleccione" class="text-xs" required />
                                                            </td>
                                                            <td class="px-2 py-3">
                                                                <x-select :options="$tecnicas" valueField="id_tecnica"
                                                                    textField="nombre_tecnica"
                                                                    wire:model.live.debounce.250ms="unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.tecnica_id"
                                                                    placeholder="Seleccione" class="text-xs" required />
                                                            </td>
                                                            <td class="px-2 py-3 text-center">
                                                                <input type="number" step="0.5" min="1" max="25"
                                                                    wire:model.live.debounce.250ms="unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.ponderacion"
                                                                    class="w-16 bg-transparent border-0 focus:ring-0 p-0 text-gray-700 dark:text-gray-300 text-xs font-bold text-center">
                                                            </td>
                                                            <td class="px-2 py-3">
                                                                <x-select :options="$formasParticipacion" valueField="id"
                                                                    textField="nombre"
                                                                    wire:model.live.debounce.250ms="unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.forma_participacion"
                                                                    placeholder="Seleccione" class="text-xs" required />
                                                            </td>
                                                            <td class="px-2 py-3 text-right">
                                                                @if (count($unidad['evaluaciones']) > 1)
                                                                    <button type="button"
                                                                        wire:click="removeItem({{ $index }}, 'evaluaciones', {{ $evaluacionIndex }})"
                                                                        class="text-gray-400 hover:text-red-500">
                                                                        <span class="material-icons text-sm">delete</span>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        {{-- Errors handled inside x-select --}}
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Navegación entre acordeones --}}
                                    <div class="flex justify-between items-center pt-4">
                                        <div>
                                            @if ($index > 0)
                                                <button type="button" @click="openUnidad = {{ $index - 1 }}"
                                                    class="inline-flex items-center gap-2 px-6 py-2 bg-[#f0f0f0] border border-[#767676] text-black rounded-lg text-sm font-bold shadow-sm hover:bg-gray-200 transition-all">
                                                    <span class="material-icons text-sm">arrow_back</span> Unidad Anterior
                                                </button>
                                            @endif
                                        </div>
                                        <div>
                                            @if ($index < count($unidades) - 1)
                                                <button type="button" @click="openUnidad = {{ $index + 1 }}"
                                                    class="inline-flex items-center gap-2 px-6 py-2 bg-[#f0f0f0] border border-[#767676] text-black rounded-lg text-sm font-bold shadow-sm hover:bg-gray-200 transition-all">
                                                    Siguiente Unidad <span class="material-icons text-sm">arrow_forward</span>
                                                </button>
                                            @else
                                                <div class="text-xs text-gray-400 italic">Última unidad de planificación</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="sogat-card mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Bibliografía</h3>
                            <div class="flex flex-wrap gap-2 self-start sm:self-auto">

                                <button type="button" wire:click="addItem(null, 'bibliografias')"
                                    class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm">
                                    <span class="material-icons text-sm">add</span>
                                    AÑADIR BIBLIOGRAFÍA
                                </button>
                            </div>
                        </div>

                        @foreach ($bibliografias as $biblioIndex => $bibliografia)
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-4">
                                <div class="flex-grow min-w-0">
                                    <x-select label="Seleccionar Bibliografía" required :options="$bibliografiasMaestras"
                                        valueField="id_bibliografia" textField="nombre_bibliografia"
                                        wire:model.live.debounce.250ms="bibliografias.{{ $biblioIndex }}.bibliografia_id" />
                                </div>
                                @if (count($bibliografias) > 1)
                                    <button type="button" wire:click="removeItem(null, 'bibliografias', {{ $biblioIndex }})"
                                        class="text-red-500 hover:text-red-700 text-sm self-start sm:self-auto">
                                        <i class="fas fa-trash mr-1"></i> ELIMINAR
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Botón de Guardar -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex font-semibold items-center px-5 py-2.5 bg-[#f0f0f0] border border-[#767676] rounded-lg font-medium text-sm text-black uppercase tracking-widest hover:bg-gray-200 focus:outline-none transition ease-in-out duration-150 disabled:bg-gray-300 disabled:opacity-75 disabled:cursor-not-allowed">
                            <i class="fas fa-save mr-2"></i> GUARDAR PLANIFICACIÓN
                        </button>
                    </div>
                </div>

        </form>
    </div>
</div>

