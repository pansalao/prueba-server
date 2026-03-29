<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight uppercase text-center">
            {{ __('Crear Planificación Académica') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 sm:rounded-lg">
        <x-table.alert-message type="exitoso" :message="session('message')" />
        <x-table.alert-message type="error" :message="session('error')" />

        @if ($errors->any())
            <x-table.alert-message type="error" message="La planificación no pudo ser guardada. Por favor, revise todos los campos requeridos en cada unidad." />
        @endif
    </div>

    <div class="sogat-card planificacion-module">
        <form wire:submit.prevent="savePlanificacion" novalidate>
            <div class="space-y-4">

                <div class="grid grid-cols-1 gap-6 mb-6">
                    {{-- Selección de Unidad Curricular --}}
                    <div class="min-w-0">
                        <x-select label="Unidad Curricular" :options="$asignaciones"
                            valueField="id_detalle_profesor_asignado" textField="descripcion_completa"
                            wire:model.live="form.id_profesor_asignado" placeholder="Seleccione una asignación"
                            selectClass="truncate max-w-full"
                            required />

                    </div>

                    {{-- Propósito de la Unidad Curricular, Malla y Lapso --}}
                    @if($form->id_profesor_asignado)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            @if($mallaNombre)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 border-l-4 border-gray-400 rounded-lg shadow-sm">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tight">Malla Curricular</h4>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $mallaNombre }}</p>
                            </div>
                            @endif
                            @if($lapsoNombre)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 border-l-4 border-gray-400 rounded-lg shadow-sm">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tight">Lapso Académico</h4>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $lapsoNombre }}</p>
                            </div>
                            @endif
                        </div>

                        @if($proposito)
                        <div class="p-4 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 rounded-lg shadow-sm mt-4">
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
                    @endif
                </div>

                <div class="space-y-6" x-data="{ openUnidad: 0 }">
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-300 dark:border-gray-600 pb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                                Planificación de Unidades
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Diligencie cada unidad paso a paso.</p>
                        </div>
                        <div class="flex items-center gap-3 bg-gray-100 dark:bg-gray-900 p-2 rounded-2xl shadow-inner">
                            @foreach ($form->unidades as $idx => $u)
                                <button type="button" @click="openUnidad = {{ $idx }}"
                                    class="relative group focus:outline-none">
                                    <div :class="openUnidad === {{ $idx }} ? 'bg-blue-600 dark:bg-blue-500 text-white scale-110 shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:border-blue-400'"
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 text-sm">
                                        {{ $idx + 1 }}
                                    </div>
                                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity" :class="openUnidad === {{ $idx }} ? 'hidden' : ''"></div>
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

                        <div x-show="openUnidad === {{ $index }}" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-x-8"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl overflow-hidden min-h-[500px] flex flex-col">
                            
                            {{-- Cabecera de la Página de Unidad --}}
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center font-black text-xl shadow-inner">
                                        {{ $unidad['numero'] }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                            Contenido de la Unidad {{ $unidad['numero'] }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Planificación Académica Detallada</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3 px-4 py-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Ponderación</span>
                                    <div class="flex items-center gap-1">
                                        <span class="text-lg font-black {{ $validPonderacion ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $totalPonderacion }}%
                                        </span>
                                        <span class="text-xs text-gray-400 font-bold">/ 25%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8 space-y-10 flex-grow">

                                    {{-- Contenidos agrupados por Objetivo --}}
                                    <div class="space-y-6">
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
                                                class="p-4 rounded-xl bg-gray-100 dark:bg-gray-950 border border-gray-200 dark:border-gray-700 space-y-4">

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
                                                        @error("form.unidades.$index.objetivos.$objetivoIndex.tema_id")
                                                            <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span>
                                                        @enderror


                                                    </div>

                                                    {{-- Columna de Objetivo --}}
                                                    <div class="space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <label
                                                                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Objetivo</label>
                                                            @php
                                                                $selectedTemaId = $unidad['objetivos'][$objetivoIndex]['tema_id'] ?? null;
                                                            @endphp
                                                            @if($isCoordinador)
                                                            <button type="button"
                                                                wire:click="openObjetivoModal('{{ $selectedTemaId }}')"
                                                                class="inline-flex items-center gap-1 text-[10px] bg-[#f0f0f0] border border-[#767676] text-black px-2 py-1 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
                                                                <span class="material-icons text-[12px]">add</span> NUEVO
                                                            </button>
                                                            @endif
                                                        </div>

                                                        @php
                                                            $opcionesObjetivo = $todosLosObjetivos->where('id_tema_unidad', $selectedTemaId);
                                                        @endphp

                                                        <x-select :options="$opcionesObjetivo" valueField="id_objetivo"
                                                            textField="titulo_objetivo"
                                                            wire:model.live.debounce.250ms="form.unidades.{{ $index }}.objetivos.{{ $objetivoIndex }}.objetivo_id"
                                                            placeholder="Seleccione un objetivo" class="text-sm w-full"
                                                            :disabled="empty($selectedTemaId)" required />
                                                        @error("form.unidades.$index.objetivos.$objetivoIndex.objetivo_id")
                                                            <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Contedor de Contenidos para este Objetivo --}}
                                                <div
                                                    class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <label
                                                            class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Contenidos
                                                            del Objetivo</label>
                                                        @php
                                                            $selectedObjetivoId = $unidad['objetivos'][$objetivoIndex]['objetivo_id'] ?? null;
                                                        @endphp
                                                        <button type="button"
                                                            wire:click="addItem({{ $index }}, 'contenidos', {{ $objetivoIndex }})"
                                                            @if(empty($selectedObjetivoId)) disabled @endif
                                                            class="inline-flex items-center gap-1 text-[10px] border border-[#767676] px-2 py-1 rounded-lg font-bold transition-colors shadow-sm uppercase {{ empty($selectedObjetivoId) ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-[#f0f0f0] text-black hover:bg-gray-200' }}">
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
                                                                    @error("form.unidades.$index.objetivos.$objetivoIndex.contenidos.$contenidoIndex.contenido_id")
                                                                        <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span>
                                                                    @enderror
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
                                                class="p-4 rounded-xl bg-gray-100 dark:bg-gray-950 border border-gray-200 dark:border-gray-700 space-y-4">
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
                                                        @error("form.unidades.$index.estrategias.$estrategiaIndex.tema_id")
                                                            <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span>
                                                        @enderror

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
                                                                class="inline-flex items-center gap-1 text-[10px] bg-[#f0f0f0] border border-[#767676] text-black px-2 py-1 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
                                                                <span class="material-icons text-xs">add</span> AÑADIR
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
                                                                    @error("form.unidades.$index.estrategias.$estrategiaIndex.recursos.$recursoIndex.recurso_id")
                                                                        <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span>
                                                                    @enderror
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
                                            class="p-4 rounded-xl bg-gray-100 dark:bg-gray-950 border border-gray-200 dark:border-gray-700">
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
                                                class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
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
                                                                    @error("form.unidades.$index.evaluaciones.$evaluacionIndex.evaluacion_id")
                                                                        <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span>
                                                                    @enderror

                                                                </div>

                                                                <div class="space-y-1">
                                                                    <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Técnica <span class="text-red-500">*</span></label>
                                                                    <x-select :options="$tecnica" valueField="id_tecnica_evaluacion"
                                                                        textField="nombre_tecnica_evaluacion"
                                                                        wire:model.live.debounce.250ms="form.unidades.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.tecnica_id"
                                                                        placeholder="Seleccione" class="text-xs w-full" required />
                                                                    @error("form.unidades.$index.evaluaciones.$evaluacionIndex.tecnica_id")
                                                                        <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span>
                                                                    @enderror

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
                                                                        @error("form.unidades.$index.evaluaciones.$evaluacionIndex.forma_participacion")
                                                                            <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span>
                                                                        @enderror

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
                                                    class="inline-flex items-center gap-1 text-xs bg-[#f0f0f0] border border-[#767676] text-black px-3 py-1.5 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
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
                                                            @error("form.unidades.$index.bibliografias.$biblioIndex.bibliografia_id")
                                                                <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span>
                                                            @enderror
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
                                        <div class="flex justify-between items-center pt-8 mt-8 border-t border-gray-100 dark:border-gray-800">
                                            <div>
                                                @if ($index > 0)
                                                    <button type="button" @click="openUnidad = {{ $index - 1 }}; window.scrollTo({top: 0, behavior: 'smooth'})"
                                                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                                        <span class="material-icons text-sm">arrow_back</span> Unidad Anterior
                                                    </button>
                                                @endif
                                            </div>
                                            <div>
                                                @if ($index < count($form->unidades) - 1)
                                                    <button type="button" @click="openUnidad = {{ $index + 1 }}; window.scrollTo({top: 0, behavior: 'smooth'})"
                                                        class="inline-flex items-center gap-2 px-8 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg hover:bg-blue-700 transition-all hover:-translate-y-0.5 active:translate-y-0">
                                                        Siguiente Unidad <span class="material-icons text-sm">arrow_forward</span>
                                                    </button>
                                                @else
                                                    <div class="flex flex-col items-end">
                                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Final del Proceso</span>
                                                        <span class="text-xs text-gray-500 italic">Planificación completada</span>
                                                    </div>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    <!-- Botón de Guardar - Siempre visible al final o solo en la última página? -->
                    {{-- Lo dejamos siempre visible pero más destacado --}}
                    <div class="flex justify-end pt-4" x-show="openUnidad === {{ count($form->unidades) - 1 }}">
                        <button type="submit"
                            class="group inline-flex items-center gap-3 px-8 py-4 bg-green-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-green-700 focus:outline-none transition-all duration-300 shadow-xl hover:shadow-green-500/20 hover:-translate-y-1 active:scale-95">
                            <i class="fas fa-save text-lg transition-transform group-hover:rotate-12"></i> 
                            GUARDAR PLANIFICACIÓN COMPLETA
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

