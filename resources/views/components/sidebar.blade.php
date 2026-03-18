<div x-data="{ open: false }" class="font-sans antialiased">
    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 h-screen flex-col justify-between shadow-[2px_0_20px_rgba(0,0,0,0.02)]">
        <div>
            <!-- Logo -->
            <div class="h-20 flex items-center px-7 border-b border-gray-50">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-black font-[900] text-2xl tracking-tighter hover:opacity-80 transition-all">
                    <div class="bg-black text-white p-1.5 rounded-xl shadow-lg shadow-black/20 transform -rotate-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <span class="mt-1">Bloggram</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5">
                @php
                    $navLinks = [
                        [
                            'route' => 'dashboard', 
                            'label' => 'Asosiy panel', 
                            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                            'active' => 'dashboard'
                        ],
                        [
                            'route' => 'network', 
                            'label' => 'Obunachilar', 
                            'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                            'active' => 'followers'
                        ], 
                        [
                            'route' => 'chat.index', 
                            'label' => 'Xabarlar', 
                            'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
                            'active' => 'chat.*' // Matches chat.index, chat.messages, etc.
                        ],
                        [
                            'route' => 'notifications', 
                            'label' => 'Bildirishnomalar', 
                            'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                            'active' => 'notifications'
                        ],
                        [
                            'route' => 'profile.show', 
                            'label' => 'Profil', 
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 
                            'params' => ['username' => auth()->user()->username],
                            'active' => 'profile.*'
                        ],
                    ];
                @endphp

                @foreach($navLinks as $link)
                    <a href="{{ route($link['route'], $link['params'] ?? []) }}" 
                       class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                       {{ request()->routeIs($link['active']) 
                            ? 'bg-[#0F1419] text-white shadow-xl shadow-gray-200 scale-[1.02]' 
                            : 'text-[#536471] hover:bg-gray-50 hover:text-black font-extrabold' }}">
                        
                        <svg class="w-6 h-6 stroke-[2.4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"></path>
                        </svg>
                        <span class="text-[16px] tracking-tight">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- User Profile & Logout (Bottom of Desktop Sidebar) -->
        <div class="p-4 bg-gray-50/40 border-t border-gray-100">
            <div class="flex items-center justify-between bg-white p-2.5 rounded-2xl shadow-sm border border-gray-200/50">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-10 w-10 bg-black rounded-full flex items-center justify-center text-white font-black overflow-hidden shadow-inner flex-shrink-0">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-[900] text-black truncate leading-tight tracking-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] font-bold text-gray-400 truncate mt-0.5">@<span>{{ auth()->user()->username }}</span></p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-1">
                    @csrf
                    <button type="submit" class="p-2 text-gray-300 hover:text-red-600 transition-colors">
                        <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile Bottom Navigation -->
    <div class="lg:hidden fixed bottom-6 left-6 right-6 z-50">
        <div class="flex items-center justify-between bg-[#0F1419] shadow-[0_20px_40px_rgba(0,0,0,0.3)] rounded-full px-7 py-3.5 border border-white/5 backdrop-blur-lg">
            
            <a href="{{ route('dashboard') }}" class="p-2 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-500 hover:text-gray-300 transition-colors' }}">
                <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </a>

            <!-- FIXED: Active state check for chat -->
            <a href="{{ route('chat.index') }}" class="p-2 {{ request()->routeIs('chat.*') ? 'text-white' : 'text-gray-500 hover:text-gray-300 transition-colors' }}">
                <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </a>

            <div class="relative -top-10">
                <button class="flex items-center justify-center w-14 h-14 bg-white text-black rounded-full shadow-2xl hover:scale-105 active:scale-95 transition-all border-[5px] border-[#0F1419]">
                    <svg class="w-7 h-7 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>

            <a href="{{ route('notifications') }}" class="p-2 relative {{ request()->routeIs('notifications') ? 'text-white' : 'text-gray-500 hover:text-gray-300 transition-colors' }}">
                <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <!-- Red dot indicator for notifications -->
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-black"></span>
            </a>

            <a href="{{ route('profile.show', ['username' => auth()->user()->username]) }}" class="p-0.5 rounded-full transition-all {{ request()->routeIs('profile.*') ? 'ring-2 ring-white scale-110' : 'opacity-60 hover:opacity-100' }}">
                <div class="w-7 h-7 rounded-full overflow-hidden">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[10px] font-black text-black bg-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </a>
        </div>
    </div>
</div>