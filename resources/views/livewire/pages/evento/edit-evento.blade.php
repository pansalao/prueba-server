<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Evento') }}
        </h2>
    </x-slot>

    <x-table.alert-message />

    <div class="sogat-card">
        <form wire:submit.prevent="guardar" class="w-full space-y-6" novalidate>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                    $deshabilitarIndependienteLaborable = $form->is_especial
                        || in_array($form->tipo_evento, ['1', '2', '6'], true);
                    $deshabilitarSuperponible = in_array($form->tipo_evento, ['1', '2', '6'], true);
                    $deshabilitarRangoDias = $form->is_especial;
                    $deshabilitarCantidadRango = $form->is_especial || !$form->is_rango_dias;
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
                    <x-datalist
                        wire:key="datalist-eventos-{{ md5($eventosExistentes->pluck('nombre_evento')->join(',')) }}"
                        label="Nombre del Evento" :options="$eventosExistentes" textField="nombre_evento"
                        wire:model.live="form.descripcion_evento" placeholder="Ej: Congreso Nacional" required
                        :disabled="$form->is_especial" />
                </div>

                <div class="w-full">
                    <x-input-label for="codigo_color_edit" :value="__('Color del Evento')" />
                    <div class="flex items-center gap-3 mt-1">
                        <input id="codigo_color_edit" type="color" wire:model.live="form.codigo_color_evento"
                            class="w-12 h-10 p-1 border border-gray-300 dark:border-gray-700 rounded-md cursor-pointer shadow-sm bg-white dark:bg-gray-900"
                            title="Seleccione un color" />
                        <x-text-input type="text" wire:model.live="form.codigo_color_evento" class="flex-1 font-mono"
                            placeholder="#000000" maxlength="7" />
                        <span class="text-red-500 font-bold">*</span>
                    </div>
                    <x-input-error :messages="$errors->first('form.codigo_color_evento')" class="mt-2" />
                </div>

                <x-toggle-switch id="is_independiente_edit" :label="__('¿Puede registrarse fuera de un semestre?')"
                    model="form.is_independiente" :disabled="$deshabilitarIndependienteLaborable" required />

                <x-toggle-switch id="is_superponible_edit" :label="__('¿Puede solaparse con otros eventos? (Superponible)')" model="form.is_superponible" :disabled="$deshabilitarSuperponible" required />

                <x-toggle-switch id="is_laborable_edit" :label="__('¿Es Laborable?')" model="form.is_laborable"
                    :disabled="$deshabilitarIndependienteLaborable" required />

                <x-toggle-switch id="is_repetible_edit" :label="__('¿Se puede repetir?')" model="form.is_repetible"
                    :disabled="true" required />

                <x-toggle-switch id="is_rango_dias_edit" :label="__('¿Tiene cantidad especifica días de duración?')"
                    model="form.is_rango_dias" :disabled="$deshabilitarRangoDias" required />

                <div class="w-full">
                    <x-input-label for="rango_dias_edit" :value="__('Cantidad de días que debe durar el evento')" />
                    <x-text-input id="rango_dias_edit" type="number" min="1" max="90" class="w-full"
                        wire:model.live="form.rango_dias" placeholder="Ej: 5" :disabled="$deshabilitarCantidadRango" required />
                    <x-input-error :messages="$errors->first('form.rango_dias')" class="mt-2" />
                </div>

                <x-toggle-switch id="is_especial_edit" :label="__('¿Es un Evento Especial?')"
                    model="form.is_especial" required />

                <x-toggle-switch id="is_semana_evento_edit" :label="__('¿Ocurre en semanas específicas?')" model="form.is_semana_evento" required />

                @if($form->is_especial)
                    <div class="w-full">
                        <x-input-label for="especial" :value="__('Seleccione el tipo de Evento Especial')" />
                        <div class="flex items-center gap-1 w-full">
                            <select id="especial" wire:model.live="form.especial_evento" @disabled(!$form->is_especial) @class([
                                'flex-1 min-w-0 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
                                'opacity-60 cursor-not-allowed' => !$form->is_especial,
                            ])>
                                <option value="">Seleccione...</option>
                                <option value="1">Vacaciones Colectivas</option>
                                <option value="2">Inicio del Lapso Académico</option>
                                <option value="3">Fin del Lapso Académico</option>
                                <option value="4">Semana Santa</option>
                                <option value="5">Carnaval</option>
                                <option value="7">Inicio del Lapso Introductorio</option>
                                <option value="8">Fin del Lapso Introductorio</option>
                                <option value="9">Inicio del Curso Intensivo</option>
                                <option value="10">Fin del Curso Intensivo</option>
                            </select>
                            <span class="text-red-500 font-bold">*</span>
                        </div>
                        <x-input-error :messages="$errors->first('form.especial_evento')" class="mt-2" />
                    </div>
                @endif

                @if($form->is_especial && $form->especial_evento == '1')
                    <div class="w-full">
                        <x-input-label for="cantidad_dias_evento" :value="__('Cantidad de Días de Vacaciones')" />
                        <x-text-input id="cantidad_dias_evento" type="number" min="1" max="365" class="w-full"
                            wire:model.live="form.cantidad_dias_evento" placeholder="Ej: 15" :disabled="!$form->is_especial || $form->especial_evento != '1'" required />
                        <x-input-error :messages="$errors->first('form.cantidad_dias_evento')" class="mt-2" />
                    </div>
                @endif

                @if($form->is_semana_evento)
                    <div class="w-full mt-4 col-span-1 md:col-span-2 lg:col-span-3">
                        <div class="flex justify-between items-center mb-2">
                            <x-input-label :value="__('Semanas en las que debe suceder el evento *')" />
                            @if($form->is_repetible && !$form->is_especial)
                                <button type="button" wire:click="agregarSemana"
                                    class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar Semana
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                            @foreach($form->semanas as $index => $semana)
                                <div wire:key="semana-item-{{ $index }}">
                                    <div class="flex items-center gap-2">
                                        <x-text-input type="number" min="1" max="99" class="w-full"
                                            wire:model.live="form.semanas.{{ $index }}" placeholder="Ej: 1"
                                            :disabled="$form->is_especial" required />
                                        @if($form->is_repetible && count($form->semanas) > 1 && !$form->is_especial)
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
                                    @error('form.semanas.' . $index) <span
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

                <x-danger-button type="button" wire:click="cancelar">
                    <link rel="stylesheet"
                        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                    <span class="material-symbols-outlined">
                        arrow_back
                    </span>
                    {{ __('Volver') }}
                </x-danger-button>

                <x-primary-button type="submit" wire:loading.attr="disabled">
                    {{ __('Actualizar') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</div>