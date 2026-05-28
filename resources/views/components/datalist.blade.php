@props([
    'options',
    'textField',
    'wireModel' => null,
    'label' => null,
    'required' => false,
    'placeholder' => '',
    'errorField' => null,
    'disabled' => false,
    'extraClasses' => '',
    'labelClasses' => '',
    'errorClasses' => '',
    'oninput' => null,
    'wireModelFromAttributes' => $attributes->whereStartsWith('wire:model')->first(),
])

@php
    $finalWireModel = $wireModel ?? ($wireModelFromAttributes ?? '');
    $labelValue = $label ?? $attributes->get('label');
    $finalErrorField = $errorField ?? $finalWireModel;

    $inputClasses = Arr::toCssClasses([
        'mt-1 block border-gray-300 rounded-md shadow-sm transition-all duration-200 cursor-text py-1.5 px-3 pr-10',
        'w-full' => !$required,
        'flex-1' => $required,
        'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 sm:text-sm',
        'dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500',
        $extraClasses,
    ]);
@endphp

<div {{ $attributes->merge(['class' => 'w-full relative']) }} 
     x-data="{ 
        open: false, 
        search: @entangle($finalWireModel).live, 
        options: {{ collect($options)->map(fn($o) => is_array($o) ? ($o[$textField] ?? '') : ($o->{$textField} ?? ''))->toJson() }},
        filteredOptions: [],
        highlightedIndex: -1,
        
        filterOptions() {
            if (!this.search) {
                this.filteredOptions = this.options;
            } else {
                const s = this.search.toLowerCase();
                this.filteredOptions = this.options.filter(o => o.toLowerCase().includes(s));
            }
        },
        
        selectOption(option) {
            this.search = option;
            this.open = false;
            this.highlightedIndex = -1;
            // No es necesario llamar a $wire.set si usamos entangle
        },
        
        toggle() {
            if (this.open) {
                this.open = false;
            } else {
                this.open = true;
                this.filterOptions();
            }
        },

        onKeyDown(e) {
            if (e.key === 'ArrowDown') {
                this.open = true;
                this.highlightedIndex = (this.highlightedIndex + 1) % this.filteredOptions.length;
            } else if (e.key === 'ArrowUp') {
                this.open = true;
                this.highlightedIndex = (this.highlightedIndex - 1 + this.filteredOptions.length) % this.filteredOptions.length;
            } else if (e.key === 'Enter') {
                if (this.highlightedIndex >= 0) {
                    this.selectOption(this.filteredOptions[this.highlightedIndex]);
                    e.preventDefault();
                } else if (this.open) {
                    this.open = false;
                    e.preventDefault();
                }
            } else if (e.key === 'Escape') {
                this.open = false;
            }
        }
     }"
     x-init="filterOptions(); $watch('search', () => filterOptions())"
     @click.away="open = false"
>
    @unless (is_null($labelValue))
        <label class="block font-bold text-sm text-gray-900 dark:text-white uppercase mb-1 {{ $labelClasses }}">
            {{ $labelValue }}
        </label>
    @endunless

    <div class="flex items-center gap-1 w-full">
        <div class="relative flex-1 min-w-0">
            <input 
                type="text"
                x-model="search"
                @click="toggle()"
                @keydown="onKeyDown"
                @input="open = true"
                placeholder="{{ $placeholder }}"
                @disabled($disabled)
                @if($oninput) oninput="{!! $oninput !!}" @endif
                class="mt-1 block border-gray-300 rounded-md shadow-sm transition-all duration-200 cursor-text py-1.5 px-3 pr-10 w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 sm:text-sm dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 {{ $extraClasses }} {{ $attributes->get('class') }}"
                autocomplete="off"
            >
            
            <!-- Icono de dropdown que también hace toggle -->
            <div @click="toggle()" class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-400 hover:text-indigo-500 transition-colors">
                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
        @if ($required)
            <span class="text-red-500 font-bold">*</span>
        @endif
    </div>

    <!-- Dropdown Menu -->
    <div 
        x-show="open && filteredOptions.length > 0"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute z-[9999] mt-1 w-full bg-white dark:bg-gray-800 border-2 border-black dark:border-gray-700 rounded-md shadow-2xl max-h-40 overflow-auto"
        style="display: none;"
    >
        <template x-for="(option, index) in filteredOptions" :key="index">
            <div 
                @click="selectOption(option)"
                @mouseenter="highlightedIndex = index"
                :class="{
                    'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-bold': highlightedIndex === index,
                    'text-gray-900 dark:text-gray-100': highlightedIndex !== index
                }"
                class="px-4 py-2 text-sm cursor-pointer transition-colors duration-150 border-b border-gray-100 dark:border-gray-700 last:border-0"
                x-text="option || '\u00A0'"
            ></div>
        </template>
    </div>

    @error($finalErrorField)
        <span class="text-red-600 text-sm {{ $errorClasses }}">{{ $message }}</span>
    @enderror
</div>
