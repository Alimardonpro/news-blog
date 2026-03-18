<x-app-layout>
    <div x-data="{ 
        openPostModal: {{ $errors->has('body') || $errors->has('image') ? 'true' : 'false' }},
        imagePreview: null,
        imageModal: null 
    }">

        <!-- Header qismi -->
        <header class="flex h-16 items-center justify-between px-4 lg:px-8 py-4 border-b border-gray-100 sticky top-0 bg-white/95 backdrop-blur z-40 mb-5 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.1)]">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-medium text-gray-800">Postlar</h2>
            </div>
            <button @click="openPostModal = true" class="bg-black hover:bg-gray-800 text-white text-sm font-medium px-6 py-2.5 rounded-full transition shadow-[0_10px_20px_-5px_rgba(0,0,0,0.4)] hover:shadow-[0_15px_25px_-5px_rgba(0,0,0,0.5)] flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Yangi Post
            </button>
        </header>

        <div class="max-w-7xl mx-auto px-4 lg:px-8 pb-20">
            
            @if($posts->isEmpty())
                <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 flex items-center justify-center rounded-full mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Hali postlar yo'q</h3>
                    <p class="text-gray-500 mt-1">Birinchi bo'lib post joylang! 🚀</p>
                </div>
            @endif

            <!-- POSTLAR GRIDI: Aynan shu joy 2 qatorga (ustunga) to'g'rilandi -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                @foreach($posts as $post)
                    <div x-data="{ 
                            openComments: false, 
                            openEditPost: false,
                            viewsCount: {{ $post->views->count() ?? 0 }},
                            viewed: false,
                            countView() {
                                if (this.viewed) return;
                                fetch('/posts/{{ $post->id }}/view', { 
                                    method: 'POST', 
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
                                })
                                .then(res => res.json())
                                .then(data => { 
                                    this.viewsCount = data.views; 
                                    this.viewed = true; 
                                }).catch(e => console.log('View count error'));
                            }
                         }" 
                         class="border border-gray-100 rounded-[2rem] p-6 transition duration-300 bg-white relative group shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] flex flex-col h-full">
                        
                        <!-- 3 Nuqta / O'chirish va Tahrirlash -->
                        @if(Auth::id() === $post->user_id)
                            <div class="absolute top-4 right-4 flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                <button @click="openEditPost = true" class="text-gray-400 hover:text-blue-500 p-2 bg-white/90 backdrop-blur rounded-full shadow-sm hover:bg-gray-50 transition border border-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirmoqchimisiz?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 p-2 bg-white/90 backdrop-blur rounded-full shadow-sm hover:bg-gray-50 transition border border-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- Avtor ma'lumotlari -->
                        <div class="flex items-center gap-3 mb-5">
                            <a href="{{ route('profile.show', ['username' => $post->user->username]) }}" class="shrink-0">
                                @if($post->user->avatar)
                                    <img src="{{ asset('storage/' . $post->user->avatar) }}" class="h-12 w-12 rounded-[1rem] object-cover shadow-sm border border-gray-50">
                                @else
                                    <div class="h-12 w-12 bg-gray-900 rounded-[1rem] flex items-center justify-center text-white font-bold text-sm shadow-sm">{{ substr($post->user->name, 0, 1) }}</div>
                                @endif
                            </a>
                            <div class="min-w-0">
                                <a href="{{ route('profile.show', ['username' => $post->user->username]) }}" class="font-bold text-gray-900 hover:text-blue-600 transition block truncate text-base">{{ $post->user->name }}</a>
                                <div class="flex items-center gap-1.5 text-xs text-gray-400 font-medium">
                                    <span class="truncate">{{ '@' . $post->user->username }}</span>
                                    <span>·</span>
                                    <span>{{ $post->created_at->diffForHumans(null, true, true) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Post matni -->
                        <p class="text-gray-700 text-[15px] leading-relaxed mb-5 flex-grow whitespace-pre-wrap">{{ $post->body }}</p>
                        
                        <!-- Post Rasmi -->
                        @if($post->image)
                            <div class="mt-auto mb-5 rounded-2xl overflow-hidden border border-gray-100 shadow-sm relative group/img bg-gray-50">
                                <!-- O'lchamini max-h-[400px] qilib cheklaymiz, 2 qatorlik gridda chiroyli turadi -->
                                <img src="{{ asset('storage/' . $post->image) }}" 
                                     class="w-full max-h-[450px] object-cover cursor-zoom-in group-hover/img:scale-[1.02] transition duration-500"
                                     @click="imageModal = '{{ asset('storage/' . $post->image) }}'; countView()">
                                
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 transition duration-300 pointer-events-none flex items-center justify-center">
                                    <div class="bg-white/20 backdrop-blur p-3 rounded-full text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-auto"></div>
                        @endif

                        <!-- Post tugmalari (Like, Comment, Views) -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50 text-gray-400">
                            <div class="flex items-center gap-6">
                                <!-- Like -->
                                <form action="{{ route('posts.like', $post) }}" method="POST">
                                    @csrf
                                    @php $iLiked = $post->isLikedBy(auth()->user()); @endphp
                                    <button type="submit" @click="countView()" class="flex items-center gap-2 group transition {{ $iLiked ? 'text-red-500' : 'hover:text-red-500' }}">
                                        <div class="p-2 group-hover:bg-red-50 rounded-full transition">
                                            <svg class="w-5 h-5 transition transform group-active:scale-125" fill="{{ $iLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold">{{ $post->likes->count() }}</span>
                                    </button>
                                </form>

                                <!-- Comment -->
                                <button @click="openComments = true; countView()" class="flex items-center gap-2 hover:text-blue-500 group transition">
                                    <div class="p-2 group-hover:bg-blue-50 rounded-full transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold">{{ $post->comments->count() }}</span>
                                </button>
                            </div>

                            <!-- Views -->
                            <div class="flex items-center gap-2 cursor-default group" title="Ko'rishlar soni">
                                <div class="p-2 rounded-full group-hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </div>
                                <span class="text-sm font-bold" x-text="viewsCount"></span>
                            </div>
                        </div>

                        <!-- MODALS UCHUN KODLAR (Edit Post) -->
                        <div x-show="openEditPost" style="display: none;" class="fixed inset-0 z-[80] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4" x-transition.opacity>
                            <div @click.away="openEditPost = false" class="bg-white w-full max-w-lg rounded-3xl shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] overflow-hidden relative border border-gray-100">
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-lg font-bold text-gray-900">Postni tahrirlash</h3>
                                    <button @click="openEditPost = false" class="text-gray-500 hover:bg-gray-100 p-2 rounded-full transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>
                                <div class="p-6">
                                    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                                        @csrf @method('PATCH')
                                        <div class="flex gap-4">
                                            <div class="flex-shrink-0 pt-1"><img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="h-10 w-10 rounded-full object-cover shadow-sm"></div>
                                            <div class="flex-1">
                                                <textarea name="body" rows="4" class="w-full border-none focus:ring-0 text-base placeholder-gray-500 resize-none p-0 bg-transparent" required>{{ $post->body }}</textarea>
                                                <div x-data="{ editImagePreview: '{{ $post->image ? asset('storage/'.$post->image) : null }}' }" class="mt-4">
                                                    <div x-show="editImagePreview" class="relative mb-2"><img :src="editImagePreview" class="w-full h-auto rounded-2xl object-cover max-h-64 border border-gray-100 shadow-sm"><button @click="editImagePreview = null" type="button" class="absolute top-2 right-2 bg-black/70 hover:bg-black text-white rounded-full p-1.5 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>
                                                    <div class="pt-3 border-t border-gray-100">
                                                        <label class="cursor-pointer text-blue-500 hover:text-blue-600 flex items-center gap-2 w-fit px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                            <span class="text-sm font-bold">Rasm tanlash</span>
                                                            <input type="file" name="image" class="hidden" @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { editImagePreview = e.target.result }; reader.readAsDataURL(file);">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end mt-4"><button type="submit" class="bg-black text-white text-sm font-bold px-6 py-2 rounded-full hover:bg-gray-800 transition">Saqlash</button></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- MODALS UCHUN KODLAR (Comments) -->
                        <div x-show="openComments" style="display: none;" @click="openComments = false" x-transition.opacity class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm"></div>
                        <div x-show="openComments" style="display: none;" 
                             x-transition:enter="transform transition ease-in-out duration-300" 
                             x-transition:enter-start="translate-y-full md:translate-y-0 md:translate-x-full" 
                             x-transition:enter-end="translate-y-0 md:translate-x-0" 
                             x-transition:leave="transform transition ease-in-out duration-300" 
                             x-transition:leave-start="translate-y-0 md:translate-x-0" 
                             x-transition:leave-end="translate-y-full md:translate-y-0 md:translate-x-full" 
                             class="fixed z-[70] bg-white shadow-[-20px_0_50px_rgba(0,0,0,0.2)] overflow-hidden bottom-0 left-0 right-0 w-full h-[85vh] rounded-t-[2.5rem] md:top-0 md:left-auto md:right-0 md:w-[450px] md:h-full md:rounded-none flex flex-col">
                            
                            <div class="flex flex-col h-full relative">
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                                    <h2 class="text-lg font-black text-gray-900">Izohlar ({{ $post->comments->count() }})</h2>
                                    <button @click="openComments = false" class="p-2 bg-gray-50 hover:bg-gray-100 rounded-full transition"><svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>
                                
                                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                                    @forelse($post->comments as $comment)
                                        <div class="flex gap-3">
                                            <img src="{{ $comment->user && $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?name='.($comment->user->name ?? 'User') }}" class="w-8 h-8 rounded-full object-cover shadow-sm shrink-0">
                                            <div class="flex-1">
                                                <div class="bg-gray-50 px-4 py-3 rounded-2xl rounded-tl-sm">
                                                    <div class="flex justify-between items-baseline mb-0.5">
                                                        <span class="text-sm font-bold text-gray-900">{{ $comment->user->name ?? 'O\'chirilgan user' }}</span>
                                                    </div>
                                                    <p class="text-[13.5px] text-gray-700">{{ $comment->body }}</p>
                                                </div>
                                                <div class="flex items-center gap-4 mt-1.5 ml-2">
                                                    <span class="text-[11px] font-medium text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                    @if(Auth::id() === $comment->user_id || Auth::id() === $post->user_id)
                                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline" onsubmit="return confirm('O\'chirasizmi?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-[11px] font-bold text-red-400 hover:text-red-600 transition">O'chirish</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-10 text-gray-400 text-sm font-medium">Hozircha izohlar yo'q. Birinchi bo'lib yozing!</div>
                                    @endforelse
                                </div>
                                
                                <div class="p-4 border-t border-gray-100 bg-white shrink-0">
                                    <form action="{{ route('comments.store', $post) }}" method="POST" class="flex gap-3 items-end">
                                        @csrf
                                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-100">
                                        <div class="flex-1 relative">
                                            <textarea name="body" rows="1" placeholder="Izoh qoldiring..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 pr-12 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none resize-none max-h-24 overflow-y-auto custom-scrollbar" required oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                                            <button type="submit" class="absolute right-2 bottom-2 p-1.5 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition shadow-sm"><svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div> 
                @endforeach
            </div>

        </div>

        <!-- IMAGE FULLSCREEN MODAL -->
        <div x-show="imageModal" 
             style="display: none;" 
             class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md flex items-center justify-center p-4"
             x-transition.opacity>
            <button @click="imageModal = null" class="absolute top-6 right-6 text-white/60 hover:text-white p-2 rounded-full bg-white/10 hover:bg-white/20 transition backdrop-blur">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img :src="imageModal" class="max-h-[90vh] max-w-full rounded-xl shadow-2xl object-contain cursor-zoom-out" @click="imageModal = null">
        </div>

        <!-- CREATE POST MODAL -->
        <div x-show="openPostModal" style="display: none;" class="fixed inset-0 z-[90] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4" x-transition.opacity>
            <div @click.away="openPostModal = false" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden relative border border-gray-100">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                    <button @click="openPostModal = false" class="text-gray-400 hover:bg-gray-100 hover:text-gray-700 p-2 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <h2 class="text-lg font-bold text-gray-900">Yangi post yaratish</h2>
                    <button form="createPostForm" class="bg-blue-600 text-white text-sm font-bold px-6 py-2 rounded-full hover:bg-blue-700 transition shadow-md shadow-blue-500/20">Ulashish</button>
                </div>
                <div class="p-6">
                    <form id="createPostForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 pt-1"><img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="h-10 w-10 rounded-full object-cover shadow-sm border border-gray-100"></div>
                            <div class="flex-1">
                                <textarea name="body" rows="4" class="w-full border-none focus:ring-0 text-[17px] placeholder-gray-400 resize-none p-0 leading-relaxed bg-transparent" placeholder="Nima gaplar? 🚀" required></textarea>
                                
                                <div x-show="imagePreview" class="relative mt-4" style="display:none;">
                                    <img :src="imagePreview" class="w-full h-auto rounded-[1.5rem] object-cover max-h-64 shadow-md border border-gray-100">
                                    <button @click="imagePreview = null; document.getElementById('imageInput').value = ''" type="button" class="absolute top-3 right-3 bg-black/70 text-white rounded-full p-1.5 backdrop-blur transition hover:bg-black"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <label class="cursor-pointer text-blue-500 hover:bg-blue-50 px-3 py-2 rounded-xl transition inline-flex items-center gap-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-bold">Rasm / Media</span>
                                        <input type="file" id="imageInput" name="image" class="hidden" @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result }; reader.readAsDataURL(file); }">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- STYLE -->
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        textarea { overflow: hidden; }
    </style>
</x-app-layout>