<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Asignación de Permisos') }}
        </h2>
    </x-slot>

    <div class="sogat-card planificacion-module">
        <form wire:submit.prevent="savePermisos">
            <div class="space-y-6">

                {{-- Header / Información General --}}
                <div class="flex justify-between items-start border-b pb-4 mb-4 dark:border-gray-700">
                    <div>
                        <h3
                            class="text-xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-tight flex items-center gap-2">
                            <span class="material-icons text-black dark:text-gray-300">manage_accounts</span>
                            Permiso para: {{ $elemento->rol_nombre }}
                        </h3>
                    </div>
                </div>

                <!-- Panel de Módulos (Estilo acordeones) -->
                <div class="space-y-4" x-data="{ openModule: null }">
                    @foreach ($modulosPermisos as $moduleName => $permisos)
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm transition-all duration-300">

                            <!-- Cabecera del Accordion -->
                            <button type="button"
                                class="w-full flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                                @click="openModule = openModule === '{{ Str::slug($moduleName) }}' ? null : '{{ Str::slug($moduleName) }}'">
                                <div class="flex items-center gap-3">
                                    @php
                                        $moduleIds = array_column($permisos, 'id');
                                        $isFull = !empty($moduleIds) && empty(array_diff($moduleIds, $selectedPermisos));
                                    @endphp
                                    <div @click.stop>
                                        <input type="checkbox" 
                                            wire:click="toggleModule('{{ $moduleName }}')"
                                            {{ $isFull ? 'checked' : '' }}
                                            class="w-5 h-5 text-black border-gray-300 rounded focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 transition-all cursor-pointer">
                                    </div>
                                    <h3
                                        class="text-lg font-semibold text-gray-800 dark:text-gray-100 uppercase tracking-wider">
                                        {{ $moduleName }}
                                    </h3>
                                </div>
                                <div class="flex items-center gap-4">
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300"
                                        :class="openModule === '{{ Str::slug($moduleName) }}' ? 'rotate-180' : ''"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            <!-- Contenido del Accordion -->
                            <div x-show="openModule === '{{ Str::slug($moduleName) }}'" x-collapse>
                                <div
                                    class="p-6 bg-white dark:bg-gray-800 space-y-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                        @foreach ($permisos as $permiso)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <div class="relative flex items-center">
                                                    <input type="checkbox" wire:model.defer="selectedPermisos"
                                                        value="{{ $permiso['id'] }}"
                                                        class="w-5 h-5 text-black border-gray-300 rounded focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 transition-all cursor-pointer peer">
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                        {{ $permiso['action'] }}
                                                    </span>
                                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">
                                                        ID: {{ $permiso['id'] }}
                                                    </span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end gap-4 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('permiso/listar') }}" wire:navigate
                        class="inline-flex font-semibold items-center px-4 py-2 bg-[#f0f0f0] border border-[#767676] rounded-lg text-sm text-black uppercase tracking-widest hover:bg-gray-200 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex font-semibold items-center px-5 py-2.5 bg-[#f0f0f0] border border-[#767676] rounded-lg font-medium text-sm text-black uppercase tracking-widest hover:bg-gray-200 focus:outline-none transition ease-in-out duration-150 disabled:bg-gray-300 disabled:opacity-75 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i> GUARDAR PERMISOS
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>