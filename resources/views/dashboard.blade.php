<x-app-layout>
    <div x-data="{ 
        openPostModal: {{ $errors->has('body') || $errors->has('image') ? 'true' : 'false' }},
        imagePreview: null
    }">

        <header class="flex h-16 items-center justify-between px-4 lg:px-8 py-4 border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur z-40 mb-5">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-medium text-gray-800">Posts</h2>
            </div>
            <button @click="openPostModal = true" class="bg-black hover:bg-gray-800 text-white text-sm font-medium px-5 py-2.5 rounded-full transition shadow-sm flex items-center gap-2">
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
                    <div x-data="{ openComments: false }" class="border border-gray-200 rounded-xl p-5 mb-6 hover:bg-gray-50 transition duration-200 bg-white relative group">
                        
                        @if(Auth::id() === $post->user_id)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition" onsubmit="return confirm('O\'chirmoqchimisiz?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        @endif

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <a href="{{ route('profile.show', ['username' => $post->user->username]) }}">
                                    @if($post->user->avatar)
                                        <img src="{{ asset('storage/' . $post->user->avatar) }}" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">{{ substr($post->user->name, 0, 1) }}</div>
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
                                    <div class="mt-3 rounded-2xl overflow-hidden border border-gray-200">
                                        <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-auto object-cover max-h-[500px]">
                                    </div>
                                @endif

                                <div class="flex items-center justify-between mt-4 pr-12 text-gray-500">
                                    
                                    <button @click="openComments = true" class="flex items-center gap-2 hover:text-blue-500 group transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span class="text-sm">{{ $post->comments->count() }}</span>
                                    </button>

                                    <button class="flex items-center gap-2 hover:text-green-500 group transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg><span class="text-sm">0</span></button>

                                    <form action="{{ route('posts.like', $post) }}" method="POST">
                                        @csrf
                                        @php $iLiked = $post->isLikedBy(auth()->user()); @endphp
                                        <button type="submit" class="flex items-center gap-2 group transition {{ $iLiked ? 'text-red-500' : 'hover:text-red-500' }}">
                                            <svg class="w-5 h-5 transition transform group-active:scale-125" fill="{{ $iLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            <span class="text-sm">{{ $post->likes->count() }}</span>
                                        </button>
                                    </form>

                                    <button class="flex items-center gap-2 hover:text-blue-500 group transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg><span class="text-sm">0</span></button>
                                </div>
                            </div>
                        </div>

                        <div x-show="openComments" 
                             style="display: none;"
                             @click="openComments = false"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 z-[60] bg-black/40 backdrop-blur-sm">
                        </div>

                        <div x-show="openComments" 
                             style="display: none;"
                             
                             /* ANIMATSIYA LOGIKASI */
                             x-transition:enter="transform transition ease-in-out duration-300"
                             x-transition:enter-start="translate-y-full md:translate-y-0 md:translate-x-full"
                             x-transition:enter-end="translate-y-0 md:translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-300"
                             x-transition:leave-start="translate-y-0 md:translate-x-0"
                             x-transition:leave-end="translate-y-full md:translate-y-0 md:translate-x-full"
                             
                             /* JOYLASHUV (CSS) */
                             class="fixed z-[70] bg-white shadow-2xl overflow-y-auto 
                                    bottom-0 left-0 right-0 w-full h-[75vh] rounded-t-2xl border-t border-gray-200
                                    md:top-0 md:left-auto md:right-0 md:w-[450px] md:h-full md:rounded-none md:border-l">
                            
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                                <h2 class="text-xl font-bold text-gray-900">Izohlar ({{ $post->comments->count() }})</h2>
                                <button @click="openComments = false" class="p-2 hover:bg-gray-100 rounded-full transition">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="p-6">
                                <form action="{{ route('comments.store', $post) }}" method="POST" class="flex gap-3 mb-8">
                                    @csrf
                                    <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    <div class="flex-1">
                                        <div class="relative">
                                            <input type="text" name="body" placeholder="Fikringizni yozing..." class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 pr-12 text-sm focus:ring-2 focus:ring-black focus:border-transparent transition" required>
                                            <button type="submit" class="absolute right-2 top-2 p-1.5 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="space-y-6">
                                    @foreach($post->comments as $comment)
                                        <div class="flex gap-3 group/item">
                                            <a href="{{ route('profile.show', ['username' => $comment->user->username]) }}">
                                                <img src="{{ $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?name='.$comment->user->name }}" class="w-9 h-9 rounded-full object-cover">
                                            </a>
                                            <div class="flex-1">
                                                <div class="flex items-baseline justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('profile.show', ['username' => $comment->user->username]) }}" class="text-sm font-bold text-gray-900 hover:underline">{{ $comment->user->name }}</a>
                                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    @if(Auth::id() === $comment->user_id)
                                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="opacity-0 group-hover/item:opacity-100 transition">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-gray-400 hover:text-red-500">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-800 mt-1 leading-relaxed">{{ $comment->body }}</p>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($post->comments->isEmpty())
                                        <div class="text-center text-gray-400 py-10">
                                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                            <p>Hozircha izohlar yo'q.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div> @endforeach
            </div>

            <div class="hidden lg:block col-span-4 pl-4">
                <div class="sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Mavzular</h3>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <div class="space-y-4">
                            <div class="cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                <p class="text-xs text-gray-500">Trending in Uzbekistan</p>
                                <p class="font-bold text-gray-900">#Laravel</p>
                                <p class="text-xs text-gray-500">2.5K posts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div x-show="openPostModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4" x-transition.opacity>
            <div @click.away="openPostModal = false" class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden relative">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                    <button @click="openPostModal = false" class="text-gray-500 hover:bg-gray-100 p-2 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <button form="createPostForm" class="bg-black text-white text-sm font-bold px-5 py-1.5 rounded-full hover:bg-gray-800 transition">Post</button>
                </div>
                <div class="p-4">
                    <form id="createPostForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 pt-1">
                                <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="h-10 w-10 rounded-full object-cover">
                            </div>
                            <div class="flex-1">
                                <textarea name="body" rows="4" class="w-full border-none focus:ring-0 text-lg placeholder-gray-500 resize-none p-0" placeholder="Nimalar bo'layapti?!" required></textarea>
                                @error('body') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                                <div x-show="imagePreview" class="relative mt-2 mb-2">
                                    <img :src="imagePreview" class="w-full h-auto rounded-xl object-cover max-h-64 border border-gray-200">
                                    <button @click="imagePreview = null; document.getElementById('fileInput').value = ''" type="button" class="absolute top-2 right-2 bg-black/70 hover:bg-black text-white rounded-full p-1 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-100">
                                    <label class="cursor-pointer text-blue-500 hover:text-blue-600 flex items-center gap-2 w-fit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-medium">Rasm qo'shish</span>
                                        <input type="file" name="image" id="fileInput" class="hidden" @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result }; reader.readAsDataURL(file);">
                                    </label>
                                    @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>