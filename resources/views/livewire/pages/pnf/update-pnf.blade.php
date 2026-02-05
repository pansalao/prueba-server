<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-500 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar PNF') }}
        </h2> 
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">

                <form wire:submit.prevent="actualizar" class="w-full space-y-6" novalidate>

                    <!-- Grid de 1 columna (solo nombre) -->
                    <div class="flex flex-col gap-4 w-full md:flex-row">

                        <!-- Nombre del PNF -->
                        <div class="w-full">
                            <x-input-label for="nombre" :value="__('Nombre del PNF')" />
                            <x-text-input id="nombre" wire:model.live="form.nombre" class="w-full"
                                placeholder="Ingrese el nuevo nombre del PNF" required />
                            <x-input-error :messages="$errors->first('form.nombre')" class="mt-2" />
                        </div>

                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4">

                        <!-- Botón Cancelar -->
                        <x-danger-button type="button" wire:click="cancelar">
                            <link rel="stylesheet"
                                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                            <span class="material-symbols-outlined">
                                arrow_back
                            </span>
                            {{ __('Volver') }}
                        </x-danger-button>

                        <!-- Botón Guardar Cambios -->
                        <x-primary-button type="submit" wire:loading.attr="disabled">
                            {{ __('Actualizar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
