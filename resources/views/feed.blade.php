<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-12 bg-[#f8fafc] min-h-screen">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">Siz kuzatayotganlar</h2>
            <a href="{{ route('dashboard') }}" class="text-xs font-bold text-indigo-600 hover:underline">Barcha postlar →</a>
        </div>
        
        <div class="space-y-10">
            @forelse($feedPosts as $post)
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden transition-all hover:shadow-md">
                    <!-- Post Tepasi -->
                    <div class="p-5 flex items-center justify-between border-b border-slate-50">
                        <a href="{{ route('profile.show', $post->user->username) }}" class="flex items-center gap-3 group">
                            <div class="w-11 h-11 rounded-2xl overflow-hidden bg-slate-100 ring-2 ring-slate-50 group-hover:ring-indigo-100 transition-all">
                                @if($post->user->avatar) 
                                    <img src="{{ asset('storage/' . $post->user->avatar) }}" class="w-full h-full object-cover"> 
                                @else
                                    <div class="w-full h-full bg-slate-900 flex items-center justify-center text-white text-sm font-black">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900 leading-none group-hover:text-indigo-600 transition-colors">{{ $post->user->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold mt-1">{{ '@' . $post->user->username }}</p>
                            </div>
                        </a>
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-tighter">{{ $post->created_at->diffForHumans() }}</span>
                    </div>

                    <!-- Post Rasmi -->
                    @if($post->image)
                        <div class="aspect-video bg-slate-50 overflow-hidden">
                            <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <!-- Post Matni va Statistika -->
                    <div class="p-7">
                        <p class="text-slate-700 font-medium leading-relaxed mb-6 text-sm">
                            {{ $post->body }}
                        </p>
                        
                        <div class="flex items-center gap-6 text-slate-400">
                            <!-- Like -->
                            <div class="flex items-center gap-1.5 hover:text-red-500 transition-colors cursor-pointer group">
                                <svg class="w-5 h-5 group-active:scale-125 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <span class="text-xs font-black">{{ $post->likes_count }}</span>
                            </div>
                            <!-- Comment -->
                            <div class="flex items-center gap-1.5 hover:text-indigo-500 transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span class="text-xs font-black">{{ $post->comments_count }}</span>
                            </div>
                            <!-- Views -->
                            <div class="ml-auto flex items-center gap-1.5 opacity-60">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span class="text-[10px] font-black">{{ $post->views_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-100">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <p class="text-slate-400 font-black text-sm uppercase tracking-widest">Hozircha hech kimni kuzatmayapsiz <br> yoki ular hali post yuklamagan</p>
                    <a href="{{ route('dashboard') }}" class="inline-block mt-6 px-8 py-3 bg-indigo-600 text-white text-xs font-black rounded-2xl shadow-lg shadow-indigo-100">Odamlarni topish</a>
                </div>
            @endforelse

            <div class="mt-10">
                {{ $feedPosts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>