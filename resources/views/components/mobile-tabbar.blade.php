@php
    $unreadCount = auth()->user()->unreadNotifications->count();
    $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
        ->where('is_read', false)
        ->count();
@endphp

<!-- FIXED MOBILE BOTTOM TAB BAR -->
<nav id="mobile-bottom-tabbar" 
     class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/90 backdrop-blur-xl border-t border-gray-200/80 shadow-[0_-8px_30px_rgba(0,0,0,0.06)] transition-transform duration-300">
    <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between pb-[env(safe-area-inset-bottom,0px)]">
        
        <!-- 1. Asosiy panel (Feed / Dashboard) -->
        <a href="{{ route('dashboard') }}" 
           class="flex flex-col items-center justify-center flex-1 py-1 group relative {{ request()->routeIs('dashboard*') ? 'text-black' : 'text-gray-400 hover:text-gray-700' }}">
            <div class="relative p-1">
                <svg class="w-6 h-6 transition-transform group-active:scale-90 {{ request()->routeIs('dashboard*') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                @if(request()->routeIs('dashboard*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-black rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-bold tracking-tight mt-0.5">Asosiy</span>
        </a>

        <!-- 2. Tarmog' (Network / Obunachilar) -->
        <a href="{{ route('network') }}" 
           class="flex flex-col items-center justify-center flex-1 py-1 group relative {{ request()->routeIs('network*') ? 'text-black' : 'text-gray-400 hover:text-gray-700' }}">
            <div class="relative p-1">
                <svg class="w-6 h-6 transition-transform group-active:scale-90 {{ request()->routeIs('network*') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                @if(request()->routeIs('network*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-black rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-bold tracking-tight mt-0.5">Tarmog'</span>
        </a>

        <!-- 3. Markaziy Yangi Post (+) Tugmasi -->
        <div class="flex flex-col items-center justify-center flex-1 -mt-4">
            @if(request()->routeIs('dashboard*'))
                <button type="button" 
                        @click="$dispatch('open-create-post')"
                        class="w-12 h-12 rounded-full bg-[#101727] hover:bg-black text-white flex items-center justify-center shadow-lg shadow-black/25 active:scale-95 transition-all transform hover:scale-105 border-2 border-white">
                    <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            @else
                <a href="{{ route('dashboard') }}?create=1" 
                   class="w-12 h-12 rounded-full bg-[#101727] hover:bg-black text-white flex items-center justify-center shadow-lg shadow-black/25 active:scale-95 transition-all transform hover:scale-105 border-2 border-white">
                    <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </a>
            @endif
        </div>

        <!-- 4. Xabarlar (Chat) -->
        <a href="{{ route('chat.index') }}" 
           class="flex flex-col items-center justify-center flex-1 py-1 group relative {{ request()->routeIs('chat*') ? 'text-black' : 'text-gray-400 hover:text-gray-700' }}">
            <div class="relative p-1">
                <svg class="w-6 h-6 transition-transform group-active:scale-90 {{ request()->routeIs('chat*') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                
                <!-- Chat Unread Badge -->
                <span id="mobile-chat-unread" 
                      style="{{ $unreadMessagesCount > 0 ? 'display: flex;' : 'display: none;' }}"
                      class="absolute -top-1 -right-1 bg-blue-500 text-white text-[9px] font-black w-4 h-4 items-center justify-center rounded-full border-2 border-white {{ $unreadMessagesCount > 0 ? 'animate-pulse' : '' }}">
                    {{ $unreadMessagesCount > 9 ? '9+' : $unreadMessagesCount }}
                </span>

                @if(request()->routeIs('chat*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-black rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-bold tracking-tight mt-0.5">Xabarlar</span>
        </a>

        <!-- 5. Profil (Profile) -->
        <a href="{{ route('profile.show', auth()->user()->username) }}" 
           class="flex flex-col items-center justify-center flex-1 py-1 group relative {{ request()->routeIs('profile*') ? 'text-black' : 'text-gray-400 hover:text-gray-700' }}">
            <div class="relative p-0.5">
                <div class="w-6 h-6 rounded-full overflow-hidden bg-black text-white flex items-center justify-center text-[10px] font-black ring-2 transition-all {{ request()->routeIs('profile*') ? 'ring-black' : 'ring-transparent' }}">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>

                <!-- Unread Notification / Message dot -->
                <span id="mobile-profile-unread-dot" 
                      style="{{ ($unreadMessagesCount + $unreadCount) > 0 ? 'display: block;' : 'display: none;' }}" 
                      class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>

                @if(request()->routeIs('profile*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-black rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-bold tracking-tight mt-0.5">Profil</span>
        </a>

    </div>
</nav>
