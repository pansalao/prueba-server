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
                    $deshabilitarSuperponible = (in_array($form->tipo_evento, ['1', '2', '6'], true))
                        || ($form->is_especial && in_array($form->id_especial_evento, ['1', '2', '3', '7', '8', '9', '10', '11', '13', '14']));
                    $deshabilitarIsCantidadDias = $form->is_especial;
                    $deshabilitarSemanaEvento = in_array($form->tipo_evento, ['1', '2', '6'], true) || $form->is_especial || $form->is_independiente || !$form->is_repetible;
                    $deshabilitarRepetible = in_array($form->tipo_evento, ['1', '2', '6'], true) || $form->is_especial;
                    $deshabilitarInputDias = $form->is_especial && in_array($form->id_especial_evento, ['1', '2', '3', '7', '8', '9', '10', '11', '13', '14']);
                    $deshabilitarCantidadRepetible = $form->is_especial && in_array($form->id_especial_evento, ['2', '3', '7', '8', '9', '10', '13', '14']);
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
                    :disabled="$deshabilitarRepetible" required />

                @if($form->is_repetible && !($form->is_especial && in_array($form->id_especial_evento, ['1', '11'])))
                <div class="w-full">
                    <label class="block uppercase font-bold text-[10px] text-gray-500 dark:text-gray-400 mb-1">
                        {{ __('Límite de repeticiones (2 a 8)') }}
                    </label>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Si se deja vacío, se repetirá un número indeterminado de veces.</p>
                    <x-text-input id="cantidad_repetible_evento" type="number" min="2" max="8" class="w-full"
                        wire:model.live="form.cantidad_repetible_evento" placeholder="Ej: 3"
                        oninput="if(this.value!==''){if(Number(this.value)>8) this.value=8; if(Number(this.value)<2) this.value=2;}"
                        :disabled="$deshabilitarCantidadRepetible" />
                    <x-input-error :messages="$errors->first('form.cantidad_repetible_evento')" class="mt-2" />
                </div>
                @endif

                <x-toggle-switch id="is_independiente" :label="__('¿Puede registrarse fuera de un semestre?')"
                    model="form.is_independiente" :disabled="$deshabilitarIndependienteLaborable" required />

                <x-toggle-switch id="is_superponible" :label="__('¿Se puede asignar en fechas no laborables?')"
                    model="form.is_superponible" :disabled="$deshabilitarSuperponible" required />

                <x-toggle-switch id="is_fin_semana_evento" :label="__('¿Puede asignarse en fines de semana?')"
                    model="form.is_fin_semana_evento" required />

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
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Auto-generado según límite de repeticiones') }}</span>
                        </div>

                        @if(count($semanasLapso1) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                            @foreach($semanasLapso1 as $index => $semana)
                                <div wire:key="semana-lapso1-{{ $index }}">
                                    <x-text-input type="number" min="1" max="18" class="w-full"
                                        wire:model.live="form.semanas.{{ $index }}.semana" placeholder="{{ __('Semana') }} {{ $loop->iteration }}" required />
                                    @error('form.semanas.' . $index . '.semana') <span
                                        class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">{{ __('Establezca un límite de repeticiones para generar las semanas.') }}</p>
                        @endif

                        @php
                            $reqsLapso1 = array_filter($justificacionesRequeridas ?? [], fn($req) => ($req['lapso'] ?? 1) == 1);
                        @endphp
                        @if(count($reqsLapso1) > 0)
                            <div class="mt-4 space-y-4">
                                @foreach($justificacionesRequeridas as $index => $req)
                                    @if(($req['lapso'] ?? 1) == 1)
                                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/50 p-4 rounded-md shadow-sm">
                                            <p class="text-sm text-orange-700 dark:text-orange-400 mb-2 font-bold">{{ $req['mensaje'] }}</p>
                                            <textarea wire:model.defer="justificacionesRequeridas.{{ $index }}.texto" rows="2" 
                                                class="w-full border-orange-300 dark:border-orange-700 dark:bg-gray-900 dark:text-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                                                placeholder="Escribe el motivo del cambio..."></textarea>
                                            @error('justificacionesRequeridas.'.$index.'.texto')
                                                <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Lapso 2 --}}
                    <div class="w-full col-span-1 md:col-span-2 lg:col-span-3 border border-gray-300 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <x-input-label :value="__('Lapso 2 - Semanas en las que debe suceder el evento')" />
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Auto-generado según límite de repeticiones') }}</span>
                        </div>

                        @if(count($semanasLapso2) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                            @foreach($semanasLapso2 as $index => $semana)
                                <div wire:key="semana-lapso2-{{ $index }}">
                                    <x-text-input type="number" min="1" max="18" class="w-full"
                                        wire:model.live="form.semanas.{{ count($semanasLapso1) + $loop->index }}.semana" placeholder="{{ __('Semana') }} {{ $loop->iteration }}" required />
                                    @error('form.semanas.' . (count($semanasLapso1) + $loop->index) . '.semana') <span
                                        class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">{{ __('Establezca un límite de repeticiones para generar las semanas.') }}</p>
                        @endif
                        <x-input-error :messages="$errors->first('form.semanas')" class="mt-2" />

                        @php
                            $reqsLapso2 = array_filter($justificacionesRequeridas ?? [], fn($req) => ($req['lapso'] ?? 1) == 2);
                        @endphp
                        @if(count($reqsLapso2) > 0)
                            <div class="mt-4 space-y-4">
                                @foreach($justificacionesRequeridas as $index => $req)
                                    @if(($req['lapso'] ?? 1) == 2)
                                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/50 p-4 rounded-md shadow-sm">
                                            <p class="text-sm text-orange-700 dark:text-orange-400 mb-2 font-bold">{{ $req['mensaje'] }}</p>
                                            <textarea wire:model.defer="justificacionesRequeridas.{{ $index }}.texto" rows="2" 
                                                class="w-full border-orange-300 dark:border-orange-700 dark:bg-gray-900 dark:text-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                                                placeholder="Escribe el motivo del cambio..."></textarea>
                                            @error('justificacionesRequeridas.'.$index.'.texto')
                                                <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-4 mt-6">
                <x-primary-button type="submit" wire:loading.attr="disabled">
                    {{ __('Guardar Evento') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>