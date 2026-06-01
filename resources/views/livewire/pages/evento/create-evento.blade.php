<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Registrar Evento') }}
        </h2>
    </x-slot>

    <x-table.alert-message />

    <div class="sogat-card">
        <form wire:submit.prevent="guardar" class="w-full space-y-6" novalidate>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                    $deshabilitarIndependienteLaborable = $form->is_especial
                        || in_array($form->tipo_evento, ['1', '2', '6'], true);
                    $deshabilitarSuperponible = (in_array($form->tipo_evento, ['1', '2', '6'], true) && !($form->is_especial && in_array($form->id_especial_evento, ['4', '5'])))
                        || ($form->is_especial && in_array($form->id_especial_evento, ['1', '7', '8', '9', '10', '11', '12', '13', '14']));
                    $deshabilitarIsCantidadDias = $form->is_especial;
                    $deshabilitarSemanaEvento = in_array($form->tipo_evento, ['1', '2', '6'], true) || $form->is_especial;
                    $deshabilitarInputDias = $form->is_especial && in_array($form->id_especial_evento, ['2', '3', '4', '5', '7', '8', '9', '10', '11', '13', '14']);
                    $deshabilitarDiaEvento = !in_array($form->tipo_evento, ['1', '2', '6'], true);
                @endphp

                <div class="w-full">
                    <x-input-label for="tipo" :value="__('Tipo de Evento')" />
                    <div class="flex items-center gap-1 w-full">
                        <select id="tipo" wire:model.live="form.tipo_evento" @disabled($form->is_especial) @class([
                            'flex-1 min-w-0 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
                            'opacity-60 cursor-not-allowed' => $form->is_especial,
                        ])>
                            <option value="1">Feriado Nacional</option>
                            <option value="2">Feriado Local</option>
                            <option value="6">Feriado Mundial</option>
                            <option value="3">Administrativo</option>
                            <option value="4">Académico</option>
                            <option value="5">Administrativo/Académico</option>
                        </select>
                        <span class="text-red-500 font-bold">*</span>
                    </div>
                    <x-input-error :messages="$errors->first('form.tipo_evento')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="descripcion_evento" :value="__('Nombre del Evento')" />
                    <x-text-input id="descripcion_evento" type="text"
                        wire:model.live="form.descripcion_evento" placeholder="Ej: Congreso Nacional" required
                        :disabled="$form->is_especial" />
                    <x-input-error :messages="$errors->first('form.descripcion_evento')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="codigo_color" :value="__('Color del Evento')" />
                    <div class="flex items-center gap-3 mt-1">
                        <input id="codigo_color" type="color" wire:model.live="form.codigo_color_evento"
                            class="w-12 h-10 p-1 border border-gray-300 dark:border-gray-700 rounded-md cursor-pointer shadow-sm bg-white dark:bg-gray-900"
                            title="Seleccione un color" />
                        <x-text-input type="text" wire:model.live="form.codigo_color_evento" class="flex-1 font-mono"
                            placeholder="#000000" maxlength="7" />
                        <span class="text-red-500 font-bold">*</span>
                    </div>
                    <x-input-error :messages="$errors->first('form.codigo_color_evento')" class="mt-2" />
                </div>

                <x-toggle-switch id="is_laborable" :label="__('¿Es Laborable?')" model="form.is_laborable"
                    :disabled="$deshabilitarIndependienteLaborable" required />

                <x-toggle-switch id="is_repetible" :label="__('¿Se puede repetir?')" model="form.is_repetible"
                    :disabled="true" required />

                <x-toggle-switch id="is_independiente" :label="__('¿Puede registrarse fuera de un semestre?')"
                    model="form.is_independiente" :disabled="$deshabilitarIndependienteLaborable" required />

                <x-toggle-switch id="is_superponible" :label="__('¿Se puede asignar en fechas no laborables?')"
                    model="form.is_superponible" :disabled="$deshabilitarSuperponible" required />

                <x-toggle-switch id="is_dia_evento" :label="__('¿Ocurre en un día específico?')"
                    model="form.is_dia_evento" :disabled="$deshabilitarDiaEvento" required />

                @if($form->is_dia_evento)
                <div class="w-full">
                    <x-input-label for="dia_evento" :value="__('Día Específico del Evento')" />
                    <x-text-input id="dia_evento" type="date" class="w-full"
                        wire:model.live="form.dia_evento" required />
                    <x-input-error :messages="$errors->first('form.dia_evento')" class="mt-2" />
                </div>
                @endif

                <x-toggle-switch id="is_cantidad_dias_evento" :label="__('¿Tiene una duración de días específica?')"
                    model="form.is_cantidad_dias_evento" :disabled="$deshabilitarIsCantidadDias" required />

                @if($form->is_cantidad_dias_evento)
                <div class="w-full">
                    <x-input-label for="cantidad_dias_evento" :value="__('Cantidad de días que debe durar el evento')" />
                    <x-text-input id="cantidad_dias_evento" type="number" min="1" max="365" class="w-full"
                        wire:model.live="form.cantidad_dias_evento" placeholder="Ej: 5" :disabled="$deshabilitarInputDias" required />
                    <x-input-error :messages="$errors->first('form.cantidad_dias_evento')" class="mt-2" />
                </div>
                @endif

                <x-toggle-switch id="is_semana_evento" :label="__('¿Ocurre en semanas específicas?')" model="form.is_semana_evento" :disabled="$deshabilitarSemanaEvento" required />

                <x-toggle-switch id="is_especial" :label="__('¿Es un Evento Especial?')" model="form.is_especial" required />
                @if($form->is_especial)
                    <div class="w-full">
                        <x-input-label for="especial" :value="__('Seleccione el tipo de Evento Especial')" />
                        <div class="flex items-center gap-1 w-full">
                            @php
                                $usados = $this->eventosEspecialesUsados;
                            @endphp
                            <select id="especial" wire:model.live="form.id_especial_evento" @disabled(!$form->is_especial) @class([
                                'flex-1 min-w-0 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
                                'opacity-60 cursor-not-allowed' => !$form->is_especial,
                            ])>
                                <option value="">-- Seleccione Especial --</option>
                                @foreach(\App\Models\EspecialEvento::orderBy('especial_evento_name')->get() as $esp)
                                    @if(!in_array($esp->id_especial_evento, $usados) || $form->id_especial_evento == $esp->id_especial_evento)
                                        <option value="{{ $esp->id_especial_evento }}">{{ $esp->especial_evento_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <span class="text-red-500 font-bold">*</span>
                        </div>
                        <x-input-error :messages="$errors->first('form.id_especial_evento')" class="mt-2" />
                    </div>
                @endif

                @if($form->is_semana_evento)
                    @php
                        $semanasLapso1 = array_filter($form->semanas ?? [], fn($s) => (is_array($s) ? ($s['lapso'] ?? 1) : 1) == 1);
                        $semanasLapso2 = array_filter($form->semanas ?? [], fn($s) => (is_array($s) ? ($s['lapso'] ?? 1) : 1) == 2);
                    @endphp

                    {{-- Lapso 1 --}}
                    <div class="w-full mt-4 col-span-1 md:col-span-2 lg:col-span-3 border border-gray-300 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <x-input-label :value="__('Lapso 1 - Semanas en las que debe suceder el evento')" />
                            @if($form->is_repetible)
                                <button type="button" wire:click="agregarSemana(1)"
                                    @disabled(count($semanasLapso1) >= 4)
                                    class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar Semana (máx. 4)
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                            @foreach($semanasLapso1 as $index => $semana)
                                <div wire:key="semana-lapso1-{{ $index }}">
                                    <div class="flex items-center gap-2">
                                        <x-text-input type="number" min="1" max="18" class="w-full"
                                            wire:model.live="form.semanas.{{ $index }}.semana" placeholder="Ej: 1" required />
                                        @if($form->is_repetible && count($semanasLapso1) > 1)
                                            <button type="button" wire:click="removerSemana({{ $index }})"
                                                class="text-red-500 hover:text-red-700 p-2 bg-red-50 dark:bg-red-900/20 rounded-md transition-colors"
                                                title="Eliminar semana">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @error('form.semanas.' . $index . '.semana') <span
                                        class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Lapso 2 --}}
                    <div class="w-full col-span-1 md:col-span-2 lg:col-span-3 border border-gray-300 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <x-input-label :value="__('Lapso 2 - Semanas en las que debe suceder el evento')" />
                            @if($form->is_repetible)
                                <button type="button" wire:click="agregarSemana(2)"
                                    @disabled(count($semanasLapso2) >= 4)
                                    class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar Semana (máx. 4)
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                            @foreach($semanasLapso2 as $index => $semana)
                                <div wire:key="semana-lapso2-{{ $index }}">
                                    <div class="flex items-center gap-2">
                                        <x-text-input type="number" min="1" max="18" class="w-full"
                                            wire:model.live="form.semanas.{{ $index }}.semana" placeholder="Ej: 1" required />
                                        @if($form->is_repetible && count($semanasLapso2) > 1)
                                            <button type="button" wire:click="removerSemana({{ $index }})"
                                                class="text-red-500 hover:text-red-700 p-2 bg-red-50 dark:bg-red-900/20 rounded-md transition-colors"
                                                title="Eliminar semana">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @error('form.semanas.' . $index . '.semana') <span
                                        class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->first('form.semanas')" class="mt-2" />
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-4">
                <x-primary-button type="submit" wire:loading.attr="disabled">
                    {{ __('Guardar Evento') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</div>