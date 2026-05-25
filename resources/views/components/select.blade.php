@props([
    'options',
    'valueField',
    'textField',
    'wireModel' => null, // Hacemos wireModel opcional
    'label' => null,
    'required' => false,
    'placeholder' => '-- Seleccione --',
    'errorField' => null,
    'disabled' => false,
    'extraClasses' => '',
    'labelClasses' => '',
    'errorClasses' => '',
    // Nuevo: Extraer el wire:model de los atributos
    'wireModelFromAttributes' => $attributes->whereStartsWith('wire:model')->first(),
])

@php
    // Obtenemos el wireModel (usando prop o atributo)
    $finalWireModel = $wireModel ?? ($wireModelFromAttributes ?? '');
    $labelValue = $label ?? $attributes->get('label');
    $finalErrorField = $errorField ?? $finalWireModel;

    // Clases condicionales
    $selectClasses = Arr::toCssClasses([
        'block py-2 px-3 border rounded-md shadow-sm',
        'w-full' => !$required,
        'flex-1' => $required,
        'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100',
        'border-black dark:border-gray-700',
        'focus:outline-none focus:ring-indigo-500 focus:border-indigo-500',
        'mt-1' => !is_null($labelValue),
        $extraClasses,
    ]);
@endphp

<div class="w-full">
    @unless (is_null($labelValue))
        <label for="{{ $finalWireModel ?: Str::random(8) }}"
            class="block font-bold text-sm text-gray-900 dark:text-white uppercase mb-1 {{ $labelClasses }}">
            {{ $labelValue }}
        </label>
    @endunless

    <div class="flex items-center gap-1 w-full">
        <select id="{{ $finalWireModel ?: Str::random(8) }}" name="{{ $name ?? $finalWireModel }}"
            @if ($finalWireModel) wire:model.live.debounce.250ms="{{ $finalWireModel }}" @endif
            @required($required) @disabled($disabled) {{ $attributes->class([$selectClasses, $attributes->get('selectClass')]) }}>
            <option value="">{{ $placeholder }}</option>
            @foreach ($options as $option)
                <option value="{{ $option->{$valueField} }}">
                    {{ $option->{$textField} }}
                </option>
            @endforeach
        </select>
        @if ($required)
            <span class="text-red-500 font-bold">*</span>
        @endif
    </div>
</div>
