@props(['type' => 'success', 'message' => ''])

<div x-data="{
    show: false,
    alertType: 'success',
    alertMessage: '',
    alertTitle: null,
    redirectUrl: null,
    onOkEvent: null,
    showCancelButton: false,
    cancelText: 'Cancelar',
    okText: 'OK',
    countdown: 0,
    countdownInterval: null,
    isSuccess() { return this.alertType === 'success' },
    isWarning() { return this.alertType === 'warning' },
    showAlert(data) {
        let d = (Array.isArray(data) && data.length > 0) ? data[0] : data;
        this.alertType = d.type || 'success';
        this.alertMessage = d.message || 'Operación completada';
        this.alertTitle = d.title || null;
        this.redirectUrl = d.redirect || null;
        this.onOkEvent = d.onOkEvent || null;
        this.showCancelButton = d.showCancelButton || false;
        this.cancelText = d.cancelText || 'Cancelar';
        this.okText = d.okText || 'OK';
        this.countdown = d.countdown || 0;
        
        if (this.countdownInterval) clearInterval(this.countdownInterval);
        
        if (this.countdown > 0) {
            this.countdownInterval = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    clearInterval(this.countdownInterval);
                }
            }, 1000);
        }
        
        this.show = true;
    },
    handleOk() {
        this.show = false;
        if (this.onOkEvent) {
            setTimeout(() => {
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch(this.onOkEvent);
                }
                window.dispatchEvent(new CustomEvent(this.onOkEvent));
            }, 10);
        }
        if (this.redirectUrl) {
            setTimeout(() => { window.location.href = this.redirectUrl; }, 100);
        }
    }
}"
x-on:show-alert.window="showAlert($event.detail)"
wire:ignore
class="fixed inset-0 flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-sm"
style="z-index: 9999999;"
x-cloak
x-show="show"
x-transition:enter="transition ease-out duration-100"
x-transition:enter-start="opacity-0 scale-95"
x-transition:enter-end="opacity-100 scale-100"
x-transition:leave="transition ease-in duration-75"
x-transition:leave-start="opacity-100 scale-100"
x-transition:leave-end="opacity-0 scale-95">
    
    <div class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border-2"
         :class="isSuccess() ? 'border-green-500' : (isWarning() ? 'border-yellow-500' : 'border-red-500')">
        
        <div class="h-24 flex items-center justify-center"
             :class="isSuccess() ? 'bg-gradient-to-br from-green-400 to-emerald-600' : (isWarning() ? 'bg-gradient-to-br from-yellow-400 to-amber-600' : 'bg-gradient-to-br from-red-400 to-rose-600')">
            <div class="bg-white/20 backdrop-blur-md rounded-full p-3">
                <span class="material-icons text-white text-5xl" 
                      x-text="isSuccess() ? 'check_circle' : (isWarning() ? 'info' : 'report_problem')"></span>
            </div>
        </div>

        <div class="p-6 text-center">
            <h3 class="text-2xl font-black mb-3 tracking-tight" 
                :class="isSuccess() ? 'text-green-600 dark:text-green-400' : (isWarning() ? 'text-yellow-600 dark:text-yellow-400' : (alertType === 'info' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400'))"
                x-text="alertTitle ? alertTitle : (isSuccess() ? '¡GUARDADO EXITOSAMENTE!' : (isWarning() ? 'RECOMENDACIÓN' : '¡HAY ERRORES!'))"></h3>
            
            <div class="mt-3 mb-6 max-h-[40vh] overflow-y-auto px-3 py-3 text-left bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed" 
                   x-text="alertMessage"></p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full">
                <template x-if="showCancelButton">
                    <button type="button" @click="show = false"
                            class="w-full sm:w-1/2 py-4 px-6 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-xl font-black uppercase tracking-widest transition-all shadow-lg active:scale-95 text-base">
                        <span x-text="cancelText"></span>
                    </button>
                </template>
                
                <button type="button" @click="if(countdown <= 0) handleOk()"
                        :disabled="countdown > 0"
                        class="w-full py-4 px-6 text-white rounded-xl font-black uppercase tracking-widest transition-all shadow-lg text-base"
                        :class="{
                            'bg-green-600 hover:bg-green-700': isSuccess() && countdown <= 0,
                            'bg-yellow-600 hover:bg-yellow-700': isWarning() && countdown <= 0,
                            'bg-red-600 hover:bg-red-700': !isSuccess() && !isWarning() && countdown <= 0,
                            'bg-gray-400 cursor-not-allowed opacity-70': countdown > 0,
                            'active:scale-95': countdown <= 0,
                            'sm:w-1/2': showCancelButton,
                            'w-full': !showCancelButton
                        }">
                    <span x-text="countdown > 0 ? okText + ' (' + countdown + 's)' : okText"></span>
                </button>
            </div>
        </div>
    </div>
    
    <style>[x-cloak] { display: none !important; }</style>
</div>