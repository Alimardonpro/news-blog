<x-app-layout>
    <div class="min-h-screen bg-[#f9fafb]">
        
        <div class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                
                <div class="flex items-center">
                    <a href="{{ route('profile.show', $user->username) }}" class="group p-2.5 bg-slate-50 border border-slate-200 rounded-2xl hover:bg-slate-900 hover:text-white transition-all duration-300 shadow-sm">
                        <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                
                <div class="text-center flex-1">
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Ro'yxat</h2>
                    <p class="text-lg font-black text-slate-900 tracking-tight">{{ $user->name }}</p>
                </div>

                <div class="w-10"></div> </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex">
                <a href="{{ route('profile.users', [$user->username, 'followers']) }}" 
                   class="flex-1 relative py-4 text-center transition-all group">
                    <span class="text-[12px] font-black uppercase tracking-widest {{ Request::is('*/followers') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-600' }}">
                        Obunachilar
                    </span>
                    @if(Request::is('*/followers'))
                        <div class="absolute bottom-0 left-0 w-full h-[3px] bg-slate-900 rounded-t-full transition-all"></div>
                    @else
                        <div class="absolute bottom-0 left-0 w-0 h-[3px] bg-indigo-500 group-hover:w-full transition-all duration-300 rounded-t-full"></div>
                    @endif
                </a>

                <a href="{{ route('profile.users', [$user->username, 'following']) }}" 
                   class="flex-1 relative py-4 text-center transition-all group">
                    <span class="text-[12px] font-black uppercase tracking-widest {{ Request::is('*/following') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-600' }}">
                        Obunalar
                    </span>
                    @if(Request::is('*/following'))
                        <div class="absolute bottom-0 left-0 w-full h-[3px] bg-slate-900 rounded-t-full transition-all"></div>
                    @else
                        <div class="absolute bottom-0 left-0 w-0 h-[3px] bg-indigo-500 group-hover:w-full transition-all duration-300 rounded-t-full"></div>
                    @endif
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            <div class="relative mb-8 w-full mx-auto">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" placeholder="Ism bo'yicha qidirish..." 
                    class="w-full bg-white border border-slate-200 rounded-2xl py-4 pl-14 pr-6 text-sm font-bold text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all placeholder:text-slate-400 shadow-sm outline-none">
            </div>

            <div class="w-full mx-auto bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="divide-y divide-slate-50">
                    @forelse($users as $u)
                        <div class="p-6 sm:px-8 flex items-center justify-between hover:bg-slate-50 transition-all group cursor-default">
                            <a href="{{ route('profile.show', $u->username) }}" class="flex items-center gap-6 flex-1 hover:no-underline">
                                
                                <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-[1.5rem] bg-white p-1 shadow-md overflow-hidden transform group-hover:rotate-3 transition-transform shrink-0">
                                    @if($u->avatar)
                                        <img src="{{ asset('storage/' . $u->avatar) }}" class="w-full h-full rounded-xl object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-900 text-white flex items-center justify-center text-xl font-black rounded-xl">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <p class="text-lg sm:text-xl font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $u->name }}</p>
                                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                    </div>
                                    <p class="text-sm sm:text-base text-slate-500 font-bold">@ {{ $u->username }}</p>
                                </div>
                            </a>

                            @if(Auth::id() !== $u->id)
                                <form action="{{ route('users.follow', $u->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="px-6 py-3 rounded-xl text-xs sm:text-sm font-black uppercase tracking-wider transition-all {{ Auth::user()->isFollowing($u) ? 'bg-slate-100 text-slate-900 border border-slate-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-slate-900 text-white hover:bg-slate-800 shadow-md active:scale-95' }}">
                                        {{ Auth::user()->isFollowing($u) ? 'Kuzatilmoqda' : 'Kuzatish' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="py-24 text-center flex flex-col items-center justify-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-slate-900 font-black text-lg">Hozircha hech kim yo'q</h3>
                            <p class="text-slate-500 font-medium mt-1">Bu ro'yxat bo'sh.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($users->hasPages())
                <div class="mt-8 w-full font-bold">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>