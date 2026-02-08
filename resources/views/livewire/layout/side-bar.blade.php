<aside id="sidebar" class="bg-white dark:bg-gray-800 w-[234px] lg:static lg:h-auto lg:shadow-none lg:translate-x-0
              h-screen overflow-y-auto fixed top-0 left-0 shadow-lg mt-[15px] border-r border-gray-200 dark:border-gray-700
              transition-transform duration-300 ease-in-out z-40 flex flex-col
              {{ $isOpen ? 'translate-x-0' : '-translate-x-full' }}">
    <!-- Logo y encabezado -->
    <!-- Logo y encabezado - Solo visible en móviles -->
    <div class="px-4 mb-4 flex items-center justify-between lg:hidden">
        <div class="flex items-center space-x-2">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">Panel</span>
        </div>
        {{-- Botón de cerrar, solo visible en móviles --}}
        <button wire:click="toggle"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>



    <nav class="flex flex-col flex-1 min-h-full px-0 space-y-0" x-data="{ openMenu: null, subMenu: null }">
        @auth
            <!-- Dashboard -->
            <a href="/dashboard"
                class="sogat-sidebar-item">
                Inicio
            </a>

            @can('is-coordinador')
                <!-- PNFS -->

                <!-- Temas -->
                <div>
                    <button @click="openMenu === 8 ? openMenu = null : openMenu = 8"
                        class="sogat-sidebar-item">
                        <span>Temas</span>
                        <img :src="openMenu === 8 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                             class="w-5 h-5 ml-auto">
                    </button>
                    <ul x-show="openMenu === 8" x-collapse class="mt-0 space-y-0">
                        <li><a href="{{ route('tema/crear') }}" class="sogat-sidebar-link">Crear Tema</a></li>
                        <li><a href="{{ route('tema/listar') }}" class="sogat-sidebar-link">Gestionar Temas</a></li>
                    </ul>
                </div>

                <!-- Contenidos -->
                <div>
                    <button @click="openMenu === 7 ? openMenu = null : openMenu = 7"
                        class="sogat-sidebar-item">
                        <span>Contenidos</span>
                        <img :src="openMenu === 7 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                             class="w-5 h-5 ml-auto">
                    </button>
                    <ul x-show="openMenu === 7" x-collapse class="mt-0 space-y-0">
                        <li><a href="{{ route('contenido/crear') }}" class="sogat-sidebar-link">Crear Contenido</a></li>
                        <li><a href="{{ route('contenido/listar') }}" class="sogat-sidebar-link">Gestionar Contenidos</a></li>
                    </ul>
                </div>

                <!-- Recursos Educativos -->
                <div>
                    <button @click="openMenu === 10 ? openMenu = null : (openMenu = 10, subMenu = null)"
                        class="sogat-sidebar-item">
                        <span>Recursos Educativos</span>
                        <img :src="openMenu === 10 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                             class="w-5 h-5 ml-auto">
                    </button>

                    <div x-show="openMenu === 10" x-collapse class="mt-0 space-y-0">
                        <!-- Indicadores de Logro -->
                        <div>
                            <button @click="subMenu === 1 ? subMenu = null : subMenu = 1"
                                class="sogat-sidebar-subitem">
                                <span>Indicadores de Logro</span>
                                <img :src="subMenu === 1 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                                     class="w-4 h-4 ml-auto">
                            </button>
                            <ul x-show="subMenu === 1" x-collapse class="mt-0 space-y-0">
                                <li><a href="{{ route('indicador-logro/crear') }}" class="sogat-sidebar-link !text-xs">Crear</a></li>
                                <li><a href="{{ route('indicador-logro/listar') }}" class="sogat-sidebar-link !text-xs">Gestionar</a></li>
                            </ul>
                        </div>

                        <!-- Recursos -->
                        <div>
                            <button @click="subMenu === 3 ? subMenu = null : subMenu = 3"
                                class="sogat-sidebar-subitem">
                                <span>Recursos</span>
                                <img :src="subMenu === 3 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                                     class="w-4 h-4 ml-auto">
                            </button>
                            <ul x-show="subMenu === 3" x-collapse class="mt-0 space-y-0">
                                <li><a href="{{ route('recurso/crear') }}" class="sogat-sidebar-link !text-xs">Crear</a></li>
                                <li><a href="{{ route('recurso/listar') }}" class="sogat-sidebar-link !text-xs">Gestionar</a></li>
                            </ul>
                        </div>

                        <!-- Estrategias Pedagógicas -->
                        <div>
                            <button @click="subMenu === 4 ? subMenu = null : subMenu = 4"
                                class="sogat-sidebar-subitem">
                                <span>Estrategias Pedagógicas</span>
                                <img :src="subMenu === 4 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                                     class="w-4 h-4 ml-auto">
                            </button>
                            <ul x-show="subMenu === 4" x-collapse class="mt-0 space-y-0">
                                <li><a href="{{ route('estrategia/crear') }}" class="sogat-sidebar-link !text-xs">Crear</a></li>
                                <li><a href="{{ route('estrategia/listar') }}" class="sogat-sidebar-link !text-xs">Gestionar</a></li>
                            </ul>
                        </div>

                        <!-- Técnicas de Evaluación -->
                        <div>
                            <button @click="subMenu === 5 ? subMenu = null : subMenu = 5"
                                class="sogat-sidebar-subitem">
                                <span>Técnicas de Evaluación</span>
                                <img :src="subMenu === 5 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                                     class="w-4 h-4 ml-auto">
                            </button>
                            <ul x-show="subMenu === 5" x-collapse class="mt-0 space-y-0">
                                <li><a href="{{ route('tecnica/crear') }}" class="sogat-sidebar-link !text-xs">Crear</a></li>
                                <li><a href="{{ route('tecnica/listar') }}" class="sogat-sidebar-link !text-xs">Gestionar</a></li>
                            </ul>
                        </div>

                        <!-- Evaluaciones -->
                        <div>
                            <button @click="subMenu === 6 ? subMenu = null : subMenu = 6"
                                class="sogat-sidebar-subitem">
                                <span>Evaluaciones</span>
                                <img :src="subMenu === 6 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                                     class="w-4 h-4 ml-auto">
                            </button>
                            <ul x-show="subMenu === 6" x-collapse class="mt-0 space-y-0">
                                <li><a href="{{ route('evaluacion/crear') }}" class="sogat-sidebar-link !text-xs">Crear</a></li>
                                <li><a href="{{ route('evaluacion/listar') }}" class="sogat-sidebar-link !text-xs">Gestionar</a></li>
                            </ul>
                        </div>

                        <!-- Bibliografía -->
                        <div>
                            <button @click="subMenu === 2 ? subMenu = null : subMenu = 2"
                                class="sogat-sidebar-subitem">
                                <span>Bibliografía</span>
                                <img :src="subMenu === 2 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                                     class="w-4 h-4 ml-auto">
                            </button>
                            <ul x-show="subMenu === 2" x-collapse class="mt-0 space-y-0">
                                <li><a href="{{ route('bibliografia/crear') }}" class="sogat-sidebar-link !text-xs">Crear</a></li>
                                <li><a href="{{ route('bibliografia/listar') }}" class="sogat-sidebar-link !text-xs">Gestionar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Eventos -->
                <div>
                    <button @click="openMenu === 15 ? openMenu = null : openMenu = 15"
                        class="sogat-sidebar-item">
                        <span>Eventos</span>
                        <img :src="openMenu === 15 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                             class="w-5 h-5 ml-auto">
                    </button>
                    <ul x-show="openMenu === 15" x-collapse class="mt-0 space-y-0">
                        <li><a href="{{ route('evento/crear') }}" class="sogat-sidebar-link">Crear Evento</a></li>
                        <li><a href="{{ route('evento/listar') }}" class="sogat-sidebar-link">Gestionar Eventos</a></li>
                    </ul>
                </div>

                <!-- Planificaciones -->
                <div>
                    <button @click="openMenu === 6 ? openMenu = null : openMenu = 6"
                        class="sogat-sidebar-item">
                        <span>Planificaciones</span>
                        <img :src="openMenu === 6 ? '{{ asset('img/down.png') }}' : '{{ asset('img/left.png') }}'" 
                             class="w-5 h-5 ml-auto">
                    </button>
                    <ul x-show="openMenu === 6" x-collapse class="mt-0 space-y-0">
                        <li><a href="{{ route('planificacion/crear') }}" class="sogat-sidebar-link">Crear</a></li>
                        <li><a href="{{ route('planificacion/listar') }}" class="sogat-sidebar-link">Gestionar</a></li>
                    </ul>
                </div>

                <!-- Perfil y Sesión -->
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('profile') }}" class="sogat-sidebar-item" wire:navigate>
                        <span>Perfil</span>
                    </a>

                    <button wire:click="logout" class="sogat-sidebar-item w-full text-left">
                        <span>Cerrar Sesión</span>
                    </button>
                </div>

                <!-- Info Usuario al final -->
                <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-3 text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                        <div class="flex flex-col">
                            <span>Usuario: {{ auth()->user()->name }}</span>
                            <span class="text-[9px] text-sogat-blue dark:text-blue-400">
                                @if(Gate::allows('is-coordinador')) 
                                    (COORDINADOR) 
                                @elseif(Gate::allows('is-profesor')) 
                                    (PROFESOR) 
                                @else 
                                    (USUARIO)
                                @endif
                            </span>
                        </div>
                         <livewire:dark-mode-toggle />
                    </div>
                </div>
            @endcan
        @else
            <a href="{{ route('login') }}" class="sogat-sidebar-item">
                Iniciar Sesión
            </a>
        @endauth
    </nav>
</aside>