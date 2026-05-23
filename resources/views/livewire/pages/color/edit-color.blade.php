<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Color') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-table.alert-message type="success" :message="session('message')" />
            <x-table.alert-message type="error" :message="session('error')" />

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form wire:submit="actualizar" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Nombre del Color -->
                            <div class="w-full">
                                <x-input-label for="nombre_color_edit" :value="__('Nombre del Color *')" />
                                <x-text-input id="nombre_color_edit" type="text" class="w-full" wire:model.live="form.nombre_color" placeholder="Ej: Rojo Pasión" required autofocus />
                                <x-input-error :messages="$errors->first('form.nombre_color')" class="mt-2" />
                            </div>

                            <!-- Código de Color -->
                            <div class="w-full">
                                <x-input-label for="codigo_color_edit" :value="__('Código Hexadecimal *')" />
                                <div class="flex items-center gap-2">
                                    <input type="color" id="color_picker_edit" wire:model.live="form.codigo_color" class="h-10 w-10 p-1 border border-gray-300 dark:border-gray-700 rounded-md cursor-pointer bg-white dark:bg-gray-900">
                                    <x-text-input id="codigo_color_edit" type="text" class="flex-1 font-mono uppercase" wire:model.live="form.codigo_color" placeholder="#FF0000" maxlength="7" required />
                                </div>
                                <x-input-error :messages="$errors->first('form.codigo_color')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">Seleccione un color o introduzca su código hexadecimal.</p>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-4 space-x-3">
                            <a href="{{ route('color.list') }}" wire:navigate class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Actualizar Color') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
