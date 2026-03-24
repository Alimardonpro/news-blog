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
         class="min-h-screen bg-[#F0F2F5] pb-10">
        
        <div class="max-w-[1150px] mx-auto px-6 py-10">
            
            <div class="space-y-6">
                
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-6">
                        <div class="flex items-center gap-5 w-full md:w-auto">
                            <a href="{{ route('profile.show', $user->username) }}" class="w-12 h-12 shrink-0 bg-[#000000] text-white rounded-[1.2rem] flex items-center justify-center hover:scale-105 transition-transform shadow-md">
                                <i class="fas fa-chevron-left fa-sm"></i>
                            </a>
                            <div class="min-w-0">
                                <h2 class="text-2xl font-black text-[#000000] tracking-tight leading-none truncate">@ {{ $user->username }}</h2>
                                <p class="text-[10px] text-[#4F46E5] font-bold uppercase tracking-[0.2em] mt-2">Tizim tarmog'i</p>
                            </div>
                        </div>

                        <div class="bg-[#F1F5F9] p-1.5 rounded-full flex gap-1 w-full md:w-auto shadow-inner">
                            <button @click="activeTab = 'followers'" 
                                class="flex-1 md:flex-none px-8 py-3 text-[12px] font-bold uppercase tracking-wider rounded-full transition-all duration-300"
                                :class="activeTab === 'followers' ? 'bg-[#000000] text-white shadow-lg' : 'text-[#64748B] hover:text-[#000000]'">
                                Obunachilar
                            </button>
                            <button @click="activeTab = 'following'" 
                                class="flex-1 md:flex-none px-8 py-3 text-[12px] font-bold uppercase tracking-wider rounded-full transition-all duration-300"
                                :class="activeTab === 'following' ? 'bg-[#000000] text-white shadow-lg' : 'text-[#64748B] hover:text-[#000000]'">
                                Kuzatuvlar
                            </button>
                        </div>
                    </div>

                    <div class="relative group">
                        <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                            <i class="fas fa-search text-[#94A3B8] group-focus-within:text-[#000000] transition-colors text-sm"></i>
                        </div>
                        <input type="text" x-model="search" placeholder="Ism yoki username orqali qidirish..." 
                            class="w-full bg-[#F8FAFC] border-2 border-transparent focus:border-[#4F46E5]/30 focus:bg-white rounded-[2rem] py-4 pl-14 pr-6 text-sm font-bold text-[#000000] transition-all shadow-inner placeholder:text-[#CBD5E1]">
                    </div>
                </div>

                <div class="space-y-4">
                    <template x-for="f in filteredList" :key="f.id">
                        <div class="bg-white p-5 md:px-8 md:py-6 rounded-[2rem] shadow-sm flex items-center justify-between group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-transparent hover:border-[#4F46E5]/10">
                            <a :href="f.url" class="flex items-center gap-6 w-full max-w-[70%]">
                                <div class="relative w-16 h-16 shrink-0 group-hover:scale-105 transition-transform duration-300">
                                    <template x-if="f.avatar">
                                        <img :src="f.avatar" class="w-full h-full rounded-[1.2rem] object-cover shadow-sm border border-slate-100">
                                    </template>
                                    <template x-if="!f.avatar">
                                        <div class="w-full h-full bg-[#F1F5F9] rounded-[1.2rem] flex items-center justify-center text-[#000000] font-black text-2xl shadow-inner">
                                            <span x-text="f.name.charAt(0)"></span>
                                        </div>
                                    </template>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-[#3B82F6] border-[3px] border-white rounded-full flex items-center justify-center text-white text-[9px] shadow-sm">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-lg md:text-xl text-[#000000] tracking-tight truncate" x-text="f.name"></h4>
                                    <p class="text-[12px] md:text-[13px] text-[#3B82F6] font-bold tracking-wider mt-1 truncate" x-text="'@' + f.username"></p>
                                </div>
                            </a>

                            <form :action="'/users/' + f.id + '/follow'" method="POST" class="shrink-0 ml-4">
                                @csrf
                                <button class="px-7 py-3 rounded-[1.2rem] text-[11px] font-bold uppercase tracking-widest transition-all"
                                        :class="f.is_following ? 'bg-[#F1F5F9] text-[#94A3B8] border border-[#E2E8F0]' : 'bg-[#000000] text-white shadow-md hover:scale-105 active:scale-95'">
                                    <span x-text="f.is_following ? 'Following' : 'Follow'"></span>
                                </button>
                            </form>
                        </div>
                    </template>
                    
                    <template x-if="filteredList.length === 0">
                        <div class="text-center py-16 bg-white rounded-[2.5rem] shadow-sm border border-slate-100">
                            <div class="w-20 h-20 bg-[#F1F5F9] rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 text-[#94A3B8] text-2xl shadow-inner">
                                <i class="fas fa-search"></i>
                            </div>
                            <p class="text-base font-bold text-[#94A3B8]">Foydalanuvchi topilmadi</p>
                        </div>
                    </template>
                </div>
                
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>