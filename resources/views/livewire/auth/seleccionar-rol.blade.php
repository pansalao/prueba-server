<div>
    <x-slot name="header">
        <!-- Logo Header (Sintillo) -->
        <div class="bg-white dark:bg-gray-800 px-8 py-2 flex items-center transition-colors duration-300">
            <div class="flex items-center w-full">
                <img src="{{ asset('img/logo_viejo-Photoroom.png') }}" alt="SOGAT Sintillo"
                    class="w-full h-auto max-h-[100px] object-contain">
            </div>
        </div>

        <!-- Línea SOGAT (decoración) -->
        <div class="flex justify-center">
            <div class="sogat-hr !my-0"></div>
        </div>

        <div class="pt-6 pb-2">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight uppercase text-center tracking-wide">
                @if($sistemaInactivo)
                    {{ __('Sistema Inactivo') }}
                @elseif(!$hayCalendarioActivo && $tieneRol3)
                    {{ __('Configurar Calendario Académico') }}
                @else
                    {{ __('Bienvenido al sistema de planificaciones') }}
                @endif
            </h2>
        </div>
    </x-slot>

    <div class="pt-2 pb-12 w-full">
        <div class="w-full max-w-[1200px] mx-auto sm:px-6 lg:px-8 transition-all duration-300">

            {{-- Mensajes flash --}}
            @if (session()->has('message'))
                <div
                    class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div
                    class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            {{-- CASO 1: Sistema inactivo (usuario sin rol 3 y sin calendario activo) --}}
            @if($sistemaInactivo)
                <div class="sogat-card planificacion-module p-10 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div
                            class="mx-auto mb-6 flex items-center justify-center w-20 h-20 rounded-full bg-red-100 dark:bg-red-900/40">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
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

                    @if($paso == 1)
                        <div class="text-center mb-10">
                            <h3
                                class="text-lg md:text-xl font-bold text-gray-800 dark:text-gray-100 mb-2 uppercase tracking-[0.2em]">
                                Calendario Académico
                            </h3>
                        </div>

                        <style>
                            /* Estilos para inputs de fecha en modo oscuro (estilo planificacion) */
                            .date-input-dark::-webkit-calendar-picker-indicator {
                                background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23d1d5db'%3e%3cpath fill-rule='evenodd' d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' clip-rule='evenodd'/%3e%3c/svg%3e") no-repeat center;
                                background-size: 20px 20px;
                                width: 20px;
                                height: 20px;
                                cursor: pointer;
                            }

                            .date-input-dark {
                                color-scheme: light;
                            }
                        </style>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl mx-auto mt-8">
                            <div class="w-full">
                                <x-input-label for="dia_inicio_calendario_academico" :value="__('Inicio del Período')" />
                                <x-text-input id="dia_inicio_calendario_academico" type="date"
                                    wire:model.live="form.dia_inicio_calendario_academico" class="w-full mt-1 date-input-dark"
                                    required />
                                <x-input-error :messages="$errors->first('form.dia_inicio_calendario_academico')"
                                    class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-input-label for="dia_fin_calendario_academico" :value="__('Fin del Período')" />
                                <x-text-input id="dia_fin_calendario_academico" type="date"
                                    wire:model.live="form.dia_fin_calendario_academico" class="w-full mt-1 date-input-dark"
                                    required />
                                <x-input-error :messages="$errors->first('form.dia_fin_calendario_academico')" class="mt-2" />
                            </div>
                        </div>

                        <div class="max-w-2xl mx-auto mt-8">
                            <x-primary-button wire:click="avanzarPaso2" type="button" class="w-full justify-center">
                                {{ __('Siguiente') }}
                            </x-primary-button>
                        </div>
                    @endif

                    @if($paso == 2 && $form->dia_inicio_calendario_academico && $form->dia_fin_calendario_academico)
                         <div x-data="{
                                                            picker: null,
                                                            showEventModal: false,
                                                            selectedEventStart: '',
                                                            selectedEventEnd: '',
                                                            eventoNombre: '',
                                                            eventoTipo: '1',
                                                            eventoColor: '',
                                                            eventoSeleccionado: '',
                                                            clickCount: 0,
                                                            eventosAlpine: @entangle('eventosRegistrados'),
                                                            tooltip: { visible: false, x: 0, y: 0, content: null },
                                                            currentYear: null,
                                                            calStart: '',
                                                            calEnd: '',
                                                            calStartYear: null,
                                                            calEndYear: null,
                                                            _savedStart: '',
                                                            _savedCount: 0,
                                                            _clickLock: false,
                                                            inicializarPicker(year) {
                                                                const isDark = document.documentElement.classList.contains('dark');

                                                                // Persistir estado de selección antes de limpiar
                                                                this._savedStart = this.selectedEventStart;
                                                                this._savedCount = this.clickCount;

                                                                // Limpiar y reconstruir
                                                                this.$refs.calendar.innerHTML = '';

                                                                this.picker = new VanillaCalendar(this.$refs.calendar, {
                                                                    type: 'multiple',
                                                                    months: 12,
                                                                    displayMonthsCount: 12,
                                                                    selectedMonth: 0,
                                                                    selectedYear: year,
                                                                    date: {
                                                                        today: new Date(year, 0, 1),
                                                                    },
                                                                    settings: {
                                                                        lang: 'es',
                                                                        range: {
                                                                            min: this.calStart,
                                                                            max: this.calEnd,
                                                                            disablePast: false,
                                                                        },
                                                                        selection: {
                                                                            day: 'single',
                                                                        },
                                                                        visibility: {
                                                                            daysOutside: false,
                                                                            today: false,
                                                                            theme: isDark ? 'dark' : 'light'
                                                                        }
                                                                    },
                                                                    actions: {
                                                                        clickDay: (e, self) => {
                                                                            // Evitar doble disparo de VanillaCalendar
                                                                            if (this._clickLock) return;
                                                                            this._clickLock = true;
                                                                            setTimeout(() => { this._clickLock = false; }, 200);

                                                                            let btn = e.target.closest('.vanilla-calendar-day__btn');
                                                                            let clickedDate = btn ? btn.dataset.calendarDay : null;
                                                                            if (!clickedDate) return;

                                                                            if (this.clickCount === 0) {
                                                                                // PRIMER CLICK
                                                                                this.selectedEventStart = clickedDate;
                                                                                this.selectedEventEnd   = '';
                                                                                this.clickCount  = 1;
                                                                                this._savedStart = clickedDate;
                                                                                this._savedCount = 1;
                                                                                this.$nextTick(() => this.refrescarEventosVisuales());
                                                                            } else {
                                                                                // SEGUNDO CLICK
                                                                                this.selectedEventEnd = clickedDate;
                                                                                let d1 = new Date(this.selectedEventStart + 'T00:00:00');
                                                                                let d2 = new Date(this.selectedEventEnd   + 'T00:00:00');
                                                                                if (d1 > d2) {
                                                                                    let tmp = this.selectedEventStart;
                                                                                    this.selectedEventStart = this.selectedEventEnd;
                                                                                    this.selectedEventEnd   = tmp;
                                                                                }
                                                                                this.clickCount  = 0;
                                                                                this._savedStart = '';
                                                                                this._savedCount = 0;
                                                                                this.showEventModal = true;
                                                                                this.$nextTick(() => this.refrescarEventosVisuales());
                                                                            }
                                                                        }
                                                                    }
                                                                });

                                                                this.picker.init();

                                                                // Restaurar estado DESPUÉS de que el picker esté listo
                                                                this.$nextTick(() => {
                                                                    this.selectedEventStart = this._savedStart;
                                                                    this.clickCount         = this._savedCount;
                                                                    this.refrescarEventosVisuales();
                                                                });
                                                            },
                                                            init() {
                                                                this.clickCount         = 0;
                                                                this.selectedEventStart = '';
                                                                this.selectedEventEnd   = '';
                                                                this._savedStart        = '';
                                                                this._savedCount        = 0;

                                                                this.calStart     = @js($form->dia_inicio_calendario_academico);
                                                                this.calEnd       = @js($form->dia_fin_calendario_academico);
                                                                this.calStartYear = parseInt(this.calStart.substring(0, 4));
                                                                this.calEndYear   = parseInt(this.calEnd.substring(0, 4));
                                                                this.currentYear  = this.calStartYear;

                                                                this.inicializarPicker(this.currentYear);

                                                                this.$watch('eventosAlpine', () => {
                                                                    if (this.picker) {
                                                                        this.picker.update();
                                                                        this.$nextTick(() => this.refrescarEventosVisuales());
                                                                    }
                                                                });
                                                            },
                                                            cambiarAnio(dir) {
                                                                const nuevoAnio = parseInt(this.currentYear) + dir;
                                                                if (nuevoAnio >= this.calStartYear && nuevoAnio <= this.calEndYear) {
                                                                    this.currentYear = nuevoAnio;
                                                                    this.inicializarPicker(nuevoAnio);
                                                                }
                                                            },
                                                                refrescarEventosVisuales() {
                                                                    if (!this.picker) return;

                                                                    // Mapear cada día al color del ÚLTIMO evento registrado que lo cubra
                                                                    let dayColors = {};

                                                                    if (this.eventosAlpine && this.eventosAlpine.length > 0) {
                                                                        this.eventosAlpine.forEach(ev => {
                                                                            let startD = new Date(ev.inicio + 'T00:00:00');
                                                                            let endD   = new Date(ev.fin   + 'T00:00:00');

                                                                            while (startD <= endD) {
                                                                                let y = startD.getFullYear();
                                                                                let m = String(startD.getMonth() + 1).padStart(2, '0');
                                                                                let d = String(startD.getDate()).padStart(2, '0');
                                                                                let dateStr = `${y}-${m}-${d}`;

                                                                                // Al sobreescribir, el último evento registrado gana el color del día
                                                                                dayColors[dateStr] = ev.color;

                                                                                startD.setDate(startD.getDate() + 1);
                                                                            }
                                                                        });
                                                                    }

                                                                    const calendarEl = this.$refs.calendar;
                                                                    if (!calendarEl) return;

                                                                    calendarEl.querySelectorAll('[data-calendar-day]').forEach(btn => {
                                                                        const day = btn.dataset.calendarDay;

                                                                        // Limpiar estilos previos
                                                                        btn.style.backgroundColor = '';
                                                                        btn.style.color = '';
                                                                        btn.style.border = '';
                                                                        btn.classList.remove('sogat-evento-registrado');

                                                                        if (dayColors[day]) {
                                                                            btn.classList.add('sogat-evento-registrado');
                                                                            // Aplicar el color del último evento registrado
                                                                            btn.style.setProperty('background-color', dayColors[day] + '26', 'important'); // 15% opacidad para el fondo
                                                                            btn.style.setProperty('color', dayColors[day], 'important');
                                                                            btn.style.setProperty('border', '2px solid ' + dayColors[day], 'important');
                                                                            btn.style.setProperty('font-weight', '900', 'important');

                                                                            const matchingEvents = this.eventosAlpine.filter(ev => {
                                                                                let s = new Date(ev.inicio + 'T00:00:00');
                                                                                let e = new Date(ev.fin   + 'T00:00:00');
                                                                                let d = new Date(day      + 'T00:00:00');
                                                                                return d >= s && d <= e;
                                                                            });

                                                                            btn.addEventListener('mouseenter', () => {
                                                                                this.tooltip.content = matchingEvents;
                                                                                this.tooltip.visible = true;
                                                                            });
                                                                            btn.addEventListener('mousemove', (e) => {
                                                                                this.tooltip.x = e.clientX;
                                                                                this.tooltip.y = e.clientY;
                                                                            });
                                                                            btn.addEventListener('mouseleave', () => {
                                                                                this.tooltip.visible = false;
                                                                                this.tooltip.content = null;
                                                                            });
                                                                        }
                                                                    });
                                                                },
                                                            closeModal() {
                                                                this.showEventModal = false;
                                                                if(this.picker) {
                                                                    this.picker.selectedDates = [];
                                                                    this.picker.update();
                                                                    // Re-apply event colors after picker resets the DOM
                                                                    this.$nextTick(() => this.refrescarEventosVisuales());
                                                                }
                                                                this.selectedEventStart = '';
                                                                this.selectedEventEnd = '';
                                                                this.eventoNombre = '';
                                                                this.eventoTipo = '1';
                                                                this.eventoColor = '';
                                                                this.eventoSeleccionado = '';
                                                                this.clickCount = 0;
                                                                sessionStorage.removeItem('sogat_event_start');
                                                                sessionStorage.removeItem('sogat_click_count');
                                                            },
                                                            guardarEvento() {
                                                                if(!this.eventoNombre.trim()) {
                                                                    alert('Debe ingresar un nombre para el evento.');
                                                                    return;
                                                                }
                                                                $wire.agregarEvento(this.selectedEventStart, this.selectedEventEnd, this.eventoSeleccionado, this.eventoNombre, this.eventoTipo, this.eventoColor);
                                                                this.closeModal();
                                                            }
                                                        }" class="space-y-6 mt-4 pt-4">

                            {{-- Floating Tooltip --}}
                            <div x-show="tooltip.visible" x-cloak
                                :style="`position: fixed; top: ${tooltip.y - 10}px; left: ${tooltip.x + 16}px; z-index: 9999; pointer-events: none; transform: translateY(-100%);`"
                                class="sogat-tooltip-card">
                                <template x-if="tooltip.content && tooltip.content.length > 0">
                                    <div>
                                        <template x-for="(ev, i) in tooltip.content" :key="i">
                                            <div :class="i > 0 ? 'mt-2 pt-2 border-t border-gray-100' : ''">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="w-3 h-3 rounded-full shadow-sm"
                                                        :style="`background-color: ${ev.color}`"></span>
                                                    <span class="font-extrabold text-sm" x-text="ev.nombre"></span>
                                                </div>

                                                <div class="text-[11px] mt-1 opacity-90">
                                                    <span x-text="ev.inicio"></span>
                                                    <template x-if="ev.inicio !== ev.fin">
                                                        <span> → <span x-text="ev.fin"></span></span>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <div class="text-center mb-6">
                                <h4 class="text-xl font-black text-gray-800 dark:text-gray-100 uppercase tracking-widest">
                                    Asignación de Eventos</h4>
                            </div>

                            {{-- Navegación de Año --}}
                            <template x-if="calStartYear && calEndYear && calStartYear < calEndYear">
                                <div class="flex items-center justify-center gap-6 mb-8 mt-2">
                                    <button type="button"
                                        @click="cambiarAnio(-1)"
                                        :disabled="currentYear <= calStartYear"
                                        :class="currentYear <= calStartYear ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer'"
                                        class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 shadow-sm transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                        </svg>
                                    </button>
                                    
                                    <div class="flex flex-col items-center">
                                        <span x-text="currentYear"
                                            class="text-7xl font-black text-gray-800 dark:text-gray-100 min-w-[150px] text-center drop-shadow-sm"
                                            style="font-family: 'Verdana', sans-serif; letter-spacing: -0.05em;"></span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Año Escolar</span>
                                    </div>

                                    <button type="button"
                                        @click="cambiarAnio(1)"
                                        :disabled="currentYear >= calEndYear"
                                        :class="currentYear >= calEndYear ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer'"
                                        class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 shadow-sm transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            {{-- Caso de un solo año --}}
                            <template x-if="calStartYear && calEndYear && calStartYear >= calEndYear">
                                <div class="text-center mb-8 mt-2">
                                    <span x-text="currentYear"
                                        class="text-7xl font-black text-gray-800 dark:text-gray-100 drop-shadow-sm"
                                        style="font-family: 'Verdana', sans-serif; letter-spacing: -0.05em;"></span>
                                </div>
                            </template>

                            <div class="flex justify-center flex-col items-center">



                                <div wire:ignore x-ref="calendar" class="sogat-datepicker-container w-full"></div>
                            </div>

                            <!-- Modal para Registrar Evento -->
                            <div x-show="showEventModal" style="display: none;"
                                class="fixed inset-0 z-50 flex items-center justify-center px-4">
                                <div @click.away="closeModal()" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                    class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-700">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 uppercase tracking-widest text-center">
                                        Registrar Evento
                                    </h3>

                                    <div
                                        class="bg-gray-100 dark:bg-gray-700/50 border-l-4 border-gray-400 p-4 rounded-r-lg mb-6 flex justify-between items-center">
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Inicio</label>
                                            <div class="text-gray-900 dark:text-gray-200 font-extrabold text-sm"
                                                x-text="selectedEventStart"></div>
                                        </div>
                                        <div class="text-gray-400 dark:text-gray-600 px-4">
                                            <span class="material-icons text-sm">arrow_forward</span>
                                        </div>
                                        <div class="text-right">
                                            <label
                                                class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Fin</label>
                                            <div class="text-gray-900 dark:text-gray-200 font-extrabold text-sm"
                                                x-text="selectedEventEnd"></div>
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Seleccionar
                                                Evento</label>
                                            <select x-model="eventoSeleccionado" x-on:change="
                                                                    let opt = $event.target.options[$event.target.selectedIndex];
                                                                    eventoNombre = opt.text; 
                                                                    eventoTipo = opt.dataset.tipo;
                                                                    eventoColor = opt.dataset.color;
                                                                "
                                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-gray-400 shadow-sm">
                                                <option value="" disabled selected>-- Seleccione un Evento --</option>
                                                @php
                                                    $idsRegistrados = collect($eventosRegistrados)->pluck('id')->toArray();
                                                @endphp
                                                @foreach($bibliotecaEventos as $evento)
                                                    @if(!in_array($evento->id_evento, $idsRegistrados))
                                                        <option value="{{ $evento->id_evento }}" data-tipo="{{ $evento->tipo_evento }}"
                                                            data-color="{{ $evento->codigo_color }}">
                                                            {{ $evento->nombre_evento }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <x-secondary-button type="button" @click="closeModal()">
                                            {{ __('Cancelar') }}
                                        </x-secondary-button>
                                        <x-primary-button type="button" @click="guardarEvento()">
                                            {{ __('Guardar') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </div>

                            {{-- Eventos Registrados Wrapper --}}
                            @if(count($eventosRegistrados) > 0)
                                <div class="w-full max-w-2xl mx-auto mt-6">
                                    <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">
                                        Eventos Registrados ({{ count($eventosRegistrados) }})</h4>
                                    <div class="space-y-3">
                                        @foreach($eventosRegistrados as $index => $evento)
                                            <div
                                                class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="w-3 h-3 rounded-full shadow-sm"
                                                            style="background-color: {{ $evento['color'] }}"></span>

                                                        <span
                                                            class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $evento['nombre'] }}</span>
                                                    </div>
                                                    <p class="text-[11px] text-gray-500 font-medium">Del
                                                        <strong>{{ $evento['inicio'] }}</strong> al
                                                        <strong>{{ $evento['fin'] }}</strong>
                                                    </p>
                                                </div>
                                                <button type="button" wire:click="removerEvento({{ $index }})"
                                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                                    <span class="material-icons text-sm">delete</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="max-w-2xl mx-auto mt-12 flex justify-end gap-4">
                            <x-secondary-button wire:click="retrocederPaso1" type="button">
                                {{ __('Volver') }}
                            </x-secondary-button>

                            <form wire:submit="guardarCalendario">
                                <x-primary-button type="submit">
                                    <span wire:loading.remove wire:target="guardarCalendario">Guardar</span>
                                    <span wire:loading wire:target="guardarCalendario">Procesando...</span>
                                </x-primary-button>
                            </form>
                        </div>
                    @endif

                    <style>
                        /* ── Quitar resaltado del día actual ─────────────────────────── */
                        .vanilla-calendar-day__btn_today,
                        .vc-day_today,
                        [class*="day__btn_today"],
                        [class*="day_today"] {
                            background-color: transparent !important;
                            color: inherit !important;
                            border: none !important;
                            box-shadow: none !important;
                        }

                        /* ── Asegurar que los días desactivados se vean iguales ────────── */
                        .vanilla-calendar-day__btn_disabled,
                        .vc-day_disabled {
                            opacity: 0.3 !important;
                            color: #999 !important;
                            background-color: transparent !important;
                        }

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

                        /* Month and Year titles in calendar */
                        .sogat-datepicker-container .vanilla-calendar-month,
                        .sogat-datepicker-container .vanilla-calendar-year,
                        .sogat-datepicker-container .vc-title,
                        .sogat-datepicker-container .vc-title__month,
                        .sogat-datepicker-container .vc-title__year {
                            color: #1f2937 !important;
                            /* text-gray-800 */
                            font-weight: 800 !important;
                            text-transform: uppercase;
                            letter-spacing: 0.05em;
                        }

                        .dark .sogat-datepicker-container .vanilla-calendar-month,
                        .dark .sogat-datepicker-container .vanilla-calendar-year,
                        .dark .sogat-datepicker-container .vc-title,
                        .dark .sogat-datepicker-container .vc-title__month,
                        .dark .sogat-datepicker-container .vc-title__year {
                            color: #ffffff !important;
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

                        /* Event Highlight Modifier */
                        .vanilla-calendar-day__btn.sogat-evento-registrado {
                            background-color: rgb(239 68 68 / 0.15) !important;
                            /* Rojo claro */
                            color: #ef4444 !important;
                            border: 2px solid #ef4444 !important;
                            font-weight: 900 !important;
                        }

                        .dark .vanilla-calendar-day__btn.sogat-evento-registrado {
                            background-color: rgb(239 68 68 / 0.25) !important;
                            color: #fca5a5 !important;
                        }

                        /* Floating Tooltip Card */
                        .sogat-tooltip-card {
                            background: #ffffff;
                            color: #1f2937;
                            border: 1px solid #e5e7eb;
                            border-radius: 0.875rem;
                            padding: 0.75rem 1rem;
                            min-width: 200px;
                            max-width: 280px;
                            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(0, 0, 0, 0.05);
                            backdrop-filter: blur(12px);
                            font-family: inherit;
                            animation: sogat-tooltip-in 0.15s ease-out forwards;
                        }

                        @keyframes sogat-tooltip-in {
                            from {
                                opacity: 0;
                                transform: translateY(-90%) scale(0.95);
                            }

                            to {
                                opacity: 1;
                                transform: translateY(-100%) scale(1);
                            }
                        }

                        [x-cloak] {
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
                                        <span
                                            class="text-base font-extrabold text-black dark:text-white uppercase transition-colors">
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