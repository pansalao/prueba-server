<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-500 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Contenido') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">
                <form wire:submit="save" class="w-full space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Tema -->
                        <div class="w-full">
                            <x-input-label for="tema" :value="__('Tema Asociado')" />
                            <x-select id="tema" wire:model.live="form.id_tema" :options="$temas" valueField="id"
                                textField="nombre" placeholder="Selecciona un tema" class="w-full mt-1"
                                errorField="form.id_tema" required />
                        </div>

                        <!-- Título -->
                        <div class="w-full">
                            <x-input-label for="titulo" :value="__('Título del Contenido')" />
                            <x-text-input id="titulo" wire:model="form.titulo_contenido" class="w-full mt-1" type="text"
                                required />
                            <x-input-error :messages="$errors->first('form.titulo_contenido')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Descripción (Full Width) -->
                    <div class="w-full">
                        <x-input-label for="descripcion" :value="__('Descripción (Opcional)')" />
                        <textarea id="descripcion" wire:model="form.descripcion_contenido" rows="4"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full mt-1"></textarea>
                        <x-input-error :messages="$errors->first('form.descripcion_contenido')" class="mt-2" />
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('contenido/listar') }}">
                            <x-danger-button type="button">
                                <link rel="stylesheet"
                                    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                                <span class="material-symbols-outlined">
                                    arrow_back
                                </span>
                                {{ __('Volver') }}
                            </x-danger-button>
                        </a>

                        <x-primary-button type="submit" wire:loading.attr="disabled">
                            {{ __('Actualizar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
