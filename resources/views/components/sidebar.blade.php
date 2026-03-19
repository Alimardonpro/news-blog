<div x-data="{ open: false }" class="font-sans antialiased">
    @php
        $unreadCount = auth()->user()->unreadNotifications->count();
        $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
            ->where(function($q) {
                $q->where('is_read', false)->orWhereNull('read_at');
            })->count();

        $navLinks = [
            ['route' => 'dashboard', 'label' => 'Asosiy panel', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'active' => 'dashboard*'],
            ['route' => 'network', 'label' => 'Obunachilar', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'active' => 'network*'], 
            ['route' => 'chat.index', 'label' => 'Xabarlar', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'active' => 'chat*'],
            ['route' => 'notifications', 'label' => 'Bildirishnomalar', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'active' => 'notifications*'],
            ['route' => 'profile.show', 'label' => 'Profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'params' => ['username' => auth()->user()->username], 'active' => 'profile*'],
        ];
    @endphp

    <!-- DESKTOP SIDEBAR (Doimiy kenglikda: w-64) -->
    <aside class="hidden lg:flex fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 h-screen flex-col justify-between shadow-sm">
        <div>
            <div class="h-20 flex items-center px-7 border-b border-gray-50">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-black font-[900] text-2xl tracking-tighter">
                    <div class="bg-black text-white p-1.5 rounded-xl transform -rotate-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <span>Bloggram</span>
                </a>
            </div>

            <nav class="p-4 space-y-1.5">
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route'], $link['params'] ?? []) }}" 
                       wire:navigate
                       class="group flex items-center justify-between px-4 py-3.5 rounded-2xl transition-all duration-300 
                       {{ request()->routeIs($link['active']) ? 'bg-[#101727] text-white shadow-xl' : 'text-[#536471] hover:bg-gray-50 hover:text-black font-extrabold' }}">
                        
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 stroke-[2.4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"></path></svg>
                            <span class="text-[16px] tracking-tight">{{ $link['label'] }}</span>
                        </div>

                        <!-- Chat soni -->
                        @if($link['route'] === 'chat.index')
                            <span id="global-unread-count" style="{{ $unreadMessagesCount > 0 ? 'display: inline-flex;' : 'display: none;' }}" class="bg-blue-500 text-white text-[11px] font-black px-2 py-0.5 rounded-full {{ $unreadMessagesCount > 0 ? 'animate-pulse' : '' }}">
                                {{ $unreadMessagesCount }}
                            </span>
                        @endif

                        <!-- Bildirishnomalar soni -->
                        @if($link['route'] === 'notifications')
                            <span id="notif-unread-count" style="{{ $unreadCount > 0 ? 'display: inline-flex;' : 'display: none;' }}" class="bg-red-500 text-white text-[10px] font-black w-5 h-5 items-center justify-center rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="p-4 bg-gray-50/40 border-t border-gray-100">
            <div class="flex items-center justify-between bg-white p-2.5 rounded-2xl border border-gray-200/50 relative">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative h-10 w-10 bg-black rounded-full flex items-center justify-center text-white font-black overflow-hidden shrink-0">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                        <span id="profile-unread-dot" style="{{ ($unreadMessagesCount + $unreadCount) > 0 ? 'display: block;' : 'display: none;' }}" class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-[900] text-black truncate tracking-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] font-bold text-gray-400 truncate tracking-tight">@<span>{{ auth()->user()->username }}</span></p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="shrink-0 m-0 p-0">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                if (badge) badge.style.display = 'none';
            }

            fetch('/chat/total-unread')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('global-unread-count');
                    const notifBadge = document.getElementById('notif-unread-count');
                    const dot = document.getElementById('profile-unread-dot');

                    if (!path.includes('/chat') && badge) {
                        badge.innerText = data.count;
                        badge.style.display = data.count > 0 ? 'inline-flex' : 'none';
                        if(data.count > 0) badge.classList.add('animate-pulse');
                    }
                    if (notifBadge) {
                        notifBadge.innerText = data.notif_count;
                        notifBadge.style.display = data.notif_count > 0 ? 'inline-flex' : 'none';
                    }
                    if (dot) {
                        dot.style.display = (data.count + data.notif_count) > 0 ? 'block' : 'none';
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