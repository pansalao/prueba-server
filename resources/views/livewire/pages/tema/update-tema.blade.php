<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Tema') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Alertas -->
            <x-table.alert-message />

            <div class="sogat-card">
                <form wire:submit.prevent="save" class="w-full space-y-6" novalidate>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <!-- Unidad Curricular -->
                        <div class="w-full">
                            <label for="unidad" class="block font-bold text-sm text-gray-900 dark:text-white uppercase mb-1">
                                {{ __('Unidad Curricular') }}
                            </label>
                            <x-select id="unidad" wire:model.live="form.id_unidad_curricular"
                                :options="$unidadesCurriculares" valueField="id" textField="nombre"
                                placeholder="SELECCIONA UNA UNIDAD" class="w-full"
                                errorField="form.id_unidad_curricular" required />
                        </div>

                        <!-- Corte -->
                        <div class="w-full">
                            <label for="corte" class="block font-bold text-sm text-gray-900 dark:text-white uppercase mb-1">
                                {{ __('Corte') }}
                            </label>
                            <x-select id="corte" wire:model.live="form.unidad_tema" :options="$cortes" valueField="id"
                                textField="nombre" placeholder="SELECCIONA UN CORTE" class="w-full"
                                errorField="form.unidad_tema" required />
                        </div>

                        <!-- Título -->
                        <div class="w-full md:col-span-2">
                            <x-datalist 
                                wire:key="datalist-temas-{{ md5($temasExistentes->pluck('titulo_tema')->join(',')) }}"
                                label="Título del Tema" 
                                :options="$temasExistentes" 
                                textField="titulo_tema"
                                wire:model.live="form.titulo_tema"
                                placeholder="EJ: TEMA 1: HARDWARE Y SOFTWARE"
                                oninput="this.value = this.value.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑüÜ0-9\s.,()':\/-]/g, '')"
                                required 
                            />
                        </div>
                    </div>
 
                    <!-- Sección de Objetivos -->
                    <div class="space-y-4 pt-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-extrabold text-gray-800 dark:text-gray-200 uppercase tracking-tight">
                                {{ __('OBJETIVOS DEL TEMA') }}
                            </h4>
                            <button type="button" wire:click="addObjetivo"
                                class="inline-flex items-center gap-1 text-[11px] bg-[#f0f0f0] border-2 border-[#767676] text-black px-4 py-2 rounded-lg font-bold hover:bg-gray-200 transition-colors shadow-sm uppercase">
                                <span class="material-icons text-sm">add</span>
                                {{ __('AÑADIR OBJETIVO') }}
                            </button>
                        </div>
 
                        <div class="space-y-4">
                            @foreach ($form->objetivos as $index => $objetivo)
                                <div class="flex items-center gap-3 group">
                                    <div class="flex-grow">
                                        <x-datalist 
                                            wire:key="datalist-objetivos-{{ $index }}-{{ md5($objetivosExistentes->pluck('titulo_objetivo')->join(',')) }}"
                                            :options="$objetivosExistentes" 
                                            textField="titulo_objetivo"
                                            wire:model.live="form.objetivos.{{ $index }}.titulo_objetivo"
                                            placeholder="DESCRIBA EL OBJETIVO..."
                                            oninput="this.value = this.value.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑüÜ0-9\s.,()':\/-]/g, '')"
                                            required 
                                        />
                                    </div>
                                    @if (count($form->objetivos) > 1)
                                        <button type="button" wire:click="removeObjetivo({{ $index }})"
                                            class="text-gray-400 hover:text-red-600 transition-all p-1">
                                            <span class="material-icons text-xl">delete</span>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <!-- Botones -->
                    <div class="flex items-center justify-end pt-6">
                        <x-primary-button type="submit" wire:loading.attr="disabled" class="px-10 py-3 text-base">
                            {{ __('ACTUALIZAR TEMA') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

