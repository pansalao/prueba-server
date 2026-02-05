<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-500 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Planificaciones') }}
        </h2>
    </x-slot>

    <div class="sogat-table-container">
        <!-- Controles -->
        <div
            class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:items-center sm:justify-between p-4 bg-white dark:bg-gray-800">
            <!-- Búsqueda -->
            <input type="text" wire:model.live.debounce.300ms="busqueda"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                placeholder="Buscar planificación...">
        </div>

        <!-- Filtros -->
        <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                <div class="flex justify-center">
                    <a href="{{ route('planificacion.reporte.general', ['search' => $this->search]) }}" 
                        target="_blank"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2"
                        title="Ver reporte general en PDF (Nueva pestaña)">
                        <span class="material-icons text-white text-xl">picture_as_pdf</span>
                        Reporte PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabla en pantallas sm y mayores -->
        <div class="hidden sm:block">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">PNF / Trayecto</th>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">U. Curricular / Sección</th>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Docente</th>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">Estatus</th>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($planificaciones->isNotEmpty())
                        @foreach ($planificaciones as $planificacion)
                            <tr wire:key="{{ $planificacion->planificacion_id }}"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <!-- PNF / Trayecto -->
                                <td class="px-4 py-4 text-gray-900 dark:text-white">
                                    <div class="font-semibold">{{ $planificacion->nombre_pnf }}</div>
                                    <div class="text-xs text-gray-500">Trayecto {{ $planificacion->trayecto_unidad_curricular }}</div>
                                </td>
                                <!-- U. Curricular / Sección -->
                                <td class="px-4 py-4 text-gray-900 dark:text-white">
                                    <div class="font-semibold">{{ $planificacion->nombre_unidad_curricular }}</div>
                                    <div class="text-xs text-gray-500">Sección: {{ $planificacion->nombre_seccion }}</div>
                                </td>
                                <!-- Docente -->
                                <td class="px-4 py-4 text-gray-900 dark:text-white">
                                    {{ $planificacion->docente_nombre }} {{ $planificacion->docente_apellido }}
                                </td>
                                <!-- Estatus -->
                                <td class="px-4 py-4 text-right">
                                    @if ($planificacion->estatus == 1)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Aprobada</span>
                                    @elseif($planificacion->estatus == 2)
                                        <span class="bg-blue-100 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-200">Pendiente</span>
                                    @elseif($planificacion->estatus == 3)
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Rechazada</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-900 dark:text-gray-300">Desconocido</span>
                                    @endif
                                </td>
                                <!-- Acciones -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end space-x-4">
                                        <!-- Ver -->
                                        <a href="{{ route('planificacion/show', $planificacion->planificacion_id) }}"
                                            class="flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                            </svg>
                                            Ver
                                        </a>
                                        <!-- Editar -->
                                        <a href="{{ route('planificaciones.update', $planificacion->planificacion_id) }}"
                                            class="flex items-center gap-1 bg-yellow-600 text-white text-xs font-medium px-2.5 py-0.5 rounded hover:bg-yellow-700 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                            </svg>
                                            Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No hay planificaciones registradas.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Vista lista en pantallas xs -->
        <div class="sm:hidden">
            @if ($planificaciones->isNotEmpty())
                @foreach ($planificaciones as $planificacion)
                    <div wire:key="{{ $planificacion->planificacion_id }}"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 py-4 px-3 hover:bg-gray-50 dark:hover:bg-gray-600">
                        
                        <div class="mb-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">PNF / Trayecto:</span>
                            <div class="text-gray-900 dark:text-white">
                                {{ $planificacion->nombre_pnf }} - Trayecto {{ $planificacion->trayecto_unidad_curricular }}
                            </div>
                        </div>

                        <div class="mb-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">U.C. / Sección:</span>
                            <div class="text-gray-900 dark:text-white">
                                {{ $planificacion->nombre_unidad_curricular }} ({{ $planificacion->nombre_seccion }})
                            </div>
                        </div>

                        <div class="mb-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Docente:</span>
                            <span class="text-gray-900 dark:text-white">
                                {{ $planificacion->docente_nombre }} {{ $planificacion->docente_apellido }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Estatus:</span>
                            @if ($planificacion->estatus == 1)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Aprobada</span>
                            @elseif($planificacion->estatus == 2)
                                <span class="bg-blue-100 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-200">Pendiente</span>
                            @elseif($planificacion->estatus == 3)
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Rechazada</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-900 dark:text-gray-300">Desconocido</span>
                            @endif
                        </div>

                        <div class="flex justify-end space-x-4 mt-3">
                            <a href="{{ route('planificacion/show', $planificacion->planificacion_id) }}"
                                class="flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>
                                Ver
                            </a>
                            <a href="{{ route('planificaciones.update', $planificacion->planificacion_id) }}"
                                class="flex items-center gap-1 bg-yellow-600 text-white text-xs font-medium px-2.5 py-0.5 rounded hover:bg-yellow-700 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                </svg>
                                Editar
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 py-4 px-3 text-center text-gray-500 dark:text-gray-400">
                    No hay planificaciones registradas.
                </div>
            @endif
        </div>

        <!-- Paginación -->
        <div
            class="flex flex-col md:flex-row items-center justify-between p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-4 md:mb-0">
                <select id="perPage" wire:model.live="perPage"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white w-24">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div>{{ $planificaciones->links() }}</div>
        </div>
    </div>
</div>
