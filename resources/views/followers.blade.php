]<x-app-layout>
    <div x-data="{ activeTab: 'followers' }" class="min-h-screen bg-[#f8fafc]">
        
        <!-- HEADER QISMI -->
        <div class="sticky top-0 z-40 bg-white/70 backdrop-blur-md border-b border-gray-100 shadow-sm">
            <div class="flex items-center gap-4 px-6 py-4">
                <!-- Orqaga profilga qaytish -->
                <a href="{{ route('profile.show', Auth::user()->username) }}" class="p-2 rounded-full hover:bg-white shadow-sm transition-all border border-transparent hover:border-gray-100">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                
                <!-- Mening ma'lumotlarim -->
                <div class="flex items-center gap-3">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-10 h-10 rounded-full object-cover shadow-lg border border-gray-100">
                    @else
                        <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white font-bold shadow-lg">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-base font-bold text-gray-900 leading-none">{{ $user->name }}</h2>
                        <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">{{ '@' . $user->username }}</p>
                    </div>
                </div>
            </div>

            <!-- TABLAR -->
            <div class="px-6 py-2">
                <div class="bg-gray-200/50 p-1 rounded-2xl flex max-w-md">
                    <button @click="activeTab = 'followers'" 
                        class="flex-1 py-2 text-sm font-bold rounded-xl transition-all duration-300"
                        :class="activeTab === 'followers' ? 'bg-white text-black shadow-md scale-[1.02]' : 'text-gray-500 hover:text-gray-700'">
                        Followers <span class="ml-1 text-[10px] opacity-50">{{ $followers->count() }}</span>
                    </button>
                    <button @click="activeTab = 'following'" 
                        class="flex-1 py-2 text-sm font-bold rounded-xl transition-all duration-300"
                        :class="activeTab === 'following' ? 'bg-white text-black shadow-md scale-[1.02]' : 'text-gray-500 hover:text-gray-700'">
                        Following <span class="ml-1 text-[10px] opacity-50">{{ $following->count() }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- RO'YXATLAR QISMI -->
        <div class="p-6">
            <div class="max-w-3xl mx-auto"> 
                
                <!-- FOLLOWERS (Menga obuna bo'lganlar) -->
                <div x-show="activeTab === 'followers'" style="display: none;" x-transition:enter="transition duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2">
                    
                    @forelse($followers as $f)
                        <div class="bg-white p-5 rounded-[24px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] transition-all duration-500 mb-4 flex items-center justify-between group">
                            
                            <!-- Profilga o'tish linki -->
                            <a href="{{ route('profile.show', $f->username) }}" class="flex items-center gap-5 flex-1 min-w-0 cursor-pointer">
                                @if($f->avatar)
                                    <img src="{{ asset('storage/' . $f->avatar) }}" class="w-14 h-14 rounded-2xl object-cover shadow-inner group-hover:rotate-3 transition-transform border border-gray-50 shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-gray-100 to-gray-50 flex items-center justify-center text-lg font-bold text-gray-700 shadow-inner group-hover:rotate-3 transition-transform shrink-0">
                                        {{ substr($f->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <div class="min-w-0 pr-4">
                                    <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors truncate">{{ $f->name }}</h4>
                                    <p class="text-sm text-gray-400 font-medium truncate">{{ '@' . $f->username }}</p>
                                    <div class="flex items-center gap-1.5 mt-1.5">
                                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full shadow-[0_0_8px_rgba(74,222,128,0.5)]"></span>
                                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-tighter">Sizni kuzatmoqda</p>
                                    </div>
                                </div>
                            </a>

                            <!-- Follow Back / Unfollow tugmasi -->
                            <form action="{{ route('users.follow', $f) }}" method="POST" class="shrink-0">
                                @csrf
                                @php $iFollowThem = Auth::user()->isFollowing($f); @endphp
                                
                                <button type="submit" class="px-5 py-2.5 text-xs font-black rounded-xl transition-all group/btn {{ $iFollowThem ? 'border-2 border-gray-100 text-gray-500 hover:bg-red-50 hover:text-red-600 hover:border-red-100' : 'bg-black text-white hover:bg-gray-800 shadow-[0_10px_20px_rgba(0,0,0,0.15)] active:scale-95' }}">
                                    @if($iFollowThem)
                                        <span class="group-hover/btn:hidden">Following</span>
                                        <span class="hidden group-hover/btn:inline">Unfollow</span>
                                    @else
                                        Follow Back
                                    @endif
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-400 font-medium">Hali obunachilar yo'q.</div>
                    @endforelse

                </div>

                <!-- FOLLOWING (Men kuzatayotganlar) -->
                <div x-show="activeTab === 'following'" style="display: none;" x-transition:enter="transition duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2">
                    
                    @forelse($following as $f)
                        <div class="bg-white p-5 rounded-[24px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center justify-between mb-4 hover:border-blue-100 transition-colors group">
                            
                            <a href="{{ route('profile.show', $f->username) }}" class="flex items-center gap-5 flex-1 min-w-0">
                                @if($f->avatar)
                                    <img src="{{ asset('storage/' . $f->avatar) }}" class="w-14 h-14 rounded-2xl object-cover shadow-lg shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-2xl bg-gray-900 flex items-center justify-center text-white font-black shadow-lg shrink-0">
                                        {{ substr($f->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <div class="min-w-0 pr-4">
                                    <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors truncate">{{ $f->name }}</h4>
                                    <p class="text-sm text-gray-400 truncate">{{ '@' . $f->username }}</p>
                                </div>
                            </a>

                            <form action="{{ route('users.follow', $f) }}" method="POST" class="shrink-0">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 border-2 border-gray-100 text-gray-500 text-xs font-bold rounded-xl hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all group/btn">
                                    <span class="group-hover/btn:hidden">Following</span>
                                    <span class="hidden group-hover/btn:inline">Unfollow</span>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-400 font-medium">Hali hech kimni kuzatmayapsiz.</div>
                    @endforelse

                </div>

            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
    </style>
</x-app-layout>