<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            @if($sistemaInactivo)
                {{ __('Sistema Inactivo') }}
            @elseif(!$hayCalendarioActivo && $tieneRol3)
                {{ __('Configurar Calendario Académico') }}
            @else
                {{ __('Selección de Perfil') }}
            @endif
        </h2>
    </x-slot>

    <div class="pt-8 pb-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensajes flash --}}
            @if (session()->has('message'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            {{-- CASO 1: Sistema inactivo (usuario sin rol 3 y sin calendario activo) --}}
            @if($sistemaInactivo)
                <div class="sogat-card planificacion-module p-10">
                    <div class="text-center">
                        <div class="mx-auto mb-6 flex items-center justify-center w-20 h-20 rounded-full bg-red-100 dark:bg-red-900/40">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-red-600 dark:text-red-400 uppercase tracking-wide mb-3">
                            El sistema se encuentra inactivo
                        </h3>
                    </div>
                </div>

            {{-- CASO 2: No hay calendario activo pero el usuario tiene rol 3 → Formulario de creación --}}
            @elseif(!$hayCalendarioActivo && $tieneRol3)
                <div class="sogat-card planificacion-module p-10">
                    <div class="text-center mb-8">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-400 mb-2">
                            Hola rector, debe configurar el período académico antes de continuar.
                        </h3>
                    </div>

                    <form wire:submit="guardarCalendario" class="space-y-6">
                        <div>
                            <label for="dia_inicio" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Fecha de Inicio del Calendario
                            </label>
                            <input type="date" id="dia_inicio"
                                wire:model="dia_inicio_calendario_academico"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-gray-200 focus:border-[#265dcf] focus:ring-2 focus:ring-[#265dcf]/20 transition-all duration-300">
                            @error('dia_inicio_calendario_academico')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="dia_fin" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Fecha de Fin del Calendario
                            </label>
                            <input type="date" id="dia_fin"
                                wire:model="dia_fin_calendario_academico"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-gray-200 focus:border-[#265dcf] focus:ring-2 focus:ring-[#265dcf]/20 transition-all duration-300">
                            @error('dia_fin_calendario_academico')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <x-primary-button type="submit" class="w-full justify-center py-3 normal-case tracking-normal">
                            <span wire:loading.remove wire:target="guardarCalendario">Crear Calendario Académico</span>
                            <span wire:loading wire:target="guardarCalendario">Creando...</span>
                        </x-primary-button>
                    </form>
                </div>

            {{-- CASO 3: Hay calendario activo → Selección de rol normal --}}
            @else
                <div class="sogat-card planificacion-module p-10">
                    <div class="text-center mb-10">
                        <h3 class="text-sm font-bold dark:text-gray-500 uppercase tracking-[0.2em]">
                            ¿Con qué rol deseas navegar hoy?
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        @if(isset($misRoles) && count($misRoles) > 0)
                            @foreach ($misRoles as $miRol)
                                <div wire:click="seleccionarRol({{ $miRol->usu_cod_rol }})"
                                    class="cursor-pointer group relative p-5 rounded-xl border-2 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 hover:border-sogat-red hover:shadow-md transition-all duration-300">

                                    <div class="flex items-center justify-between">
                                        <span class="text-base font-extrabold text-black dark:text-white uppercase transition-colors">
                                            {{ $miRol->rol_nombre }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
