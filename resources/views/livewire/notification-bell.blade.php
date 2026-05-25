<div x-data="{ open: false, visto: false }" class="relative" wire:poll.60s="loadNotifications" wire:ignore.self>
    <button wire:key="bell-button" @click="open = !open; visto = true" class="relative p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors focus:outline-none">
        <span class="material-icons">notifications</span>
        @if(count($planificacionesAceptadas) > 0)
            <span x-show="!visto" class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                {{ count($planificacionesAceptadas) }}
            </span>
        @endif
    </button>

    <div wire:key="bell-dropdown" x-show="open" x-cloak @click.away="open = false" class="absolute right-0 top-full mt-2 w-[200px] bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden origin-top-right">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Notificaciones</h3>
        </div>
        <div class="max-h-60 overflow-y-auto">
            @forelse($planificacionesAceptadas as $planificacion)
                <button type="button" wire:click="markAsRead({{ $planificacion->id_planificacion }})" class="w-full text-left px-4 py-3 border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer group">
                    <p class="text-xs text-gray-800 dark:text-gray-200 font-medium">
                        @if($planificacion->estatus == 1)
                            ¡Tu planificación ha sido <span class="text-green-500 font-bold uppercase">Aceptada</span>!
                        @else
                            ¡Tu planificación requiere <span class="text-red-500 font-bold uppercase">Modificaciones</span>!
                        @endif
                    </p>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400">
                            {{ $planificacion->ucu_nombre }} - Sec: {{ $planificacion->sec_nombre }}
                        </p>
                        <span class="material-icons text-[14px] text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity" title="Marcar como leída">check_circle</span>
                    </div>
                    @if($planificacion->estatus == 3)
                        <p class="text-[9px] text-red-400 italic mt-1">
                            Revisa los motivos en el panel de gestión.
                        </p>
                    @endif
                </button>
            @empty
                <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 text-xs">
                    No tienes notificaciones nuevas.
                </div>
            @endforelse
        </div>
    </div>
</div>
