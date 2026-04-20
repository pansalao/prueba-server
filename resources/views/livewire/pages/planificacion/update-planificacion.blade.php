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

        /* Forzar tema claro en el date picker */
        .date-input-dark {
            color-scheme: light;
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
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Planificación Académica') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 sm:rounded-lg">
        <x-table.alert-message type="exitoso" :message="session('message')" />
        <x-table.alert-message type="error" :message="session('error')" />

        @if ($errors->any())
            <x-table.alert-message type="error"
                message="La planificación no pudo ser actualizada. Por favor, revise todos los campos requeridos." />
        @endif
    </div>

    <div class="p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <form wire:submit.prevent="savePlanificacion">
            <div class="space-y-4">

                {{-- Header / Información General --}}
                <div class="flex justify-between items-start border-b pb-4 mb-4 dark:border-gray-700">
                    <div>
                        <p class="text-base font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide">
                            {{ $docente_rol }}: {{ $docente_nombre }} {{ $docente_apellido }}
                        </p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-tight truncate max-w-3xl">
                            {{ $nombre_unidad_curricular }} - Sección: {{ $nombre_seccion }}
                        </h3>
                        <div class="flex flex-col gap-1 mt-1">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                C.I: {{ $cedula }} | Malla: {{ $nombre_malla }}
                            </p>
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                                Periodo: {{ $nombre_lapso }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="space-y-6" x-data="{ openCorte: 0 }">
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-300 dark:border-gray-600 pb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                                Definición de Cortes
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gestione los contenidos y evaluaciones de cada periodo.</p>
                        </div>
                        <div class="flex items-center gap-3 bg-gray-100 dark:bg-gray-900 p-2 rounded-2xl shadow-inner">
                            @foreach ($form->cortes as $idx => $c)
                                <button type="button" @click="openCorte = {{ $idx }}"
                                    class="relative group focus:outline-none">
                                    <div :class="openCorte === {{ $idx }} ? 'bg-blue-600 dark:bg-blue-500 text-white scale-110 shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:border-blue-400'"
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 text-sm">
                                        {{ $idx + 1 }}
                                    </div>
                                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity" :class="openCorte === {{ $idx }} ? 'hidden' : ''"></div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @foreach ($form->cortes as $index => $corte)
                        @php
                            $totalPonderacion = $this->getTotalPonderacionForCorte($index);
                            $validPonderacion = abs($totalPonderacion - 25) < 0.01;
                        @endphp

                        <div x-show="openCorte === {{ $index }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-x-8"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl overflow-hidden min-h-[500px] flex flex-col">

                            {{-- Cabecera de la Página de Corte --}}
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center font-black text-xl shadow-inner">
                                        {{ $corte['corte'] }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                            Detalles del Corte {{ $corte['corte'] }}
                                        </h3>
                                        @php
                                            $estatus = $corte['estatus'] ?? 2;
                                            $bgClass = 'bg-gray-200 text-gray-600';
                                            $statusText = 'Desconocido';

                                            if ($estatus == 1) {
                                                $bgClass = 'bg-green-100 text-green-700';
                                                $statusText = 'Aprobado';
                                            } elseif ($estatus == 2) {
                                                $bgClass = 'bg-yellow-100 text-yellow-700';
                                                $statusText = 'Pendiente';
                                            } elseif ($estatus == 3) {
                                                $bgClass = 'bg-red-100 text-red-700';
                                                $statusText = 'Rechazado';
                                            }
                                        @endphp
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase {{ $bgClass }}">
                                                {{ $statusText }}
                                            </span>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Planificación de Periodo</p>
                                        </div>
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

                            <div x-data="{ openSection: 'tematica' }" class="p-8 space-y-4 flex-grow">
                                @if ($corte['ultimo_motivo_rechazo'])
                                    <div
                                        class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                        <p class="text-xs text-red-700 dark:text-red-400 font-bold uppercase mb-1">Motivo de
                                            rechazo:</p>
                                        <p class="text-sm text-red-600 dark:text-red-300">
                                            {{ $corte['ultimo_motivo_rechazo'] }}
                                        </p>
                                    </div>
                                @endif

                                {{-- Contenidos Section --}}
                                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" @click="openSection = openSection === 'tematica' ? '' : 'tematica'">
                                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                            <span class="material-icons text-blue-500">menu_book</span>
                                            Contenidos y Temas
                                        </h4>
                                        <div class="flex items-center gap-4">
                                            @if (!$locked)
                                                <button type="button" wire:click.stop="addItem({{ $index }}, 'contenidos')"
                                                    class="inline-flex items-center gap-1 text-[10px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-lg font-bold hover:bg-blue-50 dark:hover:bg-blue-900 transition-colors">
                                                    <span class="material-icons text-[12px]">add</span>
                                                    AÑADIR CONTENIDO
                                                </button>
                                            @endif
                                            <span class="material-icons transition-transform duration-200" :class="openSection === 'tematica' ? 'rotate-180' : ''">expand_more</span>
                                        </div>
                                    </div>
                                    <div x-show="openSection === 'tematica'" x-collapse class="p-4 space-y-4">
                                        @foreach ($corte['contenidos'] as $contenidoIndex => $contenido)
                                            <div class="p-4 rounded-xl bg-gray-100 dark:bg-gray-950 border border-gray-200 dark:border-gray-700">
                                                <div class="space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Tema a desarrollar</label>
                                                        @if (count($corte['contenidos']) > 1 && !$locked)
                                                            <button type="button" wire:click="removeItem({{ $index }}, 'contenidos', {{ $contenidoIndex }})"
                                                                class="text-gray-400 hover:text-red-500 transition-colors text-[10px] font-bold uppercase flex items-center gap-1">
                                                                <span class="material-icons text-xs">delete</span> ELIMINAR
                                                            </button>
                                                        @endif
                                                    </div>
                                                    <x-select :options="$contenidosDisponibles" valueField="id_contenido" textField="titulo_contenido"
                                                        wire:model.live.debounce.250ms="form.cortes.{{ $index }}.contenidos.{{ $contenidoIndex }}.contenido_id"
                                                        placeholder="Seleccione un contenido" class="text-sm w-full" :disabled="$locked" required />
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Recursos y Estrategias Section --}}
                                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" @click="openSection = openSection === 'recursos_estrategias' ? '' : 'recursos_estrategias'">
                                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                            <span class="material-icons text-green-500">psychology</span>
                                            Recursos y Estrategias
                                        </h4>
                                        <span class="material-icons transition-transform duration-200" :class="openSection === 'recursos_estrategias' ? 'rotate-180' : ''">expand_more</span>
                                    </div>
                                    <div x-show="openSection === 'recursos_estrategias'" x-collapse class="p-4 space-y-6">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                            {{-- Recursos --}}
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-xs font-bold text-gray-500 dark:text-gray-100 uppercase tracking-tight">Recursos</h4>
                                                    @if (!$locked)
                                                        <button type="button" wire:click="addItem({{ $index }}, 'recursos')"
                                                            class="inline-flex items-center gap-1 text-[10px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-lg font-bold hover:bg-green-50 dark:hover:bg-green-900 transition-colors">
                                                            <span class="material-icons text-xs">add</span> AÑADIR
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="space-y-2">
                                                    @foreach ($corte['recursos'] as $recursoIndex => $recurso)
                                                        <div class="flex items-center gap-2">
                                                            <x-select :options="$recursosDisponibles" valueField="id_recurso" textField="nombre_recurso"
                                                                wire:model.live.debounce.250ms="form.cortes.{{ $index }}.recursos.{{ $recursoIndex }}.recurso_id"
                                                                placeholder="Seleccione..." class="text-sm w-full" :disabled="$locked" />
                                                            @if (count($corte['recursos']) > 1 && !$locked)
                                                                <button type="button" wire:click="removeItem({{ $index }}, 'recursos', {{ $recursoIndex }})"
                                                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                                                    <span class="material-icons text-sm">delete</span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- Estrategias --}}
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-xs font-bold text-gray-500 dark:text-gray-100 uppercase tracking-tight">Estrategias</h4>
                                                    @if (!$locked)
                                                        <button type="button" wire:click="addItem({{ $index }}, 'estrategias')"
                                                            class="inline-flex items-center gap-1 text-[10px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-lg font-bold hover:bg-green-50 dark:hover:bg-green-900 transition-colors">
                                                            <span class="material-icons text-xs">add</span> AÑADIR
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="space-y-2">
                                                    @foreach ($corte['estrategias'] as $estrategiaIndex => $estrategia)
                                                        <div class="flex items-center gap-2">
                                                            <x-select :options="$estrategiasDisponibles" valueField="id_tecnica_actividad" textField="nombre_tecnica_actividad"
                                                                wire:model.live.debounce.250ms="form.cortes.{{ $index }}.estrategias.{{ $estrategiaIndex }}.tema_id"
                                                                placeholder="Seleccione..." class="text-sm w-full" :disabled="$locked" />
                                                            @if (count($corte['estrategias']) > 1 && !$locked)
                                                                <button type="button" wire:click="removeItem({{ $index }}, 'estrategias', {{ $estrategiaIndex }})"
                                                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                                                    <span class="material-icons text-sm">delete</span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Indicadores de Logros Section --}}
                                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" @click="openSection = openSection === 'indicadores' ? '' : 'indicadores'">
                                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                            <span class="material-icons text-orange-500">assignment_turned_in</span>
                                            Indicadores de Logros
                                        </h4>
                                        <span class="material-icons transition-transform duration-200" :class="openSection === 'indicadores' ? 'rotate-180' : ''">expand_more</span>
                                    </div>
                                    <div x-show="openSection === 'indicadores'" x-collapse class="p-4 space-y-4">
                                        <div class="p-4 rounded-xl bg-gray-100 dark:bg-gray-950 border border-gray-200 dark:border-gray-700">
                                            <textarea wire:model.live.debounce.500ms="form.cortes.{{ $index }}.indicadores_logro"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm"
                                                rows="3" placeholder="Describa los indicadores de logros para este corte..." :disabled="$locked"></textarea>
                                            @error("form.cortes.$index.indicadores_logro")
                                                <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Plan de Evaluación Section --}}
                                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" @click="openSection = openSection === 'evaluacion' ? '' : 'evaluacion'">
                                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                            <span class="material-icons text-red-500">event_available</span>
                                            Plan de Evaluación
                                        </h4>
                                        <div class="flex items-center gap-4">
                                            @if (!$locked)
                                                <button type="button" wire:click.stop="addItem({{ $index }}, 'evaluaciones')"
                                                    class="inline-flex items-center gap-1 text-[10px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-lg font-bold hover:bg-red-50 dark:hover:bg-red-900 transition-colors">
                                                    <span class="material-icons text-[12px]">add</span>
                                                    AÑADIR EVALUACIÓN
                                                </button>
                                            @endif
                                            <span class="material-icons transition-transform duration-200" :class="openSection === 'evaluacion' ? 'rotate-180' : ''">expand_more</span>
                                        </div>
                                    </div>
                                    <div x-show="openSection === 'evaluacion'" x-collapse class="p-4 space-y-4">
                                        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                            <table class="w-full text-xs text-left table-fixed min-w-[850px]">
                                                <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 uppercase font-bold">
                                                    <tr>
                                                        <th class="px-2 py-3" width="15%">Fecha</th>
                                                        <th class="px-2 py-3" width="22%">Evaluación</th>
                                                        <th class="px-2 py-3" width="22%">Técnica</th>
                                                        <th class="px-2 py-3" width="26%">Participación</th>
                                                        <th class="px-2 py-3" width="10%">Pond. (%)</th>
                                                        <th class="px-2 py-3" width="5%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                                    @foreach ($corte['evaluaciones'] as $evaluacionIndex => $evaluacion)
                                                        @php
                                                            $formasParticipacion = collect([
                                                                (object) ['id' => '1', 'nombre' => 'Individual'],
                                                                (object) ['id' => '2', 'nombre' => 'Grupal'],
                                                            ]);
                                                        @endphp
                                                        <tr>
                                                            <td class="px-2 py-3">
                                                                <x-text-input type="date" wire:model.live.debounce.250ms="form.cortes.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.fecha_evaluacion" class="w-full text-xs" :disabled="$locked" required />
                                                            </td>
                                                            <td class="px-2 py-3">
                                                                <x-select :options="$evaluacionesDisponibles" valueField="id_tipo_evaluacion" textField="nombre_tipo_evaluacion" wire:model.live.debounce.250ms="form.cortes.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.evaluacion_id" placeholder="Seleccione" class="w-full text-xs" :disabled="$locked" required />
                                                            </td>
                                                            <td class="px-2 py-3">
                                                                <x-select :options="$tecnicaDisponibles" valueField="id_tecnica_evaluacion" textField="nombre_tecnica_evaluacion" wire:model.live.debounce.250ms="form.cortes.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.tecnica_id" placeholder="Seleccione" class="w-full text-xs" :disabled="$locked" required />
                                                            </td>
                                                            <td class="px-2 py-3">
                                                                <x-select :options="$formasParticipacion" valueField="id" textField="nombre" wire:model.live.debounce.250ms="form.cortes.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.forma_participacion" placeholder="Seleccione" class="w-full text-xs" :disabled="$locked" required />
                                                                @if(isset($evaluacion['forma_participacion']) && $evaluacion['forma_participacion'] == '2')
                                                                    <div class="mt-1 flex items-center gap-1">
                                                                        <span class="text-[10px] text-gray-500 uppercase font-bold">N°:</span>
                                                                        <select wire:model.live="form.cortes.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.integrantes" class="text-[10px] rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-0 px-1 w-12" :disabled="$locked">
                                                                            <option value="">-</option>
                                                                            @for($i = 2; $i <= 10; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        </select>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td class="px-2 py-3 text-center">
                                                                <input type="number" step="1" min="5" max="25" onkeypress="return event.charCode >= 48 && event.charCode <= 57" wire:model.live="form.cortes.{{ $index }}.evaluaciones.{{ $evaluacionIndex }}.ponderacion" class="w-16 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 p-1 text-gray-700 dark:text-gray-300 text-xs font-bold text-center" :disabled="$locked">
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                @if (count($corte['evaluaciones']) > 1 && !$locked)
                                                                    <button type="button" wire:click="removeItem({{ $index }}, 'evaluaciones', {{ $evaluacionIndex }})" class="text-gray-400 hover:text-red-500">
                                                                        <span class="material-icons text-sm">delete</span>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @error("form.cortes.$index.evaluaciones.$evaluacionIndex.fecha_evaluacion")
                                                            <tr><td colspan="6" class="px-4 py-0 text-[10px] text-red-500">{{ $message }}</td></tr>
                                                        @enderror
                                                        @error("form.cortes.$index.evaluaciones.$evaluacionIndex.evaluacion_id")
                                                            <tr><td colspan="6" class="px-4 py-0 text-[10px] text-red-500">{{ $message }}</td></tr>
                                                        @enderror
                                                        @error("form.cortes.$index.evaluaciones.$evaluacionIndex.tecnica_id")
                                                            <tr><td colspan="6" class="px-4 py-0 text-[10px] text-red-500">{{ $message }}</td></tr>
                                                        @enderror
                                                        @error("form.cortes.$index.evaluaciones.$evaluacionIndex.ponderacion")
                                                            <tr><td colspan="6" class="px-4 py-0 text-[10px] text-red-500">{{ $message }}</td></tr>
                                                        @enderror
                                                        @error("form.cortes.$index.evaluaciones.$evaluacionIndex.forma_participacion")
                                                            <tr><td colspan="6" class="px-4 py-0 text-[10px] text-red-500">{{ $message }}</td></tr>
                                                        @enderror
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                    {{-- Navegación entre acordeones --}}
                                    <div class="flex justify-between items-center pt-8 mt-8 border-t border-gray-100 dark:border-gray-800">
                                        <div>
                                            @if ($index > 0)
                                                <button type="button" @click="openCorte = {{ $index - 1 }}; window.scrollTo({top: 0, behavior: 'smooth'})"
                                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                                    <span class="material-icons text-sm">arrow_back</span> Corte Anterior
                                                </button>
                                            @endif
                                        </div>
                                        <div>
                                            @if ($index < count($form->cortes) - 1)
                                                <button type="button" @click="openCorte = {{ $index + 1 }}; window.scrollTo({top: 0, behavior: 'smooth'})"
                                                    class="inline-flex items-center gap-2 px-8 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg hover:bg-blue-700 transition-all hover:-translate-y-0.5 active:translate-y-0">
                                                    Siguiente Corte <span class="material-icons text-sm">arrow_forward</span>
                                                </button>
                                            @else
                                                <div class="flex flex-col items-end">
                                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Final de la Edición</span>
                                                    <span class="text-xs text-gray-500 italic">Todos los cortes revisados</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div x-show="openCorte === {{ count($form->cortes) - 1 }}" x-data="{ biblioOpen: true }">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm mt-6">
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" @click="biblioOpen = !biblioOpen">
                                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                        <span class="material-icons text-purple-500">library_books</span>
                                        Bibliografía
                                    </h4>
                                    <div class="flex items-center gap-4">
                                        <button type="button" wire:click.stop="addItem(null, 'bibliografias')"
                                            class="inline-flex items-center gap-1 text-[10px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-lg font-bold hover:bg-purple-50 dark:hover:bg-purple-900 transition-colors">
                                            <span class="material-icons text-[12px]">add</span>
                                            AÑADIR BIBLIOGRAFÍA
                                        </button>
                                        <span class="material-icons transition-transform duration-200" :class="biblioOpen ? 'rotate-180' : ''">expand_more</span>
                                    </div>
                                </div>
                                <div x-show="biblioOpen" x-collapse class="p-4 space-y-4">
                                    @foreach ($form->bibliografias as $biblioIndex => $bibliografia)
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                                            <div class="flex-grow min-w-0">
                                                <x-select label="Seleccionar Bibliografía" required :options="$bibliografiasDisponibles"
                                                    valueField="id_bibliografia" textField="nombre_bibliografia"
                                                    wire:model.live.debounce.250ms="form.bibliografias.{{ $biblioIndex }}.bibliografia_id" />
                                                @error("form.bibliografias.$biblioIndex.bibliografia_id")
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            @if (count($form->bibliografias) > 1)
                                                <button type="button" wire:click="removeItem(null, 'bibliografias', {{ $biblioIndex }})"
                                                    class="text-gray-400 hover:text-red-500 transition-colors p-2">
                                                    <span class="material-icons text-sm">delete</span>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                            <!-- Botones de Acción -->
                            <div class="flex justify-end gap-4 pt-6">
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex font-semibold items-center px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl text-sm text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                                    Cancelar
                                </a>
                                <button type="submit"
                                    class="group inline-flex items-center gap-3 px-8 py-3 bg-green-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-green-700 focus:outline-none transition-all duration-300 shadow-xl hover:shadow-green-500/20 hover:-translate-y-1 active:scale-95">
                                    <i class="fas fa-save text-lg transition-transform group-hover:rotate-12"></i> 
                                    ACTUALIZAR PLANIFICACIÓN
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
</div>