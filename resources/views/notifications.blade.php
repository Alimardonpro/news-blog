<x-app-layout>
    <div x-data="{ tab: 'all' }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-[#f9fafb] min-h-screen">
        <div class="grid grid-cols-12 gap-8">

            <div class="col-span-12 lg:col-span-8 space-y-8">
                
                <div class="bg-white/60 backdrop-blur-xl border border-white shadow-[0_20px_50px_rgba(0,0,0,0.04)] rounded-[2.5rem] p-2.5 sticky top-8 z-50 flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <button @click="tab = 'all'" 
                            :class="tab === 'all' ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-500 hover:bg-white'"
                            class="px-8 py-3.5 rounded-full font-black text-[13px] uppercase tracking-wider transition-all duration-500">
                            Hammasi
                        </button>
                    </div>
                </div>

                <div class="space-y-6">

                    <!-- BAZADAN KELAYOTGAN XABARLAR UCHUN TSIKL -->
                    @forelse($notifications as $notification)
                        <div x-show="tab === 'all'" 
                             x-transition:enter="transition ease-out duration-500"
                             class="group p-8 rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-500 
                             {{ $notification->read_at ? 'bg-white border border-slate-200' : 'bg-blue-50/60 border border-blue-200 shadow-blue-500/5' }}">
                            <div class="flex items-start gap-6">
                                
                                <div class="relative shrink-0">
                                    <!-- Rasm -->
                                    <img src="{{ $notification->data['avatar'] ? asset('storage/'.$notification->data['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($notification->data['name']).'&background=0088cc&color=fff' }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-[2rem] shadow-xl ring-4 ring-slate-50 object-cover">
                                    
                                    <!-- Turi bo'yicha Ikonkalar (Layk: Qizil, Follow: Ko'k, Komment: Yashil) -->
                                    <div class="absolute -bottom-2 -right-2 p-1.5 rounded-full border-[4px] border-white text-white 
                                        @if($notification->data['type'] === 'like') bg-red-500 
                                        @elseif($notification->data['type'] === 'comment') bg-emerald-500 
                                        @else bg-blue-500 @endif">
                                        
                                        @if($notification->data['type'] === 'like')
                                            <!-- Yurakcha (Like) -->
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                                        @elseif($notification->data['type'] === 'comment')
                                            <!-- Chat / Komment -->
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg>
                                        @else
                                            <!-- Odam (Follow) -->
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <a href="{{ route('profile.show', $notification->data['username']) }}" class="text-lg sm:text-xl font-black text-slate-900 hover:text-blue-600 transition truncate">
                                            {{ $notification->data['name'] }}
                                        </a>
                                        <span class="text-[12px] font-black text-slate-400 ml-2 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <a href="{{ route('profile.show', $notification->data['username']) }}" class="text-blue-600 font-bold text-sm mb-3 block hover:underline">
                                        {{ '@' . $notification->data['username'] }}
                                    </a>
                                    
                                    <p class="text-slate-800 leading-relaxed font-bold text-[15px]">
                                        {{ $notification->data['message'] }}
                                    </p>

                                    @if($notification->data['type'] === 'comment' && isset($notification->data['comment_text']))
                                        <div class="mt-4 p-4 bg-emerald-50/80 border border-emerald-100 rounded-2xl italic text-slate-900 text-[15px] font-bold border-l-4 border-l-emerald-500 shadow-sm">
                                            "{{ $notification->data['comment_text'] }}"
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- AGAR XABAR BO'LMASA -->
                        <div class="bg-white border border-slate-200 p-10 rounded-[3rem] text-center shadow-sm">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 mb-1">Hozircha jimjitlik...</h3>
                            <p class="text-slate-600 font-medium text-sm">Sizda hali hech qanday bildirishnoma yo'q.</p>
                        </div>
                    @endforelse

                </div>
            </div>

            <!-- O'NG TOMON (Trendlar - Pro Maslahat) -->
            <div class="hidden lg:block col-span-4 space-y-8 sticky top-8 h-fit">
                <div class="bg-indigo-600 rounded-[3rem] p-8 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-lg font-black mb-2">Pro Maslahat:</h4>
                        <p class="text-indigo-50 text-sm leading-relaxed font-medium italic">"Boshqa foydalanuvchilar bilan ko'proq muloqot qiling, ularga obuna bo'ling va po'stlariga layk bosing."</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>