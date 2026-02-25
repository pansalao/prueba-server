<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Registrar Evento') }}
        </h2>
    </x-slot>

    <x-table.alert-message type="success" :message="session('message')" />
    <x-table.alert-message type="error" :message="session('error')" />

    <div class="sogat-card">
        <form wire:submit.prevent="guardar" class="w-full space-y-6" novalidate>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="w-full">
                    <x-input-label for="descripcion" :value="__('Descripción del Evento')" />
                    <x-text-input id="descripcion" wire:model.live="form.descripcion_evento" class="w-full" type="text"
                        placeholder="Ej: Congreso Nacional" required />
                    <x-input-error :messages="$errors->first('form.descripcion_evento')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="semana" :value="__('Semana del Evento')" />
                    <x-text-input id="semana" wire:model.live="form.semana_evento" class="w-full" type="number"
                        placeholder="Ej: 1" step="1" min="1" max="52" required
                        onkeydown="if(['e', 'E', '+', '-', '.'].includes(event.key)) event.preventDefault();" />
                    <x-input-error :messages="$errors->first('form.semana_evento')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="dia_inicio" :value="__('Fecha de Inicio')" />
                    <x-text-input id="dia_inicio" wire:model.live="form.dia_inicio_evento" class="w-full" type="date"
                        required />
                    <x-input-error :messages="$errors->first('form.dia_inicio_evento')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="dia_fin" :value="__('Fecha de Fin')" />
                    <x-text-input id="dia_fin" wire:model.live="form.dia_fin_evento" class="w-full" type="date"
                        required />
                    <x-input-error :messages="$errors->first('form.dia_fin_evento')" class="mt-2" />
                </div>

                <div class="w-full">
                    @php
                        $tiposEvento = collect([
                            (object) ['id' => '1', 'nombre' => 'Feriado'],
                            (object) ['id' => '2', 'nombre' => 'Actividad Académica'],
                            (object) ['id' => '3', 'nombre' => 'Otro'],
                        ]);
                    @endphp
                    <x-select id="tipo" wire:model.live="form.tipo_evento" label="Tipo de Evento"
                        :options="$tiposEvento" valueField="id" textField="nombre" placeholder="Seleccione..."
                        required />
                    <x-input-error :messages="$errors->first('form.tipo_evento')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <x-primary-button type="submit" wire:loading.attr="disabled">
                    {{ __('Guardar Evento') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>