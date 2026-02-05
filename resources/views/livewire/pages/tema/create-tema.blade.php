<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-500 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Crear Tema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Alertas -->
            <x-table.alert-message type="success" :message="session('message')" />
            <x-table.alert-message type="error" :message="session('error')" />

            <div class="sogat-card">
                <form wire:submit.prevent="save" class="w-full space-y-6" novalidate>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Unidad Curricular -->
                        <div class="w-full">
                            <x-input-label for="unidad" :value="__('Unidad Curricular')" />
                            <x-select id="unidad" wire:model.live="form.id_unidad_curricular"
                                :options="$unidadesCurriculares" valueField="id" textField="nombre"
                                placeholder="Selecciona una unidad" class="w-full mt-1"
                                errorField="form.id_unidad_curricular" required />
                        </div>

                        <!-- Corte -->
                        <div class="w-full">
                            <x-input-label for="corte" :value="__('Corte (Unidad)')" />
                            <x-select id="corte" wire:model.live="form.unidad_tema" :options="$cortes" valueField="id"
                                textField="nombre" placeholder="Selecciona un corte" class="w-full mt-1"
                                errorField="form.unidad_tema" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        <!-- Título -->
                        <div class="w-full">
                            <x-input-label for="titulo" :value="__('Título del Tema')" />
                            <x-text-input id="titulo" wire:model.live="form.titulo_tema" class="w-full mt-1" type="text"
                                placeholder="Ej: Tema 1: Hardware y Software" required />
                            <x-input-error :messages="$errors->first('form.titulo_tema')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Descripción (Full Width) -->
                    <div class="w-full">
                        <x-input-label for="descripcion" :value="__('Descripción (Opcional)')" />
                        <textarea id="descripcion" wire:model.live="form.descripcion_tema" rows="4"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full mt-1"
                            placeholder="Descripción detallada del tema..."></textarea>
                        <x-input-error :messages="$errors->first('form.descripcion_tema')" class="mt-2" />
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4">
                        <x-primary-button type="submit" wire:loading.attr="disabled">
                            {{ __('Guardar Tema') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
