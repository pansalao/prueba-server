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
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold break-words overflow-hidden">
                                {{ $bitacora->usuario_nombre ?? 'Sistema Automático' }}
                            </p>
                        </div>

                        <div>
                            <x-input-label value="Módulo Afectado:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold break-words overflow-hidden">
                                {{ $bitacora->modulo ?? '---' }}
                            </p>
                        </div>

                        <div>
                            <x-input-label value="Acción Realizada:" />
                            <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                @php
                                    // Determinar la acción a mostrar dinámicamente
                                    $accionMostrar = $bitacora->accion;

                                    // Verificamos si es una modificación y validamos el estatus
                                    if ($accionMostrar === 'MODIFICAR' && !empty($bitacora->nuevos)) {
                                        $datosNuevos = is_array($bitacora->nuevos) ? $bitacora->nuevos : json_decode($bitacora->nuevos, true);

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
                                        'CREAR' => 'text-green-800 bg-green-100 dark:bg-green-600 dark:text-green-100',
                                        'MODIFICAR' => 'text-blue-800 bg-blue-100 dark:bg-blue-600 dark:text-blue-100',
                                        'ELIMINAR' => 'text-red-800 bg-red-100 dark:bg-red-600 dark:text-red-100',
                                        'INHABILITAR' => 'text-orange-800 bg-orange-100 dark:bg-orange-600 dark:text-orange-100',
                                        'REHABILITAR' => 'text-teal-800 bg-teal-100 dark:bg-teal-600 dark:text-teal-100',
                                        'LOGIN' => 'text-purple-800 bg-purple-100 dark:bg-purple-600 dark:text-purple-100',
                                        'LOGOUT' => 'text-yellow-800 bg-yellow-100 dark:bg-yellow-600 dark:text-yellow-100',
                                        'MOSTRAR' => 'text-indigo-800 bg-indigo-100 dark:bg-indigo-600 dark:text-indigo-100',
                                        default => 'text-gray-800 bg-gray-100 dark:bg-gray-600 dark:text-gray-100',
                                    };
                                @endphp
                                <span class="{{ $color }} px-2 py-1 font-semibold leading-tight rounded-full uppercase">
                                    {{ $accionMostrar }}
                                </span>
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
                                        <div class="mt-2">
                                            @if($bitacora->anteriores)
                                                @php
                                                    $datosAnteriores = is_array($bitacora->anteriores) ? $bitacora->anteriores : json_decode($bitacora->anteriores, true);
                                                    $jsonAnteriores = json_encode($datosAnteriores ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                                @endphp
                                                <pre
                                                    class="bg-gray-50 dark:bg-gray-900 rounded-md p-4 overflow-x-auto text-sm font-mono text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 shadow-sm"><span class="text-blue-600 dark:text-blue-400 font-bold">{{ $bitacora->tabla }}</span> {{ $jsonAnteriores }}</pre>
                                            @else
                                                <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">No aplicable
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <x-input-label value="Valores Nuevos:" />
                                    <div class="mt-2">
                                        @if($bitacora->nuevos)
                                            @php
                                                $datosAnterioresAux = !empty($bitacora->anteriores) ? (is_array($bitacora->anteriores) ? $bitacora->anteriores : json_decode($bitacora->anteriores, true) ?? []) : [];
                                                $datosNuevosAux = is_array($bitacora->nuevos) ? $bitacora->nuevos : json_decode($bitacora->nuevos, true) ?? [];
                                                $datosCompletos = array_merge($datosAnterioresAux, $datosNuevosAux);

                                                $htmlNuevos = "{\n";
                                                $total = count($datosCompletos);
                                                $i = 0;
                                                foreach ($datosCompletos as $key => $value) {
                                                    $i++;
                                                    // Es una actualización si existe en los nuevos y también había valores anteriores
                                                    $isUpdated = array_key_exists($key, $datosNuevosAux) && !empty($datosAnterioresAux);
                                                    $comma = $i < $total ? ',' : '';
                                                    $encodedValue = json_encode($value, JSON_UNESCAPED_UNICODE);
                                                    
                                                    if ($isUpdated) {
                                                        $htmlNuevos .= "    <span class=\"bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-400 font-bold px-1 rounded\">\"{$key}\": {$encodedValue}{$comma}</span>\n";
                                                    } else {
                                                        $htmlNuevos .= "    \"{$key}\": {$encodedValue}{$comma}\n";
                                                    }
                                                }
                                                $htmlNuevos .= "}";
                                            @endphp
                                            <pre
                                                class="bg-gray-50 dark:bg-gray-900 rounded-md p-4 overflow-x-auto text-sm font-mono text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 shadow-sm"><span class="text-blue-600 dark:text-blue-400 font-bold">{{ $bitacora->tabla }}</span> {!! $htmlNuevos !!}</pre>
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

                <!-- Botón Volver -->
                <div class="flex justify-end mt-6">
                    <x-danger-button type="button" wire:click="cerrar">
                        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                        <span class="material-symbols-outlined">arrow_back</span>
                        {{ __('Volver') }}
                    </x-danger-button>
                </div>

            </div>
        </div>
    </div>
</div>