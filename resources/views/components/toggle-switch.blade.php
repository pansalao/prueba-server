@props(['id', 'label' => null, 'model', 'disabled' => false, 'required' => false])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($label)
        <x-input-label :for="$id" :value="$label" />
    @endif
    <div class="mt-2 flex items-center gap-1">
        <label @class([
            'relative inline-flex items-center',
            'cursor-pointer' => !$disabled,
            'cursor-not-allowed opacity-60' => $disabled,
        ])>
            <input type="checkbox" id="{{ $id }}" wire:model.live="{{ $model }}" @disabled($disabled) class="sr-only peer">
            <div class="w-[60px] h-8 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-['NO'] peer-checked:after:content-['SI'] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all dark:border-gray-600 peer-checked:bg-black after:flex after:items-center after:justify-center after:text-[11px] after:font-bold after:text-black"></div>
        </label>
        @if ($required)
            <span class="text-red-500 font-bold">*</span>
        @endif
    </div>
    <x-input-error :messages="$errors->first($model)" class="mt-2" />
</div>

