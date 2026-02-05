<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-500 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Recursos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 sm:rounded-lg">
            <!-- Componentes de alerta -->
            <x-table.alert-message type="success" :message="session('message')" />
            <x-table.alert-message type="error" :message="session('error')" />

            <!-- Tabla -->
            <div class="sogat-table-container">
                <!-- Controles -->
                <div
                    class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:items-center sm:justify-between p-4 bg-white dark:bg-gray-800">
                    <!-- Componente de búsqueda -->
                    <x-table.search-input model="busqueda" placeholder="Buscar recurso..." debounce="300ms" />
                </div>

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Nombre</th>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">
                                Estatus</th>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($recursos->isNotEmpty())
                            @foreach ($recursos as $recurso)
                                <tr wire:key="{{ $recurso->id_recurso }}"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <!-- Nombre -->
                                    <td class="px-4 py-4 text-gray-900 dark:text-white">
                                        {{ $recurso->nombre }}
                                    </td>
                                    <!-- Estatus -->
                                    <td class="px-4 py-4 text-right">
                                        <span class="{{ $recurso->estatus == 1 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }} 
                                                            text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $recurso->estatus == 1 ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <!-- Acciones -->
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end space-x-3">
                                            <!-- Ver -->
                                            <a href="{{ route('recurso/show', $recurso->id_recurso) }}" wire:navigate
                                                class="flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                                    class="w-4 h-4">
                                                    <path
                                                        d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                                </svg>
                                                Ver
                                            </a>
                                            <!-- Editar -->
                                            <a href="{{ route('recurso/update', $recurso->id_recurso) }}" wire:navigate
                                                class="flex items-center gap-1 bg-yellow-600 text-white text-xs font-medium px-2.5 py-0.5 rounded hover:bg-yellow-700 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                                    class="w-4 h-4">
                                                    <path
                                                        d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                </svg>
                                                Editar
                                            </a>

                                            <!-- Acciones según estado -->
                                            @if ($recurso->estatus == 1)
                                                <!-- Inhabilitar -->
                                                <button wire:click="confirmarInhabilitar({{ $recurso->id_recurso }})"
                                                    class="flex items-center gap-1 bg-red-50 text-red-600 dark:bg-red-900 dark:text-red-200 hover:bg-red-100 dark:hover:bg-red-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Inhabilitar
                                                </button>
                                            @else
                                                <!-- Restaurar -->
                                                <button wire:click="confirmarRestaurar({{ $recurso->id_recurso }})"
                                                    class="flex items-center gap-1 bg-green-50 text-green-600 dark:bg-green-900 dark:text-green-200 hover:bg-green-100 dark:hover:bg-green-800 text-xs font-medium px-2.5 py-0.5 rounded text-left">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                    </svg>
                                                    Restaurar
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ $busqueda ? 'No se encontraron recursos' : 'No hay recursos registrados' }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <!-- Modal de confirmación de inhabilitación -->
                <x-table.delete-modal :show="$idInhabilitar !== null" title="Confirmar Inhabilitación"
                    message="¿Estás seguro de que deseas inhabilitar este recurso?"
                    cancel-method="$set('idInhabilitar', null)" confirm-method="inhabilitar" actionType="delete" />

                <!-- Modal de confirmación de restauración -->
                <x-table.delete-modal :show="$idRestaurar !== null" title="Confirmar Restauración"
                    message="¿Estás seguro de que deseas restaurar este recurso?"
                    cancel-method="$set('idRestaurar', null)" confirm-method="restaurar" :confirmText="'Restaurar'"
                    :actionType="'restore'" />
            </div>

            <!-- Paginación -->
            <div
                class="flex flex-col md:flex-row items-center justify-between p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4 md:mb-0">
                    <select wire:model.live="paginacion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white w-24">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div>{{ $recursos->links() }}</div>
            </div>
        </div>
    </div>
</div>
