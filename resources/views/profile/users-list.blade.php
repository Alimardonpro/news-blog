<x-app-layout>
    <div class="min-h-screen bg-[#FFFFFF] dark:bg-[#000000]">
        
        <!-- Header: Solid & Sharp (Hiralik yo'qolgan) -->
        <div class="sticky top-0 z-50 bg-white dark:bg-black border-b-2 border-slate-900/5 dark:border-white/10">
            <div class="w-full px-8 h-20 flex items-center justify-between">
                <!-- Back Button: Darker & Bolder -->
                <a href="{{ route('profile.show', $user->username) }}" class="group p-3 bg-slate-100 dark:bg-white/10 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-black transition-all duration-300">
                    <svg class="w-6 h-6 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                
                <div class="text-center">
                    <h2 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-[0.4em] mb-1 opacity-100">Ro'yxat</h2>
                    <p class="text-xl font-black text-indigo-600 dark:text-indigo-400 tracking-tighter">@ {{ $user->username }}</p>
                </div>

                <div class="w-12"></div>
            </div>

            <!-- TABS: Ultra Sharp Indicator -->
            <div class="w-full flex px-0">
                <a href="{{ route('profile.users', [$user->username, 'followers']) }}" 
                   class="flex-1 relative py-5 text-center transition-all group">
                    <span class="text-[13px] font-black uppercase tracking-widest {{ Request::is('*/followers') ? 'text-slate-900 dark:text-white' : 'text-slate-400' }}">
                        Obunachilar
                    </span>
                    @if(Request::is('*/followers'))
                        <!-- Aktiv chiziq: To'q qora va aniq -->
                        <div class="absolute bottom-0 left-0 w-full h-[4px] bg-slate-900 dark:bg-white transition-all"></div>
                    @else
                        <div class="absolute bottom-0 left-0 w-0 h-[4px] bg-indigo-500 group-hover:w-full transition-all duration-300"></div>
                    @endif
                </a>

                <a href="{{ route('profile.users', [$user->username, 'following']) }}" 
                   class="flex-1 relative py-5 text-center transition-all group">
                    <span class="text-[13px] font-black uppercase tracking-widest {{ Request::is('*/following') ? 'text-slate-900 dark:text-white' : 'text-slate-400' }}">
                        Obunalar
                    </span>
                    @if(Request::is('*/following'))
                        <div class="absolute bottom-0 left-0 w-full h-[4px] bg-slate-900 dark:bg-white transition-all"></div>
                    @else
                        <div class="absolute bottom-0 left-0 w-0 h-[4px] bg-indigo-500 group-hover:w-full transition-all duration-300"></div>
                    @endif
                </a>
            </div>
        </div>

        <!-- Main Body: Ultra Wide & Sharp -->
        <div class="w-full px-8 py-10">
            
            <!-- Search Bar: High Contrast -->
            <div class="relative mb-12 w-full max-w-[1400px] mx-auto">
                <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                    <svg class="w-6 h-6 text-slate-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" placeholder="Ism bo'yicha qidirish..." 
                    class="w-full bg-[#F8FAFC] dark:bg-white/5 border-2 border-slate-200 dark:border-white/10 rounded-2xl py-6 pl-16 pr-8 text-lg font-black text-slate-900 dark:text-white focus:border-indigo-600 focus:ring-0 transition-all placeholder:text-slate-400">
            </div>

            <!-- Users List: Vivid & Bold -->
            <div class="w-full max-w-[1400px] mx-auto bg-white dark:bg-black rounded-3xl border-2 border-slate-100 dark:border-white/10 shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="divide-y-2 divide-slate-50 dark:divide-white/5">
                    @forelse($users as $u)
                        <div class="p-8 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-white/[0.05] transition-all group">
                            <a href="{{ route('profile.show', $u->username) }}" class="flex items-center gap-8 flex-1">
                                <!-- Sharp Square Avatar -->
                                <div class="relative w-20 h-20 rounded-2xl bg-slate-900 dark:bg-white p-0.5 shadow-lg overflow-hidden">
                                    @if($u->avatar)
                                        <img src="{{ asset('storage/' . $u->avatar) }}" class="w-full h-full rounded-[0.9rem] object-cover border-2 border-white dark:border-black">
                                    @else
                                        <div class="w-full h-full bg-slate-900 text-white flex items-center justify-center text-2xl font-black">
                                            {{ substr($u->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-xl font-black text-slate-900 dark:text-white tracking-tight group-hover:text-indigo-600 transition-colors">{{ $u->username }}</p>
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                    </div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-bold tracking-normal">{{ $u->name }}</p>
                                </div>
                            </a>

                            <!-- Follow Button: High Contrast -->
                            @if(Auth::id() !== $u->id)
                                <form action="{{ route('users.follow', $u->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-10 py-4 rounded-xl text-xs font-black uppercase tracking-[0.2em] transition-all {{ Auth::user()->isFollowing($u) ? 'bg-slate-100 text-slate-900 border-2 border-slate-900/10' : 'bg-slate-900 text-white dark:bg-white dark:text-black shadow-[0_10px_30px_rgba(0,0,0,0.2)] hover:scale-105 active:scale-95' }}">
                                        {{ Auth::user()->isFollowing($u) ? 'Kuzatilmoqda' : 'Kuzatish' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="py-40 text-center">
                            <span class="text-slate-900 dark:text-white font-black text-sm uppercase tracking-[0.5em] opacity-20">Ro'yxat bo'sh</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-12 w-full max-w-[1400px] mx-auto font-black">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>