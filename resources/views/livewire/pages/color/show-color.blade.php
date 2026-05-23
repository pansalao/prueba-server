<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Detalles del Color') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="space-y-6">
                        
                        <!-- Header with name and status -->
                        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $color->nombre_color }}</h3>
                            <span class="{{ $color->estatus == 1 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }} text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $color->estatus == 1 ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Código Hexadecimal</h4>
                                <div class="flex items-center gap-3">
                                    <span class="w-12 h-12 rounded-lg border border-gray-300 dark:border-gray-600 shadow-md" style="background-color: {{ $color->codigo_color }}"></span>
                                    <span class="text-lg font-mono text-gray-900 dark:text-gray-100">{{ strtoupper($color->codigo_color) }}</span>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('color.list') }}" wire:navigate class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition">
                                Volver a la Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
