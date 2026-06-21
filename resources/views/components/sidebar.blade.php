@props([])

@php
    $menu = json_decode(file_get_contents(resource_path('json/verticemenu.json')), true);

    $userSettings = null;
    if (auth()->check()) {
        $userSettings = \App\Models\Setting::where('user_id', auth()->id())->first();
    }
    $appName = $userSettings?->app_name ?? config('app.name', 'Laravel');
    $logoPath = $userSettings?->logo_path;

    $icons = [
        'dashboard' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>',
        'user' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>',
        'cog' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
        'expenses' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'palette' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z" /></svg>',
    ];

    function isActive($routes): bool
    {
        foreach ((array) $routes as $route) {
            if (request()->routeIs($route)) {
                return true;
            }
        }
        return false;
    }
@endphp

<aside
    data-sidebar
    class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 bg-white dark:bg-gray-800 transition-transform duration-200 ease-in-out"
    style="border-right: 2px solid var(--color-border)"
>
    <div class="flex items-center justify-between h-16 px-6" style="border-bottom: 2px solid var(--color-border)">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5">
            @if($logoPath)
                <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $appName }}" class="h-8 w-8 rounded-lg object-cover">
            @else
                <x-application-logo class="h-8 w-8" style="color: var(--color-primary)" />
            @endif
            <span class="font-bold text-lg text-gray-800 dark:text-white">{{ $appName }}</span>
        </a>
        <button type="button" onclick="window.closeSidebar()" class="lg:hidden inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
        @foreach($menu as $item)
            @continue(($item['visible'] ?? true) === false)

            @if(($item['type'] ?? '') === 'title')
                <p class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    {{ __($item['title']) }}
                </p>
            @elseif(isset($item['submenu']))
                @php
                    $hasActiveChild = isActive($item['active'] ?? []);
                @endphp
                <div x-data="{ open: @js($hasActiveChild) }">
                    <button
                        type="button"
                        x-on:click="open = !open"
                        class="flex items-center w-full gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ $hasActiveChild ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}"
                        @if($hasActiveChild) style="background-color: var(--color-primary-light); color: var(--color-primary)" @endif
                    >
                        {!! $icons[$item['icon']] ?? '' !!}
                        <span class="flex-1 text-start">{{ __($item['title']) }}</span>
                        <svg class="w-4 h-4 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse.duration.150ms>
                        <div class="mt-0.5 space-y-0.5">
                            @foreach($item['submenu'] as $sub)
                                @continue(($sub['visible'] ?? true) === false)
                                @php
                                    $isSubActive = isActive($sub['active'] ?? [$sub['route']]);
                                @endphp
                                <a
                                    href="{{ route($sub['route']) }}"
                                    wire:navigate
                                    class="flex items-center gap-3 px-3 py-2 ms-6 rounded-lg text-sm font-medium transition-colors duration-150 {{ $isSubActive ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}"
                                    @if($isSubActive) style="background-color: var(--color-primary-light); color: var(--color-primary)" @endif
                                >
                                    {!! $icons[$sub['icon'] ?? ''] ?? '' !!}
                                    <span>{{ __($sub['title']) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                @php
                    $isActive = isActive($item['active'] ?? [$item['route']]);
                @endphp
                <a
                    href="{{ route($item['route']) }}"
                    wire:navigate
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ $isActive ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200' }}"
                    @if($isActive) style="background-color: var(--color-primary-light); color: var(--color-primary)" @endif
                >
                    {!! $icons[$item['icon']] ?? '' !!}
                    <span>{{ __($item['title']) }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    <div class="p-4 space-y-3" style="border-top: 2px solid var(--color-border)">
        <div class="flex items-center gap-3 px-3">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>
        <div class="px-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                    {{ __('Cerrar Sesión') }}
                </button>
            </form>
        </div>
    </div>
</aside>
