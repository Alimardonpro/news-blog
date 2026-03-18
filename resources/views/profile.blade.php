<x-app-layout>
    @php
        $isMyProfile = Auth::id() === $user->id;
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10" 
         x-data="{ 
            openEditModal: {{ $isMyProfile && ($errors->any()) ? 'true' : 'false' }}, 
            openSettingsModal: {{ $isMyProfile && ($errors->updatePassword->any() || $errors->userDeletion->any()) ? 'true' : 'false' }},
            activeTab: 'posts'
         }">

        <div class="grid grid-cols-12 gap-8">
            
            <!-- CHaP / ASOSIY QISM (8 qator) -->
            <div class="col-span-12 lg:col-span-8">
                
                <!-- Asosiy Profil Kardi -->
                <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
                    
                    <!-- Banner qismi -->
                    <div class="h-48 sm:h-64 w-full bg-slate-100 relative group">
                        @if($user->banner)
                            <img src="{{ asset('storage/' . $user->banner) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-slate-800 to-slate-600"></div>
                        @endif
                        
                        <!-- Orqaga qaytish tugmasi -->
                        <a href="{{ route('dashboard') }}" class="absolute top-4 left-4 w-10 h-10 bg-white/20 backdrop-blur-md flex items-center justify-center rounded-full text-white hover:bg-white/40 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                    </div>

                    <!-- Profil Ma'lumotlari (Markazlashtirilgan) -->
                    <div class="px-6 pb-10 flex flex-col items-center text-center relative z-10 -mt-16 sm:-mt-20">
                        
                        <!-- Avatar -->
                        <div class="w-32 h-32 sm:w-40 sm:h-40 bg-white rounded-[2rem] p-1.5 shadow-xl shadow-slate-200/50 mb-4 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full rounded-[1.5rem] object-cover">
                            @else
                                <div class="w-full h-full bg-slate-900 rounded-[1.5rem] flex items-center justify-center text-white text-5xl font-black">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Ism va Bio -->
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
                        <p class="text-indigo-500 font-bold text-sm sm:text-base mt-1 mb-4">{{ '@' . $user->username }}</p>
                        <p class="text-slate-600 max-w-2xl text-[15px] sm:text-base leading-relaxed mb-6">{{ $user->bio ?? "Bu yerda sizning bio ma'lumotlaringiz turadi. O'zingiz haqingizda qisqacha yozib qoldiring." }}</p>

                        <!-- Statistika -->
                        <div class="flex items-center gap-6 sm:gap-12 bg-slate-50 border border-slate-100 px-8 py-4 rounded-3xl mb-8">
                            <div class="text-center">
                                <span class="block text-2xl font-black text-slate-900">{{ $user->posts->count() ?? 0 }}</span>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Postlar</span>
                            </div>
                            <div class="w-px h-10 bg-slate-200"></div>
                            <a href="{{ route('followers') }}" class="text-center cursor-pointer group block hover:no-underline">
                                <span class="block text-2xl font-black text-slate-900 group-hover:text-indigo-600 transition">{{ $user->followers()->count() }}</span>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-indigo-400 transition mt-1">Obunachi</span>
                            </a>
                            <div class="w-px h-10 bg-slate-200"></div>
                            <div class="text-center cursor-pointer group">
                                <span class="block text-2xl font-black text-slate-900 group-hover:text-indigo-600 transition">{{ $user->following()->count() }}</span>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-indigo-400 transition mt-1">Obuna</span>
                            </div>
                        </div>

                        <!-- Harakat tugmalari -->
                        <div class="flex items-center gap-3">
                            @if($isMyProfile)
                                <button @click="openEditModal = true" class="bg-slate-900 text-white font-bold py-3 px-8 rounded-2xl hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-900/20 active:scale-95 transition-all">
                                    Profilni tahrirlash
                                </button>
                                <button @click="openSettingsModal = true" class="w-12 h-12 flex items-center justify-center bg-slate-50 border border-slate-200 text-slate-600 rounded-2xl hover:bg-slate-100 hover:text-slate-900 active:scale-95 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </button>
                            @else
                                <button class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-2xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/30 active:scale-95 transition-all">
                                    Obunachi bo'lish
                                </button>
                                
                                <!-- YAP-YANGI: Xabar yuborish tugmasi -->
                                <a href="{{ route('chat.index', ['start_with' => $user->id]) }}" class="py-3 px-8 rounded-2xl flex items-center justify-center bg-slate-50 border border-slate-200 text-slate-600 rounded-2xl hover:bg-slate-100 hover:text-slate-900 active:scale-95 transition-all gap-2" title="Xabar yozish">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    <span class="">Xabar yozish</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Filter / Tablar -->
                <div class="flex justify-center mb-6">
                    <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 flex gap-1">
                        <button @click="activeTab = 'posts'" :class="activeTab === 'posts' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Postlar</button>
                        <button @click="activeTab = 'media'" :class="activeTab === 'media' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Medialar</button>
                    </div>
                </div>

                <!-- Postlar (Grid Uslubida: 1 ta yoki 2 ta ustun) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($user->posts as $post)
                        <!-- Post Kardi -->
                        <div class="bg-white rounded-[2rem] border border-slate-100 p-5 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col group relative">
                            
                            @if(Auth::id() === $post->user_id)
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity z-10" onsubmit="return confirm('Rostdan ham o\'chirmoqchimisiz?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-white/90 backdrop-blur text-red-500 p-2 rounded-xl shadow-sm hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endif

                            <div class="flex items-center gap-3 mb-4">
                                @if($post->user->avatar)
                                    <img src="{{ asset('storage/' . $post->user->avatar) }}" class="h-10 w-10 rounded-xl object-cover">
                                @else
                                    <div class="h-10 w-10 bg-slate-900 rounded-xl flex items-center justify-center text-white font-bold text-sm">{{ substr($user->name, 0, 1) }}</div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm leading-tight">{{ $user->name }}</h3>
                                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <p class="text-slate-700 text-[15px] leading-relaxed mb-4 flex-1 break-words">{{ $post->body }}</p>
                            
                            @if($post->image)
                                <div class="rounded-[1.5rem] overflow-hidden border border-slate-100 mb-4 h-48">
                                    <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-4 border-t border-slate-50 mt-auto">
                                <div class="flex items-center gap-5">
                                    <button class="flex items-center gap-1.5 text-slate-400 hover:text-indigo-500 transition-colors group/btn">
                                        <div class="p-1.5 rounded-full group-hover/btn:bg-indigo-50 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold">{{ $post->likes->count() }}</span>
                                    </button>
                                    <button class="flex items-center gap-1.5 text-slate-400 hover:text-blue-500 transition-colors group/btn">
                                        <div class="p-1.5 rounded-full group-hover/btn:bg-blue-50 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold">{{ $post->comments->count() }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center flex flex-col items-center justify-center bg-white rounded-[2rem] border border-slate-100 border-dashed">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">Hozircha postlar yo'q</h3>
                            <p class="text-slate-500 text-sm">Bu foydalanuvchi hali hech qanday post joylamagan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- O'NG TARAF / MA'LUMOTLAR PANELI (4 qator) -->
            <div class="hidden lg:block lg:col-span-4">
                <div class="sticky top-6 space-y-6">
                    
                    <!-- Qidiruv -->
                    <div class="relative group">
                        <input type="text" placeholder="Platformadan izlash..." class="w-full bg-white border border-slate-100 shadow-sm text-slate-900 text-sm rounded-2xl pl-12 pr-4 py-3.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-300 transition-all outline-none">
                        <div class="absolute left-4 top-3.5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Trendlar -->
                    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6">
                        <h3 class="font-black text-slate-900 text-lg mb-5">Siz uchun trendlar</h3>
                        <div class="space-y-5">
                            <div class="cursor-pointer group">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex justify-between">Texnologiya <span class="group-hover:text-indigo-500 transition">⋮</span></p>
                                <p class="font-bold text-slate-900 text-base group-hover:text-indigo-600 transition">#Laravel11</p>
                                <p class="text-xs text-slate-500 mt-1">15.4K postlar</p>
                            </div>
                            <div class="cursor-pointer group">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex justify-between">Dizayn <span class="group-hover:text-indigo-500 transition">⋮</span></p>
                                <p class="font-bold text-slate-900 text-base group-hover:text-indigo-600 transition">Tailwind CSS V4</p>
                                <p class="text-xs text-slate-500 mt-1">8,230 postlar</p>
                            </div>
                            <div class="cursor-pointer group">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex justify-between">O'zbekiston <span class="group-hover:text-indigo-500 transition">⋮</span></p>
                                <p class="font-bold text-slate-900 text-base group-hover:text-indigo-600 transition">#ITPark</p>
                                <p class="text-xs text-slate-500 mt-1">5,100 postlar</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Linklar -->
                    <div class="px-2 text-[13px] text-slate-500 flex flex-wrap gap-x-4 gap-y-2">
                        <a href="#" class="hover:text-slate-900 transition">Shartlar</a>
                        <a href="#" class="hover:text-slate-900 transition">Maxfiylik siyosati</a>
                        <a href="#" class="hover:text-slate-900 transition">Cookie</a>
                        <a href="#" class="hover:text-slate-900 transition">Yordam</a>
                        <span>© 2024 App Inc.</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ============================================== -->
        <!-- MODALLAR TO'LIQ KODI (EDIT PROFILE & SETTINGS) -->
        <!-- ============================================== -->
        @if($isMyProfile)
            
            <!-- EDIT PROFILE MODAL -->
            <div x-show="openEditModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4"
                 x-transition.opacity>
                
                <div @click.away="openEditModal = false" class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden relative">
                    
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white/90 backdrop-blur sticky top-0 z-10">
                        <div class="flex items-center gap-4">
                            <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-900 hover:bg-slate-100 p-2 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <h2 class="text-lg font-black text-slate-900">Profilni tahrirlash</h2>
                        </div>
                        <button form="profileUpdateForm" class="bg-slate-900 text-white text-sm font-bold px-6 py-2 rounded-xl hover:bg-slate-800 transition">Saqlash</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 max-h-[75vh] overflow-y-auto custom-scrollbar">
                        <form id="profileUpdateForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf @method('PATCH')
                            
                            <!-- Rasm va Banner joylash -->
                            <div class="relative mb-12" 
                                 x-data="{ 
                                    avatarPreview: '{{ $user->avatar ? asset('storage/' . $user->avatar) : null }}',
                                    bannerPreview: '{{ $user->banner ? asset('storage/' . $user->banner) : null }}' 
                                 }">
                                
                                <!-- Banner O'zgartirish -->
                                <div class="h-32 w-full rounded-2xl overflow-hidden relative group cursor-pointer bg-slate-100 border border-slate-200"
                                     @click="document.getElementById('bannerInput').click()">
                                    
                                    <template x-if="bannerPreview">
                                        <img :src="bannerPreview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!bannerPreview">
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm font-medium">Banner yuklash</div>
                                    </template>

                                    <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                        <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                    </div>
                                    <input type="file" name="banner" id="bannerInput" class="hidden" accept="image/*"
                                           @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { bannerPreview = e.target.result }; reader.readAsDataURL(file);">
                                </div>
                                
                                <!-- Avatar O'zgartirish -->
                                <div class="absolute -bottom-8 left-6 group/avatar cursor-pointer"
                                     @click="document.getElementById('avatarInput').click()">
                                    
                                    <div class="w-24 h-24 rounded-[1.5rem] bg-white p-1.5 shadow-lg relative overflow-hidden transform rotate-3 hover:rotate-0 transition-transform">
                                        <template x-if="avatarPreview">
                                            <img :src="avatarPreview" class="w-full h-full rounded-xl object-cover">
                                        </template>
                                        <template x-if="!avatarPreview">
                                            <div class="w-full h-full bg-slate-900 rounded-xl flex items-center justify-center text-white font-black text-2xl">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                        </template>

                                        <div class="absolute inset-0 bg-slate-900/40 rounded-[1.5rem] flex items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                    </div>
                                    <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*"
                                           @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { avatarPreview = e.target.result }; reader.readAsDataURL(file);">
                                </div>
                            </div>

                            <!-- Inputlar -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Ism</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Foydalanuvchi nomi (Username)</label>
                                <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Bio ma'lumot</label>
                                <textarea name="bio" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none resize-none">{{ old('bio', Auth::user()->bio) }}</textarea>
                                @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SETTINGS MODAL -->
            <div x-show="openSettingsModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4" x-transition.opacity>
                <div @click.away="openSettingsModal = false" class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden relative">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white/90 backdrop-blur sticky top-0 z-10">
                        <div class="flex items-center gap-4">
                            <button @click="openSettingsModal = false" class="text-slate-400 hover:text-slate-900 hover:bg-slate-100 p-2 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <h2 class="text-lg font-black text-slate-900">Sozlamalar</h2>
                        </div>
                    </div>
                    
                    <div class="p-6 max-h-[75vh] overflow-y-auto custom-scrollbar">
                        
                        <!-- Parolni yangilash -->
                        <h3 class="text-base font-bold text-slate-900 mb-4">Parolni yangilash</h3>
                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf @method('put')
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Joriy parol</label>
                                <input type="password" name="current_password" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Yangi parol</label>
                                <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Parolni tasdiqlang</label>
                                <input type="password" name="password_confirmation" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="bg-slate-900 text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-slate-800 transition">Yangilash</button>
                            </div>
                        </form>
                        
                        <hr class="my-8 border-slate-100">
                        
                        <!-- Akkauntni o'chirish -->
                        <div class="bg-red-50 p-5 rounded-2xl border border-red-100">
                            <h3 class="text-base font-bold text-red-600 mb-2">Akkauntni o'chirish</h3>
                            <p class="text-sm text-red-500/80 mb-4">Akkauntingizni o'chirganingizdan so'ng barcha ma'lumotlar qaytarib bo'lmas darajada o'chib ketadi.</p>
                            
                            <form method="post" action="{{ route('profile.destroy') }}">
                                @csrf @method('delete')
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-red-700 mb-2">Tasdiqlash uchun parolni kiriting</label>
                                    <input type="password" name="password" class="w-full bg-white border border-red-200 text-slate-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all">
                                </div>
                                <button type="submit" onclick="return confirm('Rostdan ham akkauntni o\'chirmoqchimisiz?')" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition">Akkauntni o'chirish</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @endif

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-app-layout>