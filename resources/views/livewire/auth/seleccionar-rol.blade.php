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

    <div class="pt-8 pb-12 w-full">
        <div class="w-full max-w-[1200px] mx-auto sm:px-6 lg:px-8 transition-all duration-300">

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
                <div class="sogat-card planificacion-module p-10 max-w-2xl mx-auto">
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
                    <div class="text-center mb-10">
                        <h2 class="text-6xl md:text-[8rem] leading-none font-black text-[#1a365d] dark:text-[#3b82f6] drop-shadow-lg mb-2 transition-all duration-300" style="font-family: 'Verdana', sans-serif; letter-spacing: -0.03em;">
                            {{ date('Y') }}
                        </h2>
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 dark:text-gray-100 mb-2 uppercase tracking-[0.2em]">
                             Calendario Académico
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">
                             Hola rector, por favor defina el tiempo de duración del período seleccionando la fecha de inicio y fin dentro del calendario a continuación.
                        </p>
                    </div>

                    <div x-data="{
                        picker: null,
                        init() {
                            const isDark = document.documentElement.classList.contains('dark');
                            const currentYear = new Date().getFullYear();
                            
                            this.picker = new VanillaCalendar($refs.calendar, {
                                type: 'multiple',
                                months: 12,
                                displayMonthsCount: 12, // added both just in case
                                selectedMonth: 0, // Force January
                                selectedYear: currentYear,
                                settings: {
                                    lang: 'es',
                                    selected: {
                                        month: 0, // Force January (fallback)
                                        year: currentYear
                                    },
                                    selection: {
                                        day: 'multiple-ranged'
                                    },
                                    visibility: {
                                        daysOutside: false,
                                        theme: isDark ? 'dark' : 'light'
                                    }
                                },
                                actions: {
                                    clickDay(e, self) {
                                        if (self.selectedDates && self.selectedDates.length > 0) {
                                            // Handle dates selection
                                            let fechaInicio = self.selectedDates[0];
                                            let fechaFin = self.selectedDates[self.selectedDates.length - 1];
                                            @this.set('dia_inicio_calendario_academico', fechaInicio);
                                            @this.set('dia_fin_calendario_academico', fechaFin);
                                        } else {
                                            @this.set('dia_inicio_calendario_academico', '');
                                            @this.set('dia_fin_calendario_academico', '');
                                        }
                                    }
                                }
                            });
                            this.picker.init();
                        }
                    }" class="space-y-6">
                        
                        <div class="flex justify-center flex-col items-center">
                            <div wire:ignore x-ref="calendar" class="sogat-datepicker-container w-full"></div>
                            
                            <div class="grid grid-cols-2 gap-6 w-full max-w-2xl mt-8">
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-inner">
                                    <span class="block text-[11px] uppercase font-bold text-gray-400 mb-1 tracking-wider">Inicio del Período</span>
                                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-100" x-text="$wire.dia_inicio_calendario_academico || 'Escoja una fecha...'"></span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-inner">
                                    <span class="block text-[11px] uppercase font-bold text-gray-400 mb-1 tracking-wider">Fin del Período</span>
                                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-100" x-text="$wire.dia_fin_calendario_academico || 'Escoja una fecha...'"></span>
                                </div>
                            </div>
                        </div>

                        <form wire:submit="guardarCalendario" class="max-w-2xl mx-auto mt-8">
                            @error('dia_inicio_calendario_academico')
                                <p class="text-red-500 text-xs text-center mb-4">{{ $message }}</p>
                            @enderror
                            @error('dia_fin_calendario_academico')
                                <p class="text-red-500 text-xs text-center mb-4">{{ $message }}</p>
                            @enderror

                            <x-primary-button type="submit" class="w-full justify-center py-4 text-base font-bold uppercase tracking-wider rounded-xl transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                                <span wire:loading.remove wire:target="guardarCalendario">Confirmar Período Académico</span>
                                <span wire:loading wire:target="guardarCalendario">Procesando...</span>
                            </x-primary-button>
                        </form>
                    </div>

                    <style>
                        /* Custom Vanilla Calendar tweaks */
                        .sogat-datepicker-container.vanilla-calendar,
                        .sogat-datepicker-container.vc {
                            max-width: 100%;
                            width: 100%;
                            background: transparent;
                            border: none;
                            padding: 0;
                            box-shadow: none;
                        }

                        /* Native CSS variables to control columns in Vanilla Calendar Pro v3+ */
                        .sogat-datepicker-container {
                            --vanilla-calendar-columns: 1 !important;
                            --vc-grid-columns: 1 !important;
                        }

                        @media (min-width: 768px) {
                            .sogat-datepicker-container {
                                --vanilla-calendar-columns: 2 !important;
                                --vc-grid-columns: 2 !important;
                            }
                        }

                        @media (min-width: 1024px) {
                            .sogat-datepicker-container {
                                --vanilla-calendar-columns: 3 !important;
                                --vc-grid-columns: 3 !important;
                            }
                        }

                        /* Fallback: Ensure grid layout for older versions */
                        .sogat-datepicker-container .vanilla-calendar-wrapper,
                        .sogat-datepicker-container .vc-grid,
                        .sogat-datepicker-container .vanilla-calendar-grid {
                            display: grid !important;
                            gap: 1.5rem;
                            justify-content: center;
                        }

                        /* Stacking fixes using grid template overrides for older versions fallback */
                        .sogat-datepicker-container .vanilla-calendar-wrapper,
                        .sogat-datepicker-container .vc-grid,
                        .sogat-datepicker-container .vanilla-calendar-grid {
                            grid-template-columns: repeat(var(--vanilla-calendar-columns, var(--vc-grid-columns, 1)), 1fr) !important;
                        }

                        .sogat-datepicker-container .vanilla-calendar-content,
                        .sogat-datepicker-container .vc-column {
                            background: var(--bg-white, #ffffff);
                            border-radius: 1rem;
                            padding: 1rem;
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
                            border: 1px solid #f3f4f6;
                            margin: 0 !important;
                        }

                        .dark .sogat-datepicker-container .vanilla-calendar-content,
                        .dark .sogat-datepicker-container .vc-column {
                            background: #1f2937;
                            border-color: #374151;
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
                        }

                        /* Customize selected ranged color */
                        .vanilla-calendar-day__btn_selected, 
                        .vanilla-calendar-day__btn_hover,
                        .vc-day_selected,
                        .vc-day:hover {
                            background-color: #1a365d !important;
                            color: white !important;
                        }
                        .dark .vanilla-calendar-day__btn_selected,
                        .dark .vanilla-calendar-day__btn_hover,
                        .dark .vc-day_selected,
                        .dark .vc-day:hover {
                            background-color: #3b82f6 !important;
                        }

                        /* Highlight ranges */
                        .vanilla-calendar-day__btn_selected.vanilla-calendar-day__btn_hover,
                        .vc-day_selected:hover {
                            border-radius: 0.5rem !important;
                        }

                        /* Forzar centrado absoluto del nombre del mes */
                        .vanilla-calendar-header,
                        .vc-header {
                            display: flex !important;
                            justify-content: center !important;
                            align-items: center !important;
                            width: 100% !important;
                            padding: 0 !important;
                            margin: 0 0 1rem 0 !important;
                        }

                        .vanilla-calendar-header__content,
                        .vc-header__content {
                            display: flex !important;
                            justify-content: center !important;
                            flex: 1 1 auto !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            text-align: center !important;
                        }

                        .vanilla-calendar-header__title,
                        .vc-header__title {
                            display: flex !important;
                            justify-content: center !important;
                            margin: 0 !important;
                            width: 100% !important;
                        }

                        .vanilla-calendar-year,
                        .vc-header__year,
                        .vanilla-calendar-header__title i,
                        .vc-header__title i {
                            display: none !important;
                        }

                        .vanilla-calendar-month,
                        .vc-header__month {
                            font-size: 1.2rem !important;
                            font-weight: 800 !important;
                            text-transform: capitalize !important;
                            display: block !important;
                            width: 100% !important;
                            text-align: center !important;
                            pointer-events: none !important;
                            cursor: default !important;
                            color: #1a365d !important;
                        }

                        .dark .vanilla-calendar-month,
                        .dark .vc-header__month {
                            color: #3b82f6 !important;
                        }

                        /* Remove upper main navigation arrows since it shows all 12 */
                        .vanilla-calendar-header__btn,
                        .vanilla-calendar-arrow,
                        button.vc-arrow,
                        .vc-header__arrow {
                            display: none !important;
                        }
                    </style>

                </div>

            {{-- CASO 3: Hay calendario activo → Selección de rol normal --}}
            @else
                <div class="sogat-card planificacion-module p-10 max-w-2xl mx-auto">
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
