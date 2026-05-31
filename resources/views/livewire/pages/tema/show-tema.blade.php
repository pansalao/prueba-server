<div>
    <!-- Header de la página -->
    <x-slot name="header">
        <h2
            class="font-semibold text-xl text-center {{ $tema && $tema->estatus != 1 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-500' }} leading-tight uppercase">
            {{ $tema && $tema->estatus != 1 ? __('Detalles del Tema Inactivo') : __('Detalles del Tema') }}
        </h2>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card">

                @if ($tema)
                            <!-- Grid de información -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <x-input-label value="Título del Tema:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold break-words overflow-hidden">{{ $tema->titulo_tema }}</p>
                                </div>

                                <div>
                                    <x-input-label value="Unidad Curricular:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold break-words overflow-hidden">
                                        {{ $tema->nombre_unidad_curricular }}</p>
                                </div>

                                <div>
                                    <x-input-label value="Corte:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                         Corte {{ $tema->unidad_tema }}</p>
                                </div>

                                @if(auth()->user()?->esCoordinadorOVicerrector())
                                <div>
                                    <x-input-label value="Estatus:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                        <span
                                            class="{{ $tema->estatus == 1
                    ? 'px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-600 dark:text-green-100'
                    : 'px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-600 dark:text-red-100' }}">
                                            {{ $tema->estatus == 1 ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </p>
                                </div>
                                @endif

                            </div>

                            <!-- Sección de Objetivos -->
                            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-tight mb-4">
                                    {{ __('Objetivos Asociados') }}
                                </h4>
                                @if(isset($tema->objetivos) && count($tema->objetivos) > 0)
                                    <ul class="space-y-3">
                                        @foreach($tema->objetivos as $objetivo)
                                            <li class="flex items-start gap-3 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                                <span class="material-icons text-sogat-red text-sm mt-1">lens</span>
                                                <span class="text-gray-700 dark:text-gray-300 font-medium uppercase">{{ $objetivo->titulo_objetivo }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500 italic">No hay objetivos registrados para este tema.</p>
                                @endif
                            </div>

                @else
                    <p class="text-gray-500 dark:text-gray-400">No se ha encontrado el tema...</p>
                @endif

                <!-- Botón Volver -->
                <div class="flex justify-end mt-8">
                    <a href="{{ route('tema/listar') }}">
                        <x-danger-button type="button" class="flex items-center gap-2">
                            <span class="material-icons">arrow_back</span>
                            {{ __('Volver') }}
                        </x-danger-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
