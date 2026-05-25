@props(['value'])

<label {{ $attributes->merge(['class' => 'block uppercase font-bold text-sm text-gray-900 dark:text-white mb-1']) }}>
    {{ $value ?? $slot }}
</label>

