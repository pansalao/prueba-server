<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <div class="space-y-12">
        {{-- Saludo de Bienvenida --}}
        @php
            $hora = date('H');
            $saludo = 'Buenos días';
            if ($hora >= 12 && $hora < 19) {
                $saludo = 'Buenas tardes';
            } elseif ($hora >= 19 || $hora < 5) {
                $saludo = 'Buenas noches';
            }
        @endphp
        
        <h2 class="text-[22px] font-bold text-gray-900 dark:text-white uppercase">
            {{ $saludo }}: {{ auth()->user()->name }}
        </h2>

        {{-- Sección Entérate --}}
        <div class="relative mt-24 p-10 border-2 border-sogat-red rounded-xl bg-white dark:bg-gray-900 shadow-sm">
            <!-- Imagen Entérate que bordea el mensaje -->
            <div class="absolute -top-14 left-8 bg-white dark:bg-gray-900 px-4">
                <img src="{{ asset('img/enterate.png') }}" alt="Entérate" class="h-32 w-auto object-contain">
            </div>

            <div class="mt-10 space-y-4">
                <p class="text-xl font-bold text-gray-800 dark:text-gray-100">
                    Nuestras redes sociales son:
                </p>
                <div class="flex flex-col space-y-2">
                    <a href="https://www.facebook.com/UPTP-Juan-de-Jesús-Montilla-321794751770801" target="_blank" 
                       class="text-blue-800 dark:text-blue-400 font-bold hover:underline break-all">
                        www.facebook.com/UPTP-Juan-de-Jesús-Montilla-321794751770801
                    </a>
                    <a href="https://www.instagram.com/uptpjuandejesus" target="_blank" 
                       class="text-blue-800 dark:text-blue-400 font-bold hover:underline break-all">
                        www.instagram.com/uptpjuandejesus
                    </a>
                    <a href="https://www.twitter.com/UptpJuandeJesus" target="_blank" 
                       class="text-blue-800 dark:text-blue-400 font-bold hover:underline break-all">
                        www.twitter.com/UptpJuandeJesus
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
