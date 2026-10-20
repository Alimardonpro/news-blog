<x-app-layout>
    @php
        $isMyProfile = Auth::id() === $user->id;

        $hasProfileErrors =
            $errors->has('name') ||
            $errors->has('username') ||
            $errors->has('bio') ||
            $errors->has('email') ||
            $errors->has('avatar') ||
            $errors->has('banner');
    @endphp

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 pb-20"
         x-data="{
            openEditModal: {{ $isMyProfile && $hasProfileErrors ? 'true' : 'false' }},
            openSettingsModal: {{ $isMyProfile && ($errors->updatePassword->any() || $errors->userDeletion->any()) ? 'true' : 'false' }},
            deleteConfirm: false
         }">

        <div class="w-full">

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                     class="mb-4 p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-center justify-between shadow-sm transition-all">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm font-bold">Profil rasmi muvaffaqiyatli saqlandi!</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" 
                     class="mb-4 p-3.5 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 flex items-center justify-between shadow-sm transition-all">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-sm font-bold">{{ $errors->first() }}</span>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:text-red-700 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none border border-slate-150 dark:border-slate-800 overflow-hidden mb-6 transition-colors">

                @if($isMyProfile)
                    <!-- Direct Upload Forms (With PATCH method and native label triggers) -->
                    <form id="directBannerForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="redirect_to" value="{{ route('profile.show', $user->username) }}">
                        <input type="file" id="directBannerInput" name="banner" accept="image/*" onchange="if(this.files && this.files.length) this.form.submit()">
                    </form>

                    <form id="directAvatarForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="redirect_to" value="{{ route('profile.show', $user->username) }}">
                        <input type="file" id="directAvatarInput" name="avatar" accept="image/*" onchange="if(this.files && this.files.length) this.form.submit()">
                    </form>
                @endif

                <!-- Kengaytirilgan va chiroyli ko'rinadigan Banner balandligi: h-44 sm:h-56 md:h-64 -->
                <div class="h-44 sm:h-56 md:h-64 w-full bg-slate-100 dark:bg-slate-800 relative group overflow-hidden">
                    @if($user->banner)
                        <img src="{{ asset('storage/' . $user->banner) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-slate-800 via-slate-700 to-indigo-900"></div>
                    @endif

                    @if($isMyProfile)
                        <!-- Yagona va toza Bannerni o'zgartirish tugmasi (Native Label) -->
                        <label for="directBannerInput" 
                               title="Bannerni o'zgartirish"
                               class="absolute top-3 right-3 sm:top-4 sm:right-4 z-20 flex items-center gap-2 bg-slate-900/75 hover:bg-slate-900 text-white backdrop-blur-md px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-2xl text-xs sm:text-sm font-bold shadow-lg transition active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Bannerni o'zgartirish</span>
                            <span class="sm:hidden">Banner</span>
                        </label>
                    @endif
                </div>

                <!-- Avatar va Profil ma'lumotlari: ixcham, qulay va butun ekranni to'ldirib qo'ymaydigan -->
                <div class="px-4 sm:px-6 pb-5 sm:pb-6 flex flex-col items-center text-center relative z-10 -mt-10 sm:-mt-12">

                    @if($isMyProfile)
                        <!-- Native Label Trigger for Avatar (Works 100% reliably in Safari and all browsers) -->
                        <label for="directAvatarInput"
                               title="Profil rasmini o'zgartirish"
                               class="w-20 h-20 sm:w-24 sm:h-24 bg-white dark:bg-slate-800 rounded-[1.4rem] sm:rounded-[1.6rem] p-1.5 shadow-xl shadow-slate-200/50 dark:shadow-black/50 mb-2 transform rotate-2 sm:rotate-3 hover:rotate-0 transition-transform duration-300 relative group/avatar cursor-pointer block">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full rounded-[1.2rem] sm:rounded-[1.4rem] object-cover pointer-events-none">
                            @else
                                <div class="w-full h-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-[1.2rem] sm:rounded-[1.4rem] flex items-center justify-center text-3xl sm:text-4xl font-black pointer-events-none">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <!-- Yagona va toza Avatar kamera nishoni (Badge) -->
                            <span class="absolute -bottom-1 -right-1 sm:bottom-0 sm:right-0 z-20 w-7 h-7 sm:w-8 sm:h-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full flex items-center justify-center shadow-lg border-2 border-white dark:border-slate-800 transition active:scale-95 group-hover/avatar:scale-110 pointer-events-none">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </span>
                        </label>
                    @else
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-white dark:bg-slate-800 rounded-[1.4rem] sm:rounded-[1.6rem] p-1.5 shadow-xl shadow-slate-200/50 dark:shadow-black/50 mb-2 transform rotate-2 sm:rotate-3 transition-transform duration-300 relative group/avatar block">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full rounded-[1.2rem] sm:rounded-[1.4rem] object-cover">
                            @else
                                <div class="w-full h-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-[1.2rem] sm:rounded-[1.4rem] flex items-center justify-center text-3xl sm:text-4xl font-black">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-snug">
                        {{ $user->name }}
                    </h1>

                    <p class="text-indigo-500 font-bold text-xs sm:text-sm mt-0.5 mb-2">
                        {{ '@' . $user->username }}
                    </p>

                    <p class="text-slate-600 dark:text-slate-300 max-w-xl text-xs sm:text-sm leading-relaxed mb-3.5">
                        {{ $user->bio ?? "Bu yerda sizning bio ma'lumotlaringiz turadi. O'zingiz haqingizda qisqacha yozib qoldiring." }}
                    </p>

                    <!-- Ixcham va qulay Statistika paneli -->
                    <div class="inline-flex items-center justify-center gap-5 sm:gap-8 bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800/80 px-5 sm:px-7 py-2 rounded-2xl mb-4 transition-colors">
                        <div class="flex items-center gap-1.5 text-center">
                            <span class="text-sm sm:text-base font-black text-slate-900 dark:text-white">{{ $posts->count() }}</span>
                            <span class="text-[10px] sm:text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Post</span>
                        </div>

                        <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>

                        <a href="{{ route('profile.users', [$user->username, 'followers']) }}" class="flex items-center gap-1.5 text-center cursor-pointer group hover:no-underline">
                            <span class="text-sm sm:text-base font-black text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                {{ $user->followers_count ?? $user->followers()->count() }}
                            </span>
                            <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-indigo-400 transition">
                                Obunachi
                            </span>
                        </a>

                        <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>

                        <a href="{{ route('profile.users', [$user->username, 'following']) }}" class="flex items-center gap-1.5 text-center cursor-pointer group hover:no-underline">
                            <span class="text-sm sm:text-base font-black text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                {{ $user->following_count ?? $user->following()->count() }}
                            </span>
                            <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-indigo-400 transition">
                                Obuna
                            </span>
                        </a>
                    </div>

                    <div class="flex items-center gap-2.5 flex-wrap justify-center">
                        @if($isMyProfile)
                            <button @click.stop="openEditModal = true"
                                class="bg-slate-900 dark:bg-white text-white dark:text-black font-bold py-2.5 px-6 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-150 hover:shadow-lg active:scale-95 transition-all text-xs sm:text-sm">
                                Profilni tahrirlash
                            </button>

                            <button @click.stop="openSettingsModal = true"
                                title="Sozlamalar"
                                class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white active:scale-95 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>

                            <!-- Quick Dark Mode Toggle Button on Profile -->
                            <button type="button" 
                                    @click="$dispatch('toggle-dark-mode')"
                                    :title="isDark ? 'Kunduzgi rejim' : 'Tungi rejim'"
                                    class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-amber-400 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900 active:scale-95 transition-all">
                                <template x-if="!isDark">
                                    <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                    </svg>
                                </template>
                                <template x-if="isDark">
                                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </template>
                            </button>
                        @else
                            <form action="{{ route('users.follow', $user) }}" method="POST">
                                @csrf
                                @php $isFollowing = Auth::user()->isFollowing($user); @endphp

                                <button type="submit"
                                    class="{{ $isFollowing ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 hover:bg-red-50 dark:hover:bg-red-950/40 hover:text-red-600 hover:border-red-200' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-600/30' }} font-bold py-2.5 px-6 rounded-xl active:scale-95 transition-all group text-xs sm:text-sm">
                                    <span class="group-hover:hidden">{{ $isFollowing ? 'Kuzatilmoqda' : 'Kuzatish' }}</span>
                                    <span class="hidden group-hover:block">{{ $isFollowing ? 'Bekor qilish' : 'Kuzatish' }}</span>
                                </button>
                            </form>

                            <a href="{{ route('chat.index', ['user_id' => $user->id]) }}"
                               class="py-2.5 px-6 rounded-xl flex items-center justify-center bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white active:scale-95 transition-all gap-2 text-xs sm:text-sm font-bold"
                                title="Xabar yozish">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>Xabar yozish</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RESPONSIVE 3-COLUMN PROFILE POSTS GRID -->
            <div class="grid grid-cols-3 gap-1 sm:gap-3 md:gap-4 lg:gap-6">
                @forelse($posts as $post)
                    @php
                        $imgData = [];
                        if($post->image) {
                            $decoded = json_decode($post->image, true);
                            $imgData = is_array($decoded) ? $decoded : [$post->image];
                        }
                    @endphp

                    <div class="aspect-square relative rounded-lg sm:rounded-2xl overflow-hidden group bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all duration-300">
                        
                        <!-- Owner Quick Delete Button -->
                        @if(Auth::id() === $post->user_id)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                  class="absolute top-1.5 left-1.5 sm:top-2.5 sm:left-2.5 opacity-0 group-hover:opacity-100 transition-opacity z-20"
                                  onsubmit="return confirm('Rostdan ham o\'chirmoqchimisiz?');"
                                  @click.stop>
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="O'chirish" class="bg-black/75 hover:bg-red-600 text-white p-1.5 sm:p-2 rounded-lg sm:rounded-xl shadow-md transition">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif

                        <!-- Main Content (Image or Text gradient tile) -->
                        @if(count($imgData) > 0)
                            <a href="{{ route('posts.show', $post) }}" class="block w-full h-full relative">
                                <img src="{{ asset('storage/' . $imgData[0]) }}" 
                                     alt="Post rasm"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                @if(count($imgData) > 1)
                                    <div class="absolute top-1.5 right-1.5 sm:top-2.5 sm:right-2.5 bg-black/60 backdrop-blur-sm text-white p-1 sm:p-1.5 rounded-md sm:rounded-lg shadow-sm">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('posts.show', $post) }}" 
                               class="block w-full h-full p-2.5 sm:p-4 flex flex-col justify-between group-hover:scale-[1.02] transition-transform select-none" 
                               style="background: {{ $post->gradient_style }};">
                                <div class="text-white/30">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                    </svg>
                                </div>
                                <p class="text-white font-bold text-[10px] sm:text-xs md:text-sm line-clamp-3 sm:line-clamp-4 leading-snug drop-shadow-sm text-center px-1">
                                    {{ $post->body }}
                                </p>
                                <span class="text-[8px] sm:text-[10px] font-bold text-white/60 uppercase tracking-widest text-right">Matn</span>
                            </a>
                        @endif

                        <!-- Hover/Tap Overlay with Likes & Comments Counts (Instagram style) -->
                        <a href="{{ route('posts.show', $post) }}" 
                           class="absolute inset-0 bg-black/50 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-2.5 sm:gap-6 text-white font-black text-xs sm:text-base z-10 pointer-events-none sm:pointer-events-auto">
                            <div class="flex items-center gap-1 sm:gap-1.5 drop-shadow-md">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current text-white" viewBox="0 0 20 20">
                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                                </svg>
                                <span>{{ $post->likes->count() }}</span>
                            </div>

                            <div class="flex items-center gap-1 sm:gap-1.5 drop-shadow-md">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current text-white" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $post->comments->count() }}</span>
                            </div>
                        </a>

                    </div>
                @empty
                    <div class="col-span-full py-16 text-center flex flex-col items-center justify-center bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 border-dashed">
                        <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Hozircha postlar yo‘q</h3>
                    </div>
                @endforelse
            </div>
        </div>

        @if($isMyProfile)
            <template x-teleport="body">
                <div x-show="openEditModal"
                     x-cloak
                     style="display: none;"
                     class="fixed inset-0 z-[120] flex items-center justify-center p-4 overflow-y-auto">

                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                         @click="openEditModal = false"
                         x-transition.opacity></div>

                    <form id="profileUpdateForm"
                          method="POST"
                          action="{{ route('profile.update') }}"
                          enctype="multipart/form-data"
                          class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-100 dark:border-slate-800 w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden relative z-10 my-auto"
                          x-transition>
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="redirect_to" value="{{ route('profile.show', $user->username) }}">

                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 backdrop-blur sticky top-0 z-10">
                        <div class="flex items-center gap-4">
                            <button type="button"
                                    @click="openEditModal = false"
                                    class="text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            <h2 class="text-lg font-black text-slate-900 dark:text-white">Profilni tahrirlash</h2>
                        </div>

                        <button type="submit"
                                class="bg-slate-900 dark:bg-white text-white dark:text-black text-sm font-bold px-6 py-2 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-200 transition">
                            Saqlash
                        </button>
                    </div>

                    <div class="p-6 max-h-[75vh] overflow-y-auto space-y-6">

                            <div class="relative mb-12"
                                 x-data="{
                                    avatarPreview: '{{ $user->avatar ? asset('storage/' . $user->avatar) : '' }}',
                                    bannerPreview: '{{ $user->banner ? asset('storage/' . $user->banner) : '' }}'
                                 }">

                                <label for="bannerInput" class="h-32 w-full rounded-2xl overflow-hidden relative group cursor-pointer bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 block">

                                    <template x-if="bannerPreview">
                                        <img :src="bannerPreview" class="w-full h-full object-cover">
                                    </template>

                                    <template x-if="!bannerPreview">
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm font-medium">
                                            Banner yuklash
                                        </div>
                                    </template>

                                    <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                        <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <input type="file"
                                           name="banner"
                                           id="bannerInput"
                                           class="hidden"
                                           accept="image/*"
                                           @change="
                                                const file = $event.target.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = e => bannerPreview = e.target.result;
                                                    reader.readAsDataURL(file);
                                                }
                                           ">
                                </label>

                                <label for="avatarInput" class="absolute -bottom-8 left-6 group/avatar cursor-pointer block">
                                    <div class="w-24 h-24 rounded-[1.5rem] bg-white dark:bg-slate-800 p-1.5 shadow-lg relative overflow-hidden transform rotate-3 hover:rotate-0 transition-transform">
                                        <template x-if="avatarPreview">
                                            <img :src="avatarPreview" class="w-full h-full rounded-xl object-cover pointer-events-none">
                                        </template>

                                        <template x-if="!avatarPreview">
                                            <div class="w-full h-full bg-slate-900 dark:bg-white rounded-xl flex items-center justify-center text-white dark:text-black font-black text-2xl pointer-events-none">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                        </template>

                                        <div class="absolute inset-0 bg-slate-900/40 rounded-[1.5rem] flex items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition pointer-events-none">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <input type="file"
                                           name="avatar"
                                           id="avatarInput"
                                           class="hidden"
                                           accept="image/*"
                                           @change="
                                                const file = $event.target.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = e => avatarPreview = e.target.result;
                                                    reader.readAsDataURL(file);
                                                }
                                           ">
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Ism</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                       class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-3 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                                       required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Foydalanuvchi nomi (Username)</label>
                                <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}"
                                       class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-3 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                                       required>
                                @error('username')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Bio ma'lumot</label>
                                <textarea name="bio" rows="3"
                                          class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-3 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none resize-none">{{ old('bio', Auth::user()->bio) }}</textarea>
                                @error('bio')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                       class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-3 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                                       required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            @error('avatar')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror

                            @error('banner')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                    </div>
                </form>
            </div>
            </template>

            <template x-teleport="body">
                <div x-show="openSettingsModal"
                     x-cloak
                     style="display: none;"
                     class="fixed inset-0 z-[120] flex items-center justify-center p-4 overflow-y-auto">

                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                         @click="openSettingsModal = false"
                         x-transition.opacity></div>

                    <div class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-100 dark:border-slate-800 w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden relative z-10 my-auto"
                         x-transition>

                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 backdrop-blur sticky top-0 z-10">
                        <div class="flex items-center gap-4">
                            <button type="button"
                                    @click="openSettingsModal = false"
                                    class="text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            <h2 class="text-lg font-black text-slate-900 dark:text-white">Sozlamalar</h2>
                        </div>
                    </div>

                    <div class="p-6 max-h-[80vh] overflow-y-auto space-y-6">

                        <!-- 1. Tungi rejim (Dark Mode) sozlamasi -->
                        <div class="bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-700/80 rounded-[1.5rem] p-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-1">Tungi rejim (Dark Mode)</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Kechalari ko'zingizni toliqtirmaslik uchun qulay qorong'i rejimni yoqing.
                                </p>
                            </div>
                            <button type="button" 
                                    @click="$dispatch('toggle-dark-mode')"
                                    class="relative inline-flex h-8 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="isDark ? 'bg-indigo-600' : 'bg-slate-300 dark:bg-slate-700'">
                                <span class="sr-only">Tungi rejimni o'zgartirish</span>
                                <span class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out flex items-center justify-center text-xs"
                                      :class="isDark ? 'translate-x-6 text-indigo-600' : 'translate-x-0 text-slate-500'">
                                    <template x-if="isDark">
                                        <i class="fa-solid fa-moon text-[11px]"></i>
                                    </template>
                                    <template x-if="!isDark">
                                        <i class="fa-solid fa-sun text-[11px]"></i>
                                    </template>
                                </span>
                            </button>
                        </div>

                        <!-- 2. Parolni yangilash & Tekshirish (Show/Hide ko'zcha bilan) -->
                        <div class="bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-700/80 rounded-[1.5rem] p-6"
                             x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                            <h3 class="text-lg font-black text-slate-900 dark:text-white mb-1">Parolni yangilash</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                                Akkaunt xavfsizligi uchun kuchli parol ishlating.
                            </p>

                            <!-- Xavfsizlik eslatmasi -->
                            <div class="mb-5 p-3 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-start gap-2.5 text-xs text-indigo-700 dark:text-indigo-300">
                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Parolingiz xavfsiz xeshlanadi. Yangi parol kiritayotganda adashmaslik uchun o'ng tarafdagi <strong>ko'zcha</strong> tugmasini bosib tekshirishingiz mumkin.</span>
                            </div>

                            @if (session('status') === 'password-updated')
                                <div class="mb-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 text-sm font-medium">
                                    Parol muvaffaqiyatli yangilandi.
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Joriy parol</label>
                                    <div class="relative">
                                        <input :type="showCurrent ? 'text' : 'password'" 
                                               name="current_password"
                                               class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-3 pr-11 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                                        <button type="button" 
                                                @click="showCurrent = !showCurrent" 
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition p-1">
                                            <template x-if="!showCurrent">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </template>
                                            <template x-if="showCurrent">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                            </template>
                                        </button>
                                    </div>
                                    @if($errors->updatePassword->has('current_password'))
                                        <p class="mt-2 text-sm text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Yangi parol</label>
                                    <div class="relative">
                                        <input :type="showNew ? 'text' : 'password'" 
                                               name="password"
                                               class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-3 pr-11 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                                        <button type="button" 
                                                @click="showNew = !showNew" 
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition p-1">
                                            <template x-if="!showNew">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </template>
                                            <template x-if="showNew">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                            </template>
                                        </button>
                                    </div>
                                    @if($errors->updatePassword->has('password'))
                                        <p class="mt-2 text-sm text-red-500">{{ $errors->updatePassword->first('password') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Parolni tasdiqlang</label>
                                    <div class="relative">
                                        <input :type="showConfirm ? 'text' : 'password'" 
                                               name="password_confirmation"
                                               class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-3 pr-11 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                                        <button type="button" 
                                                @click="showConfirm = !showConfirm" 
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition p-1">
                                            <template x-if="!showConfirm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </template>
                                            <template x-if="showConfirm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                            </template>
                                        </button>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button type="submit"
                                            class="bg-slate-900 dark:bg-white text-white dark:text-black font-bold py-3 px-6 rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-200 transition-all">
                                        Parolni saqlash
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- 3. Akkauntni o'chirish -->
                        <div class="bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/30 rounded-[1.5rem] p-6"
                             x-data="{ showDelete: false }">
                            <h3 class="text-lg font-black text-red-600 dark:text-red-400 mb-2">Akkauntni o‘chirish</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-5">
                                Akkaunt o‘chirilsa, barcha ma’lumotlaringiz qayta tiklanmaydigan tarzda o‘chadi.
                            </p>

                            <button type="button"
                                    @click="deleteConfirm = !deleteConfirm"
                                    class="bg-white dark:bg-slate-900 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900 font-bold py-3 px-6 rounded-2xl hover:bg-red-100 dark:hover:bg-red-950/40 transition-all">
                                Akkauntni o‘chirish
                            </button>

                            <div x-show="deleteConfirm" x-transition class="mt-5">
                                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                                    @csrf
                                    @method('DELETE')

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                                            Tasdiqlash uchun parolingizni kiriting
                                        </label>
                                        <div class="relative">
                                            <input :type="showDelete ? 'text' : 'password'" 
                                                   name="password"
                                                   class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-3 pr-11 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none">
                                            <button type="button" 
                                                    @click="showDelete = !showDelete" 
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition p-1">
                                                <template x-if="!showDelete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </template>
                                                <template x-if="showDelete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                                </template>
                                            </button>
                                        </div>
                                        @if($errors->userDeletion->has('password'))
                                            <p class="mt-2 text-sm text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <button type="submit"
                                                class="bg-red-600 text-white font-bold py-3 px-6 rounded-2xl hover:bg-red-700 transition-all">
                                            Ha, o‘chirilsin
                                        </button>

                                        <button type="button"
                                                @click="deleteConfirm = false"
                                                class="bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-bold py-3 px-6 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                                            Bekor qilish
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </template>
        @endif
    </div>
</x-app-layout>