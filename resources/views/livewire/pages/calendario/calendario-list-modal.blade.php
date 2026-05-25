{{-- Modal Listado de Eventos --}}
<div x-show="showListModal" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center px-4">
    
    <!-- Overlay -->
    <div @click="showListModal = false" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity">
    </div>

    <!-- Modal Content -->
    <div x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4 sm:translate-y-0"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-2xl w-full max-w-2xl border border-gray-200 dark:border-gray-700 relative z-10 flex flex-col max-h-[85vh]">
        
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 uppercase tracking-widest text-center flex items-center justify-center gap-2">
            <span class="material-icons text-blue-500">list_alt</span>
            {{ __('Eventos Asignados') }}
        </h3>
        
        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="material-icons text-gray-400 text-sm">search</span>
                </div>
                <input type="text" x-model="searchListQuery" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors" 
                    placeholder="Buscar evento por nombre...">
            </div>
        </div>
        
        <div class="overflow-y-auto flex-1 pr-2 space-y-3 custom-scrollbar">
            <template x-for="(evento, index) in eventosAlpine.filter(e => (e.nombre_evento || e.nombre || '').toLowerCase().includes(searchListQuery.toLowerCase()))" :key="index">
                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-100 dark:border-gray-600 shadow-sm hover:shadow-md transition-shadow flex flex-col sm:flex-row justify-between sm:items-center gap-3 relative overflow-hidden group">
                    
                    <!-- Color Line Indicator -->
                    <div class="absolute left-0 top-0 bottom-0 w-1.5" :style="'background-color: ' + (evento.codigo_color_evento || evento.color || '#6c757d')"></div>
                    
                    <div class="pl-2">
                        <h4 class="font-bold text-sm text-gray-800 dark:text-gray-200 uppercase tracking-wide" x-text="evento.nombre_evento || evento.nombre"></h4>
                    </div>
                    
                    <div class="flex items-center gap-3 bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 shadow-inner">
                        <div class="flex flex-col items-center">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Inicio</span>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300" x-text="evento.inicio"></span>
                        </div>
                        <span class="material-icons text-gray-300 dark:text-gray-500 text-sm">arrow_forward</span>
                        <div class="flex flex-col items-center">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Fin</span>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300" x-text="evento.fin"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Estados vacíos -->
            <div x-show="eventosAlpine.length === 0" class="text-center py-10 flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                <span class="material-icons text-5xl text-gray-300 dark:text-gray-600 mb-3">event_busy</span>
                <p class="font-bold uppercase tracking-wider text-sm">No hay eventos asignados</p>
                <p class="text-xs mt-1">Haga clic en los días del calendario para asignar eventos.</p>
            </div>
            
            <div x-show="eventosAlpine.length > 0 && eventosAlpine.filter(e => (e.nombre_evento || e.nombre || '').toLowerCase().includes(searchListQuery.toLowerCase())).length === 0" class="text-center py-10 flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                <span class="material-icons text-5xl text-gray-300 dark:text-gray-600 mb-3">search_off</span>
                <p class="font-bold uppercase tracking-wider text-sm">No se encontraron resultados</p>
                <p class="text-xs mt-1">Intente buscar con otro nombre.</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
            <x-secondary-button type="button" @click="showListModal = false" class="uppercase tracking-widest text-xs">
                Cerrar
            </x-secondary-button>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #475569;
    }
</style>
