<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Crear Calendario Académico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-table.alert-message type="success" :message="session('message')" />
            <x-table.alert-message type="error" :message="session('error')" />

            <div class="sogat-card planificacion-module p-10">
                @if($paso == 1)
                    <div class="text-center mb-10">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 dark:text-gray-100 mb-2 uppercase tracking-[0.2em]">
                            Configuración del Período
                        </h3>
                    </div>

                    <style>
                        .date-input-dark::-webkit-calendar-picker-indicator {
                            background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23d1d5db'%3e%3cpath fill-rule='evenodd' d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' clip-rule='evenodd'/%3e%3c/svg%3e") no-repeat center;
                            background-size: 20px 20px;
                            width: 20px;
                            height: 20px;
                            cursor: pointer;
                        }
                        .date-input-dark { color-scheme: light; }
                    </style>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl mx-auto mt-8">
                        <div class="w-full">
                            <x-input-label for="dia_inicio_calendario_academico" :value="__('Inicio del Período')" />
                            <x-text-input id="dia_inicio_calendario_academico" type="date"
                                wire:model.live="form.dia_inicio_calendario_academico" class="w-full mt-1 date-input-dark" required />
                            <x-input-error :messages="$errors->first('form.dia_inicio_calendario_academico')" class="mt-2" />
                        </div>
                        <div class="w-full">
                            <x-input-label for="dia_fin_calendario_academico" :value="__('Fin del Período')" />
                            <x-text-input id="dia_fin_calendario_academico" type="date"
                                wire:model.live="form.dia_fin_calendario_academico" class="w-full mt-1 date-input-dark" required />
                            <x-input-error :messages="$errors->first('form.dia_fin_calendario_academico')" class="mt-2" />
                        </div>
                    </div>

                    <div class="max-w-2xl mx-auto mt-12 flex justify-end gap-4">
                        <a href="{{ route('calendario.list') }}" wire:navigate>
                            <x-secondary-button type="button">{{ __('Cancelar') }}</x-secondary-button>
                        </a>
                        <x-primary-button wire:click="avanzarPaso2" type="button">
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
                            this._savedStart = this.selectedEventStart;
                            this._savedCount = this.clickCount;
                            this.$refs.calendar.innerHTML = '';
                            this.picker = new VanillaCalendar(this.$refs.calendar, {
                                type: 'multiple',
                                months: 12,
                                displayMonthsCount: 12,
                                selectedMonth: 0,
                                selectedYear: year,
                                date: { today: new Date(year, 0, 1) },
                                settings: {
                                    lang: 'es',
                                    range: { min: this.calStart, max: this.calEnd, disablePast: false },
                                    selection: { day: 'single' },
                                    visibility: { daysOutside: false, today: false, theme: isDark ? 'dark' : 'light' }
                                },
                                actions: {
                                    clickDay: (e, self) => {
                                        if (this._clickLock) return;
                                        this._clickLock = true;
                                        setTimeout(() => { this._clickLock = false; }, 200);
                                        let btn = e.target.closest('.vanilla-calendar-day__btn');
                                        let clickedDate = btn ? btn.dataset.calendarDay : null;
                                        if (!clickedDate) return;
                                        if (this.clickCount === 0) {
                                            this.selectedEventStart = clickedDate;
                                            this.selectedEventEnd   = '';
                                            this.clickCount  = 1;
                                            this._savedStart = clickedDate;
                                            this._savedCount = 1;
                                            this.$nextTick(() => this.refrescarEventosVisuales());
                                        } else {
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
                            this.$nextTick(() => {
                                this.selectedEventStart = this._savedStart;
                                this.clickCount         = this._savedCount;
                                this.refrescarEventosVisuales();
                            });
                        },
                        init() {
                            this.clickCount = 0;
                            this.calStart = @js($form->dia_inicio_calendario_academico);
                            this.calEnd = @js($form->dia_fin_calendario_academico);
                            this.calStartYear = parseInt(this.calStart.substring(0, 4));
                            this.calEndYear = parseInt(this.calEnd.substring(0, 4));
                            this.currentYear = this.calStartYear;
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
                            let dayColors = {};
                            if (this.eventosAlpine && this.eventosAlpine.length > 0) {
                                this.eventosAlpine.forEach(ev => {
                                    let startD = new Date(ev.inicio + 'T00:00:00');
                                    let endD   = new Date(ev.fin + 'T00:00:00');
                                    while (startD <= endD) {
                                        let y = startD.getFullYear();
                                        let m = String(startD.getMonth() + 1).padStart(2, '0');
                                        let d = String(startD.getDate()).padStart(2, '0');
                                        dayColors[`${y}-${m}-${d}`] = ev.color;
                                        startD.setDate(startD.getDate() + 1);
                                    }
                                });
                            }
                            const calendarEl = this.$refs.calendar;
                            if (!calendarEl) return;
                            calendarEl.querySelectorAll('[data-calendar-day]').forEach(btn => {
                                const day = btn.dataset.calendarDay;
                                btn.style.backgroundColor = ''; btn.style.color = ''; btn.style.border = '';
                                btn.classList.remove('sogat-evento-registrado');
                                if (dayColors[day]) {
                                    btn.classList.add('sogat-evento-registrado');
                                    btn.style.setProperty('background-color', dayColors[day] + '26', 'important');
                                    btn.style.setProperty('color', dayColors[day], 'important');
                                    btn.style.setProperty('border', '2px solid ' + dayColors[day], 'important');
                                    btn.style.setProperty('font-weight', '900', 'important');
                                    const matchingEvents = this.eventosAlpine.filter(ev => {
                                        let s = new Date(ev.inicio + 'T00:00:00');
                                        let e = new Date(ev.fin + 'T00:00:00');
                                        let d = new Date(day + 'T00:00:00');
                                        return d >= s && d <= e;
                                    });
                                    btn.addEventListener('mouseenter', () => { this.tooltip.content = matchingEvents; this.tooltip.visible = true; });
                                    btn.addEventListener('mousemove', (e) => { this.tooltip.x = e.clientX; this.tooltip.y = e.clientY; });
                                    btn.addEventListener('mouseleave', () => { this.tooltip.visible = false; this.tooltip.content = null; });
                                }
                            });
                        },
                        closeModal() {
                            this.showEventModal = false;
                            if(this.picker) {
                                this.picker.selectedDates = [];
                                this.picker.update();
                                this.$nextTick(() => this.refrescarEventosVisuales());
                            }
                            this.selectedEventStart = ''; this.selectedEventEnd = ''; this.eventoNombre = '';
                            this.eventoTipo = '1'; this.eventoColor = ''; this.eventoSeleccionado = ''; this.clickCount = 0;
                        },
                        guardarEvento() {
                            if(!this.eventoNombre.trim()) { alert('Debe ingresar un nombre para el evento.'); return; }
                            $wire.agregarEvento(this.selectedEventStart, this.selectedEventEnd, this.eventoSeleccionado, this.eventoNombre, this.eventoTipo, this.eventoColor);
                            this.closeModal();
                        }
                    }" class="space-y-6 mt-4 pt-4">

                        {{-- Tooltip --}}
                        <div x-show="tooltip.visible" x-cloak :style="`position: fixed; top: ${tooltip.y - 10}px; left: ${tooltip.x + 16}px; z-index: 9999; pointer-events: none; transform: translateY(-100%);`" class="sogat-tooltip-card">
                            <template x-if="tooltip.content && tooltip.content.length > 0">
                                <div>
                                    <template x-for="(ev, i) in tooltip.content" :key="i">
                                        <div :class="i > 0 ? 'mt-2 pt-2 border-t border-gray-100' : ''">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="w-3 h-3 rounded-full shadow-sm" :style="`background-color: ${ev.color}`"></span>
                                                <span class="font-extrabold text-sm" x-text="ev.nombre"></span>
                                            </div>
                                            <div class="text-[11px] mt-1 opacity-90">
                                                <span x-text="ev.inicio"></span>
                                                <template x-if="ev.inicio !== ev.fin"><span> → <span x-text="ev.fin"></span></span></template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="text-center mb-6">
                            <h4 class="text-xl font-black text-gray-800 dark:text-gray-100 uppercase tracking-widest">Asignación de Eventos</h4>
                        </div>

                        {{-- Navegación Año --}}
                        <div class="flex items-center justify-center gap-6 mb-8 mt-2">
                            <button type="button" @click="cambiarAnio(-1)" :disabled="currentYear <= calStartYear" :class="currentYear <= calStartYear ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer'" class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 shadow-sm transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                            </button>
                            <div class="flex flex-col items-center">
                                <span x-text="currentYear" class="text-7xl font-black text-gray-800 dark:text-gray-100 min-w-[150px] text-center drop-shadow-sm" style="font-family: 'Verdana', sans-serif; letter-spacing: -0.05em;"></span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Año Escolar</span>
                            </div>
                            <button type="button" @click="cambiarAnio(1)" :disabled="currentYear >= calEndYear" :class="currentYear >= calEndYear ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer'" class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 shadow-sm transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </button>
                        </div>

                        <div class="flex justify-center flex-col items-center">
                            <div wire:ignore x-ref="calendar" class="sogat-datepicker-container w-full"></div>
                        </div>

                        {{-- Modal Registro --}}
                        <div x-show="showEventModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4">
                            <div @click.away="closeModal()" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-700">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 uppercase tracking-widest text-center">Registrar Evento</h3>
                                <div class="bg-gray-100 dark:bg-gray-700/50 border-l-4 border-gray-400 p-4 rounded-r-lg mb-6 flex justify-between items-center">
                                    <div><label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Inicio</label><div class="text-gray-900 dark:text-gray-200 font-extrabold text-sm" x-text="selectedEventStart"></div></div>
                                    <div class="text-gray-400 dark:text-gray-600 px-4"><span class="material-icons text-sm">arrow_forward</span></div>
                                    <div class="text-right"><label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Fin</label><div class="text-gray-900 dark:text-gray-200 font-extrabold text-sm" x-text="selectedEventEnd"></div></div>
                                </div>
                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Seleccionar Evento</label>
                                        <select x-model="eventoSeleccionado" x-on:change="let opt = $event.target.options[$event.target.selectedIndex]; eventoNombre = opt.text; eventoTipo = opt.dataset.tipo; eventoColor = opt.dataset.color;" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-gray-400 shadow-sm">
                                            <option value="" disabled selected>-- Seleccione un Evento --</option>
                                            @foreach($bibliotecaEventos as $evento)
                                                @if(!collect($eventosRegistrados)->contains('id', $evento->id_evento))
                                                    <option value="{{ $evento->id_evento }}" data-tipo="{{ $evento->tipo_evento }}" data-color="{{ $evento->codigo_color }}">{{ $evento->nombre_evento }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <x-secondary-button type="button" @click="closeModal()">{{ __('Cancelar') }}</x-secondary-button>
                                    <x-primary-button type="button" @click="guardarEvento()">{{ __('Guardar') }}</x-primary-button>
                                </div>
                            </div>
                        </div>

                        {{-- Lista de Eventos --}}
                        @if(count($eventosRegistrados) > 0)
                            <div class="w-full max-w-2xl mx-auto mt-6">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">Eventos Registrados ({{ count($eventosRegistrados) }})</h4>
                                <div class="space-y-3">
                                    @foreach($eventosRegistrados as $index => $evento)
                                        <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $evento['color'] }}"></span>
                                                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $evento['nombre'] }}</span>
                                                </div>
                                                <p class="text-[11px] text-gray-500 font-medium">Del <strong>{{ $evento['inicio'] }}</strong> al <strong>{{ $evento['fin'] }}</strong></p>
                                            </div>
                                            <button type="button" wire:click="removerEvento({{ $index }})" class="text-gray-400 hover:text-red-500 transition-colors"><span class="material-icons text-sm">delete</span></button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="max-w-2xl mx-auto mt-12 flex justify-end gap-4">
                            <x-secondary-button wire:click="retrocederPaso1" type="button">{{ __('Volver') }}</x-secondary-button>
                            <x-primary-button wire:click="save" type="button">
                                <span wire:loading.remove wire:target="save">Finalizar</span>
                                <span wire:loading wire:target="save">Procesando...</span>
                            </x-primary-button>
                        </div>
                    </div>

                    <style>
                        .vanilla-calendar-day__btn_today, .vc-day_today { background-color: transparent !important; color: inherit !important; border: none !important; }
                        .vanilla-calendar-day__btn_disabled, .vc-day_disabled { opacity: 0.3 !important; color: #999 !important; }
                        .sogat-datepicker-container { --vanilla-calendar-columns: 1 !important; --vc-grid-columns: 1 !important; }
                        @media (min-width: 768px) { .sogat-datepicker-container { --vanilla-calendar-columns: 2 !important; --vc-grid-columns: 2 !important; } }
                        @media (min-width: 1024px) { .sogat-datepicker-container { --vanilla-calendar-columns: 3 !important; --vc-grid-columns: 3 !important; } }
                        .sogat-datepicker-container .vanilla-calendar-grid { display: grid !important; gap: 1.5rem; grid-template-columns: repeat(var(--vanilla-calendar-columns, 1), 1fr) !important; }
                        .sogat-datepicker-container .vanilla-calendar-content { background: white; border-radius: 1rem; padding: 1rem; border: 1px solid #f3f4f6; }
                        .dark .sogat-datepicker-container .vanilla-calendar-content { background: #1f2937; border-color: #374151; }
                        .vanilla-calendar-month { font-size: 1.2rem !important; font-weight: 800 !important; text-transform: capitalize !important; color: #1a365d !important; text-align: center !important; }
                        .dark .vanilla-calendar-month { color: #3b82f6 !important; }
                        
                        /* Ocultar flechas de navegación internas */
                        .vanilla-calendar-header__btn, 
                        .vanilla-calendar-arrow, 
                        .vc-arrow, 
                        .vc-header__arrow,
                        .vanilla-calendar-header__btn_prev,
                        .vanilla-calendar-header__btn_next { 
                            display: none !important; 
                        }

                        .vanilla-calendar-year { display: none !important; }
                        .sogat-tooltip-card { background: white; border-radius: 0.875rem; padding: 0.75rem 1rem; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1); }
                        [x-cloak] { display: none !important; }
                    </style>
                @endif
            </div>
        </div>
    </div>
</div>
