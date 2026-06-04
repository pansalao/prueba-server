{{-- Modal de Justificaciones Requeridas (fuera de cualquier x-show para que siempre sea visible) --}}
@if($mostrarModalJustificacion)
<div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl max-w-xl w-full overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="h-20 flex items-center justify-center bg-gradient-to-br from-yellow-400 to-amber-600">
            <div class="bg-white/20 backdrop-blur-md rounded-full p-3">
                <span class="material-icons text-white text-4xl">warning</span>
            </div>
        </div>
        <div class="p-6">
            <h3 class="text-xl font-bold text-center mb-4 text-gray-800 dark:text-gray-200">
                Justificaciones Requeridas
            </h3>
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                @foreach($justificacionesRequeridas as $index => $req)
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
                        <h4 class="font-bold text-gray-700 dark:text-gray-300">{{ $req['titulo'] }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $req['mensaje'] }}</p>
                        <textarea
                            wire:model="justificacionesRequeridas.{{ $index }}.texto"
                            class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-yellow-500"
                            rows="3"
                            placeholder="Escriba su justificación aquí..."
                        ></textarea>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <x-secondary-button wire:click="$set('mostrarModalJustificacion', false)">
                    Cancelar
                </x-secondary-button>
                <x-primary-button wire:click="confirmarJustificaciones" class="bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-600 text-white border-transparent">
                    Guardar y Continuar
                </x-primary-button>
            </div>
        </div>
    </div>
</div>
@endif
