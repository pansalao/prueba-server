<div>
    <x-slot name="header">
        <h2
            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight uppercase text-center {{ $evento->estatus == 3 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-500' }}">
            {{ __('Detalles del Evento') }}
        </h2>
    </x-slot>

    <div class="sogat-card">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Lapso -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Lapso</h3>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $evento->nombre_lapso }}
                    </p>
                </div>

                <!-- Descripción -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</h3>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $evento->descripcion_evento }}
                    </p>
                </div>

                <!-- Semana -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Semana</h3>
                    <p class="mt-1 text-xl text-gray-900 dark:text-white">
                        {{ $evento->semana_evento }}
                    </p>
                </div>

                <!-- Fecha Inicio -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Inicio</h3>
                    <p class="mt-1 text-xl text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($evento->dia_inicio_evento)->format('d/m/Y') }}
                    </p>
                </div>

                <!-- Fecha Fin -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Fin</h3>
                    <p class="mt-1 text-xl text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($evento->dia_fin_evento)->format('d/m/Y') }}
                    </p>
                </div>

                <!-- Tipo -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</h3>
                    <p class="mt-1 text-xl text-gray-900 dark:text-white">
                        @if($evento->tipo_evento == '1') Feriado
                        @elseif($evento->tipo_evento == '2') Actividad Académica
                        @else Otro
                        @endif
                    </p>
                </div>

                <!-- Estatus -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Estatus</h3>
                    <span
                        class="{{ $evento->estatus == 1 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} text-lg font-bold">
                        {{ $evento->estatus == 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('evento/listar') }}" wire:navigate>
                    <x-danger-button type="button" class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        {{ __('Volver') }}
                    </x-danger-button>
                </a>
            </div>
        </div>
    </div>
</div>