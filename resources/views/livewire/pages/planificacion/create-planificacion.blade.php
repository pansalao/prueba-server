<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight uppercase text-center">
            {{ __('Crear Planificación Académica') }}
        </h2>
    </x-slot>

    <div class="sogat-card planificacion-module">
        <form wire:submit.prevent="savePlanificacion" novalidate>
            <div class="space-y-4">

                <x-table.alert-message type="exitoso" :message="session('message')" />
                <x-table.alert-message type="error" :message="session('error')" />

                @if ($errors->any())
                    <x-table.alert-message type="error" message="La planificación no pudo ser guardada. Por favor, revise todos los campos requeridos en cada unidad." />
                @endif

                <div class="grid grid-cols-1 gap-6 mb-6">
                    {{-- Selección de Unidad Curricular --}}
                    <div class="min-w-0">
                        <x-select label="Unidad Curricular" :options="$asignaciones"
                            valueField="id_detalle_profesor_asignado" textField="descripcion_completa"
                            wire:model.live="form.id_profesor_asignado" placeholder="Seleccione una asignación"
                            selectClass="truncate max-w-full"
                            required />

                    </div>

                    {{-- Propósito de la Unidad Curricular --}}
                    @if($form->id_profesor_asignado && $proposito)
                        <div class="p-4 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 rounded-lg shadow-sm">
                            <div class="flex items-start gap-3">
                                <span class="material-icons text-blue-600 dark:text-blue-400">info</span>
                                <div>
                                    <h4 class="text-sm font-bold text-blue-800 dark:text-blue-200 uppercase tracking-tight">
                                        Propósito de la Unidad Curricular</h4>
                                    <p class="mt-1 text-sm text-blue-700 dark:text-blue-300 leading-relaxed italic">
                                        "{{ $proposito }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-6" x-data="{ openUnidad: 0 }">
                    <div class="flex items-center justify-between border-b border-gray-300 dark:border-gray-600 pb-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                            Planificación de Unidades
                        </h2>
                        <div class="flex gap-2">
                            @foreach ($form->unidades as $idx => $u)
                                <button type="button" @click="openUnidad = {{ $idx }}"
                                    :class="openUnidad === {{ $idx }} ? 'bg-[#767676] text-white' : 'bg-[#f0f0f0] text-black border border-[#767676]'"
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold transition-all duration-200 text-sm shadow-sm">
                                    {{ $idx + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @foreach ($form->unidades as $index => $unidad)
                        @php
                            $totalPonderacion = $this->form->getTotalPonderacionForUnidad($index);
                            $validPonderacion = abs($totalPonderacion - 25) < 0.01;

                            // Temas disponibles para esta unidad específica (Renamed for clarity)
                            $temasUnidad = isset($temasPorUnidad[$index + 1]) ? $temasPorUnidad[$index + 1] : [];

                            // Opciones para la forma de participación
                            $formasParticipacion = collect([
                                (object) ['id' => '1', 'nombre' => 'Individual'],
                                (object) ['id' => '2', 'nombre' => 'Grupal'],
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

                                    {{-- Contenidos agrupados por Objetivo --}}
                                    <div class="space-y-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <h4
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                Temática General
                                            </h4>
                                            <button type="button" wire:click="addItem({{ $index }}, 'objetivos')"
                                                class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm">
                                                <span class="material-icons text-sm">add</span>
                                                AÑADIR TEMA
                                            </button>
                                        </div>

                                        @foreach ($unidad['objetivos'] as $objetivoIndex => $objetivo)
                                            <div
                                                class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 space-y-4">

                                                {{-- Selección de Tema y Objetivo --}}
                                                <div class="grid grid-cols-1 gap-4">
                                                    {{-- Columna de Tema --}}
                                                    <div class="space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <label
                                                                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Tema</label>
                                                            @if (count($unidad['objetivos']) > 1)
                                                                <button type="button"
                                                                    wire:click="removeItem({{ $index }}, 'objetivos', {{ $objetivoIndex }})"
                                                                    class="text-gray-400 hover:text-red-500 transition-colors text-[10px] font-bold uppercase flex items-center gap-1">
                                                                    <span class="material-icons text-xs">delete</span> ELIMINAR
                                                                    OBJETIVO
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <x-select :options="$temasUnidad" valueField="id_tema_unidad"
                                                            textField="titulo_tema"
                                                            wire:model.live.debounce.250ms="form.unidades.{{ $index }}.objetivos.{{ $objetivoIndex }}.tema_id"
                                                            placeholder="Seleccione un tema" class="text-sm w-full" required />


                                                    </div>

                                                    {{-- Columna de Objetivo --}}
                                                    <div class="space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <label
                                                                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Objetivo</label>
                                                            @php
                                                                $selectedTemaId = $unidad['objetivos'][$objetivoIndex]['tema_id'] ?? null;
                                                            @endphp
                                                            <button type="button"
                                                                wire:click="openObjetivoModal('{{ $selectedTemaId }}')"
                                                                class="text-[10px] text-blue-600 dark:text-blue-400 font-bold hover:underline flex items-center gap-1 uppercase">
                                                                <span class="material-icons text-[12px]">add</span> NUEVO
                                                            </button>
                                                        </div>

                                                        @php
                                                            $opcionesObjetivo = $todosLosObjetivos->where('id_tema_unidad', $selectedTemaId);
                                                        @endphp

                                                        <x-select :options="$opcionesObjetivo" valueField="id_objetivo"
                                                            textField="titulo_objetivo"
                                                            wire:model.live.debounce.250ms="form.unidades.{{ $index }}.objetivos.{{ $objetivoIndex }}.objetivo_id"
                                                            placeholder="Seleccione un objetivo" class="text-sm w-full"
                                                            :disabled="empty($selectedTemaId)" required />
                                                    </div>
                                                </div>

                                                {{-- Contedor de Contenidos para este Objetivo --}}
                                                <div
                                                    class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <label
                                                            class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Contenidos
                                                            del Objetivo</label>
                                                        <button type="button"
                                                            wire:click="addItem({{ $index }}, 'contenidos', {{ $objetivoIndex }})"
                                                            class="text-[10px] font-bold uppercase text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                                            <span class="material-icons text-xs">add</span> Agregar Contenido
                                                        </button>
                                                    </div>

                                                    <div class="space-y-3">
                                                        @foreach ($objetivo['contenidos'] as $contenidoIndex => $contenido)
                                                            <div class="flex items-start gap-2">
                                                                <div class="flex-grow">
                                                                    @php
                                                                        $selectedObjetivoId = $unidad['objetivos'][$objetivoIndex]['objetivo_id'] ?? null;
                                                                        $opcionesContenido = $todosLosContenidos->where('id_objetivo', $selectedObjetivoId);
                                                                    @endphp
                                                                    <x-select :options="$opcionesContenido"
                                                                        valueField="id_contenido" textField="titulo_contenido"
                                                                        wire:model.live.debounce.250ms="form.unidades.{{ $index }}.objetivos.{{ $objetivoIndex }}.contenidos.{{ $contenidoIndex }}.contenido_id"
                                                                        placeholder="Seleccione un contenido" class="text-sm w-full"
                                                                        :disabled="empty($selectedObjetivoId)" required />
                                                                </div>
                                                                @if (count($objetivo['contenidos']) > 1)
                                                                    <button type="button"
                                                                        wire:click="removeItem({{ $index }}, 'contenidos', {{ $contenidoIndex }}, {{ $objetivoIndex }})"
                                                                        class="mt-2 text-gray-400 hover:text-red-500 transition-colors">
                                                                        <span class="material-icons text-sm">delete</span>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach



                                    </div>

                                    {{-- Estrategias Pedagógicas --}}
                                    <div class="space-y-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <h4
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                Estrategias Pedagógicas
                                            </h4>

                                        </div>

                                        @foreach ($unidad['estrategias'] as $estrategiaIndex => $estrategia)
                                            <div
                                                class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 space-y-4">
                                                <div class="flex items-center justify-end">
                                                    @if (count($unidad['estrategias']) > 1)
                                                        <button type="button"
                                                            wire:click="removeItem({{ $index }}, 'estrategias', {{ $estrategiaIndex }})"
                                                            class="text-gray-400 hover:text-red-500 transition-colors text-[10px] font-bold uppercase flex items-center gap-1">
                                                            <span class="material-icons text-xs">delete</span> ELIMINAR
                                                        </button>
                                                    @endif
                                                </div>

                                                <div class="grid grid-cols-1 gap-4">
                                                    {{-- Tema Select --}}
                                                    <div class="space-y-2">
                                                        <label
                                                            class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Tema
                                                            de la Estrategia</label>
                                                        <x-select :options="$temasUnidad" valueField="id_tema_unidad"
                                                            textField="titulo_tema"
                                                            wire:model.live.debounce.250ms="form.unidades.{{ $index }}.estrategias.{{ $estrategiaIndex }}.tema_id"
                                                            placeholder="Seleccione un tema" class="text-sm w-full" required />

                                                    </div>

                                                    {{-- Actividad Textarea --}}
                                                    <div class="space-y-2">
                                                        <label
                                                            class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Actividad</label>
                                                        <textarea
                                                            wire:model.live.debounce.500ms="form.unidades.{{ $index }}.estrategias.{{ $estrategiaIndex }}.actividad"
                                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm"
                                                            rows="3" placeholder="Describa la actividad..."></textarea>
                                                        @error("form.unidades.$index.estrategias.$estrategiaIndex.actividad")
                                                            <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                                                        @enderror

                                                    </div>

                                                    {{-- Recursos --}}
                                                    <div class="space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <label
                                                                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Recursos</label>
                                                            <button type="button"
                                                                wire:click="addItem({{ $index }}, 'estrategia_recursos', {{ $estrategiaIndex }})"
                                                                class="text-black dark:text-gray-300 hover:underline text-[10px] font-bold uppercase flex items-center gap-1">
                                                                <span class="material-icons text-xs">add_circle</span> AÑADIR
                                                            </button>
                                                        </div>
                                                        @foreach ($estrategia['recursos'] as $recursoIndex => $recurso)
                                                            <div class="flex items-center gap-2">
                                                                <div class="flex-grow">
                                                                    <x-select :options="$recursosMaestros" valueField="id_recurso"
                                                                        textField="nombre_recurso"
                                                                        wire:model.live.debounce.250ms="form.unidades.{{ $index }}.estrategias.{{ $estrategiaIndex }}.recursos.{{ $recursoIndex }}.recurso_id"
                                                                        placeholder="Seleccione un recurso" class="text-sm w-full"
                                                                        required />
                                                                </div>
                                                                @if (count($estrategia['recursos']) > 1)
                                                                    <button type="button"
                                                                        wire:click="removeItem({{ $index }}, 'estrategia_recursos', {{ $recursoIndex }}, {{ $estrategiaIndex }})"
                                                                        class="text-gray-400 hover:text-red-500 transition-colors">
                                                                        <span class="material-icons text-sm">delete</span>
                                                                    </button>
                                                                @endif
                                                            </div>

                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Indicadores de Logros --}}
                                    <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <h4
                                            class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                            Indicadores de Logros
                                        </h4>
                                        <div
                                            class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                                            <textarea
                                                wire:model.live.debounce.500ms="form.unidades.{{ $index }}.indicadores_logro"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm"
                                                rows="3"
                                                placeholder="Describa los indicadores de logros para esta unidad..."></textarea>
                                            @error("form.unidades.$index.indicadores_logro")
                                                <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                                            @enderror
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

                                        <div class="space-y-4">
                                            @foreach ($unidad['evaluaciones'] as $evaluacionIndex => $evaluacion)
                                                <div
                                                    class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm relative group">
                                                    {{-- Botón Eliminar --}}
                                                    @if (count($unidad['evaluaciones']) > 1)
                                                        <button type="button"
                                                            wire:click="removeItem({{ $index }}, 'evaluaciones', {{ $evaluacionIndex }})"
                                                            class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors">
                                                            <span class="material-icons text-sm">delete</span>
                                                        </button>
                                                    @endif

                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        {{-- Fila 1: Fecha, Evaluación, Técnica --}}
                                                        <div class="space-y-1">
                                                            <label
                                                                class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Fecha
                                                                <span class="text-red-500">*</span></label>
                                                            <x-text-input type="date"
                                                                wire:model.live.debounce.250ms="form.unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.fecha_evaluacion"
                                                                        class="w-full text-xs" required />
                                                                    @error("form.unidades.$index.evaluaciones.$evaluacionIndex.fecha_evaluacion")
                                                                        <span class="text-red-500 text-[10px] font-bold block">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <div class="space-y-1">
                                                                    <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Evaluación <span class="text-red-500">*</span></label>
                                                                    <x-select :options="$evaluaciones" valueField="id_tipo_evaluacion"
                                                                        textField="nombre_tipo_evaluacion"
                                                                        wire:model.live.debounce.250ms="form.unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.evaluacion_id"
                                                                        placeholder="Seleccione" class="text-xs w-full" required />

                                                                </div>

                                                                <div class="space-y-1">
                                                                    <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Técnica <span class="text-red-500">*</span></label>
                                                                    <x-select :options="$tecnica" valueField="id_tecnica_evaluacion"
                                                                        textField="nombre_tecnica_evaluacion"
                                                                        wire:model.live.debounce.250ms="form.unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.tecnica_id"
                                                                        placeholder="Seleccione" class="text-xs w-full" required />

                                                                </div>
                                                            </div>

                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                                                {{-- Fila 2: Participación, Pond. (%) --}}
                                                                <div class="space-y-1">
                                                                    <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Participación <span class="text-red-500">*</span></label>
                                                                    <div class="flex gap-2">
                                                                        <x-select :options="$formasParticipacion" valueField="id"
                                                                            textField="nombre"
                                                                            wire:model.live="form.unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.forma_participacion"
                                                                            placeholder="Seleccione" class="text-xs w-full" required />

                                                                        @if(isset($evaluacion['forma_participacion']) && $evaluacion['forma_participacion'] == '2')
                                                                            <select 
                                                                                wire:model.live="form.unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.integrantes"
                                                                                class="text-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-24">
                                                                                <option value="">N° Integrantes</option>
                                                                                @for($i = 2; $i <= 10; $i++)
                                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        @endif
                                                                    </div>

                                                                </div>

                                                                <div class="space-y-1">
                                                                    <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase text-center block">Pond. (%) <span class="text-red-500">*</span></label>
                                                                    <div class="flex justify-center">
                                                                        <input type="number" step="1" min="5" max="25"
                                                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                                            wire:model.live="form.unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.ponderacion"
                                                                            class="w-20 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 p-1.5 text-gray-700 dark:text-gray-300 text-sm font-bold text-center">
                                                                    </div>
                                                                    @error("form.unidades.$index.evaluaciones.$evaluacionIndex.ponderacion")
                                                                        <span class="text-red-500 text-[10px] font-bold block text-center">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                            @endforeach
                                            </div>
                                        </div>

                                        {{-- Sección: Referencias Bibliográficas (Por Unidad) --}}
                                        <div class="mt-8">
                                            <div class="flex justify-between items-center mb-4">
                                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                    Referencias Bibliográficas
                                                </h4>
                                                <button type="button" wire:click="addItem({{ $index }}, 'bibliografias')"
                                                    class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm">
                                                    <span class="material-icons text-sm">add</span>
                                                    AÑADIR BIBLIOGRAFÍA
                                                </button>
                                            </div>

                                            <div class="space-y-4">
                                                @foreach ($unidad['bibliografias'] as $biblioIndex => $bibliografia)
                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                                                        <div class="flex-grow min-w-0">
                                                            <x-select label="Seleccionar Bibliografía" required :options="$bibliografiasMaestras"
                                                                valueField="id_bibliografia" textField="nombre_bibliografia"
                                                                wire:model.live.debounce.250ms="form.unidades.{{ $index }}.bibliografias.{{ $biblioIndex }}.bibliografia_id" 
                                                                placeholder="Seleccione una referencia..." />
                                                        </div>
                                                        @if (count($unidad['bibliografias']) > 1)
                                                            <button type="button" 
                                                                wire:click="removeItem({{ $index }}, 'bibliografias', {{ $biblioIndex }})"
                                                                class="text-gray-400 hover:text-red-500 transition-colors"
                                                                title="Eliminar referencia">
                                                                <span class="material-icons text-sm">delete</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                    {{-- Mostrar error si existe --}}

                                                @endforeach
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
                                                @if ($index < count($form->unidades) - 1)
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

    @if($showObjetivoModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeObjetivoModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Crear Nuevo Objetivo
                                </h3>
                                <div class="mt-4">
                                    <label for="newObjetivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titulo del Objetivo</label>
                                    <input type="text" wire:model="newObjetivoNombre" id="newObjetivo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Ingrese el título del objetivo">
                                    @error('newObjetivoNombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="button" wire:click="saveObjetivo" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button type="button" wire:click="closeObjetivoModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

