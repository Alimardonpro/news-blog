<x-app-layout>
    <div x-data="{ 
            activeTab: 'followers', 
            search: '',
            followers: {{ $followers->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'username' => $f->username, 'avatar' => $f->avatar ? asset('storage/'.$f->avatar) : null, 'url' => route('profile.show', $f->username), 'is_following' => Auth::user()->isFollowing($f)])->toJson() }},
            following: {{ $following->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'username' => $f->username, 'avatar' => $f->avatar ? asset('storage/'.$f->avatar) : null, 'url' => route('profile.show', $f->username)])->toJson() }},
            get filteredList() {
                let list = this.activeTab === 'followers' ? this.followers : this.following;
                return list.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()) || i.username.toLowerCase().includes(this.search.toLowerCase()));
            }
         }" 
         class="min-h-screen bg-[#F0F2F5] dark:bg-black pb-12">
        
        <div class="max-w-[1300px] mx-auto px-6 py-10 flex flex-col lg:flex-row gap-10">
            
            <!-- CHAP TOMON: ASOSIY QISM -->
            <div class="flex-1 space-y-8">
                
                <!-- 1. HEADER & SEARCH: Tiniq va Baland Kontrast -->
                <div class="bg-white dark:bg-[#0A0A0A] p-8 rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.06)] border border-white/20">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-8 mb-10">
                        <div class="flex items-center gap-6">
                            <!-- Back Button -->
                            <a href="{{ route('profile.show', $user->username) }}" class="w-12 h-12 bg-[#000000] text-white rounded-[1.2rem] flex items-center justify-center hover:scale-110 transition-all shadow-xl shadow-black/20">
                                <i class="fas fa-chevron-left fa-sm"></i>
                            </a>
                            <div>
                                <h2 class="text-3xl font-[1000] text-[#000000] dark:text-white tracking-tighter italic leading-none">@ {{ $user->username }}</h2>
                                <p class="text-[11px] text-[#4F46E5] font-black uppercase tracking-[0.4em] mt-3">Tizim tarmog'i</p>
                            </div>
                        </div>

                        <!-- TABLAR: Rasmdegidek to'q qora -->
                        <div class="bg-[#F1F5F9] dark:bg-white/5 p-1.5 rounded-[1.8rem] flex gap-1">
                            <button @click="activeTab = 'followers'" 
                                class="px-10 py-4 text-[12px] font-[1000] uppercase tracking-widest rounded-[1.4rem] transition-all duration-300"
                                :class="activeTab === 'followers' ? 'bg-[#000000] text-white shadow-2xl scale-[1.05]' : 'text-[#64748B] hover:text-[#000000]'">
                                Obunachilar
                            </button>
                            <button @click="activeTab = 'following'" 
                                class="flex-1 px-10 py-4 text-[12px] font-[1000] uppercase tracking-widest rounded-[1.4rem] transition-all duration-300"
                                :class="activeTab === 'following' ? 'bg-[#000000] text-white shadow-2xl scale-[1.05]' : 'text-[#64748B] hover:text-[#000000]'">
                                Kuzatuvlar
                            </button>
                        </div>
                    </div>

                    <!-- SEARCH: Tiniq ko'k border -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                            <i class="fas fa-search text-[#94A3B8] group-focus-within:text-[#000000] transition-colors text-lg"></i>
                        </div>
                        <input type="text" x-model="search" placeholder="Ism yoki username orqali qidirish..." 
                            class="w-full bg-[#F8FAFC] dark:bg-white/5 border-2 border-transparent focus:border-[#4F46E5]/30 focus:bg-white rounded-[1.8rem] py-5 pl-16 pr-8 text-base font-bold text-[#000000] dark:text-white transition-all shadow-inner placeholder:text-[#CBD5E1]">
                    </div>
                </div>

                <!-- 2. RO'YXAT: Jonli va Tiniq -->
                <div class="space-y-5">
                    <template x-for="f in filteredList" :key="f.id">
                        <div class="bg-white dark:bg-[#0A0A0A] p-7 rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] flex items-center justify-between group hover:shadow-[0_25px_60px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-500 border border-transparent hover:border-[#4F46E5]/10">
                            <a :href="f.url" class="flex items-center gap-7">
                                <!-- Avatar: Rasmdegidek Squircle -->
                                <div class="relative w-20 h-20 shrink-0 group-hover:scale-105 transition-transform duration-500">
                                    <template x-if="f.avatar">
                                        <img :src="f.avatar" class="w-full h-full rounded-[2rem] object-cover shadow-[0_10px_25px_rgba(0,0,0,0.1)] border-2 border-white">
                                    </template>
                                    <template x-if="!f.avatar">
                                        <div class="w-full h-full bg-[#F1F5F9] rounded-[2rem] flex items-center justify-center text-[#000000] font-[1000] text-2xl shadow-inner">
                                            <span x-text="f.name.charAt(0)"></span>
                                        </div>
                                    </template>
                                    <!-- Status Icon: Jonli ko'k -->
                                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-[#3B82F6] border-4 border-white dark:border-[#0A0A0A] rounded-full flex items-center justify-center text-white text-[10px] shadow-lg shadow-blue-500/30">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-[1000] text-xl text-[#000000] dark:text-white tracking-tighter" x-text="f.name"></h4>
                                    <p class="text-[12px] text-[#3B82F6] font-[1000] uppercase tracking-widest mt-1 italic" x-text="'@' + f.username"></p>
                                </div>
                            </a>

                            <form :action="'/users/' + f.id + '/follow'" method="POST" class="shrink-0">
                                @csrf
                                <button class="px-10 py-4 rounded-[1.4rem] text-[11px] font-[1000] uppercase tracking-[0.2em] transition-all"
                                        :class="f.is_following ? 'bg-[#F1F5F9] text-[#94A3B8] border border-[#E2E8F0]' : 'bg-[#000000] text-white shadow-2xl shadow-black/30 hover:scale-105 active:scale-95'">
                                    <span x-text="f.is_following ? 'FOLLOWING' : 'FOLLOW BACK'"></span>
                                </button>
                            </form>
                        </div>
                    </template>
                </div>
            </div>

         <!-- O'NG TOMON: SIDEBAR (Ixcham va Chiroyli) -->
<div class="w-full lg:w-[350px] shrink-0">
    <div class="sticky top-6 space-y-4">
        
        <!-- 1. TIZIM STATISTIKASI (Kichraytirildi) -->
        <div class="bg-white dark:bg-[#0A0A0A] p-6 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-white/10">
            <h4 class="text-[10px] font-[1000] uppercase tracking-[0.4em] text-[#000000] dark:text-white mb-6 text-center border-b-2 border-[#4F46E5] inline-block w-full pb-2">Tizim Statistikasi</h4>
            
            <div class="space-y-4">
                <!-- Natijalar Soni (Ixcham) -->
                <div class="bg-[#F8FAFC] dark:bg-white/5 rounded-[2rem] p-5 text-center relative group border border-slate-50 dark:border-white/5">
                    <p class="text-[9px] font-black text-[#94A3B8] uppercase tracking-[0.3em] mb-1">Natijalar</p>
                    <span class="text-5xl font-[1000] text-[#000000] dark:text-white tracking-tighter block" x-text="filteredList.length"></span>
                    <div class="w-10 h-10 bg-[#4F46E5] text-white rounded-xl flex items-center justify-center text-lg mx-auto mt-3 shadow-lg shadow-blue-500/20">
                        <i class="fas fa-users"></i>
                    </div>
                </div>

                <!-- Jami statistika qatorlari (Ixchamroq) -->
                <div class="space-y-2 px-2">
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-[#94A3B8] border-b border-[#F1F5F9] pb-2">
                        <span>Obunachilar:</span>
                        <span class="text-[#000000] dark:text-white text-base" x-text="followers.length"></span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-[#94A3B8]">
                        <span>Kuzatuvlar:</span>
                        <span class="text-[#000000] dark:text-white text-base" x-text="following.length"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. PRO MASLAHAT (Kichikroq va Elegant) -->
        <div class="bg-gradient-to-br from-[#4F46E5] to-[#7C3AED] p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
            <h3 class="text-[9px] font-black uppercase tracking-[0.3em] mb-3 opacity-80 italic">Pro Maslahat:</h3>
            <p class="text-base font-bold italic leading-tight tracking-tight opacity-95">
                "Obunachilaringiz bilan faol bo'ling va ularning postlariga layk bosing!"
            </p>
            <div class="mt-5 flex items-center gap-2">
                <div class="h-[1px] w-8 bg-white/40"></div>
                <span class="text-[8px] font-black uppercase tracking-[0.2em] opacity-40">Bloggram Team</span>
            </div>
        </div>

    </div>
</div>

        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #000; border-radius: 20px; }
    </style>
</x-app-layout>