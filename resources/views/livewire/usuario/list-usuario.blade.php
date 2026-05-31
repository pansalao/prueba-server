<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <!-- Controles -->
        <div
            class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:items-center sm:justify-between p-4 bg-white dark:bg-gray-800">
            <!-- Búsqueda -->
            <input type="text" wire:model.live.debounce.300ms="busqueda"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                placeholder="Buscar usuario por nombre, apellido o rol...">
        </div>

        <!-- Tabla en pantallas sm y mayores -->
        <div class="hidden sm:block">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Nombre
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Apellido
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Roles
                        </th>
                        @if(auth()->user()?->esCoordinadorOVicerrector())
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">
                            Estatus</th>
                        @endif
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($users->isNotEmpty())
                        @foreach ($users as $user)
                            <tr wire:key="{{ $user->id }}"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <!-- Nombre -->
                                <td class="px-4 py-4 text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                </td>
                                <!-- Apellido -->
                                <td class="px-4 py-4 text-gray-900 dark:text-white">
                                    {{ $user->apellido }}
                                </td>
                                <!-- Roles -->
                                <td class="px-4 py-4 text-gray-900 dark:text-white">
                                    {{ $user->roles_nombres ?? 'Sin Rol' }}
                                </td>
                                <!-- Estatus -->
                                @if(auth()->user()?->esCoordinadorOVicerrector())
                                <td class="px-4 py-4 text-right">
                                    @if ($user->estatus == 1)
                                        <span
                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Activo</span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Inactivo</span>
                                    @endif
                                </td>
                                @endif
                                <!-- Acciones -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end space-x-4">
                                        <!-- Ver -->
                                        <button x-data
                                            x-on:click="$dispatch('openModal', { component: 'usuario.show-usuario', arguments: { userId: {{ $user->id }} } })"
                                            class="flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                            </svg>
                                            Ver
                                        </button>
                                        <!-- Editar -->
                                        <button x-data
                                            x-on:click="$dispatch('openModal', { component: 'usuario.update-usuario', arguments: { userId: {{ $user->id }} } })"
                                            class="flex items-center gap-1 bg-yellow-600 text-white text-xs font-medium px-2.5 py-0.5 rounded hover:bg-yellow-700 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                            </svg>
                                            Editar
                                        </button>
                                        <!-- Cambiar Estatus -->
                                        @if(auth()->user()?->esCoordinadorOVicerrector())
                                        @if ($user->estatus == 1)
                                            <button wire:click="cambiarEstatusUsuario({{ $user->id }})"
                                                class="flex items-center gap-1 bg-red-50 text-red-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-red-100 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800">
                                                <i class="fas fa-trash w-4 h-4 flex items-center justify-center"></i>
                                                Desactivar
                                            </button>
                                        @else
                                            <button wire:click="cambiarEstatusUsuario({{ $user->id }})"
                                                class="flex items-center gap-1 bg-green-50 text-green-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-green-100 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                                <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M11,7L16.5,12L11,17V14H5V10H11V7Z"/>
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
                            <td colspan="{{ auth()->user()?->esCoordinadorOVicerrector() ? 5 : 4 }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Vista lista en pantallas xs -->
        <div class="sm:hidden">
            @if ($users->isNotEmpty())
                @foreach ($users as $user)
                    <div wire:key="{{ $user->id }}"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 py-4 px-3 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <div class="mb-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Nombre:</span>
                            <span class="text-gray-900 dark:text-white">{{ $user->name }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Apellido:</span>
                            <span class="text-gray-900 dark:text-white">{{ $user->apellido }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Roles:</span>
                            <span class="text-gray-900 dark:text-white">{{ $user->roles_nombres ?? 'Sin Rol' }}</span>
                        </div>
                        @if(auth()->user()?->esCoordinadorOVicerrector())
                        <div class="mb-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Estatus:</span>
                            @if ($user->estatus == 1)
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Activo</span>
                            @else
                                <span
                                    class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Inactivo</span>
                            @endif
                        </div>
                        @endif
                        <div class="flex justify-end space-x-4">
                            <button x-data
                                x-on:click="$dispatch('openModal', { component: 'usuario.show-usuario', arguments: { userId: {{ $user->id }} } })"
                                class="flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                                Ver
                            </button>
                            <button x-data
                                x-on:click="$dispatch('openModal', { component: 'usuario.update-usuario', arguments: { userId: {{ $user->id }} } })"
                                class="flex items-center gap-1 bg-yellow-600 text-white text-xs font-medium px-2.5 py-0.5 rounded hover:bg-yellow-700 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                </svg>
                                Editar
                            </button>
                            @if(auth()->user()?->esCoordinadorOVicerrector())
                            @if ($user->estatus == 1)
                                <button wire:click="cambiarEstatusUsuario({{ $user->id }})"
                                    class="flex items-center gap-1 bg-red-50 text-red-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-red-100 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800">
                                    <i class="fas fa-trash w-4 h-4 flex items-center justify-center"></i>
                                    Desactivar
                                </button>
                            @else
                                <button wire:click="cambiarEstatusUsuario({{ $user->id }})"
                                    class="flex items-center gap-1 bg-green-50 text-green-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-green-100 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Activar
                                </button>
                            @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 py-4 px-3 text-center text-gray-500 dark:text-gray-400">
                    No hay usuarios registrados.
                </div>
            @endif
        </div>

        <!-- Paginación -->
        <div
            class="flex flex-col md:flex-row items-center justify-between p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-4 md:mb-0">
                <select id="paginacion" wire:model.live="paginacion"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white w-24">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div>{{ $users->links() }}</div>
        </div>
    </div>
</div>
