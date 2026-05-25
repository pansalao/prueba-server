<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-300 leading-tight uppercase text-center">
            {{ __('Estadísticas de Entrega de Planificaciones') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. Tarjetas de Resumen (KPIs) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card A Tiempo -->
                <div class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-xl border-2 border-green-500 shadow-sm transition-all duration-300 hover:scale-[1.02]">
                    <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-900">
                        <span class="material-icons text-3xl">check_circle</span>
                    </div>
                    <div>
                        <p class="mb-1 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total A Tiempo</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $totalATiempo }}</p>
                        <p class="text-[11px] text-green-600 dark:text-green-400 font-semibold mt-1">Entregas dentro de la fecha límite</p>
                    </div>
                </div>

                <!-- Card Atrasados -->
                <div class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-xl border-2 border-amber-500 shadow-sm transition-all duration-300 hover:scale-[1.02]">
                    <div class="p-3 mr-4 text-amber-500 bg-amber-100 rounded-full dark:text-amber-100 dark:bg-amber-900">
                        <span class="material-icons text-3xl">warning</span>
                    </div>
                    <div>
                        <p class="mb-1 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Atrasados</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $totalAtrasados }}</p>
                        <p class="text-[11px] text-amber-600 dark:text-amber-400 font-semibold mt-1">Entregas posterior a la fecha límite</p>
                    </div>
                </div>

                <!-- Card Pendientes / Vencidos -->
                <div class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-xl border-2 border-red-500 shadow-sm transition-all duration-300 hover:scale-[1.02]">
                    <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-900">
                        <span class="material-icons text-3xl">error</span>
                    </div>
                    <div>
                        <p class="mb-1 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pendientes</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $totalPendientes }}</p>
                        <p class="text-[11px] text-red-600 dark:text-red-400 font-semibold mt-1">
                            {{ $totalPendientesSolo }} activos | <span class="font-extrabold text-red-700 dark:text-red-300">{{ $totalVencidosSolo }} no entregados (vencidos)</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- 2. Filtros y Tabla Detalle --}}
            <div class="sogat-table-container bg-white dark:bg-gray-800">
                <!-- Formulario de Filtros -->
                <form method="GET" action="{{ route('planificacion.reporte.cumplimiento') }}" class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        <!-- Filtro Buscar Docente -->
                        <div class="md:col-span-5">
                            <label for="docente" class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Buscar Docente</label>
                            <div class="relative">
                                <input type="text" id="docente" name="docente" value="{{ $filtroDocente }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sogat-red focus:border-sogat-red block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Nombre, apellido o cédula...">
                            </div>
                        </div>

                        <!-- Filtro Periodo Académico -->
                        <div class="md:col-span-4">
                            <label for="periodo" class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Calendario Académico (Límite)</label>
                            <select id="periodo" name="periodo" onchange="this.form.submit()"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sogat-red focus:border-sogat-red block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @if($calendarios->isEmpty())
                                    <option value="">No hay calendarios registrados</option>
                                @else
                                    @foreach($calendarios as $cal)
                                        <option value="{{ $cal->id_calendario_academico }}" 
                                            {{ $calendarioSeleccionado && $calendarioSeleccionado->id_calendario_academico == $cal->id_calendario_academico ? 'selected' : '' }}>
                                            Semanas: {{ $cal->semana_calendario_academico }} ({{ \Carbon\Carbon::parse($cal->dia_inicio_calendario_academico)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($cal->dia_fin_calendario_academico)->format('d/m/Y') }})
                                            @if($cal->estatus == 1) [ACTIVO] @endif
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="md:col-span-3 flex gap-2">
                            <button type="submit" class="flex-1 inline-flex font-bold items-center justify-center px-4 py-2.5 bg-sogat-blue text-white rounded-lg text-xs uppercase tracking-widest hover:bg-blue-700 focus:outline-none transition ease-in-out duration-150 shadow-sm gap-2">
                                <span class="material-icons text-sm">search</span>
                                Filtrar
                            </button>
                            <a href="{{ route('planificacion.reporte.cumplimiento') }}" class="flex-1 inline-flex font-bold items-center justify-center px-4 py-2.5 bg-[#f0f0f0] border border-[#767676] rounded-lg text-xs text-black uppercase tracking-widest hover:bg-gray-200 focus:outline-none transition ease-in-out duration-150 shadow-sm gap-2">
                                <span class="material-icons text-sm">clear</span>
                                Limpiar
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Tabla Detalle (Pantallas Medianas y Mayores) -->
                <div class="hidden sm:block">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Docente</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Planificación (U.C. / Sección)</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 dark:text-white text-center">Fecha Límite</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 dark:text-white text-center">Fecha de Entrega</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 dark:text-white text-center">Estado</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-gray-900 dark:text-white text-right">Días de Diferencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($docentes->isNotEmpty())
                                @foreach ($docentes as $row)
                                    <tr class="bg-white border-b dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <!-- Docente -->
                                        <td class="px-4 py-4 text-gray-900 dark:text-white font-medium">
                                            <div>{{ $row->docente_nombres }} {{ $row->docente_apellidos }}</div>
                                            <div class="text-[11px] text-gray-400 font-bold tracking-tight">C.I. {{ $row->docente_cedula }}</div>
                                        </td>
                                        <!-- Planificación (U.C. / Sección) -->
                                        <td class="px-4 py-4 text-gray-900 dark:text-white">
                                            <div class="font-bold text-xs text-gray-700 dark:text-gray-300">{{ $row->nombre_unidad_curricular }}</div>
                                            <div class="text-xs text-gray-400">Sección: {{ $row->nombre_seccion }}</div>
                                        </td>
                                        <!-- Fecha Límite -->
                                        <td class="px-4 py-4 text-center font-bold text-xs">
                                            {{ isset($fechaLimite) ? \Carbon\Carbon::parse($fechaLimite)->format('d/m/Y') : 'N/D' }}
                                        </td>
                                        <!-- Fecha de Entrega -->
                                        <td class="px-4 py-4 text-center text-xs">
                                            @if($row->fecha_entrega)
                                                <span class="font-bold text-gray-700 dark:text-gray-300">
                                                    {{ \Carbon\Carbon::parse($row->fecha_entrega)->format('d/m/Y') }}
                                                </span>
                                                <div class="text-[10px] text-gray-400 font-medium">
                                                    {{ \Carbon\Carbon::parse($row->fecha_entrega)->format('h:i A') }}
                                                </div>
                                            @else
                                                <span class="text-gray-400 font-semibold italic">No entregado</span>
                                            @endif
                                        </td>
                                        <!-- Estado -->
                                        <td class="px-4 py-4 text-center">
                                            @if ($row->estado === 'A tiempo')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                    A Tiempo
                                                </span>
                                            @elseif ($row->estado === 'Atrasado')
                                                <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-amber-900 dark:text-amber-300">
                                                    Atrasado
                                                </span>
                                            @elseif ($row->estado === 'Pendiente')
                                                <span class="bg-blue-100 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-200">
                                                    Pendiente
                                                </span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                                    Vencido
                                                </span>
                                            @endif
                                        </td>
                                        <!-- Días de Diferencia -->
                                        <td class="px-4 py-4 text-right text-xs font-bold">
                                            @if ($row->estado === 'A tiempo')
                                                <span class="text-green-600 dark:text-green-400">{{ $row->dias_diferencia_texto }}</span>
                                            @elseif ($row->estado === 'Atrasado')
                                                <span class="text-amber-600 dark:text-amber-400">{{ $row->dias_diferencia_texto }}</span>
                                            @elseif ($row->estado === 'Pendiente')
                                                <span class="text-blue-600 dark:text-blue-400">{{ $row->dias_diferencia_texto }}</span>
                                            @else
                                                <span class="text-red-600 dark:text-red-400">{{ $row->dias_diferencia_texto }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No se encontraron registros de entregas para los filtros seleccionados.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Tabla Detalle (Pantallas Móviles) -->
                <div class="block sm:hidden divide-y divide-gray-200 dark:divide-gray-700">
                    @if ($docentes->isNotEmpty())
                        @foreach ($docentes as $row)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 space-y-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white">
                                            {{ $row->docente_nombres }} {{ $row->docente_apellidos }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase">C.I. {{ $row->docente_cedula }}</div>
                                    </div>
                                    <div>
                                        @if ($row->estado === 'A tiempo')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                A Tiempo
                                            </span>
                                        @elseif ($row->estado === 'Atrasado')
                                            <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-amber-900 dark:text-amber-300">
                                                Atrasado
                                            </span>
                                        @elseif ($row->estado === 'Pendiente')
                                            <span class="bg-blue-100 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-200">
                                                Pendiente
                                            </span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                                Vencido
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Planificación:</span> {{ $row->nombre_unidad_curricular }} (Sección {{ $row->nombre_seccion }})
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-gray-400">Límite:</span> 
                                        <span class="font-bold text-gray-700 dark:text-gray-300">
                                            {{ isset($fechaLimite) ? \Carbon\Carbon::parse($fechaLimite)->format('d/m/Y') : 'N/D' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400">Entrega:</span> 
                                        <span class="font-bold text-gray-700 dark:text-gray-300">
                                            {{ $row->fecha_entrega ? \Carbon\Carbon::parse($row->fecha_entrega)->format('d/m/Y') : 'No entregado' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-xs flex justify-between pt-1 border-t border-dashed border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-400">Diferencia:</span>
                                    @if ($row->estado === 'A tiempo')
                                        <span class="text-green-600 dark:text-green-400 font-bold">{{ $row->dias_diferencia_texto }}</span>
                                    @elseif ($row->estado === 'Atrasado')
                                        <span class="text-amber-600 dark:text-amber-400 font-bold">{{ $row->dias_diferencia_texto }}</span>
                                    @elseif ($row->estado === 'Pendiente')
                                        <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $row->dias_diferencia_texto }}</span>
                                    @else
                                        <span class="text-red-600 dark:text-red-400 font-bold">{{ $row->dias_diferencia_texto }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            No se encontraron registros de entregas para los filtros seleccionados.
                        </div>
                    @endif
                </div>

                <!-- Paginación -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    {{ $docentes->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
