<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Semana de Calendario') }}
        </h2>
    </x-slot>

    <div class="sogat-card">
        <form wire:submit.prevent="actualizar" class="w-full space-y-6" novalidate>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="w-full">
                    <x-select id="lapso" wire:model.live="form.id_lapso_academico" label="Lapso Académico"
                        :options="$lapsos" valueField="id_lapso_academico" textField="nombre_lapso_academico"
                        placeholder="Seleccione..." required />
                    <x-input-error :messages="$errors->first('form.id_lapso_academico')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="semana" :value="__('Número de Semana')" />
                    <x-text-input id="semana" wire:model.live="form.semana_calendario_academico" class="w-full"
                        type="number" placeholder="Ej: 1" step="1" min="1" max="52" required
                        onkeydown="if(['e', 'E', '+', '-', '.'].includes(event.key)) event.preventDefault();" />
                    <x-input-error :messages="$errors->first('form.semana_calendario_academico')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="dia_inicio" :value="__('Fecha de Inicio')" />
                    <x-text-input id="dia_inicio" wire:model.live="form.dia_inicio_calendario_academico" class="w-full"
                        type="date" required />
                    <x-input-error :messages="$errors->first('form.dia_inicio_calendario_academico')" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-input-label for="dia_fin" :value="__('Fecha de Fin')" />
                    <x-text-input id="dia_fin" wire:model.live="form.dia_fin_calendario_academico" class="w-full"
                        type="date" required />
                    <x-input-error :messages="$errors->first('form.dia_fin_calendario_academico')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('calendario-academico/listar') }}" wire:navigate>
                    <x-danger-button type="button" class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        {{ __('Volver') }}
                    </x-danger-button>
                </a>
                <x-primary-button type="submit" wire:loading.attr="disabled">
                    {{ __('Actualizar Registro') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>