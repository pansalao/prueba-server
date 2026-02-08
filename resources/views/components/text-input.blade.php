@props(['disabled' => false, 'required' => false])

@if ($required)
    <div class="flex items-center gap-1 w-full">
@endif

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-black dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm' . ($required ? ' flex-1' : ' w-full')]) }}>

@if ($required)
        <span class="text-red-500 font-bold">*</span>
    </div>
@endif
