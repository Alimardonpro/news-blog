<x-app-layout>
    <div x-data="{ tab: 'all' }" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-5 pb-24 sm:py-8 lg:pb-10 bg-[#f9fafb] dark:bg-slate-950 min-h-screen transition-colors">
        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-12 lg:col-span-8 space-y-3">
                
                <div class="flex items-center justify-between px-1">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Bildirishnomalar</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Barcha bildirishnomalaringiz va javoblaringiz</p>
                    </div>
                </div>

                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-slate-800 shadow-sm rounded-2xl p-1.5 sticky top-4 z-30 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <button @click="tab = 'all'" 
                            :class="tab === 'all' ? 'bg-slate-900 dark:bg-white text-white dark:text-black shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'"
                            class="px-4 py-1.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-200">
                            Hammasi
                        </button>
                    </div>
                    <div class="flex items-center gap-3 pr-2">
                        @if($notifications->count() > 0)
                            <form action="{{ route('notifications.clear') }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] font-bold text-red-500 hover:text-red-600 dark:text-red-400 transition hover:underline">
                                    Tozalash
                                </button>
                            </form>
                        @endif
                        <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">
                            {{ $notifications->count() }} ta
                        </span>
                    </div>
                </div>

                <div class="space-y-2.5">

                    @forelse($notifications as $notification)
                        <div x-data="{ showReply: false, deleted: false }"
                             x-show="tab === 'all' && !deleted" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="group p-3 sm:p-4 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 
                             {{ $notification->read_at ? 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800' : 'bg-blue-50/70 dark:bg-blue-950/20 border border-blue-200/70 dark:border-blue-900/40 shadow-blue-500/5' }}">
                            
                            <div class="flex items-start gap-3">
                                
                                <div class="relative shrink-0">
                                    <img src="{{ isset($notification->data['avatar']) ? asset('storage/'.$notification->data['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($notification->data['name'] ?? 'U').'&background=0088cc&color=fff' }}" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full shadow-sm ring-2 ring-slate-100 dark:ring-slate-800 object-cover">
                                    
                                    <div class="absolute -bottom-0.5 -right-0.5 p-1 rounded-full border-2 border-white dark:border-slate-900 text-white 
                                        @if(($notification->data['type'] ?? '') === 'like') bg-red-500 
                                        @elseif(($notification->data['type'] ?? '') === 'comment') bg-emerald-500 
                                        @else bg-blue-500 @endif">
                                        
                                        @if(($notification->data['type'] ?? '') === 'like')
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                                        @elseif(($notification->data['type'] ?? '') === 'comment')
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z" clip-rule="evenodd"></path></svg>
                                        @else
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start gap-2 mb-0.5">
                                        <div class="min-w-0 flex items-center gap-1.5 flex-wrap">
                                            <a href="{{ route('profile.show', ['username' => $notification->data['username'] ?? '#']) }}" class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition truncate">
                                                {{ $notification->data['name'] ?? 'Foydalanuvchi' }}
                                            </a>
                                            <span class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold truncate">
                                                {{ '@' . ($notification->data['username'] ?? 'user') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-400 whitespace-nowrap bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md uppercase">{{ $notification->created_at->diffForHumans() }}</span>
                                            <button @click.prevent="deleted = true; fetch('/notifications/{{ $notification->id }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })" 
                                                    class="text-slate-400 hover:text-red-500 dark:hover:text-red-400 p-0.5 rounded hover:bg-slate-100 dark:hover:bg-slate-800 transition" 
                                                    title="O'chirish">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <p class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm leading-relaxed font-medium mt-0.5">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>

                                    @if(isset($notification->data['post_id']))
                                        <a href="{{ url('/posts/'.$notification->data['post_id']) }}" class="mt-2 inline-flex items-center gap-2.5 bg-slate-100/60 dark:bg-slate-800/60 px-2.5 py-1.5 rounded-xl border border-slate-200/50 dark:border-slate-700/50 w-fit group hover:bg-slate-200/70 dark:hover:bg-slate-800 transition">
                                            @if(isset($notification->data['post_image']))
                                                <img src="{{ asset('storage/' . $notification->data['post_image']) }}" class="w-8 h-8 rounded-lg object-cover shadow-sm group-hover:scale-105 transition duration-300">
                                            @else
                                                <div class="w-8 h-8 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[9px] text-slate-400 font-bold">Post</div>
                                            @endif
                                            <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 pr-1">Postni ko'rish →</span>
                                        </a>
                                    @endif

                                    @if(($notification->data['type'] ?? '') === 'comment')
                                        @if(isset($notification->data['comment_text']))
                                            <div class="mt-2 p-2.5 bg-emerald-50/70 dark:bg-emerald-950/20 border-l-2 border-l-emerald-500 border border-emerald-100 dark:border-emerald-900/40 rounded-xl text-slate-800 dark:text-emerald-200 text-xs sm:text-sm font-medium flex items-start justify-between gap-2 shadow-sm">
                                                <p class="italic break-words flex-1 min-w-0">"{{ $notification->data['comment_text'] }}"</p>
                                                
                                                <button @click="showReply = !showReply" class="shrink-0 bg-white dark:bg-slate-800 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold px-2.5 py-1 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                    Javob
                                                </button>
                                            </div>
                                        @endif

                                        <div x-show="showReply" x-transition class="mt-2.5 space-y-2">
                                            <form action="{{ route('comments.store', $notification->data['post_id'] ?? 0) }}" method="POST">
                                                @csrf
                                                <textarea name="body" rows="2" placeholder="Javobingizni yozing..." class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white rounded-xl p-2.5 text-xs sm:text-sm font-medium focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none resize-none" required></textarea>
                                                <div class="flex justify-end gap-1.5 mt-1.5">
                                                    <button type="button" @click="showReply = false" class="text-[11px] font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 px-3 py-1">Bekor qilish</button>
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold px-3.5 py-1 rounded-lg shadow-sm transition">Yuborish</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-8 rounded-2xl text-center shadow-sm">
                            <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Hozircha jimjitlik...</h3>
                            <p class="text-slate-500 dark:text-slate-400 font-medium text-xs">Sizda hali hech qanday bildirishnoma yo'q.</p>
                        </div>
                    @endforelse

                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-4 lg:sticky lg:top-6 h-fit">
                <!-- Foydali Maslahat -->
                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 dark:from-slate-900 dark:to-slate-800 rounded-2xl p-4 text-white shadow-sm border border-transparent dark:border-slate-800 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center gap-1.5 mb-1.5 text-amber-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14H8a4 4 0 01-.9-7.898 4 4 0 017.8 0A4 4 0 0112 14z"></path></svg>
                            <h4 class="text-xs font-black uppercase tracking-wider">Foydali Maslahat</h4>
                        </div>
                        <p class="text-indigo-50 dark:text-slate-300 text-xs leading-relaxed font-medium">"Boshqa foydalanuvchilar bilan ko'proq muloqot qiling, ularga obuna bo'ling va po'stlariga fikr bildiring."</p>
                    </div>
                </div>

                <!-- Ro'yxatdan o'tgan foydalanuvchilar (Minnagram a'zolari) -->
                @php
                    $usersList = $suggestedUsers ?? \App\Models\User::where('id', '!=', auth()->id())->latest()->take(8)->get();
                @endphp
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center justify-between pb-3 mb-2 border-b border-slate-100 dark:border-slate-800/80">
                        <div class="flex items-center gap-2">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Minnagram A'zolari</h3>
                        </div>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500">{{ $usersList->count() }} ta profil</span>
                    </div>

                    <div class="space-y-2">
                        @forelse($usersList as $registeredUser)
                            <div class="flex items-center justify-between gap-2.5 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition border border-transparent hover:border-slate-200/60 dark:hover:border-slate-700/60">
                                <a href="{{ route('profile.show', $registeredUser->username) }}" class="flex items-center gap-2.5 min-w-0 flex-1 group">
                                    <img src="{{ $registeredUser->avatar ? asset('storage/'.$registeredUser->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($registeredUser->name).'&background=0088cc&color=fff' }}" 
                                         class="w-9 h-9 rounded-full object-cover ring-2 ring-slate-100 dark:ring-slate-800 shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition truncate">
                                            {{ $registeredUser->name }}
                                        </h4>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">
                                            {{ '@' . $registeredUser->username }}
                                        </p>
                                    </div>
                                </a>

                                @if(Auth::id() !== $registeredUser->id)
                                    @php $isFollowing = Auth::user() ? Auth::user()->isFollowing($registeredUser) : false; @endphp
                                    <form action="{{ route('users.follow', $registeredUser->id) }}" method="POST" class="shrink-0">
                                        @csrf
                                        <button type="submit" 
                                            class="{{ $isFollowing 
                                                ? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200/80 dark:border-slate-700 hover:bg-red-50 hover:text-red-600 hover:border-red-200 dark:hover:bg-red-950/40 dark:hover:text-red-400' 
                                                : 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm shadow-blue-500/20' }} font-bold text-[11px] px-3 py-1.5 rounded-lg active:scale-95 transition-all group/btn">
                                            @if($isFollowing)
                                                <span class="group-hover/btn:hidden">Obunadasiz</span>
                                                <span class="hidden group-hover/btn:inline">Bekor qilish</span>
                                            @else
                                                <span>Obuna bo'lish</span>
                                            @endif
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="py-6 text-center text-slate-400 text-xs font-medium">
                                Yangi foydalanuvchilar topilmadi
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                        <a href="{{ route('network') }}" class="w-full flex items-center justify-center gap-1.5 py-2 text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition rounded-xl hover:bg-blue-50 dark:hover:bg-blue-950/30">
                            <span>Barcha a'zolarni ko'rish</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>