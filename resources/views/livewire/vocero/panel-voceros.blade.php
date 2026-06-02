<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight uppercase text-center">
            {{ __('GESTIÓN DE VOCEROS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sogat-card">
                <div>

                    <x-table.alert-message />

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase mb-2">SELECCIONE A LOS ESTUDIANTES QUE VAN A SER VOCEROS</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Seleccione a un estudiante para que sea el vocero principal de su sección. y dos ayudantes de vocero</p>
                        
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="w-full">
                                <x-input 
                                    label="Buscar" 
                                    name="search" 
                                    placeholder="CÉDULA..." 
                                    wire:model.live.debounce.300ms="search" 
                                    maxlength="12"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                />
                            </div>
                            
                            <div class="w-full">
                                <x-select 
                                    label="Filtrar por Trayecto" 
                                    wireModel="trayectoSeleccionado" 
                                    :options="$trayectosDisponibles" 
                                    valueField="id" 
                                    textField="nombre" 
                                    placeholder="TODOS LOS TRAYECTOS" 
                                />
                            </div>

                            <div class="w-full">
                                <x-select 
                                    label="Filtrar por Sección" 
                                    wireModel="seccionSeleccionada" 
                                    :options="$seccionesDisponibles" 
                                    valueField="codigo" 
                                    textField="nombre" 
                                    placeholder="TODAS LAS SECCIONES" 
                                />
                            </div>
                        </div>

                        <div class="space-y-6">
                            @forelse($secciones as $seccion)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
                                    <div class="flex justify-between items-center mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                                        <h4 class="font-bold text-md text-blue-600 dark:text-blue-400 uppercase">
                                            Sección: {{ $seccion['sec_nombre'] }}
                                        </h4>
                                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase">{{ $seccion['trayecto_nombre'] }}</span>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-100 dark:bg-gray-800">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Cédula</th>
                                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Estudiante</th>
                                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Estado Actual</th>
                                                    <th class="px-4 py-2 text-right text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($seccion['estudiantes'] as $est)
                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $est['per_cedula'] }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $est['per_nombres'] }} {{ $est['per_apellidos'] }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                            @if($est['es_vocero'])
                                                                <div class="flex flex-col">
                                                                    @if($est['tipo_vocero'] == 1)
                                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200 w-max">Vocero Principal</span>
                                                                    @elseif($est['tipo_vocero'] == 2)
                                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200 w-max">Vocero Secundario</span>
                                                                    @elseif($est['tipo_vocero'] == 3)
                                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-200 w-max">Vocero Terciario</span>
                                                                    @endif
                                                                    <span class="text-[10px] text-gray-600 dark:text-gray-400 mt-1 font-bold" title="Fecha de asignación">
                                                                        <span class="material-icons text-[10px] align-middle font-bold">calendar_today</span>
                                                                        {{ $est['fecha_asignacion'] }}
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <span class="text-gray-700 dark:text-gray-300 text-xs font-bold">Estudiante</span>
                                                            @endif
                                                            </td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                                            @if(!$est['es_vocero'])
                                                                @if(count($seccion['tipos_ocupados']) >= 3)
                                                                    <span class="text-[11px] text-red-600 font-bold uppercase border border-red-200 bg-red-50 px-2 py-1 rounded" title="Debe revocar un vocero para asignar otro.">Límite (3/3)</span>
                                                                @else
                                                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                                                        <button @click="open = !open" @click.away="open = false" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-md text-xs font-bold uppercase transition-colors flex items-center">
                                                                            Asignar <span class="material-icons text-sm ml-1">arrow_drop_down</span>
                                                                        </button>
                                                                        <div x-show="open" style="display: none;" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10">
                                                                            <div class="py-1" role="menu" aria-orientation="vertical">
                                                                                @if(!in_array(1, $seccion['tipos_ocupados']))
                                                                                    <button wire:click="confirmarAsignar('{{ $est['per_cedula'] }}', {{ $seccion['sec_codigo'] }}, 1)" class="block w-full text-left px-4 py-2 text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Como Principal</button>
                                                                                @endif
                                                                                @if(!in_array(2, $seccion['tipos_ocupados']))
                                                                                    <button wire:click="confirmarAsignar('{{ $est['per_cedula'] }}', {{ $seccion['sec_codigo'] }}, 2)" class="block w-full text-left px-4 py-2 text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Como Secundario</button>
                                                                                @endif
                                                                                @if(!in_array(3, $seccion['tipos_ocupados']))
                                                                                    <button wire:click="confirmarAsignar('{{ $est['per_cedula'] }}', {{ $seccion['sec_codigo'] }}, 3)" class="block w-full text-left px-4 py-2 text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Como Terciario</button>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <button wire:click="confirmarQuitar({{ $seccion['sec_codigo'] }}, {{ $est['tipo_vocero'] }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-md text-xs font-bold uppercase transition-colors" title="Quitar rol de vocero">
                                                                    Revocar
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <span class="material-icons text-4xl mb-2">info</span>
                                    <p>No se encontraron secciones o estudiantes.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
