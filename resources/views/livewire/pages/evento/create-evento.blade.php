<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-500 dark:text-gray-500 leading-tight uppercase text-center">
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
                        placeholder="Ej: Congreso Nacional" />
                    <x-input-error :messages="$errors->first('form.descripcion_evento')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="fecha" :value="__('Fecha del Evento')" />
                    <x-text-input id="fecha" wire:model.live="form.fecha_evento" class="w-full" type="date" />
                    <x-input-error :messages="$errors->first('form.fecha_evento')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="tipo" :value="__('Tipo de Evento')" />
                    <select id="tipo" wire:model.live="form.tipo_evento"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">Seleccione...</option>
                        <option value="1">Tipo 1</option>
                        <option value="2">Tipo 2</option>
                        <option value="3">Tipo 3</option>
                    </select>
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
