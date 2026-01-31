<aside id="sidebar" class="bg-gray-100 dark:bg-gray-900 w-64 h-screen overflow-y-auto fixed top-0 left-0 py-4 shadow-lg
              transition-transform duration-300 ease-in-out z-40
              {{ $isOpen ? 'translate-x-0' : '-translate-x-full' }}">
    <!-- Logo y encabezado -->
    <div class="px-4 mb-4 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">Panel</span>
        </div>
        <button wire:click="toggle"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <hr class="border-gray-200 dark:border-gray-700">

    <nav class="mt-6 px-2 space-y-1" x-data="{ openMenu: null, subMenu: null }">
        @auth
            <!-- Dashboard -->
            <a href="/dashboard"
                class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-200 dark:text-white dark:hover:bg-gray-700">
                <svg class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10h3m10-11l2 2v10h-3m-6 0l6-6" />
                </svg>
                Dashboard
            </a>

            @can('is-coordinador')
                <!-- PNFS -->
                {{-- <div>
                    <button @click="openMenu === 1 ? openMenu = null : openMenu = 1"
                        :class="{
                                                                                                                                                                                                                                                                                'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                                                                                                                'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': openMenu !== 1,
                                                                                                                                                                                                                                                                                'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white': openMenu === 1
                                                                                                                                                                                                                                                                            }">
                        <i class="material-icons mr-2 text-gray-500 dark:text-gray-400">school</i>
                        <span>PNFS</span>
                        <svg class="w-4 h-4 ml-auto transform transition-transform" :class="{ 'rotate-180': openMenu === 1 }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 1" x-collapse class="ml-8 mt-2 space-y-1">
                        <li><a href="{{ route('pnf/crear') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300">Crear
                                PNFS</a></li>
                        <li><a href="{{ route('pnf/listar') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300">Gestionar
                                PNFS</a></li>
                    </ul>
                </div> --}}

                <!-- Temas -->
                <div>
                    <button @click="openMenu === 8 ? openMenu = null : openMenu = 8"
                        :class="{
                                                                                                                                                                                                                                                                                'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                                                                                                                'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': openMenu !== 8,
                                                                                                                                                                                                                                                                                'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white': openMenu === 8
                                                                                                                                                                                                                                                                            }">
                        <i class="material-icons mr-2 text-gray-500 dark:text-gray-400">topic</i>
                        <span>Temas</span>
                        <svg class="w-4 h-4 ml-auto transform transition-transform" :class="{ 'rotate-180': openMenu === 8 }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 8" x-collapse class="ml-8 mt-2 space-y-1">
                        <li><a href="{{ route('tema/crear') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Crear
                                Tema</a></li>
                        <li><a href="{{ route('tema/listar') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Gestionar
                                Temas</a></li>
                    </ul>
                </div>

                <!-- Contenidos -->
                <div>
                    <button @click="openMenu === 7 ? openMenu = null : openMenu = 7"
                        :class="{
                                                                                                                                                                                                                                                                                'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                                                                                                                'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': openMenu !== 7,
                                                                                                                                                                                                                                                                                'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white': openMenu === 7
                                                                                                                                                                                                                                                                            }">
                        <i class="material-icons mr-2 text-gray-500 dark:text-gray-400">description</i>
                        <span>Contenidos</span>
                        <svg class="w-4 h-4 ml-auto transform transition-transform" :class="{ 'rotate-180': openMenu === 7 }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 7" x-collapse class="ml-8 mt-2 space-y-1">
                        <li><a href="{{ route('contenido/crear') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Crear
                                Contenido</a></li>
                        <li><a href="{{ route('contenido/listar') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Gestionar
                                Contenidos</a></li>
                    </ul>
                </div>

                <!-- Recursos Educativos -->
                <div>
                    <button @click="openMenu === 10 ? openMenu = null : (openMenu = 10, subMenu = null)"
                        :class="{
                                                                                                                                                                                'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': openMenu !== 10,
                                                                                                                                                                                'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white': openMenu === 10
                                                                                                                                                                            }">
                        <i class="material-icons mr-2 text-gray-500 dark:text-gray-400">folder_special</i>
                        <span>Recursos Educativos</span>
                        <svg class="w-4 h-4 ml-auto transform transition-transform" :class="{ 'rotate-180': openMenu === 10 }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openMenu === 10" x-collapse
                        class="ml-4 mt-2 space-y-1 border-l-2 border-gray-200 dark:border-gray-700 pl-2">
                        <!-- Indicadores de Logro -->
                        <div>
                            <button @click="subMenu === 1 ? subMenu = null : subMenu = 1"
                                :class="{
                                                                                                                                                                                        'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                        'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': subMenu !== 1,
                                                                                                                                                                                        'bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white': subMenu === 1
                                                                                                                                                                                    }">
                                <span>Indicadores de Logro</span>
                                <svg class="w-3 h-3 ml-auto transform transition-transform"
                                    :class="{ 'rotate-180': subMenu === 1 }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="subMenu === 1" x-collapse class="ml-6 mt-1 space-y-1">
                                <li><a href="{{ route('indicador-logro/crear') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Crear
                                        Indicador</a></li>
                                <li><a href="{{ route('indicador-logro/listar') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Gestionar
                                        Indicadores</a></li>
                            </ul>
                        </div>

                        <!-- Bibliografía -->
                        <div>
                            <button @click="subMenu === 2 ? subMenu = null : subMenu = 2"
                                :class="{
                                                                                                                                                                                        'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                        'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': subMenu !== 2,
                                                                                                                                                                                        'bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white': subMenu === 2
                                                                                                                                                                                    }">
                                <span>Bibliografía</span>
                                <svg class="w-3 h-3 ml-auto transform transition-transform"
                                    :class="{ 'rotate-180': subMenu === 2 }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="subMenu === 2" x-collapse class="ml-6 mt-1 space-y-1">
                                <li><a href="{{ route('bibliografia/crear') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Crear
                                        Bibliografía</a></li>
                                <li><a href="{{ route('bibliografia/listar') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Gestionar
                                        Bibliografía</a></li>
                            </ul>
                        </div>

                        <!-- Recursos -->
                        <div>
                            <button @click="subMenu === 3 ? subMenu = null : subMenu = 3"
                                :class="{
                                                                                                                                                                                        'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                        'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': subMenu !== 3,
                                                                                                                                                                                        'bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white': subMenu === 3
                                                                                                                                                                                    }">
                                <span>Recursos</span>
                                <svg class="w-3 h-3 ml-auto transform transition-transform"
                                    :class="{ 'rotate-180': subMenu === 3 }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="subMenu === 3" x-collapse class="ml-6 mt-1 space-y-1">
                                <li><a href="{{ route('recurso/crear') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Crear
                                        Recurso</a></li>
                                <li><a href="{{ route('recurso/listar') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Gestionar
                                        Recursos</a></li>
                            </ul>
                        </div>

                        <!-- Estrategias -->
                        <div>
                            <button @click="subMenu === 4 ? subMenu = null : subMenu = 4"
                                :class="{
                                                                                                                                                                                        'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                        'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': subMenu !== 4,
                                                                                                                                                                                        'bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white': subMenu === 4
                                                                                                                                                                                    }">
                                <span>Estrategias</span>
                                <svg class="w-3 h-3 ml-auto transform transition-transform"
                                    :class="{ 'rotate-180': subMenu === 4 }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="subMenu === 4" x-collapse class="ml-6 mt-1 space-y-1">
                                <li><a href="{{ route('estrategia/crear') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Crear
                                        Estrategia</a></li>
                                <li><a href="{{ route('estrategia/listar') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Gestionar
                                        Estrategias</a></li>
                            </ul>
                        </div>

                        <!-- Técnicas -->
                        <div>
                            <button @click="subMenu === 5 ? subMenu = null : subMenu = 5"
                                :class="{
                                                                                                                                                                                        'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                        'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': subMenu !== 5,
                                                                                                                                                                                        'bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white': subMenu === 5
                                                                                                                                                                                    }">
                                <span>Técnicas</span>
                                <svg class="w-3 h-3 ml-auto transform transition-transform"
                                    :class="{ 'rotate-180': subMenu === 5 }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="subMenu === 5" x-collapse class="ml-6 mt-1 space-y-1">
                                <li><a href="{{ route('tecnica/crear') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Crear
                                        Técnica</a></li>
                                <li><a href="{{ route('tecnica/listar') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Gestionar
                                        Técnicas</a></li>
                            </ul>
                        </div>

                        <!-- Evaluaciones -->
                        <div>
                            <button @click="subMenu === 6 ? subMenu = null : subMenu = 6"
                                :class="{
                                                                                                                                                                                        'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                        'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': subMenu !== 6,
                                                                                                                                                                                        'bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white': subMenu === 6
                                                                                                                                                                                    }">
                                <span>Evaluaciones</span>
                                <svg class="w-3 h-3 ml-auto transform transition-transform"
                                    :class="{ 'rotate-180': subMenu === 6 }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="subMenu === 6" x-collapse class="ml-6 mt-1 space-y-1">
                                <li><a href="{{ route('evaluacion/crear') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Crear
                                        Evaluación</a></li>
                                <li><a href="{{ route('evaluacion/listar') }}"
                                        class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Gestionar
                                        Evaluaciones</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Eventos -->
                <div>
                    <button @click="openMenu === 15 ? openMenu = null : openMenu = 15"
                        :class="{
                                                                                                                                                                                                                                                                                'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                                                                                                                'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': openMenu !== 15,
                                                                                                                                                                                                                                                                                'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white': openMenu === 15
                                                                                                                                                                                                                                                                            }">
                        <i class="material-icons mr-2 text-gray-500 dark:text-gray-400">date_range</i>
                        <span>Eventos</span>
                        <svg class="w-4 h-4 ml-auto transform transition-transform" :class="{ 'rotate-180': openMenu === 15 }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 15" x-collapse class="ml-8 mt-2 space-y-1">
                        <li><a href="{{ route('evento/crear') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Crear
                                Evento</a></li>
                        <li><a href="{{ route('evento/listar') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-amber-500 transition-colors">Gestionar
                                Eventos</a></li>
                    </ul>
                </div>

                <!-- Usuarios -->
                {{-- <div>
                    <button @click="openMenu === 5 ? openMenu = null : openMenu = 5"
                        :class="{
                                                                                                                                                                                                                                                                                'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                                                                                                                'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': openMenu !== 5,
                                                                                                                                                                                                                                                                                'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white': openMenu === 5
                                                                                                                                                                                                                                                                            }">
                        <i class="material-icons mr-2 text-gray-500 dark:text-gray-400">people</i>
                        <span>Usuarios</span>
                        <svg class="w-4 h-4 ml-auto transform transition-transform" :class="{ 'rotate-180': openMenu === 5 }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 5" x-collapse class="ml-8 mt-2 space-y-1">
                        <li><a href="{{ route('usuarios/crear') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300">Crear</a>
                        </li>
                        <li><a href="{{ route('usuarios/listar') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300">Gestionar</a>
                        </li>
                    </ul>
                </div> --}}

                <!-- Planificaciones -->
                <div>
                    <button @click="openMenu === 6 ? openMenu = null : openMenu = 6"
                        :class="{
                                                                                                                                                                                                                                                                                'flex items-center w-full p-2 rounded-lg': true,
                                                                                                                                                                                                                                                                                'text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700': openMenu !== 6,
                                                                                                                                                                                                                                                                                'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white': openMenu === 6
                                                                                                                                                                                                                                                                            }">
                        <i class="material-icons mr-2 text-gray-500 dark:text-gray-400">assignment</i>
                        <span>Planificaciones</span>
                        <svg class="w-4 h-4 ml-auto transform transition-transform" :class="{ 'rotate-180': openMenu === 6 }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="openMenu === 6" x-collapse class="ml-8 mt-2 space-y-1">
                        <li><a href="{{ route('planificacion/crear') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300">Crear</a>
                        </li>
                        <li><a href="{{ route('planificacion/listar') }}"
                                class="block px-2 py-1 text-sm text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300">Gestionar</a>
                        </li>
                    </ul>
                </div>

            @endcan
        @else
            <a href="{{ route('login') }}"
                class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-200 dark:text-white dark:hover:bg-gray-700">
                Iniciar Sesión
            </a>
        @endauth
    </nav>
</aside>