<div x-data="{
        showModal: false,
        timer: null,
        countdown: 0,
        countdownTimer: null,
        sessionLifetime: @js($sessionLifetime * 60), {{-- convertir a segundos --}}
        warningBefore: @js($warningTime * 60), {{-- segundos antes de expirar --}}
        
        init() {
            this.resetTimers();
            
            {{-- Resetear timer en caso de clics o teclas --}}
            window.addEventListener('click', () => this.resetTimers());
            window.addEventListener('keydown', () => this.resetTimers());
            
            {{-- Escuchar el evento de refresco desde Livewire --}}
            this.$on('session-refreshed', () => {
                this.showModal = false;
                this.resetTimers();
            });
        },
        
        resetTimers() {
            if (this.showModal) return; {{-- No resetear si el modal ya está visible --}}
            
            clearTimeout(this.timer);
            clearInterval(this.countdownTimer);
            
            {{-- Tiempo antes de mostrar el aviso --}}
            let timeToShowWarning = (this.sessionLifetime - this.warningBefore) * 1000;
            
            this.timer = setTimeout(() => {
                this.startWarning();
            }, timeToShowWarning);
        },
        
        startWarning() {
            this.showModal = true;
            this.countdown = this.warningBefore;
            
            this.countdownTimer = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    clearInterval(this.countdownTimer);
                    this.$wire.logout();
                }
            }, 1000);
        },
        
        formatTime(seconds) {
            let mins = Math.floor(seconds / 60);
            let secs = seconds % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }
    }" 
    x-show="showModal" 
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center overflow-y-auto px-4 py-6 sm:px-0"
    style="display: none;"
>
    {{-- Overlay --}}
    <div class="fixed inset-0 transform transition-all" x-on:click="showModal = false">
        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
    </div>

    {{-- Modal Content --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">
        <div class="p-8">
            <div class="flex items-center justify-center w-20 h-20 mx-auto bg-amber-100 dark:bg-amber-900/30 rounded-full mb-6">
                <span class="material-icons text-amber-600 dark:text-amber-400 text-4xl">timer</span>
            </div>
            
            <h3 class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100 mb-2 uppercase tracking-tight">
                ¡Tu sesión está por expirar!
            </h3>
            
            <p class="text-center text-gray-600 dark:text-gray-400 mb-8">
                Por seguridad, tu sesión se cerrará automáticamente en:
                <span class="block text-4xl font-black text-amber-600 dark:text-amber-500 mt-2 font-mono" x-text="formatTime(countdown)"></span>
            </p>

            <div class="flex flex-col gap-3">
                <button 
                    wire:click="stayConnected" 
                    @click="showModal = false"
                    class="w-full py-4 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 rounded-xl font-bold uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-white transition-all shadow-lg active:scale-95"
                >
                    Continuar Conectado
                </button>
                
                <button 
                    wire:click="logout"
                    class="w-full py-3 bg-transparent text-red-600 dark:text-red-400 font-semibold uppercase tracking-wider text-sm hover:underline"
                >
                    Cerrar Sesión Ahora
                </button>
            </div>
        </div>
    </div>
</div>
