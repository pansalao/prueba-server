<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Contenido') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-table.alert-message />
            <div class="sogat-card">
                <form wire:submit="save" class="w-full space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Tema -->
                        <div class="w-full">
                            <label for="tema" class="block font-bold text-sm text-gray-900 dark:text-white uppercase mb-1">
                                {{ __('Tema Asociado') }}
                            </label>
                            <x-select id="tema" wire:model.live="form.id_tema" :options="$temas" valueField="id"
                                textField="nombre" placeholder="Selecciona un tema" class="w-full"
                                errorField="form.id_tema" required />
                        </div>

                        <!-- Selección Dinámica de Objetivos (Selects Repetibles) -->
                        <div class="w-full md:col-span-2">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block font-bold text-sm text-gray-900 dark:text-white uppercase">
                                    {{ __('Objetivos Específicos') }}
                                </label>
                                <button type="button" wire:click="addObjetivo"
                                    class="inline-flex items-center gap-1 text-[11px] bg-[#f0f0f0] border-2 border-[#767676] text-black px-4 py-2 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
                                    <span class="material-icons text-sm">add</span>
                                    {{ __('AÑADIR OBJETIVO') }}
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($form->id_objetivo as $index => $idSel)
                                    <div class="flex items-center gap-3">
                                        <div class="flex-grow">
                                            <x-select id="objetivo_{{ $index }}" 
                                                wire:model.live="form.id_objetivo.{{ $index }}" 
                                                :options="$objetivos" valueField="id" textField="nombre" 
                                                :placeholder="empty($form->id_tema) ? '-- PRIMERO ELIJA UN TEMA --' : 'SELECCIONA UN OBJETIVO'" 
                                                class="w-full"
                                                :disabled="empty($form->id_tema)" />
                                        </div>
                                        <button type="button" wire:click="removeObjetivo({{ $index }})" 
                                            class="text-gray-400 hover:text-red-500 transition-colors p-2"
                                            title="ELIMINAR ESTE OBJETIVO">
                                            <span class="material-icons text-xl">delete</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->first('form.id_objetivo')" class="mt-2" />
                        </div>

                        <!-- Título -->
                        <div class="w-full md:col-span-2">
                            <x-input 
                                label="Título del Contenido" 
                                name="titulo_contenido"
                                errorField="form.titulo_contenido"
                                wire:model.live="form.titulo_contenido"
                                placeholder="EJ: INVOCACIÓN DE MÉTODOS Y PASO DE PARÁMETROS"
                                oninput="this.value = this.value.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑüÜ0-9\s.,()':\/-]/g, '')"
                                required 
                            />
                        </div>
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

                        <x-primary-button type="submit" wire:loading.attr="disabled" class="px-10 py-3 text-base">
                            {{ __('ACTUALIZAR CONTENIDO') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

