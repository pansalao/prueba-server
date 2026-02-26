<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Bitácora') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 sm:rounded-lg">

            <div class="sogat-table-container">
                <div
                    class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:items-center sm:justify-between p-4 bg-white dark:bg-gray-800">
                    <x-table.search-input model="busqueda" placeholder="Buscar transacción..." debounce="300ms" />
                </div>

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Fecha</th>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Usuario</th>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Acción</th>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Tabla</th>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($bitacoras->isNotEmpty())
                            @foreach ($bitacoras as $log)
                                <tr wire:key="log-{{ $log->id_bitacora }}"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($log->fecha)->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-4 text-gray-900 dark:text-white">
                                        {{ $log->usuario_nombre ?? 'Sistema' }}
                                    </td>
                                    <td class="px-4 py-4">
                                        @php
                                            $accionMostrar = $log->accion;

                                            // Verificamos si es una modificación y validamos el estatus en el JSON
                                            if ($accionMostrar === 'MODIFICAR' && !empty($log->nuevos)) {
                                                $datosNuevos = json_decode($log->nuevos, true);

                                                if (is_array($datosNuevos) && isset($datosNuevos['estatus'])) {
                                                    if ($datosNuevos['estatus'] == 3) {
                                                        $accionMostrar = 'INACTIVAR';
                                                    } elseif ($datosNuevos['estatus'] == 1) {
                                                        $accionMostrar = 'ACTIVAR'; // Agregamos la nueva acción
                                                    }
                                                }
                                            }

                                            // Asignamos el color
                                            $color = match ($accionMostrar) {
                                                'CREAR' => 'text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-300',
                                                'MODIFICAR' => 'text-blue-800 bg-blue-100 dark:bg-blue-900 dark:text-blue-300',
                                                'ELIMINAR' => 'text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-300',
                                                'INACTIVAR' => 'text-orange-800 bg-orange-100 dark:bg-orange-900 dark:text-orange-300',
                                                'ACTIVAR' => 'text-teal-800 bg-teal-100 dark:bg-teal-900 dark:text-teal-300', // Color nuevo
                                                'LOGIN' => 'text-purple-800 bg-purple-100 dark:bg-purple-900 dark:text-purple-300',
                                                'LOGOUT' => 'text-yellow-800 bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-300',
                                                'MOSTRAR' => 'text-indigo-800 bg-indigo-100 dark:bg-indigo-900 dark:text-indigo-300',
                                                default => 'text-gray-800 bg-gray-100 dark:bg-gray-900 dark:text-gray-300',
                                            };
                                        @endphp
                                        <span class="{{ $color }} text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $accionMostrar }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-gray-900 dark:text-white">
                                        {{ $log->tabla ?? '---' }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <a href="{{ route('bitacora/show', $log->id_bitacora) }}" wire:navigate
                                            class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                                class="w-4 h-4">
                                                <path
                                                    d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                            </svg>
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ $busqueda ? 'No se encontraron registros para esta búsqueda.' : 'No hay registros en la bitácora.' }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div
                class="flex flex-col md:flex-row items-center justify-between p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 sm:rounded-b-lg">
                <div class="flex items-center mb-4 md:mb-0">
                    <select wire:model.live="paginacion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white w-24">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div>{{ $bitacoras->links() }}</div>
            </div>

        </div>
    </div>
</div>