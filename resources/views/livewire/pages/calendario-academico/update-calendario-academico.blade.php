<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-500 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Calendario Académico') }}
        </h2>
    </x-slot>

    <div class="sogat-card">
        <form wire:submit.prevent="actualizar" class="w-full space-y-6" novalidate>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="w-full">
                    <x-input-label for="lapso" :value="__('Lapso Académico')" />
                    <select id="lapso" wire:model.live="form.id_lapso_academico"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">Seleccione Lapso...</option>
                        @foreach($lapsos as $lapso)
                            <option value="{{ $lapso->id_lapso_academico }}">{{ $lapso->nombre_lapso_academico }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->first('form.id_lapso_academico')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="semana" :value="__('Número de Semana')" />
                    <x-text-input id="semana" wire:model.live="form.semana" class="w-full" type="number" min="1" />
                    <x-input-error :messages="$errors->first('form.semana')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="inicio" :value="__('Fecha Inicio')" />
                    <x-text-input id="inicio" wire:model.live="form.dia_inicio" class="w-full" type="date" />
                    <x-input-error :messages="$errors->first('form.dia_inicio')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="fin" :value="__('Fecha Fin')" />
                    <x-text-input id="fin" wire:model.live="form.dia_fin" class="w-full" type="date" />
                    <x-input-error :messages="$errors->first('form.dia_fin')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="carga" :value="__('Carga Corte')" />
                    <select id="carga" wire:model.live="form.carga_corte"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">Seleccione...</option>
                        <option value="1">Opción 1</option>
                        <option value="2">Opción 2</option>
                    </select>
                    <x-input-error :messages="$errors->first('form.carga_corte')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <x-danger-button type="button" wire:click="cancelar" class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    {{ __('Volver') }}
                </x-danger-button>
                <x-primary-button type="submit" wire:loading.attr="disabled">
                    {{ __('Actualizar Calendario') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
