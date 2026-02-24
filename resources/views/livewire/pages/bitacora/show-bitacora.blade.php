<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-center text-gray-800 dark:text-gray-500 leading-tight uppercase">
            {{ __('Detalles de la Bitácora') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">

                @if ($bitacora)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <div>
                            <x-input-label value="Fecha del Movimiento:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                {{ \Carbon\Carbon::parse($bitacora->fecha)->format('d/m/Y H:i:s') }}
                            </p>
                        </div>

                        <div>
                            <x-input-label value="Usuario Autor:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold overflow-wrap break-words">
                                {{ $bitacora->usuario_nombre ?? 'Sistema Automático' }}
                            </p>
                        </div>

                        <div>
                            <x-input-label value="Acción Realizada:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                @php
                                    // Determinar la acción a mostrar dinámicamente
                                    $accionMostrar = $bitacora->accion;

                                    // Verificamos si es una modificación y validamos el estatus en el JSON
                                    if ($accionMostrar === 'MODIFICAR' && !empty($bitacora->nuevos)) {
                                        $datosNuevos = json_decode($bitacora->nuevos, true);

                                        if (is_array($datosNuevos) && isset($datosNuevos['estatus'])) {
                                            if ($datosNuevos['estatus'] == 3) {
                                                $accionMostrar = 'INHABILITAR';
                                            } elseif ($datosNuevos['estatus'] == 1) {
                                                $accionMostrar = 'REHABILITAR';
                                            }
                                        }
                                    }

                                    // Asignamos el color basado en la acción final a mostrar
                                    $color = match ($accionMostrar) {
                                        'CREAR' => 'text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-300',
                                        'MODIFICAR' => 'text-blue-800 bg-blue-100 dark:bg-blue-900 dark:text-blue-300',
                                        'ELIMINAR' => 'text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-300',
                                        'INHABILITAR' => 'text-orange-800 bg-orange-100 dark:bg-orange-900 dark:text-orange-300',
                                        'REHABILITAR' => 'text-teal-800 bg-teal-100 dark:bg-teal-900 dark:text-teal-300',
                                        'LOGIN' => 'text-purple-800 bg-purple-100 dark:bg-purple-900 dark:text-purple-300',
                                        'LOGOUT' => 'text-yellow-800 bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-300',
                                        'MOSTRAR' => 'text-indigo-800 bg-indigo-100 dark:bg-indigo-900 dark:text-indigo-300',
                                        default => 'text-gray-800 bg-gray-100 dark:bg-gray-900 dark:text-gray-300',
                                    };
                                @endphp
                                <span
                                    class="{{ $color }} px-2 py-1 font-semibold leading-tight rounded-full uppercase text-sm align-middle">
                                    {{ $accionMostrar }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <x-input-label value="Tabla Afectada:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                {{ $bitacora->tabla ?? '---' }}
                            </p>
                        </div>

                        <div>
                            <x-input-label value="Dirección IP de Origen:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold font-mono">
                                {{ $bitacora->ip ?? 'Desconocida' }}
                            </p>
                        </div>
                    </div>

                    @if($bitacora->accion !== 'MOSTRAR' && ($bitacora->anteriores || $bitacora->nuevos))
                        <div class="mt-8 border-t dark:border-gray-200 dark:border-gray-700 pt-6">

                            <div class="mb-4">
                                <x-input-label value="Detalles del Movimiento de Datos:" />
                            </div>

                            <div class="grid grid-cols-1 gap-6">

                                @if($accionMostrar !== 'CREAR')
                                    <div>
                                        <x-input-label value="Valores Anteriores:" />
                                        <div class="mt-1">
                                            @if($bitacora->anteriores)
                                                @php
                                                    $datosAnteriores = json_decode($bitacora->anteriores, true) ?? $bitacora->anteriores;
                                                    $jsonAnteriores = json_encode($datosAnteriores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                                @endphp
                                                <pre
                                                    class="bg-gray-50 dark:bg-gray-900 rounded-md p-4 overflow-x-auto text-sm font-mono text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 shadow-sm">{{ $jsonAnteriores }}</pre>
                                            @else
                                                <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">No aplicable
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <x-input-label value="Valores Nuevos:" />
                                    <div class="mt-1">
                                        @if($bitacora->nuevos)
                                            @php
                                                $datosNuevos = json_decode($bitacora->nuevos, true) ?? $bitacora->nuevos;
                                                $jsonNuevos = json_encode($datosNuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                            @endphp
                                            <pre
                                                class="bg-gray-50 dark:bg-gray-900 rounded-md p-4 overflow-x-auto text-sm font-mono text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 shadow-sm">{{ $jsonNuevos }}</pre>
                                        @else
                                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">No aplicable o nulo
                                            </p>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                @else
                    <p class="text-gray-500 dark:text-gray-400">No se ha encontrado el registro de Bitácora...</p>
                @endif

                <div class="flex justify-end mt-6">
                    <x-danger-button type="button" wire:click="cerrar">
                        <link rel="stylesheet"
                            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                        <span class="material-symbols-outlined mr-1">arrow_back</span>
                        {{ __('Volver') }}
                    </x-danger-button>
                </div>

            </div>
        </div>
    </div>
</div>