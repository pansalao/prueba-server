<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-500 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Registrar Técnica de Evaluación') }}
        </h2>
    </x-slot>

    <x-table.alert-message type="success" :message="session('message')" />
    <x-table.alert-message type="error" :message="session('error')" />

    <div class="sogat-card">
        <form wire:submit.prevent="guardar" class="w-full space-y-6" novalidate>
            <!-- Grid -->
            <div class="flex flex-col gap-4 w-full md:flex-row">
                <!-- Nombre -->
                <div class="w-full">
                    <x-input-label for="nombre" :value="__('Nombre de la Técnica')" />
                    <x-text-input id="nombre" wire:model.live="form.nombre" class="w-full" type="text"
                        placeholder="Ej: Observación, Análisis de contenido, etc." />
                    <x-input-error :messages="$errors->first('form.nombre')" class="mt-2" />
                </div>
            </div>

            <!-- Boton Guardar -->
            <div class="flex items-center justify-end gap-4">
                <x-primary-button type="submit" wire:loading.attr="disabled">
                    {{ __('Guardar Técnica') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
