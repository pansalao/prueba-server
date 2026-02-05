<div>
    <x-slot name="header">
        <h2
            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight uppercase text-center {{ $calendario->estatus == 3 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-500' }}">
            {{ __('Detalles del Calendario Académico') }}
        </h2>
    </x-slot>

    <div class="sogat-card">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Lapso -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Lapso Académico</h3>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $this->obtenerNombreLapso($calendario->id_lapso_academico) }}
                    </p>
                </div>

                <!-- Semana -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Semana</h3>
                    <p class="mt-1 text-xl text-gray-900 dark:text-white">Semana {{ $calendario->semana }}</p>
                </div>

                <!-- Fechas -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Período</h3>
                    <p class="mt-1 text-lg text-gray-900 dark:text-white">
                        Del {{ \Carbon\Carbon::parse($calendario->dia_inicio)->format('d/m/Y') }} al
                        {{ \Carbon\Carbon::parse($calendario->dia_fin)->format('d/m/Y') }}
                    </p>
                </div>

                <!-- Carga Corte -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Carga Corte</h3>
                    <p class="mt-1 text-xl text-gray-900 dark:text-white">
                        @if($calendario->carga_corte == '1') Opción 1
                        @else Opción 2
                        @endif
                    </p>
                </div>

                <!-- Estatus -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Estatus</h3>
                    <span
                        class="{{ $calendario->estatus == 1 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} text-lg font-bold">
                        {{ $calendario->estatus == 1 ? 'Activo' : ($calendario->estatus == 3 ? 'Eliminado' : 'Inactivo') }}
                    </span>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('calendario-academico/listar') }}" wire:navigate
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <span class="material-symbols-outlined text-sm mr-1">arrow_back</span>
                    Volver
                </a>
            </div>
        </div>
    </div>
</div>
