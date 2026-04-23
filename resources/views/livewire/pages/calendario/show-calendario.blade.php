<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Detalles de Semana') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">
                <!-- Grid de información - 3 columnas como en Recurso -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

                    {{-- Semana --}}
                    <div>
                        <x-input-label value="Número de Semana:" />
                        <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                            Semana {{ $calendario->semana_calendario_academico }}
                        </p>
                    </div>

                    {{-- Estatus --}}
                    <div>
                        <x-input-label value="Estatus:" />
                        <p class="mt-1">
                            <span class="{{ $calendario->estatus == 1
    ? 'px-3 py-1 text-lg font-bold text-green-700 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300'
    : 'px-3 py-1 text-lg font-bold text-red-700 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300' }}">
                                {{ $calendario->estatus == 1 ? 'Activo' : 'Inactivo' }}
                            </span>
                        </p>
                    </div>

                    {{-- Fecha Inicio --}}
                    <div>
                        <x-input-label value="Fecha de Inicio:" />
                        <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                            {{ \Carbon\Carbon::parse($calendario->dia_inicio_calendario_academico)->format('d/m/Y') }}
                        </p>
                    </div>

                    {{-- Fecha Fin --}}
                    <div>
                        <x-input-label value="Fecha de Finalización:" />
                        <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                            {{ \Carbon\Carbon::parse($calendario->dia_fin_calendario_academico)->format('d/m/Y') }}
                        </p>
                    </div>

                    </div>
                </div>

                {{-- Associated Events --}}
                <div class="mt-8 border-t pt-4 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 uppercase">Eventos en esta Semana</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Evento</th>
                                    <th class="px-4 py-3 text-center">Color</th>
                                    <th class="px-4 py-3">Inicio</th>
                                    <th class="px-4 py-3">Fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($calendario->detalles as $detalle)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                            {{ $detalle->evento->descripcion_evento ?? 'Sin descripción' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($detalle->evento && $detalle->evento->color)
                                                <div class="w-4 h-4 mx-auto rounded-full" style="background-color: {{ $detalle->evento->color }}"></div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($detalle->dia_inicio_detalle_evento)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($detalle->dia_fin_detalle_evento)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">No hay eventos vinculados a esta semana.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex justify-end gap-4 pt-8">
                    <a href="{{ route('calendario.list') }}" wire:navigate>
                        <x-danger-button type="button" class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            {{ __('Volver') }}
                        </x-danger-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>