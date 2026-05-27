@php
$wireKey = $wireKey ?? 'datalist-calendario';
@endphp

{{-- Modal Registro de Evento en el Calendario --}}
<div x-show="showEventModal" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center px-4">
    <div @click.away="closeModal()" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-700">
        <h3
            class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 uppercase tracking-widest text-center">
            {{ __('Registrar Evento') }}</h3>
        <div
            class="bg-gray-100 dark:bg-gray-700/50 border-l-4 border-gray-400 p-4 rounded-r-lg mb-6 flex justify-between items-center">
            <div><label
                    class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Inicio</label>
                <div class="text-gray-900 dark:text-gray-200 font-extrabold text-sm"
                    x-text="selectedEventStart"></div>
            </div>
            <div class="text-gray-400 dark:text-gray-600 px-4"><span
                    class="material-icons text-sm">arrow_forward</span></div>
            <div class="text-right"><label
                    class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Fin</label>
                <div class="text-gray-900 dark:text-gray-200 font-extrabold text-sm"
                    x-text="selectedEventEnd"></div>
            </div>
        </div>
        <div class="space-y-5">
            <div>
                <x-datalist wire:key="{{ $wireKey . '-' . count($bibliotecaFiltrada) }}"
                    label="Nombre del Evento" :options="$bibliotecaFiltrada"
                    textField="nombre_evento" wire:model.live="form.nombreEventoTemporal"
                    placeholder="ESCRIBA O SELECCIONE UN EVENTO" />
            </div>
        </div>
        <div
            class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
            <x-secondary-button type="button"
                @click="closeModal()">{{ __('Cancelar') }}</x-secondary-button>
            <x-primary-button type="button"
                @click="guardarEvento()">{{ __('Guardar') }}</x-primary-button>
        </div>
    </div>
</div>

{{-- Modal Registro Rápido (Nuevo Evento) --}}
<div x-show="showQuickModal" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center px-4">
    <div @click.away="closeModal()" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-2xl w-full max-w-3xl border border-gray-200 dark:border-gray-700">
        <h3
            class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2 uppercase tracking-widest text-center">
            {{ __('Nuevo Evento Detectado') }}
        </h3>
        <p class="text-center text-gray-500 dark:text-gray-400 text-sm mb-6">
            {{ __('El evento') }} "<span class="font-bold text-gray-700 dark:text-gray-200"
                x-text="eventoNombre"></span>" {{ __('no existe en la biblioteca. Por favor, configúrelo:') }}
        </p>
        <div class="space-y-6">
            {{-- Lógica PHP de Control de Deshabilitación --}}
            @php
            $deshabilitarIndependienteLaborable = in_array($form->nuevoTipo, ['1', '2', '6'], true);
            $deshabilitarCantidadRango = !$form->nuevoIsRangoDias;
            $deshabilitarSuperponible = in_array($form->nuevoTipo, ['1', '2', '6'], true);
            @endphp

            {{-- Cuadrícula Principal de 3 Columnas (3x3) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-2 items-start">

                {{-- FILA 1 --}}
                {{-- Columna 1: Tipo de Evento --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Tipo de Evento') }}</label>
                    <select x-model="nuevoTipo" wire:model.live="form.nuevoTipo"
                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-gray-400">
                        <option value="1">{{ __('FERIADOS NACIONALES') }}</option>
                        <option value="2">{{ __('FERIADOS LOCALES') }}</option>
                        <option value="6">{{ __('FERIADO MUNDIAL') }}</option>
                        <option value="3">{{ __('ADMINISTRATIVO') }}</option>
                        <option value="4">{{ __('ACADÉMICO') }}</option>
                        <option value="5">{{ __('ADMINISTRATIVO/ACADÉMICO') }}</option>
                    </select>
                </div>

                {{-- Columna 2: Nombre del Evento --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Nombre del Evento') }}</label>
                    <div class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-3 text-sm text-gray-700 dark:text-gray-300 font-semibold shadow-sm">
                        <span x-text="eventoNombre ? eventoNombre.toUpperCase() : '{{ __('EJ: CONGRESO NACIONAL') }}'"></span>
                    </div>
                </div>

                {{-- Columna 3: Selección de Color con input nativo --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Color del Evento *') }}</label>
                    <div class="flex items-center gap-3">
                        <input type="color" x-model="nuevoColorHex" wire:model.live="form.nuevoColorHex"
                            class="w-14 h-14 rounded-xl border border-gray-300 dark:border-gray-600 cursor-pointer bg-transparent p-1"
                            title="{{ __('Seleccione un color') }}">
                        <input type="text" x-model="nuevoColorHex" wire:model.live="form.nuevoColorHex"
                            class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-3 text-sm text-gray-900 dark:text-white font-mono uppercase focus:ring-2 focus:ring-gray-400"
                            placeholder="#FFFFFF" maxlength="7" pattern="#[0-9A-Fa-f]{6}">
                    </div>
                    <x-input-error :messages="$errors->first('form.nuevoColorHex')" class="mt-2" />
                </div>

                {{-- FILA 2 --}}
                {{-- Columna 1: ¿Es Laborable? --}}
                <div>
                    <x-toggle-switch id="{{ $wireKey }}_laborable_switch" :label="__('¿Es Laborable?')"
                        model="form.nuevoLaborable" :disabled="$deshabilitarIndependienteLaborable" />
                </div>

                {{-- Columna 2: ¿Se puede repetir? --}}
                <div>
                    <x-toggle-switch id="{{ $wireKey }}_repetible_switch" :label="__('¿Se puede repetir?')"
                        model="form.nuevoRepetible" :disabled="true" />
                </div>

                {{-- Columna 3: ¿Puede registrarse fuera de un semestre? --}}
                <div>
                    <x-toggle-switch id="{{ $wireKey }}_is_independiente_switch" :label="__('¿Puede registrarse fuera de un semestre?')"
                        model="form.nuevoIsIndependiente" :disabled="$deshabilitarIndependienteLaborable" />
                    <x-input-error :messages="$errors->get('form.nuevoIsIndependiente')" class="mt-2" />
                </div>

                {{-- FILA 3 --}}
                {{-- Columna 1: ¿Puede asignarse en la misma fecha que días de vacaciones? --}}
                <div>
                    <x-toggle-switch id="{{ $wireKey }}_is_superponible_switch" :label="__('¿Puede asignarse en la misma fecha que días de vacaciones?')"
                        model="form.nuevoIsSuperponible" :disabled="$deshabilitarSuperponible" />
                    <x-input-error :messages="$errors->get('form.nuevoIsSuperponible')" class="mt-2" />
                </div>

                {{-- Columna 2: ¿Tiene cantidad específica de días de duración? (último switch) --}}
                <div>
                    <x-toggle-switch id="{{ $wireKey }}_is_rango_dias_switch" :label="__('¿Tiene una duración de días específica?')"
                        model="form.nuevoIsRangoDias" />
                </div>

                {{-- Columna 3: Cantidad de Días (oculto dinámicamente con x-show) --}}
                <div class="w-full" x-show="nuevoIsRangoDias" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Cantidad de días que debe durar el evento') }}</label>
                    <x-text-input id="{{ $wireKey }}_rango_dias_input" type="number"
                        class="w-full block" wire:model.live="form.nuevoRangoDias"
                        placeholder="{{ __('EJ: 5') }}" min="1" max="365" :disabled="$deshabilitarCantidadRango" />
                    <x-input-error :messages="$errors->get('form.nuevoRangoDias')" class="mt-2" />
                </div>

            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t border-gray-100 dark:border-gray-700">
            <x-secondary-button type="button" class="!px-5 !py-2.5 !text-sm" @click="closeModal()">
                {{ __('Cancelar') }}
            </x-secondary-button>
            <x-primary-button @click="confirmarNuevoEvento()" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('Registrar y Asignar') }}</span>
                <span wire:loading>{{ __('Procesando...') }}</span>
            </x-primary-button>
        </div>
    </div>
</div>
