<x-app-layout>
    <div x-data="{ 
        openPostModal: {{ $errors->has('body') || $errors->has('image') ? 'true' : 'false' }},
        imagePreview: null,
        imageModal: null 
    }">

        <header class="flex h-16 items-center justify-between px-4 lg:px-8 py-4 border-b border-gray-100 sticky top-0 bg-white/95 backdrop-blur z-40 mb-5 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.1)]">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-medium text-gray-800">Posts </h2>
            </div>
            <button @click="openPostModal = true" class="bg-black hover:bg-gray-800 text-white text-sm font-medium px-6 py-2.5 rounded-full transition shadow-[0_10px_20px_-5px_rgba(0,0,0,0.4)] hover:shadow-[0_15px_25px_-5px_rgba(0,0,0,0.5)] flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Post
            </button>
        </header>

        <div class="grid grid-cols-12 gap-6 px-4 pb-20">
            
            <div class="col-span-12 lg:col-span-8">
                
                @if($posts->isEmpty())
                    <div class="text-center py-10 text-gray-500">Hali postlar yo'q. 🚀</div>
                @endif

                @foreach($posts as $post)
                    <div x-data="{ 
                            openComments: false, 
                            openEditPost: false,
                            viewsCount: {{ $post->views->count() }},
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
                                });
                            }
                         }" 
                         class="border border-gray-100 rounded-2xl p-6 mb-8 hover:bg-gray-50 transition duration-300 bg-white relative group shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)]">
                        
                        @if(Auth::id() === $post->user_id)
                            <div class="absolute top-4 right-4 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition z-10">
                                <button @click="openEditPost = true" class="text-gray-400 hover:text-blue-500 p-2 bg-white rounded-full shadow-[0_5px_15px_rgba(0,0,0,0.1)] border border-gray-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirmoqchimisiz?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 p-2 bg-white rounded-full shadow-[0_5px_15px_rgba(0,0,0,0.1)] border border-gray-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <a href="{{ route('profile.show', ['username' => $post->user->username]) }}">
                                    @if($post->user->avatar)
                                        <img src="{{ asset('storage/' . $post->user->avatar) }}" class="h-12 w-12 rounded-full object-cover shadow-[0_4px_10px_rgba(0,0,0,0.1)]">
                                    @else
                                        <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">{{ substr($post->user->name, 0, 1) }}</div>
                                    @endif
                                </a>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <a href="{{ route('profile.show', ['username' => $post->user->username]) }}" class="font-bold text-gray-900 hover:underline">{{ $post->user->name }}</a>
                                    <span class="text-gray-500 text-sm">{{ '@' . $post->user->username }}</span>
                                    <span class="text-gray-400 text-sm">· {{ $post->created_at->diffForHumans(null, true, true) }}</span>
                                </div>
                                
                                <p class="text-gray-800 text-[15px] leading-normal mb-3 whitespace-pre-wrap">{{ $post->body }}</p>
                                
                                @if($post->image)
                                    <div class="mt-4 rounded-2xl overflow-hidden border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.05)]">
                                        <img src="{{ asset('storage/' . $post->image) }}" 
                                             class="w-full h-auto object-cover max-h-[500px] cursor-zoom-in hover:opacity-95 transition"
                                             @click="imageModal = '{{ asset('storage/' . $post->image) }}'; countView()">
                                    </div>
                                @endif

                                <div class="flex items-center justify-between mt-5 pr-12 text-gray-500">
                                    <button @click="openComments = true; countView()" class="flex items-center gap-2 hover:text-blue-500 group transition">
                                        <div class="p-2 group-hover:bg-blue-50 rounded-full transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        </div>
                                        <span class="text-sm font-medium">{{ $post->comments->count() }}</span>
                                    </button>

                                    <form action="{{ route('posts.like', $post) }}" method="POST">
                                        @csrf
                                        @php $iLiked = $post->isLikedBy(auth()->user()); @endphp
                                        <button type="submit" @click="countView()" class="flex items-center gap-2 group transition {{ $iLiked ? 'text-red-500' : 'hover:text-red-500' }}">
                                            <div class="p-2 group-hover:bg-red-50 rounded-full transition">
                                                <svg class="w-5 h-5 transition transform group-active:scale-125" fill="{{ $iLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            </div>
                                            <span class="text-sm font-medium">{{ $post->likes->count() }}</span>
                                        </button>
                                    </form>

                                    <div class="flex items-center gap-2 cursor-default">
                                        <div class="p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </div>
                                        <span class="text-sm font-medium" x-text="viewsCount"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="openEditPost" style="display: none;" class="fixed inset-0 z-[80] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4" x-transition.opacity>
                            <div @click.away="openEditPost = false" class="bg-white w-full max-w-lg rounded-3xl shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] overflow-hidden relative border border-gray-100">
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-lg font-bold text-gray-900">Edit Post</h3>
                                    <button @click="openEditPost = false" class="text-gray-500 hover:bg-gray-100 p-2 rounded-full transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>
                                <div class="p-6">
                                    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                                        @csrf @method('PATCH')
                                        <div class="flex gap-4">
                                            <div class="flex-shrink-0 pt-1"><img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="h-10 w-10 rounded-full object-cover shadow-sm"></div>
                                            <div class="flex-1">
                                                <textarea name="body" rows="4" class="w-full border-none focus:ring-0 text-lg placeholder-gray-500 resize-none p-0" required>{{ $post->body }}</textarea>
                                                <div x-data="{ editImagePreview: '{{ $post->image ? asset('storage/'.$post->image) : null }}' }" class="mt-4">
                                                    <div x-show="editImagePreview" class="relative mb-2"><img :src="editImagePreview" class="w-full h-auto rounded-2xl object-cover max-h-64 border border-gray-100 shadow-lg"><button @click="editImagePreview = null" type="button" class="absolute top-2 right-2 bg-black/70 hover:bg-black text-white rounded-full p-1.5 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>
                                                    <div class="pt-3 border-t border-gray-100"><label class="cursor-pointer text-blue-500 hover:text-blue-600 flex items-center gap-2 w-fit"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><span class="text-sm font-bold">O'zgartirish</span><input type="file" name="image" class="hidden" @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { editImagePreview = e.target.result }; reader.readAsDataURL(file);"></label></div>
                                                </div>
                                                <div class="flex justify-end mt-6"><button type="submit" class="bg-black text-white text-sm font-bold px-8 py-2.5 rounded-full hover:bg-gray-800 transition shadow-lg">Update Post</button></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div x-show="openComments" style="display: none;" @click="openComments = false" x-transition.opacity class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm"></div>
                        <div x-show="openComments" style="display: none;" x-transition:enter="transform transition ease-in-out duration-400" x-transition:enter-start="translate-y-full md:translate-y-0 md:translate-x-full" x-transition:enter-end="translate-y-0 md:translate-x-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-y-0 md:translate-x-0" x-transition:leave-end="translate-y-full md:translate-y-0 md:translate-x-full" class="fixed z-[70] bg-white shadow-[-20px_0_50px_rgba(0,0,0,0.2)] overflow-y-auto bottom-0 left-0 right-0 w-full h-[80vh] rounded-t-[2.5rem] border-t border-gray-100 md:top-0 md:left-auto md:right-0 md:w-[480px] md:h-full md:rounded-none md:border-l">
                            <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur z-10">
                                <h2 class="text-2xl font-black text-gray-900">Izohlar</h2>
                                <button @click="openComments = false" class="p-2 hover:bg-gray-100 rounded-full transition"><svg class="w-7 h-7 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <div class="p-8">
                                <form action="{{ route('comments.store', $post) }}" method="POST" class="flex gap-4 mb-10">
                                    @csrf
                                    <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-100 shadow-sm">
                                    <div class="flex-1">
                                        <div class="relative">
                                            <input type="text" name="body" placeholder="Fikringizni qoldiring..." class="w-full bg-gray-50 border-gray-200 rounded-2xl px-5 py-4 pr-14 text-sm focus:ring-2 focus:ring-black focus:border-transparent transition shadow-inner" required>
                                            <button type="submit" class="absolute right-2.5 top-2.5 p-2 bg-black text-white rounded-xl hover:bg-gray-800 transition shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="space-y-8">
                                    @foreach($post->comments as $comment)
                                        <div class="flex gap-4 group/item">
                                            <img src="{{ $comment->user && $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?name='.($comment->user->name ?? 'User') }}" class="w-10 h-10 rounded-full object-cover shadow-sm">
                                            <div class="flex-1 bg-gray-50 p-4 rounded-2xl shadow-sm">
                                                <div class="flex items-baseline justify-between mb-1">
                                                    <span class="text-sm font-bold text-gray-900">{{ $comment->user->name ?? 'O\'chirilgan user' }}</span>
                                                    <span class="text-[11px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-sm text-gray-700 leading-relaxed">{{ $comment->body }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div> 
                @endforeach
            </div>

            <div class="hidden lg:block col-span-4 pl-4">
                <div class="sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 px-2">Sizga yoqishi mumkin</h3>
                    <div class="bg-white rounded-[2rem] p-6 border border-gray-50 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)]">
                        <div class="space-y-6">
                            <div class="cursor-pointer hover:bg-gray-50 p-3 rounded-2xl transition group">
                                <p class="text-xs text-gray-400 font-medium">Trending in Uzbekistan</p>
                                <p class="font-bold text-gray-900 group-hover:text-blue-600 transition">#Laravel_Mastery</p>
                                <p class="text-xs text-gray-500">4.2K posts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div x-show="imageModal" 
             style="display: none;" 
             class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-xl flex items-center justify-center p-4"
             x-transition.opacity>
            <button @click="imageModal = null" class="absolute top-8 right-8 text-white/50 hover:text-white p-3 rounded-full bg-white/5 hover:bg-white/10 transition shadow-2xl border border-white/10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img :src="imageModal" 
                 class="max-h-[92vh] max-w-full rounded-2xl shadow-[0_40px_100px_rgba(0,0,0,0.8)] border border-white/5 object-contain cursor-zoom-out"
                 @click="imageModal = null">
        </div>

        <div x-show="openPostModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md px-4" x-transition.opacity>
            <div @click.away="openPostModal = false" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] overflow-hidden relative border border-gray-100">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-50">
                    <button @click="openPostModal = false" class="text-gray-400 hover:bg-gray-100 p-2 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <button form="createPostForm" class="bg-black text-white text-sm font-black px-8 py-2.5 rounded-full hover:bg-gray-800 transition shadow-[0_10px_20px_rgba(0,0,0,0.3)]">Post</button>
                </div>
                <div class="p-8">
                    <form id="createPostForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex gap-4">
                            <div class="flex-shrink-0"><img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="h-12 w-12 rounded-full object-cover shadow-md"></div>
                            <div class="flex-1">
                                <textarea name="body" rows="5" class="w-full border-none focus:ring-0 text-xl placeholder-gray-400 resize-none p-0 leading-relaxed" placeholder="Nimalar bo'layapti?!" required></textarea>
                                <div x-show="imagePreview" class="relative mt-4"><img :src="imagePreview" class="w-full h-auto rounded-3xl object-cover max-h-72 shadow-2xl border border-gray-100"><button @click="imagePreview = null" type="button" class="absolute top-3 right-3 bg-black/80 text-white rounded-full p-2 shadow-2xl transition hover:bg-black"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>
                                <div class="mt-6 pt-4 border-t border-gray-50">
                                    <label class="cursor-pointer text-blue-500 hover:bg-blue-50 px-4 py-2 rounded-full transition inline-flex items-center gap-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-bold">Rasm yuklash</span>
                                        <input type="file" name="image" class="hidden" @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result }; reader.readAsDataURL(file);">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>