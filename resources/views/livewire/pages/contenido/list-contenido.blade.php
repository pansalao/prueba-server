<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Contenidos') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 sm:rounded-lg">
            <x-table.alert-message type="success" :message="session('message')" />
            <x-table.alert-message type="error" :message="session('error')" />

            <div class="sogat-table-container">
                <div
                    class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:items-center sm:justify-between p-4 bg-white dark:bg-gray-800">
                    <x-table.search-input model="busqueda" placeholder="Buscar Contenido..." debounce="300ms" />
                </div>

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Título</th>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Tema</th>
                            @if(auth()->user()?->esCoordinadorOVicerrector())
                                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">
                                    Estatus</th>
                            @endif
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($contenidos->isNotEmpty())
                            @foreach ($contenidos as $contenido)
                                <tr wire:key="{{ $contenido->id_contenido }}"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-4 text-gray-900 dark:text-white">{{ $contenido->titulo_contenido }}</td>
                                    <td class="px-4 py-4 text-gray-900 dark:text-white">
                                        {{ $contenido->titulo_tema }}
                                    </td>

                                    @if(auth()->user()?->esCoordinadorOVicerrector())
                                        <td class="px-4 py-4 text-right">
                                            <span
                                                class="{{ $contenido->estatus == 1 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }} text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $contenido->estatus == 1 ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end space-x-3">
                                            <!-- Ver -->
                                            @can('ver-contenido')
                                                <a href="{{ route('contenido/show', $contenido->id_contenido) }}" wire:navigate
                                                    class="flex items-center gap-1 bg-blue-600 text-white text-xs font-medium px-2.5 py-1 rounded-md hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 shadow-sm transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                                        class="w-4 h-4">
                                                        <path
                                                            d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                                    </svg>
                                                    Ver
                                                </a>
                                            @endcan
                                            <!-- Editar -->
                                            @can('editar-contenido')
                                                <a href="{{ route('contenido/update', $contenido->id_contenido) }}" wire:navigate
                                                    class="flex items-center gap-1 bg-amber-500 text-white text-xs font-medium px-2.5 py-1 rounded-md hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 shadow-sm transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                                        class="w-4 h-4">
                                                        <path
                                                            d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                    </svg>
                                                    Editar
                                                </a>
                                            @endcan
                                            <!-- Acciones según estado -->
                                            @if(auth()->user()?->esCoordinadorOVicerrector())
                                                @if ($contenido->estatus == 1)
                                                    <!-- Inactivar -->
                                                    <button wire:click="confirmarInhabilitar({{ $contenido->id_contenido }})"
                                                        class="flex items-center gap-1 bg-red-600 text-white text-xs font-medium px-2.5 py-1 rounded-md hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 shadow-sm transition-colors">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Inactivar
                                                    </button>
                                                @else
                                                    <!-- Activar -->
                                                    <button wire:click="confirmarRestaurar({{ $contenido->id_contenido }})"
                                                        class="flex items-center gap-1 bg-green-600 text-white text-xs font-medium px-2.5 py-1 rounded-md hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 shadow-sm transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                        </svg>
                                                        Activar
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="{{ auth()->user()?->esCoordinadorOVicerrector() ? 4 : 3 }}"
                                    class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ $busqueda ? 'No se encontraron contenidos' : 'No hay contenidos registrados' }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <x-table.delete-modal :show="$idInhabilitar !== null" title="Confirmar Inactivación"
                    message="¿Estás seguro de que deseas inactivar este contenido?"
                    cancel-method="$set('idInhabilitar', null)" confirm-method="inhabilitar" actionType="delete" />

                <x-table.delete-modal :show="$idRestaurar !== null" title="Confirmar Activación"
                    message="¿Estás seguro de que deseas activar este contenido?"
                    cancel-method="$set('idRestaurar', null)" confirm-method="restaurar" :confirmText="'Activar'"
                    :actionType="'restore'" />
            </div>

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
                <div>{{ $contenidos->links() }}</div>
            </div>
        </div>
    </div>
</div>