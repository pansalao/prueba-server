<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sogat-card planificacion-module">

                {{-- Sección de Roles --}}
                <div class="space-y-6">
                    {{-- Header removido --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($misRoles as $miRol)
                                            <div wire:click="cambiarRol({{ $miRol->usu_cod_rol }})"
                                                class="cursor-pointer group relative p-5 rounded-xl border-2 transition-all duration-300 shadow-sm
                                                     {{ $rolActivo == $miRol->usu_cod_rol
                            ? 'bg-white dark:bg-gray-800 border-sogat-red ring-2 ring-sogat-red/30'
                            : 'bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 hover:border-sogat-red hover:shadow-md' }}">

                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="text-base font-extrabold text-black dark:text-white uppercase transition-colors">
                                                        {{ $miRol->rol_nombre }}
                                                    </span>

                                                    @if($rolActivo == $miRol->usu_cod_rol)
                                                        <span class="material-icons text-gray-500">check</span>
                                                    @endif
                                                </div>
                                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>