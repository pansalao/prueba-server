<div>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-500 leading-tight uppercase text-center">
            {{ __('Mi Firma Digital') }}
        </h2>
    </x-slot>

    <x-table.alert-message />

    <div class="sogat-card max-w-4xl mx-auto">
        <div class="space-y-8">
            
            <!-- Firma Actual -->
            @if ($firmaActual)
                <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 text-center">Tu Firma Actual</h3>
                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-white p-4 rounded-lg shadow-inner border border-gray-100" style="background-image: radial-gradient(circle, #e5e7eb 1px, transparent 1px); background-size: 15px 15px;">
                            <img src="{{ $firmaActual->foto_base64 }}" alt="Firma Actual" class="max-h-48 object-contain drop-shadow-md">
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                            Si deseas actualizarla, puedes subir una nueva imagen a continuación.
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">Aviso</p>
                            <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                                Actualmente no tienes ninguna firma registrada. Por favor, sube tu firma para poder utilizarla en el sistema.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario de Subida -->
            <form wire:submit.prevent="guardar" class="w-full space-y-6" novalidate>
                <!-- Info -->
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">Información para la subida</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                Sube una imagen de tu firma (escaneada o foto). El sistema <strong>eliminará automáticamente el fondo</strong>
                                y la convertirá a PNG transparente. Formatos aceptados: JPEG, PNG, GIF, WebP. Máximo 10MB.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Imagen -->
                    <div class="w-full max-w-lg mx-auto">
                        <x-input-label for="foto_firma" :value="__('Cargar Nueva Firma *')" />
                        <div class="mt-1"
                            x-data="{ 
                                preview: null,
                                isDragging: false,
                                uploading: false,
                                init() {
                                    this.$watch('preview', val => {
                                        if (val) this.isDragging = false;
                                    });
                                },
                                handleDrop(e) {
                                    e.preventDefault();
                                    this.isDragging = false;
                                    const file = e.dataTransfer.files[0];
                                    if (file) {
                                        this.preview = URL.createObjectURL(file);
                                        this.uploading = true;
                                        this.$wire.upload('form.foto_firma', file, () => {
                                            this.uploading = false;
                                        }, () => {
                                            this.uploading = false;
                                        });
                                    }
                                },
                                handleClick() {
                                    this.$refs.fileInput.click();
                                },
                                onFileSelect(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        this.preview = URL.createObjectURL(file);
                                        this.uploading = true;
                                        this.$wire.upload('form.foto_firma', file, () => {
                                            this.uploading = false;
                                        }, () => {
                                            this.uploading = false;
                                        });
                                    }
                                },
                                clearFile() {
                                    this.preview = null;
                                    this.uploading = false;
                                    this.$refs.fileInput.value = '';
                                    this.$wire.set('form.foto_firma', null);
                                }
                            }"
                            x-init="init()"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop"
                            @click="handleClick"
                            :class="{
                                'border-sogat-blue dark:border-blue-500 bg-blue-50 dark:bg-blue-900/20': isDragging,
                                'border-gray-300 dark:border-gray-600': !isDragging && !preview
                            }"
                            class="flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg transition-all duration-200 cursor-pointer hover:border-sogat-blue dark:hover:border-blue-500">
                            
                            <!-- Estado inicial: sin imagen -->
                            <template x-if="!preview && !uploading">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <span class="relative cursor-pointer rounded-md font-medium text-sogat-blue hover:text-blue-500">
                                            <span>Seleccionar archivo</span>
                                        </span>
                                        <p class="pl-1">o arrastra la imagen aquí</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, WebP hasta 10MB</p>
                                </div>
                            </template>
                            
                            <!-- Estado: subiendo -->
                            <template x-if="uploading">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-sogat-blue dark:text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-sogat-blue dark:text-blue-400">Subiendo imagen...</p>
                                </div>
                            </template>
                            
                            <!-- Estado: arrastrando -->
                            <template x-if="isDragging && !preview && !uploading">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-sogat-blue dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-sm font-medium text-sogat-blue dark:text-blue-400">Suelta la imagen aquí</p>
                                </div>
                            </template>
                            
                            <!-- Estado: con preview -->
                            <template x-if="preview && !uploading">
                                <div class="relative w-full">
                                    <img :src="preview" class="max-h-48 mx-auto object-contain rounded border border-gray-200 dark:border-gray-700">
                                    <button type="button" @click.stop="clearFile()" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 shadow transition-transform hover:scale-110">
                                        ✕
                                    </button>
                                </div>
                            </template>

                            <!-- Input file oculto -->
                            <input id="foto_firma" type="file" class="hidden" x-ref="fileInput"
                                accept="image/*"
                                @change="onFileSelect">
                        </div>
                        <x-input-error :messages="$errors->get('form.foto_firma')" class="mt-2" />
                        <p wire:loading wire:target="form.foto_firma" class="text-xs text-blue-600 mt-1">
                            <svg class="inline animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Subiendo imagen...
                        </p>
                    </div>
                </div>

                <!-- Vista previa del procesamiento -->
                @if ($form->foto_firma)
                    <div class="max-w-lg mx-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vista previa temporal (sin procesar):</p>
                        <div class="flex items-center justify-center p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700"
                            style="background-image: radial-gradient(circle, #cccccc 1px, transparent 1px); background-size: 10px 10px;">
                            <img src="{{ $form->foto_firma->temporaryUrl() }}" 
                                class="max-h-32 object-contain" 
                                style="image-rendering: auto;">
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                            ✅ Al guardar, el fondo será eliminado automáticamente y la firma quedará con fondo transparente.
                        </p>
                    </div>
                @endif

                <!-- Botón Guardar -->
                <div class="flex items-center justify-end gap-4 max-w-lg mx-auto mt-6">
                    <x-primary-button type="submit" wire:loading.attr="disabled">
                        <svg wire:loading wire:target="guardar" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ $firmaActual ? __('Actualizar Firma') : __('Guardar Firma') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
