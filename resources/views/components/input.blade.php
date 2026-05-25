@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'errorField' => null,
])

{{-- Si errorField no está definido, usa name como fallback --}}
@php
    $finalErrorField = $errorField ?? $name;
@endphp

<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}" class="block font-bold text-sm text-gray-900 dark:text-white uppercase mb-1">
            {{ $label }}
        </label>
    @endif
    <div class="flex items-center gap-1">
        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'mt-1 block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 ' . ($required ? 'flex-1' : 'w-full')]) }} />
        @if ($required)
            <span class="text-red-500 font-bold">*</span>
        @endif
    </div>
    @error($finalErrorField)
        <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror
</div>
