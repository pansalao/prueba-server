<div>
    <!-- Header de la página -->
    <x-slot name="header">
        <h2
            class="font-semibold text-xl text-center {{ $tema && $tema->estatus != 1 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-500' }} leading-tight uppercase">
            {{ $tema && $tema->estatus != 1 ? __('Detalles del Tema Inactivo') : __('Detalles del Tema') }}
        </h2>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                @if ($tema)
                            <!-- Grid de información -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <x-input-label value="Título del Tema:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">{{ $tema->titulo_tema }}</p>
                                </div>

                                <div>
                                    <x-input-label value="Unidad Curricular:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                        {{ $tema->nombre_unidad_curricular }}</p>
                                </div>

                                <div>
                                    <x-input-label value="Unidad (Corte):" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">Unidad
                                        {{ $tema->unidad_tema }}</p>
                                </div>

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

                                <div>
                                    <x-input-label value="Fecha de Creación:" />
                                    <p class="text-gray-700 dark:text-gray-300 text-2xl font-semibold">
                                        {{ \Carbon\Carbon::parse($tema->fecha_creacion)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="mb-6">
                                <x-input-label value="Descripción del Tema:" />
                                <p class="text-gray-700 dark:text-gray-300 text-xl italic leading-relaxed">
                                    {{ $tema->descripcion_tema ?: 'Sin descripción registrada.' }}
                                </p>
                            </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No se ha encontrado el tema...</p>
                @endif

                <!-- Botón Volver -->
                <div class="flex justify-end mt-6">
                    <a href="{{ route('tema/listar') }}">
                        <x-danger-button type="button">
                            <link rel="stylesheet"
                                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                            <span class="material-symbols-outlined">arrow_back</span>
                            {{ __('Volver') }}
                        </x-danger-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
```