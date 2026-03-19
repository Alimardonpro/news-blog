<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links (Kompyuter uchun) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- YANGI: Xabarlar menyusi -->
                    <x-nav-link href="/chat" :active="request()->is('chat*')">
                        {{ __('Xabarlar') }}
                        <!-- Ko'k raqam nishoni -->
                        <span id="global-unread-count" style="display: none;" class="bg-blue-500 text-white text-[11px] font-black px-2 py-0.5 rounded-full ml-2 shadow-sm animate-pulse">
                            0
                        </span>
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown (Kompyuter profil) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <!-- YANGI: "relative" klassi qo'shildi, qizil nuqta uchun -->
                        <button class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            <!-- YANGI: Qizil nuqta (Bildirishnoma) -->
                            <span id="profile-unread-dot" style="display: none;" class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full shadow-sm animate-bounce"></span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Telefon uchun menyu tugmasi) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="relative inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                    <!-- YANGI: Telefon menyusida ham qizil nuqta ko'rinishi uchun -->
                    <span id="mobile-hamburger-dot" style="display: none;" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full animate-bounce"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Telefon menyusi ochilganda) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- YANGI: Telefon uchun Xabarlar menyusi -->
            <x-responsive-nav-link href="/chat" :active="request()->is('chat*')" class="flex items-center justify-between">
                <span>{{ __('Xabarlar') }}</span>
                <!-- Telefon versiyasi uchun ko'k raqam -->
                <span id="mobile-unread-count" style="display: none;" class="bg-blue-500 text-white text-[11px] font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                    0
                </span>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- YANGI: JONLI BILDIRISHNOMA (REAL-TIME) SCRIPT -->
<!-- Buni faqat shu navigatsiya faylida saqlaymiz, shunda u saytning hamma joyida ishlaydi -->
<script>
    function checkGlobalUnread() {
        if (!document.querySelector('meta[name="csrf-token"]')) return;

        fetch('/chat/total-unread')
            .then(response => response.json())
            .then(data => {
                let count = data.count;
                
                // Elementlarni tutib olamiz
                let countBadge = document.getElementById('global-unread-count');
                let countBadgeMobile = document.getElementById('mobile-unread-count');
                let profileDot = document.getElementById('profile-unread-dot');
                let hamburgerDot = document.getElementById('mobile-hamburger-dot');

                if (count > 0) {
                    // Agar xabar bo'lsa, hammasini ko'rsatamiz
                    if (countBadge) { countBadge.innerText = count; countBadge.style.display = 'inline-flex'; }
                    if (countBadgeMobile) { countBadgeMobile.innerText = count; countBadgeMobile.style.display = 'inline-flex'; }
                    if (profileDot) profileDot.style.display = 'block';
                    if (hamburgerDot) hamburgerDot.style.display = 'block';
                } else {
                    // Xabar yo'q bo'lsa yashiramiz
                    if (countBadge) countBadge.style.display = 'none';
                    if (countBadgeMobile) countBadgeMobile.style.display = 'none';
                    if (profileDot) profileDot.style.display = 'none';
                    if (hamburgerDot) hamburgerDot.style.display = 'none';
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
    }
    
    // Sayt ochilganda tekshiradi
    document.addEventListener("DOMContentLoaded", checkGlobalUnread);
    // Har 4 soniyada orqa fonda aylanib turadi
    setInterval(checkGlobalUnread, 4000);
</script>