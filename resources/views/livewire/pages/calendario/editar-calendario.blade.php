<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Editar Planificación de Calendario') }}
        </h2>
    </x-slot>

    <x-table.alert-message />

    <div class="sogat-card planificacion-module">

        <style>
            .date-input-dark::-webkit-calendar-picker-indicator {
                background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23d1d5db'%3e%3cpath fill-rule='evenodd' d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' clip-rule='evenodd'/%3e%3c/svg%3e") no-repeat center;
                background-size: 20px 20px;
                cursor: pointer;
            }

            .date-input-dark {
                color-scheme: light;
            }

            .vanilla-calendar-day__btn_today,
            .vc-day_today {
                background-color: transparent !important;
                color: inherit !important;
                border: none !important;
            }

            .vanilla-calendar-day__btn_disabled,
            .vc-day_disabled {
                opacity: 0.3 !important;
                color: #999 !important;
            }



            .sogat-datepicker-container {
                width: 100%;
            }

            .sogat-datepicker-container .vanilla-calendar-grid {
                display: grid !important;
                gap: 1.5rem;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important;
                justify-items: center !important;
            }

            .sogat-datepicker-container .vanilla-calendar-content {
                background: white;
                border-radius: 1rem;
                padding: 1rem;
                border: 1px solid #f3f4f6;
            }

            .dark .sogat-datepicker-container .vanilla-calendar-content {
                background: #1f2937;
                border-color: #374151;
            }

            .vanilla-calendar-month {
                font-size: 1.2rem !important;
                font-weight: 800 !important;
                text-transform: capitalize !important;
                color: #1a365d !important;
                text-align: center !important;
            }

            .dark .vanilla-calendar-month {
                color: #3b82f6 !important;
            }

            .vanilla-calendar-header__btn,
            .vanilla-calendar-arrow,
            .vc-arrow,
            .vc-header__arrow,
            .vanilla-calendar-header__btn_prev,
            .vanilla-calendar-header__btn_next {
                display: none !important;
            }

            .vanilla-calendar-year {
                display: none !important;
            }

            .sogat-tooltip-card {
                background: white;
                border-radius: 0.875rem;
                padding: 0.75rem 1rem;
                box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
            }

            [x-cloak] {
                display: none !important;
            }
        </style>        <div class="space-y-6" x-data="{ 
                    openSection: 'fechas',
                    inicio: @entangle('form.dia_inicio_calendario_academico'),
                    fin: @entangle('form.dia_fin_calendario_academico'),

                    init() {
                        this.$watch('inicio', () => this.calculateFin());
                    },

                    calculateFin() {
                        if (!this.inicio) return;
                        
                        let date = new Date(this.inicio + 'T12:00:00');
                        if (isNaN(date.getTime())) return;

                        // Semestral por defecto: +17 semanas (119 días) para terminar el mismo día de la semana 18
                        date.setDate(date.getDate() + 119);

                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        this.fin = `${year}-${month}-${day}`;
                    }
                }">

            {{-- Acordeón 1: Fechas --}}
            <div
                class="border-2 {{ $errors->has('form.dia_inicio_calendario_academico') || $errors->has('form.dia_fin_calendario_academico') ? 'border-sogat-red shadow-[0_0_10px_rgba(160,0,0,0.1)]' : 'border-gray-200 dark:border-gray-700 shadow-sm' }} rounded-xl transition-all duration-300">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 transition-colors">
                    <h4
                        class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-icons text-blue-500">calendar_today</span>
                        Configuración del Período
                    </h4>
                    <span class="material-icons transition-transform duration-200"
                        :class="openSection === 'fechas' ? 'rotate-180' : ''">expand_more</span>
                </div>
                <div x-show="openSection === 'fechas'" x-collapse class="p-4 space-y-6">

                    {{-- Selección de Fechas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl mx-auto">
                        <div class="w-full">
                            <x-input-label for="dia_inicio_calendario_academico" :value="__('Inicio del Período')" />
                            <x-text-input id="dia_inicio_calendario_academico" type="date"
                                wire:model.live="form.dia_inicio_calendario_academico"
                                class="w-full mt-1 date-input-dark" required />
                        </div>
                        <div class="w-full">
                            <x-input-label for="dia_fin_calendario_academico" :value="__('Fin del Período')" />
                            <x-text-input id="dia_fin_calendario_academico" type="date"
                                wire:model.live="form.dia_fin_calendario_academico" class="w-full mt-1 date-input-dark"
                                required />
                        </div>
                    </div>
                    <div
                        class="flex justify-end pt-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 mt-4 -mx-4 -mb-4 p-4">
                        <x-primary-button type="button" @click="openSection = 'eventos'">
                            CONTINUAR <span class="material-icons text-sm">arrow_forward</span>
                        </x-primary-button>
                    </div>
                </div>
            </div>

            {{-- Acordeón 2: Eventos --}}
            <div
                class="border-2 {{ $errors->has('eventosRegistrados') ? 'border-sogat-red shadow-[0_0_10px_rgba(160,0,0,0.1)]' : 'border-gray-200 dark:border-gray-700 shadow-sm' }} rounded-xl transition-all duration-300">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 transition-colors">
                    <h4
                        class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-icons text-red-500">event</span>
                        Revisión de Eventos
                    </h4>
                    <span class="material-icons transition-transform duration-200"
                        :class="openSection === 'eventos' ? 'rotate-180' : ''">expand_more</span>
                </div>
                <div x-show="openSection === 'eventos'" x-collapse class="p-4 space-y-6">

                    <div x-show="inicio && fin">
                        <div x-data="{
                                    openTrimestre: 1,
                                    picker1: null,
                                    picker2: null,
                                    picker3: null,
                                    picker4: null,
                                    showEventModal: false,
                                    selectedEventStart: '',
                                    selectedEventEnd: '',
                                    eventoNombre: @entangle('form.nombreEventoTemporal'),
                                    eventoTipo: '1',
                                    eventoColor: '',
                                    eventoSeleccionado: '',
                                    clickCount: 0,
                                    mapaEventosAlpine: @entangle('eventosPorFecha'),
                                    bibliotecaAlpine: @js($bibliotecaEventos),
                                    eventosAlpine: @entangle('eventosRegistrados'),

                                    tooltip: { visible: false, x: 0, y: 0, content: null },
                                    tooltipTimeout: null,
                                    isOverTooltip: false,
                                    currentYear: null,
                                    calStartYear: null,
                                    calEndYear: null,
                                    _savedStart: '',
                                    _savedCount: 0,
                                    _clickLock: false,
                                    showQuickModal: false,
                                    nuevoColorId: @entangle('form.nuevoColorId'),
                                    nuevoTipo: @entangle('form.nuevoTipo'),
                                    nuevoLaborable: @entangle('form.nuevoLaborable'),
                                     nuevoRepetible: @entangle('form.nuevoRepetible'),
                                      nuevoIsRangoDias: @entangle('form.nuevoIsRangoDias'),
                                      nuevoRangoDias: @entangle('form.nuevoRangoDias'),
                                      nuevoIsIndependiente: @entangle('form.nuevoIsIndependiente'),


                                    formatDate(dateStr) {
                                        if (!dateStr) return '';
                                        const parts = dateStr.split('-');
                                        return `${parts[2]}/${parts[1]}/${parts[0]}`;
                                    },

                                    isTrimestreHabilitado(trimIndex) {
                                        if (!inicio || !fin) return false;
                                        const startMonth = (trimIndex - 1) * 3;
                                        const endMonth = trimIndex * 3 - 1;
                                        
                                        // Fecha inicial del trimestre para el año actual
                                        const trimStart = new Date(this.currentYear, startMonth, 1);
                                        // Fecha final del trimestre (último día del mes final)
                                        const trimEnd = new Date(this.currentYear, endMonth + 1, 0);
                                        
                                        const calInicio = new Date(inicio + 'T00:00:00');
                                        const calFin = new Date(fin + 'T00:00:00');
                                        
                                        // Hay solapamiento si (trimStart <= calFin) && (trimEnd >= calInicio)
                                        return (trimStart <= calFin) && (trimEnd >= calInicio);
                                    },

                                    handleMouseEnterDay(btn, day) {
                                        clearTimeout(this.tooltipTimeout);
                                        const events = this.mapaEventosAlpine[day];
                                        if (!events || events.length === 0) {
                                            this.tooltip.visible = false;
                                            return;
                                        }
                                        const rect = btn.getBoundingClientRect();
                                        this.tooltip.content = events;
                                        this.tooltip.visible = true;
                                        this.tooltip.x = rect.left + (rect.width / 2);
                                        this.tooltip.y = rect.top;
                                    },

                                    handleMouseLeaveDay() {
                                        this.tooltipTimeout = setTimeout(() => {
                                            this.tooltip.visible = false;
                                            this.tooltip.content = null;
                                        }, 300);
                                    },
                                    
                                    abrirPrimerTrimestreValido() {
                                         for (let i = 1; i <= 4; i++) {
                                             if (this.isTrimestreHabilitado(i)) {
                                                this.openTrimestre = i;
                                                return;
                                             }
                                         }
                                         this.openTrimestre = null;
                                     },
                                    
                                    getVanillaConfig(year, monthIndex) {
                                        const isDark = document.documentElement.classList.contains('dark');
                                        return {
                                            type: 'multiple',
                                            months: 3,
                                            settings: {
                                                lang: 'es',
                                                selected: {
                                                    month: monthIndex,
                                                    year: year
                                                },
                                                range: { 
                                                    min: inicio, 
                                                    max: fin, 
                                                    disablePast: false, 
                                                    disableAllDays: false
                                                },
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
                                                        let targetYear = new Date(this.selectedEventStart + 'T00:00:00').getFullYear();
                                                        $wire.set('selectedYearTemporal', targetYear);
                                                        this.showEventModal = true;
                                                        this.$nextTick(() => this.refrescarEventosVisuales());
                                                    }
                                                }
                                            }
                                        };
                                    },

                                    inicializarPicker(year) {
                                        if(!inicio || !fin) return;
                                        this._savedStart = this.selectedEventStart;
                                        this._savedCount = this.clickCount;
                                        
                                        if(this.$refs.calendar1) { this.$refs.calendar1.innerHTML = ''; this.picker1 = new VanillaCalendar(this.$refs.calendar1, this.getVanillaConfig(year, 0)); this.picker1.init(); }
                                        if(this.$refs.calendar2) { this.$refs.calendar2.innerHTML = ''; this.picker2 = new VanillaCalendar(this.$refs.calendar2, this.getVanillaConfig(year, 3)); this.picker2.init(); }
                                        if(this.$refs.calendar3) { this.$refs.calendar3.innerHTML = ''; this.picker3 = new VanillaCalendar(this.$refs.calendar3, this.getVanillaConfig(year, 6)); this.picker3.init(); }
                                        if(this.$refs.calendar4) { this.$refs.calendar4.innerHTML = ''; this.picker4 = new VanillaCalendar(this.$refs.calendar4, this.getVanillaConfig(year, 9)); this.picker4.init(); }

                                        this.$nextTick(() => {
                                            this.selectedEventStart = this._savedStart;
                                            this.clickCount         = this._savedCount;
                                            this.refrescarEventosVisuales();
                                        });
                                    },

                                    init() {
                                        this.clickCount = 0;
                                        this.$watch('inicio', (val) => { if(val && fin) this.setupCalendar(); });
                                        this.$watch('fin', (val) => { if(val && inicio) this.setupCalendar(); });
                                        this.$watch('nuevoTipo', (val) => {
                                            if (val == '1' || val == '2') {
                                                this.nuevoLaborable = false;
                                                this.nuevoRepetible = false;
                                                this.nuevoIsRangoDias = false;
                                                this.nuevoRangoDias = '';
                                            }
                                        });
                                        this.$watch('mapaEventosAlpine', () => {
                                            if (this.picker1) this.picker1.update();
                                            if (this.picker2) this.picker2.update();
                                            if (this.picker3) this.picker3.update();
                                            if (this.picker4) this.picker4.update();
                                            this.$nextTick(() => this.refrescarEventosVisuales());
                                        });

                                        // Delegación de eventos para tooltips
                                        this.$nextTick(() => {
                                            [this.$refs.calendar1, this.$refs.calendar2, this.$refs.calendar3, this.$refs.calendar4].forEach(calendarEl => {
                                                if (!calendarEl) return;
                                                calendarEl.addEventListener('mouseover', (e) => {
                                                    const btn = e.target.closest('[data-calendar-day]');
                                                    if (btn && btn.classList.contains('sogat-evento-registrado')) {
                                                        this.handleMouseEnterDay(btn, btn.dataset.calendarDay);
                                                    }
                                                });
                                                calendarEl.addEventListener('mouseout', (e) => {
                                                    const btn = e.target.closest('[data-calendar-day]');
                                                    if (btn) this.handleMouseLeaveDay();
                                                });
                                            });
                                        });

                                        if(inicio && fin) this.setupCalendar();
                                    },
                                    setupCalendar() {
                                        this.calStartYear = parseInt(inicio.substring(0, 4));
                                        this.calEndYear = parseInt(fin.substring(0, 4));
                                        this.currentYear = this.calStartYear;
                                        this.abrirPrimerTrimestreValido();
                                        this.inicializarPicker(this.currentYear);
                                    },

                                    cambiarAnio(dir) {
                                        const nuevoAnio = parseInt(this.currentYear) + dir;
                                        if (nuevoAnio >= this.calStartYear && nuevoAnio <= this.calEndYear) {
                                            this.currentYear = nuevoAnio;
                                            this.abrirPrimerTrimestreValido();
                                            this.inicializarPicker(nuevoAnio);
                                        }
                                    },
                                    refrescarEventosVisuales() {
                                        if (!this.picker1 && !this.picker2 && !this.picker3 && !this.picker4) return;
                                        this.mapaEventosAlpine = this.mapaEventosAlpine || {};
                                        const dayColors = {};
                                        const dayEventsCount = {};
                                            
                                        Object.keys(this.mapaEventosAlpine).forEach(fecha => {
                                            const eventos = this.mapaEventosAlpine[fecha];
                                            if (eventos && eventos.length > 0) {
                                                dayColors[fecha] = eventos[0].color;
                                                dayEventsCount[fecha] = eventos.length;
                                            }
                                        });

                                        [this.$refs.calendar1, this.$refs.calendar2, this.$refs.calendar3, this.$refs.calendar4].forEach(calendarEl => {
                                            if (!calendarEl) return;
                                            calendarEl.querySelectorAll('[data-calendar-day]').forEach(btn => {
                                                const day = btn.dataset.calendarDay;
                                                const dObj = new Date(day + 'T00:00:00');
                                                const dayOfWeek = dObj.getDay();

                                                btn.style.backgroundColor = ''; btn.style.color = ''; btn.style.border = '';
                                                btn.style.fontWeight = '';
                                                btn.style.position = '';
                                                btn.classList.remove('sogat-evento-registrado');
                                            
                                            const existingBadge = btn.querySelector('.sogat-event-counter');
                                            if (existingBadge) existingBadge.remove();

                                            if (dayColors[day]) {
                                                btn.classList.add('sogat-evento-registrado');
                                                btn.style.setProperty('background-color', dayColors[day] + '26', 'important');
                                                btn.style.setProperty('color', dayColors[day], 'important');
                                                btn.style.setProperty('border', '2px solid ' + dayColors[day], 'important');
                                                btn.style.setProperty('font-weight', '900', 'important');
                                                
                                                if (dayEventsCount[day] >= 2) {
                                                    const badge = document.createElement('span');
                                                    badge.className = 'sogat-event-counter absolute -top-2 -right-1 w-4 h-4 bg-gray-700 text-white text-[9px] flex items-center justify-center rounded-full border border-white dark:border-gray-800 shadow-sm font-bold z-10';
                                                    badge.innerText = dayEventsCount[day];
                                                    btn.style.position = 'relative';
                                                    btn.appendChild(badge);
                                                }
                                            }
                                            });
                                                            this.tooltip.content = null;
                                                        }
                                                    }, 300);
                                                });

                                                calendarEl.addEventListener('mouseleave', () => {
                                                    this.tooltipTimeout = setTimeout(() => {
                                                        if (!this.isOverTooltip) {
                                                            this.tooltip.visible = false;
                                                            this.tooltip.content = null;
                                                        }
                                                    }, 300);
                                                });
                                                calendarEl._hasTooltipListeners = true;
                                            }
                                        });
                                    },

                                    guardarEvento() {
                                         if(!this.eventoNombre || !this.eventoNombre.trim()) { alert('Debe ingresar un nombre para el evento.'); return; }
                                         
                                         // Buscar si el nombre existe en la biblioteca
                                         const existing = this.bibliotecaAlpine.find(o => o.nombre_evento.trim().toUpperCase() === this.eventoNombre.trim().toUpperCase());
                                         
                                         if (existing) {
                                             if (this._clickLock) return;
                                             this._clickLock = true;
                                             this.eventoSeleccionado = existing.id_evento;
                                             this.eventoTipo = existing.tipo_evento;
                                             this.eventoColor = existing.codigo_color;
                                             $wire.agregarEvento(this.selectedEventStart, this.selectedEventEnd, this.eventoSeleccionado, this.eventoNombre, this.eventoTipo, this.eventoColor)
                                                .then(() => { 
                                                    this._clickLock = false; 
                                                    this.closeModal(); 
                                                });
                                         } else {
                                             // No existe, abrir el modal de registro rápido
                                             this.showEventModal = false;
                                             this.showQuickModal = true;
                                             this.isCreatingEvento = true;
                                         }
                                     },

                                     confirmarNuevoEvento() {
                                         if (!this.nuevoColorId) { alert('Debe seleccionar un color para el nuevo evento.'); return; }
                                         if (this._clickLock) return;
                                         this._clickLock = true;
                                         
                                         $wire.crearYAgregarEvento(
                                             this.selectedEventStart, 
                                             this.selectedEventEnd, 
                                             this.eventoNombre, 
                                             this.nuevoTipo, 
                                             this.nuevoColorId, 
                                             this.nuevoLaborable, 
                                             this.nuevoRepetible,
                                             this.nuevoIsRangoDias,
                                             this.nuevoRangoDias
                                         ).then(success => {
                                             this._clickLock = false;
                                             if (success) {
                                                 this.showQuickModal = false;
                                                 this.closeModal();
                                             }
                                         });
                                     },

                                     closeModal() {
                                         $wire.set('selectedYearTemporal', null);
                                         this.showEventModal = false;
                                         this.showQuickModal = false;
                                         if(this.picker1) {
                                             this.picker1.selectedDates = []; this.picker1.update();
                                             this.picker2.selectedDates = []; this.picker2.update();
                                             this.picker3.selectedDates = []; this.picker3.update();
                                             this.picker4.selectedDates = []; this.picker4.update();
                                             this.$nextTick(() => this.refrescarEventosVisuales());
                                         }
                                         this.selectedEventStart = ''; this.selectedEventEnd = ''; this.eventoNombre = '';
                                         this.eventoSeleccionado = ''; this.clickCount = 0;
                                         this.nuevoColorId = ''; this.nuevoTipo = '1'; this.nuevoLaborable = false; this.nuevoRepetible = false; this.nuevoIsRangoDias = false; this.nuevoRangoDias = ''; this.nuevoIsIndependiente = true;
                                     },
                                     eliminarEventoDesdeTooltip(ev) {
                                        let index = this.eventosAlpine.findIndex(e => e.id === ev.id && e.inicio === ev.inicio && e.fin === ev.fin);
                                        if (index !== -1) {
                                            $wire.removerEvento(index);
                                            this.tooltip.visible = false;
                                            this.tooltip.content = null;
                                        }
                                    },
                                    contarEventosTrimestre(m1, m2) {
                                        if (!this.eventosAlpine || this.eventosAlpine.length === 0) return 0;
                                        const tStart = new Date(this.currentYear, m1, 1);
                                        const tEnd = new Date(this.currentYear, m2 + 1, 0);
                                        return this.eventosAlpine.filter(ev => {
                                            const evStart = new Date(ev.inicio + 'T00:00:00');
                                            const evEnd = new Date(ev.fin + 'T00:00:00');
                                            return evStart <= tEnd && evEnd >= tStart;
                                        }).length;
                                    }
                                }" class="space-y-6 pt-4">

                            {{-- Tooltip --}}
                            <div x-show="tooltip.visible" x-cloak wire:ignore
                                @mouseenter="isOverTooltip = true; clearTimeout(tooltipTimeout)"
                                @mouseleave="isOverTooltip = false; tooltipTimeout = setTimeout(() => { tooltip.visible = false; tooltip.content = null; }, 300)"
                                :style="`position: fixed; top: ${tooltip.y - 8}px; left: ${tooltip.x}px; z-index: 9999; transform: translate(-50%, -100%); pointer-events: auto; min-width: 250px;`"
                                class="sogat-tooltip-card bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                                <template x-if="tooltip.content && tooltip.content.length > 0">
                                    <div>
                                        <template x-for="(ev, i) in tooltip.content" :key="i">
                                            <div :class="i > 0 ? 'mt-3 pt-3 border-t border-gray-100 dark:border-gray-700' : ''"
                                                class="flex justify-between items-center gap-4">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="w-3 h-3 rounded-full shadow-sm"
                                                            :style="`background-color: ${ev.color}`"></span>
                                                        <span
                                                            class="font-extrabold text-sm text-gray-800 dark:text-gray-100"
                                                            x-text="ev.nombre_evento || 'Evento sin nombre'"></span>
                                                    </div>
                                                    <div
                                                        class="text-[11px] mt-1 opacity-90 text-gray-600 dark:text-gray-400">
                                                        <span x-text="ev.inicio"></span>
                                                        <template x-if="ev.inicio !== ev.fin"><span> → <span
                                                                    x-text="ev.fin"></span></span></template>
                                                    </div>
                                                </div>
                                                <button type="button" @click="eliminarEventoDesdeTooltip(ev)"
                                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors">
                                                    <span class="material-icons text-sm">delete</span>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            {{-- Navegación Año --}}
                            <template x-if="calStartYear && calEndYear && calStartYear < calEndYear">
                                <div class="flex items-center justify-center gap-6 mb-8 mt-2">
                                    <button type="button" @click="cambiarAnio(-1)"
                                        :disabled="currentYear <= calStartYear"
                                        :class="currentYear <= calStartYear ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer'"
                                        class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 shadow-sm transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                            viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 19.5 8.25 12l7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div class="flex flex-col items-center">
                                        <span x-text="currentYear"
                                            class="text-7xl font-black text-gray-800 dark:text-gray-100 min-w-[150px] text-center drop-shadow-sm"
                                            style="font-family: 'Verdana', sans-serif; letter-spacing: -0.05em;"></span>

                                    </div>
                                    <button type="button" @click="cambiarAnio(1)" :disabled="currentYear >= calEndYear"
                                        :class="currentYear >= calEndYear ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer'"
                                        class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 shadow-sm transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                            viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <template x-if="calStartYear && calEndYear && calStartYear >= calEndYear">
                                <div class="text-center mb-4 mt-2">
                                    <span x-text="currentYear"
                                        class="text-7xl font-black text-gray-800 dark:text-gray-100 drop-shadow-sm"
                                        style="font-family: 'Verdana', sans-serif; letter-spacing: -0.05em;"></span>
                                </div>
                            </template>

                            @if ($this->vacacionesContador)
                                <div class="flex justify-center mb-6 -mt-4">
                                    @if ($this->vacacionesContador['faltantes'] > 0)
                                        <div class="px-4 py-1.5 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 rounded-full text-amber-600 dark:text-amber-400 text-xs font-bold flex items-center">
                                            <span>
                                                Vacaciones Colectivas {{ $this->vacacionesContador['anio'] }}: Asignados {{ $this->vacacionesContador['total_assignados'] }} de {{ $this->vacacionesContador['requeridos'] }} días
                                                @if ($this->vacacionesContador['asignados_otros'] > 0)
                                                    <span class="text-[10px] opacity-80">({{ $this->vacacionesContador['asignados_otros'] }} días en otros períodos)</span>
                                                @endif
                                            </span>
                                        </div>
                                    @elseif ($this->vacacionesContador['excedidos'] > 0)
                                        <div class="px-4 py-1.5 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-full text-red-600 dark:text-red-400 text-xs font-bold flex items-center">
                                            <span>
                                                Vacaciones Colectivas {{ $this->vacacionesContador['anio'] }}: Asignados {{ $this->vacacionesContador['total_assignados'] }} de {{ $this->vacacionesContador['requeridos'] }} días
                                                @if ($this->vacacionesContador['asignados_otros'] > 0)
                                                    <span class="text-[10px] opacity-80">({{ $this->vacacionesContador['asignados_otros'] }} días en otros períodos)</span>
                                                @endif
                                            </span>
                                        </div>
                                    @else
                                        <div class="px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-full text-blue-600 dark:text-blue-400 text-xs font-bold flex items-center">
                                            <span>
                                                Vacaciones Colectivas {{ $this->vacacionesContador['anio'] }}: Asignados {{ $this->vacacionesContador['total_assignados'] }} de {{ $this->vacacionesContador['requeridos'] }} días
                                                @if ($this->vacacionesContador['asignados_otros'] > 0)
                                                    <span class="text-[10px] opacity-80">({{ $this->vacacionesContador['asignados_otros'] }} días en otros períodos)</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif


                            {{-- Indicador de fecha seleccionada (siempre visible) --}}
                            <div x-show="selectedEventStart" class="flex justify-center mb-6 -mt-4">
                                <div
                                    class="px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-full text-blue-600 dark:text-blue-400 text-xs font-bold flex items-center gap-2">
                                    <span class="material-icons text-sm">event</span>
                                    <span
                                        x-text="'Fecha de inicio seleccionada para el evento: ' + formatDate(selectedEventStart)"></span>
                                </div>
                            </div>

                            <div class="space-y-4 w-full">
                                <!-- Trimestre 1 -->
                                <div
                                    class="border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-sm transition-all duration-300">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 transition-colors"
                                        :class="!isTrimestreHabilitado(1) ? 'opacity-40 cursor-not-allowed bg-gray-200 dark:bg-gray-800' : 'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800'"
                                        @click="if(isTrimestreHabilitado(1)) openTrimestre = openTrimestre === 1 ? null : 1">
                                        <h4
                                            class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                            Primer Trimestre del Año
                                            <template x-if="isTrimestreHabilitado(1)">
                                                <span>| Eventos Asignados: <span
                                                        x-text="contarEventosTrimestre(0, 2)"></span></span>
                                            </template>
                                        </h4>
                                        <span class="material-icons transition-transform duration-200"
                                            x-show="isTrimestreHabilitado(1)"
                                            :class="openTrimestre === 1 ? 'rotate-180' : ''">expand_more</span>
                                        <span class="material-icons text-gray-400"
                                            x-show="!isTrimestreHabilitado(1)">lock</span>
                                    </div>
                                    <div x-show="openTrimestre === 1" x-collapse
                                        class="p-4 flex justify-center flex-col items-center">
                                        <div wire:ignore x-ref="calendar1" class="sogat-datepicker-container w-full">
                                        </div>
                                    </div>
                                </div>
                                <!-- Trimestre 2 -->
                                <div
                                    class="border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-sm transition-all duration-300">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 transition-colors"
                                        :class="!isTrimestreHabilitado(2) ? 'opacity-40 cursor-not-allowed bg-gray-200 dark:bg-gray-800' : 'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800'"
                                        @click="if(isTrimestreHabilitado(2)) openTrimestre = openTrimestre === 2 ? null : 2">
                                        <h4
                                            class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                            Segundo Trimestre del Año
                                            <template x-if="isTrimestreHabilitado(2)">
                                                <span>| Eventos Asignados: <span
                                                        x-text="contarEventosTrimestre(3, 5)"></span></span>
                                            </template>
                                        </h4>
                                        <span class="material-icons transition-transform duration-200"
                                            x-show="isTrimestreHabilitado(2)"
                                            :class="openTrimestre === 2 ? 'rotate-180' : ''">expand_more</span>
                                        <span class="material-icons text-gray-400"
                                            x-show="!isTrimestreHabilitado(2)">lock</span>
                                    </div>
                                    <div x-show="openTrimestre === 2" x-collapse
                                        class="p-4 flex justify-center flex-col items-center">
                                        <div wire:ignore x-ref="calendar2" class="sogat-datepicker-container w-full">
                                        </div>
                                    </div>
                                </div>
                                <!-- Trimestre 3 -->
                                <div
                                    class="border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-sm transition-all duration-300">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 transition-colors"
                                        :class="!isTrimestreHabilitado(3) ? 'opacity-40 cursor-not-allowed bg-gray-200 dark:bg-gray-800' : 'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800'"
                                        @click="if(isTrimestreHabilitado(3)) openTrimestre = openTrimestre === 3 ? null : 3">
                                        <h4
                                            class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                            Tercer Trimestre del Año
                                            <template x-if="isTrimestreHabilitado(3)">
                                                <span>| Eventos Asignados: <span
                                                        x-text="contarEventosTrimestre(6, 8)"></span></span>
                                            </template>
                                        </h4>
                                        <span class="material-icons transition-transform duration-200"
                                            x-show="isTrimestreHabilitado(3)"
                                            :class="openTrimestre === 3 ? 'rotate-180' : ''">expand_more</span>
                                        <span class="material-icons text-gray-400"
                                            x-show="!isTrimestreHabilitado(3)">lock</span>
                                    </div>
                                    <div x-show="openTrimestre === 3" x-collapse
                                        class="p-4 flex justify-center flex-col items-center">
                                        <div wire:ignore x-ref="calendar3" class="sogat-datepicker-container w-full">
                                        </div>
                                    </div>
                                </div>
                                <!-- Trimestre 4 -->
                                <div
                                    class="border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-sm transition-all duration-300">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 transition-colors"
                                        :class="!isTrimestreHabilitado(4) ? 'opacity-40 cursor-not-allowed bg-gray-200 dark:bg-gray-800' : 'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800'"
                                        @click="if(isTrimestreHabilitado(4)) openTrimestre = openTrimestre === 4 ? null : 4">
                                        <h4
                                            class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                            Cuarto Trimestre del Año
                                            <template x-if="isTrimestreHabilitado(4)">
                                                <span>| Eventos Asignados: <span
                                                        x-text="contarEventosTrimestre(9, 11)"></span></span>
                                            </template>
                                        </h4>
                                        <span class="material-icons transition-transform duration-200"
                                            x-show="isTrimestreHabilitado(4)"
                                            :class="openTrimestre === 4 ? 'rotate-180' : ''">expand_more</span>
                                        <span class="material-icons text-gray-400"
                                            x-show="!isTrimestreHabilitado(4)">lock</span>
                                    </div>
                                    <div x-show="openTrimestre === 4" x-collapse
                                        class="p-4 flex justify-center flex-col items-center">
                                        <div wire:ignore x-ref="calendar4" class="sogat-datepicker-container w-full">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Registro --}}
                            <div x-show="showEventModal" style="display: none;"
                                class="fixed inset-0 z-50 flex items-center justify-center px-4">
                                <div @click.away="closeModal()" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-700">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 uppercase tracking-widest text-center">
                                        Registrar Evento</h3>
                                    <div
                                        class="bg-gray-100 dark:bg-gray-700/50 border-l-4 border-gray-400 p-4 rounded-r-lg mb-6 flex justify-between items-center">
                                        <div><label
                                                class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Inicio</label>
                                            <div class="text-gray-900 dark:text-gray-200 font-extrabold text-sm"
                                                x-text="selectedEventStart"></div>
                                        </div>
                                        <div class="text-gray-400 dark:text-gray-600 px-4"><span
                                                class="material-icons text-sm">arrow_forward</span></div>
                                        <div class="text-right"><label
                                                class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Fin</label>
                                            <div class="text-gray-900 dark:text-gray-200 font-extrabold text-sm"
                                                x-text="selectedEventEnd"></div>
                                        </div>
                                    </div>
                                    <div class="space-y-5">
                                        <div>
                                            <x-datalist wire:key="datalist-edit-{{ count($bibliotecaFiltrada) }}"
                                                label="Nombre del Evento" :options="$bibliotecaFiltrada"
                                                textField="nombre_evento" wire:model.live="form.nombreEventoTemporal"
                                                placeholder="ESCRIBA O SELECCIONE UN EVENTO" />
                                        </div>
                                    </div>
                                    <div
                                        class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <x-secondary-button type="button"
                                            @click="closeModal()">{{ __('Cancelar') }}</x-secondary-button>
                                        <x-primary-button type="button"
                                            @click="guardarEvento()">{{ __('Guardar') }}</x-primary-button>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Registro Rápido (Nuevo Evento) --}}
                            <div x-show="showQuickModal" style="display: none;"
                                class="fixed inset-0 z-50 flex items-center justify-center px-4">
                                <div @click.away="closeModal()" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-2xl w-full max-w-3xl border border-gray-200 dark:border-gray-700">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2 uppercase tracking-widest text-center">
                                        Nuevo Evento Detectado
                                    </h3>
                                    <p class="text-center text-gray-500 dark:text-gray-400 text-sm mb-6">
                                        El evento "<span class="font-bold text-gray-700 dark:text-gray-200"
                                            x-text="eventoNombre"></span>" no existe en la biblioteca. Por favor,
                                        configúrelo:
                                    </p>
                                    <div class="space-y-6">
                                        {{-- Primera Fila: Color y Tipo --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            {{-- Selección de Color --}}
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Color
                                                    del Evento</label>
                                                <div x-data="{ 
                                                          openColores: false, 
                                                          colores: @entangle('colores'),
                                                          get selectedHex() {
                                                              let color = this.colores.find(c => c.id_color == nuevoColorId);
                                                              return color ? color.codigo_color : null;
                                                          },
                                                          get selectedName() {
                                                              let color = this.colores.find(c => c.id_color == nuevoColorId);
                                                              return color ? color.nombre_color : 'Seleccione un color';
                                                          }
                                                      }" class="relative">

                                                    <button @click="openColores = !openColores" type="button"
                                                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-3 text-left focus:ring-2 focus:ring-gray-400 flex items-center justify-between transition-all">
                                                        <div class="flex items-center gap-3 overflow-hidden">
                                                            <div x-show="selectedHex"
                                                                class="flex-shrink-0 w-5 h-5 rounded-full border border-gray-200 dark:border-gray-700 shadow-sm"
                                                                :style="'background-color: ' + selectedHex"></div>
                                                            <span
                                                                class="text-sm text-gray-700 dark:text-gray-200 font-medium truncate"
                                                                x-text="selectedName.toUpperCase()"></span>
                                                        </div>
                                                        <span
                                                            class="material-icons text-gray-400 transition-transform duration-200"
                                                            :class="openColores ? 'rotate-180' : ''">expand_more</span>
                                                    </button>

                                                    <div x-show="openColores" @click.away="openColores = false"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 translate-y-2"
                                                        x-transition:enter-end="opacity-100 translate-y-0"
                                                        class="absolute z-[60] mt-2 w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 max-h-60 overflow-y-auto sogat-scrollbar">
                                                        <ul class="divide-y divide-gray-50 dark:divide-gray-700/50">
                                                            @foreach($colores as $color)
                                                                <li @click="nuevoColorId = {{ $color->id_color }}; openColores = false"
                                                                    class="cursor-pointer select-none w-full px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center transition-colors"
                                                                    :class="nuevoColorId == {{ $color->id_color }} ? 'bg-gray-50 dark:bg-gray-700/50' : ''">
                                                                    <div class="flex items-center gap-4">
                                                                        <span
                                                                            class="w-7 h-7 rounded-full border border-gray-200 dark:border-gray-700 shadow-sm"
                                                                            style="background-color: {{ $color->codigo_color }}"></span>
                                                                        <span
                                                                            class="text-gray-900 dark:text-gray-200 text-sm font-semibold">{{ mb_strtoupper($color->nombre_color) }}</span>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Tipo de Evento --}}
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tipo
                                                    de Evento</label>
                                                <select x-model="nuevoTipo" wire:model.live="form.nuevoTipo"
                                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-gray-400">
                                                    <option value="1">FERIADOS NACIONALES</option>
                                                    <option value="2">FERIADOS LOCALES</option>
                                                    <option value="3">ADMINISTRATIVO</option>
                                                    <option value="4">ACADÉMICO</option>
                                                    <option value="5">ADMINISTRATIVO/ACADÉMICO</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Segunda Fila: Switches --}}
                                        <div x-show="nuevoTipo != '1' && nuevoTipo != '2'"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 -translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6 pt-2">

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <x-toggle-switch id="laborable_switch_edit" :label="__('¿Es Laborable?')" model="form.nuevoLaborable" />
                                                <x-toggle-switch id="repetible_switch_edit" :label="__('¿Es Repetible?')" model="form.nuevoRepetible" />
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                                                <div>
                                                    <x-toggle-switch id="is_rango_dias_switch_edit" :label="__('¿Tiene cantidad especifica días?')" model="form.nuevoIsRangoDias" />
                                                </div>
                                                <div x-show="nuevoIsRangoDias" x-transition>
                                                    <x-input-label for="nuevo_rango_dias_input_edit"
                                                        :value="__('Cantidad de Días')" class="mb-2" />
                                                    <x-text-input id="nuevo_rango_dias_input_edit" type="number"
                                                        class="w-full block" wire:model.live="form.nuevoRangoDias"
                                                        placeholder="EJ: 5" min="1" max="90" />
                                                    <x-input-error :messages="$errors->get('form.nuevoRangoDias')"
                                                        class="mt-2" />
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <x-toggle-switch id="nuevo_is_independiente_switch_edit" :label="__('¿Es Independiente?')"
                                                        model="form.nuevoIsIndependiente" />
                                                    <x-input-error :messages="$errors->get('form.nuevoIsIndependiente')"
                                                        class="mt-2" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="flex flex-col gap-3 mt-8">
                                        <x-primary-button @click="confirmarNuevoEvento()" wire:loading.attr="disabled"
                                            class="w-full justify-center py-3 rounded-xl">
                                            <span wire:loading.remove>REGISTRAR Y ASIGNAR</span>
                                            <span wire:loading>PROCESANDO...</span>
                                        </x-primary-button>
                                        <x-secondary-button @click="closeModal()"
                                            class="w-full justify-center py-3 rounded-xl">
                                            CANCELAR
                                        </x-secondary-button>
                                    </div>
                                </div>
                            </div>

                            {{-- Botón para regresar a la sección anterior --}}
                            <div class="flex justify-start mt-8 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <x-secondary-button type="button" @click="openSection = 'fechas'">
                                    <span class="material-icons text-sm mr-2">arrow_back</span>
                                    VOLVER
                                </x-secondary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="flex justify-end pt-4 gap-4">
                <!-- Botón Cancelar -->
                <x-danger-button type="button" wire:click="cancelar">
                    <link rel="stylesheet"
                        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
                    <span class="material-symbols-outlined">
                        arrow_back
                    </span>
                    {{ __('Volver') }}
                </x-danger-button>

                <!-- Botón Editar -->
                <x-secondary-button type="button" wire:click="actualizar" wire:loading.attr="disabled">
                    {{ __('Editar Calendario') }}
                </x-secondary-button>

                <!-- Botón Aprobar -->
                <x-secondary-button type="button" wire:click="aprobar" wire:loading.attr="disabled">
                    {{ __('Aceptar Calendario') }}
                </x-secondary-button>
            </div>
        </div>
    </div>
</div>