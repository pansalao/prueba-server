<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Crear Indicador de Logro') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-table.alert-message type="success" :message="session('message')" />
            <x-table.alert-message type="error" :message="session('error')" />

            <div class="sogat-card">
                <form wire:submit.prevent="guardar" class="w-full space-y-6" novalidate>
                    <div class="flex flex-col gap-4 w-full md:flex-row">
                        <!-- Nombre -->
                        <div class="w-full">
                            <x-input-label for="nombre" :value="__('Nombre del Indicador')" />
                            <x-text-input id="nombre" wire:model.live="form.nombre_indicador_logro" class="w-full mt-1"
                                type="text" placeholder="Ej: Demuestra dominio de los conceptos básicos..." required />
                            <x-input-error :messages="$errors->first('form.nombre_indicador_logro')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <x-primary-button type="submit" wire:loading.attr="disabled">
                            {{ __('Guardar Indicador') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

