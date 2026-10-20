<div class="font-sans antialiased">
    @php
        $unreadCount = auth()->user()->unreadNotifications->count();
        $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $navLinks = [
            ['route' => 'dashboard', 'label' => 'Asosiy panel', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'active' => 'dashboard*'],
            ['route' => 'network', 'label' => 'Obunachilar', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'active' => 'network*'], 
            ['route' => 'chat.index', 'label' => 'Xabarlar', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'active' => 'chat*'],
            ['route' => 'notifications', 'label' => 'Bildirishnomalar', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'active' => 'notifications*'],
            ['route' => 'profile.show', 'label' => 'Profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'params' => ['username' => auth()->user()->username], 'active' => 'profile*'],
        ];
    @endphp

    <!-- DESKTOP COLLAPSIBLE SIDEBAR -->
    <aside id="app-sidebar" 
           class="hidden lg:flex fixed inset-y-0 left-0 z-50 bg-white dark:bg-slate-900 border-r border-gray-150 dark:border-slate-800 h-screen flex-col justify-between shadow-sm select-none">

        <div class="flex-1 flex flex-col min-h-0">
            <!-- Header Logo with Internal Toggle Button -->
            <div class="relative border-b border-gray-100 dark:border-slate-800">
                <div class="sidebar-header-box h-20 flex items-center justify-between px-5"
                     :class="{ 'cursor-pointer': collapsed }"
                     @click="if (collapsed) toggleSidebar()">
                    
                    <!-- Logo / Brand link -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 text-black dark:text-white font-[900] text-2xl tracking-tighter"
                       @click="if (collapsed) { $event.preventDefault(); toggleSidebar(); }">
                        <div class="bg-black dark:bg-white text-white dark:text-black p-2 rounded-xl transform -rotate-3 shrink-0 shadow-sm transition-transform hover:rotate-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <span class="sidebar-text-hide whitespace-nowrap overflow-hidden"
                              x-show="!collapsed">Minnagram</span>
                    </a>

                    <!-- Clean Internal Toggle Button (Visible inside sidebar when expanded) -->
                    <button type="button" 
                            @click.stop="toggleSidebar()"
                            title="Saydbarni yopish"
                            class="sidebar-text-hide p-2 text-gray-400 hover:text-gray-800 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer"
                            x-show="!collapsed">
                        <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-3 space-y-1.5 flex-1 overflow-y-auto custom-scrollbar">
                @foreach($navLinks as $link)
                    <div class="relative" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
                        <a href="{{ route($link['route'], $link['params'] ?? []) }}" 
                           class="sidebar-nav-link group flex items-center justify-between px-4 py-3.5 rounded-2xl relative
                           {{ request()->routeIs($link['active']) 
                                ? 'bg-[#101727] dark:bg-slate-800 text-white dark:text-white shadow-xl' 
                                : 'text-[#536471] dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800/60 hover:text-black dark:hover:text-white font-extrabold' }}">
                            
                            <div class="sidebar-nav-inner flex items-center gap-4">
                                <div class="relative shrink-0 flex items-center justify-center">
                                    <svg class="w-6 h-6 stroke-[2.4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"></path></svg>
                                    
                                    <!-- Mini Badge on Icon in Collapsed mode -->
                                    @if($link['route'] === 'chat.index')
                                        <span id="global-unread-count-mini" 
                                              class="sidebar-mini-show absolute -top-1.5 -right-2 bg-blue-500 text-white text-[9px] font-black w-4 h-4 items-center justify-center rounded-full border-2 border-white dark:border-slate-900 {{ $unreadMessagesCount > 0 ? 'animate-pulse' : '' }}"
                                              x-show="collapsed && {{ $unreadMessagesCount > 0 ? 'true' : 'false' }}" 
                                              style="{{ $unreadMessagesCount > 0 ? 'display: flex;' : 'display: none;' }}">
                                            {{ $unreadMessagesCount > 9 ? '9+' : $unreadMessagesCount }}
                                        </span>
                                    @endif

                                    @if($link['route'] === 'notifications')
                                        <span id="notif-unread-count-mini" 
                                              class="sidebar-mini-show absolute -top-1.5 -right-2 bg-red-500 text-white text-[9px] font-black w-4 h-4 items-center justify-center rounded-full border-2 border-white dark:border-slate-900"
                                              x-show="collapsed && {{ $unreadCount > 0 ? 'true' : 'false' }}" 
                                              style="{{ $unreadCount > 0 ? 'display: flex;' : 'display: none;' }}">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    @endif
                                </div>
                                <span class="sidebar-text-hide text-[16px] tracking-tight whitespace-nowrap overflow-hidden"
                                      x-show="!collapsed">{{ $link['label'] }}</span>
                            </div>

                            <!-- Expanded badges: shows beside label when sidebar is expanded -->
                            <div class="sidebar-text-hide flex items-center ml-2" x-show="!collapsed">
                                @if($link['route'] === 'chat.index')
                                    <span id="global-unread-count" 
                                          style="{{ $unreadMessagesCount > 0 ? 'display: inline-flex;' : 'display: none;' }}" 
                                          class="bg-blue-500 text-white text-[11px] font-black px-2 py-0.5 rounded-full {{ $unreadMessagesCount > 0 ? 'animate-pulse' : '' }}">
                                        {{ $unreadMessagesCount }}
                                    </span>
                                @endif

                                @if($link['route'] === 'notifications')
                                    <span id="notif-unread-count" 
                                          style="{{ $unreadCount > 0 ? 'display: inline-flex;' : 'display: none;' }}" 
                                          class="bg-red-500 text-white text-[10px] font-black min-w-[20px] h-5 px-1.5 flex items-center justify-center rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                        </a>

                        <!-- Floating Tooltip in Collapsed Mode -->
                        <div x-show="collapsed && hovered"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-x-1"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-x-0"
                             x-transition:leave-end="opacity-0 translate-x-1"
                             class="hidden lg:flex absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-[#101727] dark:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xl z-[60] whitespace-nowrap pointer-events-none border border-white/10 items-center gap-2">
                            <span>{{ $link['label'] }}</span>
                            @if($link['route'] === 'chat.index' && $unreadMessagesCount > 0)
                                <span class="bg-blue-500 text-[10px] px-1.5 py-0.2 rounded-full font-black">{{ $unreadMessagesCount }}</span>
                            @elseif($link['route'] === 'notifications' && $unreadCount > 0)
                                <span class="bg-red-500 text-[10px] px-1.5 py-0.2 rounded-full font-black">{{ $unreadCount }}</span>
                            @endif
                            <div class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-[#101727] dark:bg-slate-800 rotate-45 border-l border-b border-white/10"></div>
                        </div>
                    </div>
                @endforeach
            </nav>
        </div>

        <!-- Footer Profile Info -->
        <div class="p-3 bg-gray-50/50 dark:bg-slate-900/50 border-t border-gray-150 dark:border-slate-800">
            <div class="sidebar-footer-box flex items-center justify-between bg-white dark:bg-slate-800/80 p-2 rounded-2xl border border-gray-200/50 dark:border-slate-700/50 relative">
                <a href="{{ route('profile.show', ['username' => auth()->user()->username]) }}" 
                   class="flex items-center gap-3 min-w-0 group/prof"
                   :title="collapsed ? '{{ auth()->user()->name }} (@{{ auth()->user()->username }})' : ''">
                    <div class="relative h-10 w-10 bg-black dark:bg-white rounded-full flex items-center justify-center text-white dark:text-black font-black overflow-hidden shrink-0 shadow-sm group-hover/prof:scale-105 transition-transform">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                        <span id="profile-unread-dot" style="{{ ($unreadMessagesCount + $unreadCount) > 0 ? 'display: block;' : 'display: none;' }}" class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white dark:border-slate-800 rounded-full"></span>
                    </div>
                    <div class="sidebar-text-hide min-w-0"
                         x-show="!collapsed">
                        <p class="text-[13px] font-[900] text-black dark:text-white truncate tracking-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-400 truncate tracking-tight">@<span>{{ auth()->user()->username }}</span></p>
                    </div>
                </a>

                <div class="sidebar-text-hide shrink-0 m-0 p-0" x-show="!collapsed">
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" title="Chiqish" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-colors">
                            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mini Logout in Collapsed Mode -->
            <div class="sidebar-mini-show mt-2 flex justify-center" x-show="collapsed">
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                    @csrf
                    <button type="submit" title="Chiqish" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-colors">
                        <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <script>
        function refreshUnreadUI() {
            const path = window.location.pathname;

            // Chatda o'tirgan bo'lsa raqamni ko'rsatmaslik
            if (path.includes('/chat')) {
                const badge = document.getElementById('global-unread-count');
                const badgeMini = document.getElementById('global-unread-count-mini');
                const mobileChatBadge = document.getElementById('mobile-chat-unread');
                if (badge) badge.style.display = 'none';
                if (badgeMini) badgeMini.style.display = 'none';
                if (mobileChatBadge) mobileChatBadge.style.display = 'none';
            }

            fetch('/chat/total-unread')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('global-unread-count');
                    const badgeMini = document.getElementById('global-unread-count-mini');
                    const mobileChatBadge = document.getElementById('mobile-chat-unread');
                    const notifBadge = document.getElementById('notif-unread-count');
                    const notifBadgeMini = document.getElementById('notif-unread-count-mini');
                    const dot = document.getElementById('profile-unread-dot');
                    const mobileDot = document.getElementById('mobile-profile-unread-dot');

                    if (!path.includes('/chat')) {
                        if (badge) {
                            badge.innerText = data.count;
                            badge.style.display = data.count > 0 ? 'inline-flex' : 'none';
                            if(data.count > 0) badge.classList.add('animate-pulse');
                        }
                        if (badgeMini) {
                            badgeMini.innerText = data.count > 9 ? '9+' : data.count;
                            badgeMini.style.display = data.count > 0 ? 'flex' : 'none';
                        }
                        if (mobileChatBadge) {
                            mobileChatBadge.innerText = data.count > 9 ? '9+' : data.count;
                            mobileChatBadge.style.display = data.count > 0 ? 'flex' : 'none';
                        }
                    }
                    if (notifBadge) {
                        notifBadge.innerText = data.notif_count;
                        notifBadge.style.display = data.notif_count > 0 ? 'inline-flex' : 'none';
                    }
                    if (notifBadgeMini) {
                        notifBadgeMini.innerText = data.notif_count > 9 ? '9+' : data.notif_count;
                        notifBadgeMini.style.display = data.notif_count > 0 ? 'flex' : 'none';
                    }
                    const hasUnread = (data.count + data.notif_count) > 0;
                    if (dot) {
                        dot.style.display = hasUnread ? 'block' : 'none';
                    }
                    if (mobileDot) {
                        mobileDot.style.display = hasUnread ? 'block' : 'none';
                    }
                }).catch(() => {});
        }

        document.addEventListener("DOMContentLoaded", () => {
            refreshUnreadUI();
            
            // Faqat yangi narsa bo'lgandagina serverga so'rov yuboramiz (WebSocket)
            if (window.Echo) {
                window.Echo.private(`chat.{{ auth()->id() }}`)
                    .listen('MessageSent', (e) => {
                        console.log('Real-time message received!');
                        refreshUnreadUI();
                    });
            }
        });

        document.addEventListener("livewire:navigated", refreshUnreadUI);
    </script>
</div>