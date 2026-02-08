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
                    <x-input-label for="fecha" :value="__('Fecha del Evento')" />
                    <x-text-input id="fecha" wire:model.live="form.fecha_evento" class="w-full" type="date" required />
                    <x-input-error :messages="$errors->first('form.fecha_evento')" class="mt-2" />
                </div>

                <div class="w-full">
                    @php
                        $tiposEvento = collect([
                            (object) ['id' => '1', 'nombre' => 'Tipo 1'],
                            (object) ['id' => '2', 'nombre' => 'Tipo 2'],
                            (object) ['id' => '3', 'nombre' => 'Tipo 3'],
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

