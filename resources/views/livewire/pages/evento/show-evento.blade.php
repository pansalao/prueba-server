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
                <!-- Descripción -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</h3>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $evento->descripcion_evento }}</p>
                </div>

                <!-- Fecha -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha</h3>
                    <p class="mt-1 text-xl text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y') }}</p>
                </div>

                <!-- Tipo -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</h3>
                    <p class="mt-1 text-xl text-gray-900 dark:text-white">
                        @if($evento->tipo_evento == '1') Tipo 1
                        @elseif($evento->tipo_evento == '2') Tipo 2
                        @else Tipo 3
                        @endif
                    </p>
                </div>

                <!-- Estatus -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Estatus</h3>
                    <span
                        class="{{ $evento->estatus == 1 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} text-lg font-bold">
                        {{ $evento->estatus == 1 ? 'Activo' : ($evento->estatus == 3 ? 'Eliminado' : 'Inactivo') }}
                    </span>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('evento/listar') }}" wire:navigate
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <span class="material-symbols-outlined text-sm mr-1">arrow_back</span>
                    Volver
                </a>
            </div>
        </div>
    </div>
</div>
