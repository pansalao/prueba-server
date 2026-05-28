<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Técnica de Evaluación') }}
        </h2>
    </x-slot>
    <x-table.alert-message />

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">

                <form wire:submit.prevent="actualizar" class="w-full space-y-6" novalidate>

                    <div class="flex flex-col gap-4 w-full md:flex-row">
                        <!-- Nombre -->
                        <div class="w-full">
                            <x-input 
                                label="Nombre de la Técnica" 
                                name="nombre"
                                errorField="form.nombre"
                                wire:model.live="form.nombre"
                                placeholder="Ej: Portafolio, Prueba escrita, Ensayo, etc."
                                oninput="this.value = this.value.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑüÜ0-9\s.,()':\/-]/g, '')"
                                required 
                            />
                        </div>
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
    </div>
</div>