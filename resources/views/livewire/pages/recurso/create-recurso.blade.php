<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Registrar Recurso') }}
        </h2>
    </x-slot>

    <x-table.alert-message />

    <div class="sogat-card">
        <form wire:submit.prevent="guardar" class="w-full space-y-6" novalidate>
            <!-- Grid -->
            <div class="flex flex-col gap-4 w-full md:flex-row">
                <!-- Nombre -->
                <div class="w-full">
                    <x-input 
                        label="Nombre del Recurso" 
                        name="nombre"
                        errorField="form.nombre"
                        wire:model.live="form.nombre"
                        placeholder="Ej: Proyector, Marcadores, etc."
                        required 
                    />
                </div>
            </div>

            <!-- Boton Guardar -->
            <div class="flex items-center justify-end gap-4">
                <x-primary-button type="submit" wire:loading.attr="disabled">
                    {{ __('Guardar Recurso') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

