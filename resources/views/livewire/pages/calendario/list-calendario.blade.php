<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Calendario Académico') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 sm:rounded-lg">
            <x-table.alert-message type="success" :message="session('message')" />
            <x-table.alert-message type="error" :message="session('error')" />

            <div class="sogat-table-container">
                <div
                    class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:items-center sm:justify-between p-4 bg-white dark:bg-gray-800">
                    <x-table.search-input model="busqueda" placeholder="Buscar calendario..." debounce="300ms" />
                </div>

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Semana</th>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Inicio</th>
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white">Fin</th>
                            @can('cambiar-estatus-calendario')
                                <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">
                                    Estatus</th>
                            @endcan
                            <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">
                                Acciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if ($calendarios->isNotEmpty())
                            @foreach ($calendarios as $calendario)
                                <tr wire:key="{{ $calendario->id_calendario_academico }}"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-4 text-gray-900 dark:text-white">L1:
                                        {{ $calendario->semana_lapso_uno_calendario_academico }} - L2:
                                        {{ $calendario->semana_lapso_dos_calendario_academico }}</td>
                                    <td class="px-4 py-4 text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($calendario->dia_inicio_calendario_academico)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-4 text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($calendario->dia_fin_calendario_academico)->format('d/m/Y') }}
                                    </td>
                                    @can('cambiar-estatus-calendario')
                                                                <td class="px-4 py-4 text-right">
                                                                    @php
                                                                        $statusClasses = [
                                                                            '1' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                                            '2' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                                            '3' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                                            '4' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                                        ];
                                                                        $statusLabels = [
                                                                            '1' => 'Activo',
                                                                            '2' => 'En revisión',
                                                                            '3' => 'Inactivo',
                                                                            '4' => 'En Espera / Incompleto',
                                                                        ];
                                                                    @endphp
                                        <span
                                                                        class="{{ $statusClasses[$calendario->estatus] ?? $statusClasses['3'] }} text-xs font-medium px-2.5 py-0.5 rounded">
                                                                        {{ $statusLabels[$calendario->estatus] ?? 'Inactivo' }}
                                                                    </span>
                                                                </td>
                                    @endcan
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end space-x-3">
                                            @if($calendario->estatus == 1)
                                                <a href="{{ route('calendario.notas', $calendario->id_calendario_academico) }}"
                                                    class="flex items-center gap-1 bg-indigo-500 text-white text-xs font-medium px-2.5 py-1 rounded-md hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 shadow-sm transition-colors"
                                                    title="Agregar notas al calendario">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>
                                                    Notas
                                                </a>

                                                <a href="{{ route('calendario.reporte.especifico', $calendario->id_calendario_academico) }}"
                                                    class="flex items-center gap-1 bg-green-600 text-white text-xs font-medium px-2.5 py-1 rounded-md hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 shadow-sm transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>
                                                    Reporte
                                                </a>
                                            @endif

                                            @if ($calendario->estatus == 2)
                                                @can('cambiar-estatus-calendario')
                                                    <a href="{{ route('calendario.editar', $calendario->id_calendario_academico) }}"
                                                        class="flex items-center gap-1 bg-amber-500 text-white text-xs font-medium px-2.5 py-1 rounded-md hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 shadow-sm transition-colors">
                                                        <span class="material-icons text-sm">edit</span>
                                                        Editar
                                                    </a>
                                                @endcan
                                            @endif

                                            @if ($calendario->estatus == 4)
                                                <a href="{{ route('calendario.create', $calendario->id_calendario_academico) }}"
                                                    class="flex items-center gap-1 bg-orange-500 text-white text-xs font-medium px-2.5 py-1 rounded-md hover:bg-orange-600 dark:bg-orange-500 dark:hover:bg-orange-600 shadow-sm transition-colors">
                                                    <span class="material-icons text-sm">play_arrow</span>
                                                    Continuar
                                                </a>
                                            @endif

                                            @if(!empty($calendario->justificativo_calendario_academico))
                                                <a href="{{ route('calendario.justificaciones', $calendario->id_calendario_academico) }}"
                                                    class="flex items-center gap-1 bg-orange-500 text-white text-xs font-medium px-2.5 py-1 rounded-md hover:bg-orange-600 dark:bg-orange-500 dark:hover:bg-orange-600 shadow-sm transition-colors">
                                                    Atenuantes
                                                </a>
                                            @endif
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                        @else
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="{{ auth()->user()->can('cambiar-estatus-calendario') ? 5 : 4 }}"
                                    class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ $busqueda ? 'No se encontraron registros' : 'No hay semanas registradas' }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>

            <div
                class="flex flex-col md:flex-row items-center justify-between p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4 md:mb-0">
                    <select wire:model.live="paginacion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white w-24">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                    </select>
                </div>
                <div>{{ $calendarios->links() }}</div>
            </div>
        </div>
    </div>
</div>