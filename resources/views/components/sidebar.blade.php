<div x-data="{ open: false }">
    
    <aside class="hidden lg:flex fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 h-screen flex-col justify-between">
        <div>
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-900 font-bold text-xl hover:opacity-80 transition">
                    <div class="bg-black text-white p-1 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    BlogHub
                </a>
            </div>

            <nav class="p-4 space-y-1">
                @php
                    $navLinks = [
                        [
                            'route' => 'dashboard', 
                            'label' => 'Dashboard', 
                            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
                        ],
                        [
                            'route' => 'followers', 
                            'label' => 'Followers', 
                            'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
                        ], 
                        [
                            'route' => 'chat', 
                            'label' => 'Chat', 
                            'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'
                        ],
                        [
                            'route' => 'notifications', 
                            'label' => 'Notifications', 
                            'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'
                        ],
                        [
                            'route' => 'profile.show', 
                            'label' => 'Profile', 
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                            // MUHIM: Profile uchun username parametrini qo'shamiz
                            'params' => ['username' => auth()->user()->username] 
                        ],
                    ];
                @endphp

                @foreach($navLinks as $link)
                    <a href="{{ route($link['route'], $link['params'] ?? []) }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition duration-200 
                       {{ request()->routeIs($link['route']) ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"></path>
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold text-gray-600 overflow-hidden">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ '@'.auth()->user()->username }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>


    <div class="lg:hidden fixed bottom-6 left-4 right-4 z-50">
        <div class="flex items-center justify-between bg-white/95 backdrop-blur-xl border border-gray-200 shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-2xl px-6 py-2">

            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-10 h-10 {{ request()->routeIs('dashboard') ? 'text-black' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-7 h-7" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </a>

            <a href="{{ route('chat') }}" class="flex flex-col items-center justify-center w-10 h-10 {{ request()->routeIs('chat') ? 'text-black' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </a>

            <div class="relative -top-5">
                <button class="flex items-center justify-center w-14 h-14 bg-black text-white rounded-full shadow-lg hover:scale-105 active:scale-95 transition border-4 border-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>

            <a href="{{ route('notifications') }}" class="flex flex-col items-center justify-center w-10 h-10 relative {{ request()->routeIs('notifications') ? 'text-black' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-7 h-7" fill="{{ request()->routeIs('notifications') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
            </a>

            <a href="{{ route('profile.show', ['username' => auth()->user()->username]) }}" class="flex flex-col items-center justify-center w-10 h-10 {{ request()->routeIs('profile*') ? 'ring-2 ring-black rounded-full p-0.5' : '' }}">
                <div class="w-7 h-7 rounded-full overflow-hidden bg-gray-200">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[10px] font-bold text-gray-600 bg-gray-200">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </a>

        </div>
    </div>
</div>