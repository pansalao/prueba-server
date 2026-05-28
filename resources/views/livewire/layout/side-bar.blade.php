<aside id="sidebar" class="bg-white dark:bg-gray-800 w-[234px] lg:static lg:h-auto lg:shadow-none lg:translate-x-0
              h-full overflow-y-auto absolute top-0 left-0 shadow-lg border-r border-gray-200 dark:border-gray-700
              transition-transform duration-300 ease-in-out z-40 flex flex-col
              {{ $isOpen ? 'translate-x-0' : '-translate-x-full' }}">
    <!-- Logo y encabezado -->
    <!-- Logo y encabezado - Solo visible en móviles -->
    <div class="px-4 mb-4 flex items-center justify-between lg:hidden">
        <div class="flex items-center w-full mt-4">
            <img src="{{ asset('img/logo_new.png') }}" alt="SOGAT Logo" class="block h-12 w-auto object-contain" />
        </div>
        {{-- Botón de cerrar, solo visible en móviles --}}
        <button wire:click="toggle"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>



    <nav class="flex flex-col flex-1 min-h-full px-0 space-y-0 pb-60" x-data="{ openMenu: null, subMenu: null }">
        @auth
            <!-- Dashboard -->
            <a href="/dashboard" class="sogat-sidebar-item">
                Inicio
            </a>

            @if($roleCount > 1 || auth()->user()->can('mi-firma'))
                <!-- Perfil -->
                <div>
                    <button @click="openMenu === 'perfil' ? openMenu = null : openMenu = 'perfil'" class="sogat-sidebar-item">
                        <span>Perfil</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200" :class="openMenu === 'perfil' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 'perfil'" x-collapse class="mt-0 space-y-0" style="display: none;">
                        @if($roleCount > 1)
                            <li><a href="{{ route('perfil') }}" class="sogat-sidebar-link">Seleccionar Rol</a></li>
                        @endif
                        @can('mi-firma')
                            <li><a href="{{ route('firma/mi-firma') }}" class="sogat-sidebar-link">Mi Firma</a></li>
                        @endcan
                    </ul>
                </div>
            @endif



            @can('listar-tema')
                <!-- Temas -->
                <div>
                    <button @click="openMenu === 8 ? openMenu = null : openMenu = 8" class="sogat-sidebar-item">
                        <span>Temas</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200" :class="openMenu === 8 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 8" x-collapse class="mt-0 space-y-0" style="display: none;">
                        @can('crear-tema')
                            <li><a href="{{ route('tema/crear') }}" class="sogat-sidebar-link">Crear Tema</a></li>
                        @endcan
                        <li><a href="{{ route('tema/listar') }}" class="sogat-sidebar-link">Gestionar Temas</a></li>
                    </ul>
                </div>
            @endcan

            @can('listar-contenido')
                <!-- Contenidos -->
                <div>
                    <button @click="openMenu === 7 ? openMenu = null : openMenu = 7" class="sogat-sidebar-item">
                        <span>Contenidos</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200" :class="openMenu === 7 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 7" x-collapse class="mt-0 space-y-0" style="display: none;">
                        @can('crear-contenido')
                            <li><a href="{{ route('contenido/crear') }}" class="sogat-sidebar-link">Crear Contenido</a></li>
                        @endcan
                        <li><a href="{{ route('contenido/listar') }}" class="sogat-sidebar-link">Gestionar Contenidos</a></li>
                    </ul>
                </div>
            @endcan

            @canany(['listar-recurso', 'listar-estrategia', 'listar-evaluacion', 'listar-tipo-evaluacion', 'listar-bibliografia'])
                <!-- Recursos Educativos -->
                <div>
                    <button @click="openMenu === 10 ? openMenu = null : (openMenu = 10, subMenu = null)"
                        class="sogat-sidebar-item">
                        <span>Recursos Educativos</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200" :class="openMenu === 10 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openMenu === 10" x-collapse class="mt-0 space-y-0" style="display: none;">


                        @can('listar-recurso')
                            <!-- Recursos -->
                            <div>
                                <button @click="subMenu === 3 ? subMenu = null : subMenu = 3" class="sogat-sidebar-subitem">
                                    <span>Recursos</span>
                                    <svg class="w-3 h-3 ml-auto transition-transform duration-200" :class="subMenu === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul x-show="subMenu === 3" x-collapse class="mt-0 space-y-0" style="display: none;">
                                    @can('crear-recurso')
                                        <li><a href="{{ route('recurso/crear') }}" class="sogat-sidebar-link !text-xs">Crear Recurso</a></li>
                                    @endcan
                                    <li><a href="{{ route('recurso/listar') }}" class="sogat-sidebar-link !text-xs">Gestionar Recursos</a>
                                    </li>
                                </ul>
                            </div>
                        @endcan

                        @can('listar-estrategia')
                            <!-- Estrategias Pedagógicas -->
                            <div>
                                <button @click="subMenu === 4 ? subMenu = null : subMenu = 4" class="sogat-sidebar-subitem">
                                    <span>Estrategias Pedagógicas</span>
                                    <svg class="w-3 h-3 ml-auto transition-transform duration-200" :class="subMenu === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul x-show="subMenu === 4" x-collapse class="mt-0 space-y-0" style="display: none;">
                                    @can('crear-estrategia')
                                        <li><a href="{{ route('estrategia/crear') }}" class="sogat-sidebar-link !text-xs">Crear Estrategia</a></li>
                                    @endcan
                                    <li><a href="{{ route('estrategia/listar') }}" class="sogat-sidebar-link !text-xs">Gestionar Estrategias</a>
                                    </li>
                                </ul>
                            </div>
                        @endcan


                        @can('listar-evaluacion')
                            <!-- Evaluaciones -->
                            <div>
                                <button @click="subMenu === 6 ? subMenu = null : subMenu = 6" class="sogat-sidebar-subitem">
                                    <span>Técnicas de Evaluación</span>
                                    <svg class="w-3 h-3 ml-auto transition-transform duration-200" :class="subMenu === 6 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul x-show="subMenu === 6" x-collapse class="mt-0 space-y-0" style="display: none;">
                                    @can('crear-evaluacion')
                                        <li><a href="{{ route('tecnica-evaluacion/crear') }}"
                                                class="sogat-sidebar-link !text-xs">Crear Técnica</a></li>
                                    @endcan
                                    <li><a href="{{ route('tecnica-evaluacion/listar') }}"
                                            class="sogat-sidebar-link !text-xs">Gestionar Técnicas</a>
                                    </li>
                                </ul>
                            </div>
                        @endcan

                        @can('listar-tipo-evaluacion')
                            <!-- Tipos de Evaluación -->
                            <div>
                                <button @click="subMenu === 7 ? subMenu = null : subMenu = 7" class="sogat-sidebar-subitem">
                                    <span>Tipos de Evaluación</span>
                                    <svg class="w-3 h-3 ml-auto transition-transform duration-200" :class="subMenu === 7 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul x-show="subMenu === 7" x-collapse class="mt-0 space-y-0" style="display: none;">
                                    @can('crear-tipo-evaluacion')
                                        <li><a href="{{ route('tipo-evaluacion/crear') }}"
                                                class="sogat-sidebar-link !text-xs">Crear Tipo</a></li>
                                    @endcan
                                    <li><a href="{{ route('tipo-evaluacion/listar') }}"
                                            class="sogat-sidebar-link !text-xs">Gestionar Tipos</a>
                                    </li>
                                </ul>
                            </div>
                        @endcan

                        @can('listar-bibliografia')
                            <!-- Bibliografía -->
                            <div>
                                <button @click="subMenu === 2 ? subMenu = null : subMenu = 2" class="sogat-sidebar-subitem">
                                    <span>Bibliografía</span>
                                    <svg class="w-3 h-3 ml-auto transition-transform duration-200" :class="subMenu === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul x-show="subMenu === 2" x-collapse class="mt-0 space-y-0" style="display: none;">
                                    @can('crear-bibliografia')
                                        <li><a href="{{ route('bibliografia/crear') }}" class="sogat-sidebar-link !text-xs">Crear Bibliografía</a>
                                        </li>
                                    @endcan
                                    <li><a href="{{ route('bibliografia/listar') }}"
                                            class="sogat-sidebar-link !text-xs">Gestionar Bibliografía</a></li>
                                </ul>
                            </div>
                        @endcan
                    </div>
                </div>
            @endcanany

            @can('listar-calendario')
                <div>
                    <button @click="openMenu === 18 ? openMenu = null : openMenu = 18" class="sogat-sidebar-item">
                        <span>Calendario Académico</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200" :class="openMenu === 18 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 18" x-collapse class="mt-0 space-y-0" style="display: none;">
                        @can('crear-calendario')
                            @if($puedoCrearCalendario)
                                <li><a href="{{ route('calendario.create') }}" class="sogat-sidebar-link">Crear Calendario</a></li>
                            @endif
                        @endcan
                        <li><a href="{{ route('calendario.list') }}" class="sogat-sidebar-link">Gestionar Calendario</a></li>
                        @can('crear-calendario')
                            @if($hayCalendarioActivo)
                                <li><a href="{{ route('calendario.reporte') }}" target="_blank" class="sogat-sidebar-link">Imprimir Calendario</a></li>
                            @endif
                        @endcan
                    </ul>
                </div>
            @endcan

            @can('listar-evento')
                <div>
                    <button @click="openMenu === 15 ? openMenu = null : openMenu = 15" class="sogat-sidebar-item">
                        <span>Eventos</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200" :class="openMenu === 15 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 15" x-collapse class="mt-0 space-y-0" style="display: none;">
                        @can('crear-evento')
                            <li><a href="{{ route('evento/crear') }}" class="sogat-sidebar-link">Crear Evento</a></li>
                        @endcan
                        <li><a href="{{ route('evento/listar') }}" class="sogat-sidebar-link">Gestionar Eventos</a></li>
                    </ul>
                </div>
            @endcan

            @can('listar-permiso')
                <!-- Permisos (DAECE) -->
                <div>
                    <a href="{{ route('permiso/listar') }}" class="sogat-sidebar-item">
                        Permisos
                    </a>
                </div>
            @endcan

            @php
                $activeRole = session('active_role', auth()->user()->usu_cod_rol);
                $isCoordinadorMenu = $activeRole == 5;
                $isVoceroMenu = $activeRole == 3 && \App\Models\Vocero::where('id_estudiante', auth()->user()->usu_cedula)->where('estatus', 'A')->exists();
            @endphp
            @if($isCoordinadorMenu || $isVoceroMenu)
                <!-- Voceros -->
                <div>
                    <a href="{{ route('voceros.panel') }}" class="sogat-sidebar-item">
                        Voceros
                    </a>
                </div>
            @endif


            @can('listar-planificacion')
                <!-- Planificaciones -->
                <div>
                    <button @click="openMenu === 6 ? openMenu = null : openMenu = 6" class="sogat-sidebar-item">
                        <span>Planificaciones</span>
                        <svg class="w-4 h-4 ml-auto transition-transform duration-200" :class="openMenu === 6 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 6" x-collapse class="mt-0 space-y-0" style="display: none;">
                        @can('crear-planificacion')
                            <li><a href="{{ route('planificacion/crear') }}" class="sogat-sidebar-link">Crear Planificación</a></li>
                        @endcan
                        <li><a href="{{ route('planificacion/listar') }}" class="sogat-sidebar-link">Gestionar Planificaciones</a></li>
                        @if(in_array(auth()->user()->usu_cod_rol, [1, 5, 11]))
                            <li><a href="{{ route('planificacion.reporte.cumplimiento') }}" class="sogat-sidebar-link">Estadísticas de Entrega</a></li>
                        @endif
                    </ul>
                </div>
            @endcan



            @can('listar-bitacora')
                <!-- Bitácora -->
                <a href="{{ route('bitacora/listar') }}" class="sogat-sidebar-item">
                    <span>Bitácora</span>
                </a>
            @endcan

            <!-- Sesión -->
            <div class="border-t border-gray-200 dark:border-gray-700">
                <button wire:click="logout" class="sogat-sidebar-item w-full text-left">
                    <span>Cerrar Sesión</span>
                </button>
            </div>

            <!-- Info Usuario al final -->
            <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-3 text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                    <div class="flex flex-col">
                        <span>Usuario: {{ auth()->user()->name }}</span>
                        <span class="text-[9px] text-sogat-blue dark:text-blue-400">
                            {{ auth()->user()->rol->rol_nombre}}
                        </span>
                    </div>
                    <livewire:notification-bell />
                </div>
            </div>

        @else
            <a href="{{ route('login') }}" class="sogat-sidebar-item">
                Iniciar Sesión
            </a>
        @endauth
    </nav>
</aside>