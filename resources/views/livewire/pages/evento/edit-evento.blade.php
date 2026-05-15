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
                <div class="w-full">
                    <x-datalist 
                        wire:key="datalist-eventos-{{ md5($eventosExistentes->pluck('nombre_evento')->join(',')) }}"
                        label="Nombre del Evento" 
                        :options="$eventosExistentes" 
                        textField="nombre_evento"
                        wire:model.live="form.descripcion_evento"
                        placeholder="Ej: Congreso Nacional"
                        required 
                    />
                </div>

                <div class="w-full">
                    <x-input-label for="tipo" :value="__('Tipo de Evento')" />
                    <select id="tipo" wire:model.live="form.tipo_evento"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="1">Feriado Nacional</option>
                        <option value="2">Feriado Local</option>
                        <option value="3">Administrativo</option>
                        <option value="4">Académico</option>
                        <option value="5">Vacaciones Colectivas</option>
                    </select>
                    <x-input-error :messages="$errors->first('form.tipo_evento')" class="mt-2" />
                </div>



                <div class="w-full">
                    <x-input-label for="id_color" :value="__('Color del Evento *')" />
                    <div x-data="{ 
                            open: false, 
                            selectedId: @entangle('form.id_color'),
                            colores: {{ $colores->toJson() }},
                            get selectedHex() {
                                let color = this.colores.find(c => c.id_color == this.selectedId);
                                return color ? color.codigo_color : null;
                            },
                            get selectedName() {
                                let color = this.colores.find(c => c.id_color == this.selectedId);
                                return color ? color.nombre_color : 'Seleccione un color';
                            }
                        }" 
                        class="relative w-full">
                        
                        <button @click="if(!open) $wire.cargarColores(); open = !open" type="button" class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm pl-3 pr-10 py-2 text-left focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm min-h-[42px]">
                            <span class="flex items-center gap-2">
                                <template x-if="selectedHex">
                                    <span class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 shadow-sm" :style="`background-color: ${selectedHex}`"></span>
                                </template>
                                <span class="block truncate" :class="selectedId ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400'" x-text="selectedName"></span>
                            </span>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg max-h-56 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm" style="display: none;">
                            <ul tabindex="-1" role="listbox" class="flex flex-col w-full">
                                @foreach($colores as $color)
                                    <li @click="selectedId = {{ $color->id_color }}; open = false" class="cursor-pointer select-none w-full px-4 py-2.5 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center transition-colors" :class="selectedId == {{ $color->id_color }} ? 'bg-gray-50 dark:bg-gray-700/50' : ''" role="option">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-full border border-gray-300 dark:border-gray-600 shadow-sm" style="background-color: {{ $color->codigo_color }}"></span>
                                            <span class="text-gray-900 dark:text-gray-200 font-medium">{{ $color->nombre_color }}</span>

                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->first('form.id_color')" class="mt-2" />
                </div>
                @if($form->tipo_evento != '1' && $form->tipo_evento != '2')
                <x-toggle-switch 
                    id="is_laborable_edit" 
                    :label="__('¿Es Laborable?')" 
                    model="form.is_laborable" 
                />

                <x-toggle-switch 
                    id="is_repetible_edit" 
                    :label="__('¿Es Repetible?')" 
                    model="form.is_repetible" 
                />
                @endif
            </div>

            <!-- Botones -->
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
