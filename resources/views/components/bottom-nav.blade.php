@php
    $currentRoute = request()->route()?->getName() ?? '';
    $items = [
        ['title' => 'Inicio', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['title' => 'Gastos', 'route' => 'gastos', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['title' => 'Tema', 'route' => 'settings', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
        ['title' => 'Perfil', 'route' => 'profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ];
@endphp

<nav
    x-data
    class="fixed bottom-0 left-0 right-0 z-50 lg:hidden flex justify-center px-3 pb-2 pointer-events-none"
>
    <div class="relative flex w-full max-w-md justify-around items-center bg-white dark:bg-gray-800 px-2 py-2 shadow-xl rounded-b-3xl rounded-t-xl pointer-events-auto" style="border-top: 1px solid var(--color-border)">
        @foreach($items as $item)
            @php
                $isActive = str_starts_with($currentRoute, $item['route']);
            @endphp
            <a
                href="{{ route($item['route']) }}"
                wire:navigate
                class="relative flex flex-col items-center justify-center p-2 focus:outline-none transition-colors duration-300 z-10 {{ $isActive ? '' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}"
                @if($isActive) style="color: var(--color-primary)" @endif
            >
                @if($isActive)
                    <div class="absolute -top-[17px] flex flex-col items-center">
                        <span class="h-2 w-2 rounded-full animate-fade-in" style="background-color: var(--color-primary)"></span>
                        <div class="absolute top-2 w-12 h-4 bg-white dark:bg-gray-800 rounded-t-full border-t-2 border-transparent"></div>
                    </div>
                @endif
                <svg class="h-5 w-5 sm:h-6 sm:w-6 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                </svg>
                <span class="text-[9px] sm:text-[10px] font-medium mt-0.5 sm:mt-1 tracking-wide">{{ $item['title'] }}</span>
            </a>
        @endforeach

        {{-- Logout Button --}}
        <form method="POST" action="{{ route('logout') }}" class="relative flex flex-col items-center justify-center p-2 z-10 text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-300 cursor-pointer">
            @csrf
            <button type="submit" class="flex flex-col items-center justify-center focus:outline-none">
                <svg class="h-5 w-5 sm:h-6 sm:w-6 stroke-current stroke-2 fill-none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                <span class="text-[9px] sm:text-[10px] font-medium mt-0.5 sm:mt-1 tracking-wide">Salir</span>
            </button>
        </form>
    </div>
</nav>
