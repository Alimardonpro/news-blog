<x-app-layout>
    <div x-data="{ 
        openPostModal: false,
        openEditModal: false,
        openNotifications: false,
        imagePreviews: [], 
        editImagePreviews: [], 
        imageModal: null,
        editingPost: { id: null, body: '', images: [] } 
    }" class="h-screen flex flex-col bg-[#F8FAFC] font-sans overflow-hidden"> 

        <header class="flex h-[70px] shrink-0 items-center justify-between px-6 lg:px-10 border-b border-gray-200 bg-white/95 backdrop-blur-md z-40 relative shadow-sm">
            <h2 class="text-xl font-black text-gray-900 tracking-tight">Postlar Lentasi</h2>
            
            <div class="flex items-center gap-3">
                <button @click="openNotifications = true" class="relative p-2.5 text-gray-700 hover:bg-gray-100 rounded-full transition group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-2 right-2.5 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                </button>

                <button @click="openPostModal = true" class="bg-black hover:bg-gray-800 text-white text-sm font-bold px-6 py-2.5 rounded-full transition shadow-lg flex items-center gap-2 hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Yangi Post  
                </button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto snap-y snap-mandatory no-scrollbar relative w-full">
            
            @if($posts->isEmpty())
                <div class="flex items-center justify-center h-full">
                    <div class="text-center bg-white p-12 rounded-[3rem] shadow-sm max-w-md w-full mx-4 border border-gray-100">
                        <div class="w-20 h-20 bg-gray-50 flex items-center justify-center rounded-full mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Hali postlar yo'q</h3>
                        <p class="text-gray-500 mt-2">Birinchi bo'lib post joylang!</p>
                    </div>
                </div>
            @endif

            @foreach($posts as $feedPost)
                @php
                    $userOtherPosts = $feedPost->user->posts->where('id', '!=', $feedPost->id)->take(5);
                    $horizontalPosts = collect([$feedPost])->merge($userOtherPosts);
                @endphp

                <div x-data="{ 
                        activeSlide: 0,
                        updateActiveSlide() {
                            let container = this.$refs.slider;
                            let slideWidth = container.children[0].offsetWidth;
                            let gap = parseFloat(window.getComputedStyle(container).gap) || 0;
                            this.activeSlide = Math.round(container.scrollLeft / (slideWidth + gap));
                        }
                     }" 
                     x-init="
                        let observer = new IntersectionObserver((entries) => {
                            if(!entries[0].isIntersecting) {
                                $refs.slider.scrollLeft = 0;
                                activeSlide = 0;
                            }
                        }, { threshold: 0.1 });
                        observer.observe($el);
                     "
                     class="w-full h-[calc(100vh-70px)] snap-start flex items-center justify-center relative py-8">
                    
                    <div x-ref="slider" @scroll.debounce.10ms="updateActiveSlide" class="w-full h-full flex overflow-x-auto snap-x snap-mandatory no-scrollbar items-center gap-8 px-[5vw] md:px-[calc(50vw-325px)]">
                        
                        @foreach($horizontalPosts as $index => $p)
                            @php
                                $imgData = [];
                                if($p->image) {
                                    $decoded = json_decode($p->image, true);
                                    $imgData = is_array($decoded) ? $decoded : [$p->image];
                                }
                            @endphp

                            <div class="shrink-0 w-[90vw] md:w-[650px] h-full snap-center transition-all duration-500 ease-out"
                                 :class="activeSlide === {{ $index }} ? 'scale-100 opacity-100 z-20 pointer-events-auto' : 'scale-[0.85] opacity-40 blur-[2px] z-10 pointer-events-none'">
                                 
                                <div x-data="{ 
                                        openComments: false,
                                        expandedText: false,
                                        viewsCount: {{ $p->views->count() ?? 0 }},
                                        viewed: false,
                                        isLiked: {{ $p->isLikedBy(auth()->user()) ? 'true' : 'false' }},
                                        likesCount: {{ $p->likes->count() }},
                                        isDisliked: false,
                                        dislikesCount: 0,
                                        newComment: '',
                                        currentImg: 0,
                                        imgs: {{ json_encode($imgData) }},
                                        comments: [
                                            @foreach($p->comments as $c)
                                                { id: {{ $c->id }}, name: '{{ $c->user->name ?? 'User' }}', avatar: '{{ $c->user && $c->user->avatar ? asset('storage/'.$c->user->avatar) : 'https://ui-avatars.com/api/?name='.($c->user->name ?? 'U') }}', body: `{{ $c->body }}`, time: '{{ $c->created_at->diffForHumans() }}', can_delete: {{ (Auth::id() === $c->user_id || Auth::id() === $p->user_id) ? 'true' : 'false' }} },
                                            @endforeach
                                        ],
                                        toggleLike() {
                                            this.isLiked = !this.isLiked;
                                            this.likesCount += this.isLiked ? 1 : -1;
                                            if(this.isDisliked && this.isLiked) { this.isDisliked = false; this.dislikesCount--; }
                                            fetch('/posts/{{ $p->id }}/like', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                                        },
                                        toggleDislike() {
                                            this.isDisliked = !this.isDisliked;
                                            this.dislikesCount += this.isDisliked ? 1 : -1;
                                            if(this.isLiked && this.isDisliked) { this.isLiked = false; this.likesCount--; }
                                        },
                                        submitComment() {
                                            if(!this.newComment.trim()) return;
                                            this.comments.push({ id: Date.now(), name: '{{ Auth::user()->name }}', avatar: '{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}', body: this.newComment, time: 'Hozirgina', can_delete: true });
                                            let formData = new FormData(); formData.append('body', this.newComment); formData.append('_token', '{{ csrf_token() }}');
                                            this.newComment = ''; 
                                            fetch('{{ route('comments.store', $p) }}', { method: 'POST', body: formData });
                                        },
                                        countView() {
                                            if (this.viewed) return;
                                            fetch('/posts/{{ $p->id }}/view', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(res => res.json()).then(data => { this.viewsCount = data.views; this.viewed = true; });
                                        },
                                        nextImg() {
                                            if(this.imgs.length > 1) {
                                                this.currentImg = (this.currentImg + 1) % this.imgs.length;
                                                this.countView();
                                            }
                                        },
                                        prevImg() {
                                            if(this.imgs.length > 1) {
                                                this.currentImg = (this.currentImg - 1 + this.imgs.length) % this.imgs.length;
                                                this.countView();
                                            }
                                        }
                                     }"
                                     class="w-full h-full bg-[#111] rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                                    
                                    <template x-if="imgs.length > 0">
                                        <div class="absolute inset-0 w-full h-full overflow-hidden transition-all duration-500 ease-in-out"
                                             :class="expandedText ? 'scale-110 opacity-30 blur-md' : 'opacity-100'">
                                            
                                            <div class="absolute inset-0 w-full h-full bg-[#111]">
                                                <img :src="'/storage/' + imgs[currentImg]" class="w-full h-full object-cover opacity-20 blur-3xl transition-all duration-500">
                                            </div>
                                            
                                            <div class="relative w-full h-full flex items-center justify-center">
                                                <template x-for="(img, idx) in imgs" :key="idx">
                                                    <div class="absolute inset-0 w-full h-full transition-transform duration-500 ease-[cubic-bezier(0.25,1,0.5,1)]"
                                                         :style="`
                                                            transform: translateX(${(idx - currentImg) * 100}%);
                                                            z-index: ${idx === currentImg ? 20 : 10};
                                                         `">
                                                        <img :src="'/storage/' + img" 
                                                             @click.stop="imageModal = '/storage/' + img" 
                                                             class="w-full h-full object-cover cursor-pointer select-none">
                                                    </div>
                                                </template>
                                            </div>

                                            <div x-show="imgs.length > 1" class="absolute bottom-[20%] left-1/2 -translate-x-1/2 flex items-center gap-3 z-40">
                                                <button @click.stop="prevImg()" class="bg-black/50 hover:bg-black/80 backdrop-blur text-white text-[10px] font-black tracking-widest px-4 py-2 rounded-lg uppercase transition border border-white/10 shadow-[0_5px_15px_rgba(0,0,0,0.5)]">Prev</button>
                                                <div class="bg-black/80 text-white text-xs font-bold px-3 py-1.5 rounded-lg border border-white/10 shadow-[0_5px_15px_rgba(0,0,0,0.5)]">
                                                    <span x-text="currentImg + 1"></span> / <span x-text="imgs.length"></span>
                                                </div>
                                                <button @click.stop="nextImg()" class="bg-[#4CAF50] hover:bg-[#45a049] shadow-[0_5px_15px_rgba(76,175,80,0.5)] text-white text-[10px] font-black tracking-widest px-4 py-2 rounded-lg uppercase transition border border-white/10">Next</button>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <template x-if="imgs.length === 0">
                                        <div class="absolute inset-0 w-full h-full bg-[#111]"></div>
                                    </template>

                                    <div class="absolute inset-0 bg-gradient-to-t pointer-events-none transition-all duration-300 z-20"
                                         :class="expandedText ? 'from-black/95 via-black/80 to-transparent' : 'from-black/90 via-black/30 to-transparent'"></div>

                                    @if(Auth::id() === $p->user_id)
                                        <div class="absolute top-5 right-5 z-40 flex gap-2">
                                            <button type="button" 
                                                @click="
                                                    editingPost = { id: {{ $p->id }}, body: `{{ $p->body }}`, images: imgs };
                                                    editImagePreviews = editingPost.images.map(img => '/storage/' + img);
                                                    openEditModal = true;
                                                " 
                                                class="text-white/60 hover:text-white p-2.5 bg-black/40 backdrop-blur rounded-full transition border border-white/10 shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <form action="{{ route('posts.destroy', $p) }}" method="POST" onsubmit="return confirm('O\'chirmoqchimisiz?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-white/60 hover:text-white p-2.5 bg-black/40 backdrop-blur rounded-full transition border border-white/10 shadow-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </form>
                                        </div>
                                    @endif

                                    <div class="absolute bottom-0 left-0 w-full px-8 py-8 z-30 flex flex-col justify-end h-full pointer-events-none">
                                        <div class="pointer-events-auto w-full">
                                            
                                            <div class="flex items-center gap-4 mb-4">
                                                <a href="{{ route('profile.show', ['username' => $p->user->username]) }}" class="shrink-0 relative">
                                                    @if($p->user->avatar)
                                                        <img src="{{ asset('storage/' . $p->user->avatar) }}" class="h-12 w-12 rounded-full object-cover border-2 border-white/20 shadow-lg">
                                                    @else
                                                        <div class="h-12 w-12 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white font-bold text-xl border-2 border-white/20">{{ substr($p->user->name, 0, 1) }}</div>
                                                    @endif
                                                </a>
                                                <div class="text-white drop-shadow-md">
                                                    <a href="{{ route('profile.show', ['username' => $p->user->username]) }}" class="font-bold text-xl hover:underline block leading-tight">{{ $p->user->name }}</a>
                                                    <span class="text-sm text-gray-300">{{ $p->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>

                                            <div class="text-white mb-6 transition-all duration-300">
                                                <p class="text-base sm:text-lg leading-relaxed drop-shadow-md cursor-pointer whitespace-pre-wrap"
                                                   :class="expandedText ? 'line-clamp-none max-h-[40vh] overflow-y-auto custom-scrollbar pr-2' : 'line-clamp-2'"
                                                   @click="expandedText = !expandedText">{{ $p->body }}</p>
                                                
                                                @if(strlen($p->body) > 100)
                                                    <button x-show="!expandedText" @click="expandedText = true" class="text-gray-300 font-bold text-sm mt-1 hover:text-white transition">Ko'proq...</button>
                                                    <button x-show="expandedText" @click="expandedText = false" class="text-gray-300 font-bold text-sm mt-1 hover:text-white transition">Yashirish</button>
                                                @endif
                                            </div>

                                            <div class="flex items-center justify-between pt-4 border-t border-white/20">
                                                <div class="flex items-center gap-8 text-white drop-shadow-md">
                                                    <button type="button" @click="toggleLike(); countView()" class="flex items-center gap-2 transition" :class="isLiked ? 'text-red-500' : 'text-white'">
                                                        <svg class="w-9 h-9 active:scale-125 transition" :fill="isLiked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                                        <span class="text-lg font-bold" x-text="likesCount"></span>
                                                    </button>
                                                    <button type="button" @click="toggleDislike(); countView()" class="flex items-center gap-2 transition" :class="isDisliked ? 'text-gray-400' : 'text-white'">
                                                        <svg class="w-9 h-9 active:scale-125 transition" :fill="isDisliked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a3 3 0 013 3v8m-6 4v2a3 3 0 003 3h4.018a3 3 0 002.684-1.631l3.5-7A2 2 0 0019 10h-5.236a2 2 0 00-1.789 2.894z"></path></svg>
                                                    </button>
                                                    <button @click="openComments = true; countView()" class="flex items-center gap-2 hover:text-blue-300 transition">
                                                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                                        <span class="text-lg font-bold" x-text="comments.length"></span>
                                                    </button>
                                                </div>
                                                <div class="flex items-center gap-2 text-gray-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    <span class="text-base font-bold" x-text="viewsCount"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <template x-teleport="body">
                                        <div x-show="openComments" style="display: none;" class="fixed inset-0 z-[150] flex justify-end">
                                            <div @click="openComments = false" class="absolute inset-0 bg-black/40 backdrop-blur-sm" x-transition.opacity></div>
                                            <div class="relative h-full w-full sm:w-[450px] bg-white shadow-[-20px_0_50px_rgba(0,0,0,0.15)] flex flex-col transform transition-transform duration-300 ease-out"
                                                 x-show="openComments"
                                                 x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                                                 x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
                                                
                                                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-white shrink-0">
                                                    <h2 class="text-xl font-black text-gray-900">Izohlar (<span x-text="comments.length"></span>)</h2>
                                                    <button @click="openComments = false" class="p-2 hover:bg-gray-100 rounded-full transition"><svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                                </div>
                                                <div class="flex-1 overflow-y-auto p-5 space-y-5 bg-gray-50/50 custom-scrollbar">
                                                    <template x-for="(comment, i) in comments" :key="comment.id || i">
                                                        <div class="flex gap-3 slide-up-animation">
                                                            <img :src="comment.avatar" class="w-9 h-9 rounded-full object-cover shrink-0 shadow-sm border border-gray-200">
                                                            <div class="flex-1">
                                                                <div class="bg-white px-4 py-3 rounded-[1.2rem] rounded-tl-sm shadow-sm border border-gray-100 text-[14px]">
                                                                    <span class="font-bold text-gray-900 block mb-0.5" x-text="comment.name"></span>
                                                                    <p class="text-gray-700 whitespace-pre-wrap" x-text="comment.body"></p>
                                                                </div>
                                                                <div class="flex items-center gap-3 mt-1 ml-2">
                                                                    <span class="text-[11px] font-medium text-gray-400" x-text="comment.time"></span>
                                                                    <template x-if="comment.can_delete">
                                                                        <button @click="comments.splice(i, 1);" class="text-[11px] font-bold text-red-400 hover:text-red-600">O'chirish</button>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                                <div class="p-4 border-t border-gray-100 bg-white shrink-0 shadow-[0_-10px_20px_rgba(0,0,0,0.02)]">
                                                    <form @submit.prevent="submitComment" class="flex gap-2 items-end">
                                                        <textarea x-model="newComment" rows="1" placeholder="Izoh yozing..." class="w-full bg-gray-50 border border-gray-200 rounded-[1.2rem] px-4 py-3 text-[14px] focus:ring-1 focus:ring-black outline-none resize-none custom-scrollbar max-h-32" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                                                        <button type="submit" :disabled="!newComment.trim()" class="p-3 bg-black text-white rounded-[1rem] transition mb-0.5" :class="!newComment.trim() ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-800'"><svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <template x-teleport="body">
            <div x-show="openNotifications" style="display: none;" class="fixed inset-0 z-[160] flex justify-end">
                <div @click="openNotifications = false" class="absolute inset-0 bg-black/20 backdrop-blur-sm" x-transition.opacity></div>
                <div class="relative h-full w-full sm:w-[380px] bg-white shadow-[-10px_0_30px_rgba(0,0,0,0.1)] flex flex-col transform transition-transform duration-300 ease-out"
                     x-show="openNotifications"
                     x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
                    
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-white shrink-0">
                        <h2 class="text-xl font-black text-gray-900">Bildirishnomalar</h2>
                        <button @click="openNotifications = false" class="p-2 hover:bg-gray-100 rounded-full transition"><svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex gap-3 hover:bg-gray-100 transition cursor-pointer">
                            <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white font-bold shrink-0">B</div>
                            <div>
                                <p class="text-sm text-gray-800"><span class="font-bold">Bloggram</span> sizni xush ko'rdik!</p>
                                <span class="text-[11px] text-gray-400">Hozirgina</span>
                            </div>
                        </div>

                        <div class="h-40 flex flex-col items-center justify-center text-gray-400 opacity-60">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <p class="text-sm font-medium">Yangi bildirishnomalar yo'q</p>
                        </div>
                    </div>

                    <div class="p-4 border-t border-gray-100">
                        <a href="/notifications" class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-900 font-bold py-3 rounded-2xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            Barcha xabarlarni ko'rish
                        </a>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="imageModal" 
             class="fixed inset-0 z-[200] flex items-center justify-center bg-black/95 backdrop-blur-md px-4 py-10" 
             x-transition.opacity 
             @click="imageModal = null"
             style="display: none;">
            <button class="absolute top-5 right-5 text-white p-3 hover:bg-white/10 rounded-full transition z-[210]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img :src="imageModal" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl animate-zoom-in">
        </div>

        <div x-show="openPostModal" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4" x-transition.opacity>
            <div @click.away="openPostModal = false" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative border border-gray-100">
               <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                    <button @click="openPostModal = false" class="text-gray-400 hover:bg-gray-100 p-2 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <h2 class="text-lg font-bold text-gray-900">Yangi post</h2>
                    <button form="createPostForm" type="submit" :disabled="imagePreviews.length === 0" class="text-white text-sm font-bold px-6 py-2 rounded-full transition shadow-md" :class="imagePreviews.length > 0 ? 'bg-black hover:bg-gray-800' : 'bg-gray-300 cursor-not-allowed'">Ulashish</button>
                </div>
                <div class="p-6">
                    <form id="createPostForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex gap-4">
                            <div class="shrink-0"><img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="h-10 w-10 rounded-full object-cover border border-gray-100"></div>
                            <div class="flex-1 w-full overflow-hidden">
                                <textarea name="body" rows="4" class="w-full border-none focus:ring-0 text-[16px] placeholder-gray-400 resize-none p-0 bg-transparent outline-none" placeholder="Matn yozing..." required></textarea>
                                
                                <div x-show="imagePreviews.length > 0" style="display:none;" class="relative mt-3 flex gap-2 overflow-x-auto pb-2 custom-scrollbar">
                                    <template x-for="(preview, index) in imagePreviews" :key="index">
                                        <div class="relative shrink-0 w-24 h-24">
                                            <img :src="preview" class="w-full h-full rounded-xl object-cover shadow-sm border border-gray-100">
                                        </div>
                                    </template>
                                    <button @click="imagePreviews = []; document.getElementById('imageInput').value = ''" type="button" class="absolute top-1 right-1 bg-black/70 text-white rounded-full p-1.5 transition hover:bg-black shadow-md z-10"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <label class="cursor-pointer text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-xl transition inline-flex items-center gap-2" :class="imagePreviews.length === 0 ? 'animate-pulse text-blue-600 bg-blue-50' : ''">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-bold" x-text="imagePreviews.length > 0 ? imagePreviews.length + ' ta tanlandi' : 'Rasm (Maks 6ta)'"></span>
                                        <input type="file" id="imageInput" name="images[]" multiple="multiple" class="hidden" required accept="image/*" 
                                               @change="
                                                    const files = Array.from($event.target.files).slice(0, 6);
                                                    imagePreviews = [];
                                                    files.forEach(file => {
                                                        const reader = new FileReader();
                                                        reader.onload = (e) => imagePreviews.push(e.target.result);
                                                        reader.readAsDataURL(file);
                                                    });
                                               ">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="openEditModal" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4" x-transition.opacity>
            <div @click.away="openEditModal = false" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative border border-gray-100 animate-zoom-in">
               <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                    <button @click="openEditModal = false" class="text-gray-400 hover:bg-gray-100 p-2 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <h2 class="text-lg font-bold text-gray-900">Postni tahrirlash</h2>
                    <button form="editPostForm" type="submit" class="bg-black hover:bg-gray-800 text-white text-sm font-bold px-6 py-2 rounded-full transition shadow-md">Saqlash</button>
                </div>
                <div class="p-6">
                    <form id="editPostForm" :action="`/posts/${editingPost.id}`" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-4">
                            <div class="shrink-0"><img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="h-10 w-10 rounded-full object-cover border border-gray-100"></div>
                            <div class="flex-1 w-full overflow-hidden">
                                <textarea name="body" x-model="editingPost.body" rows="4" class="w-full border-none focus:ring-0 text-[16px] placeholder-gray-400 resize-none p-0 bg-transparent outline-none" placeholder="Matn yozing..." required></textarea>
                                
                                <div x-show="editImagePreviews.length > 0" style="display:none;" class="relative mt-3 flex gap-2 overflow-x-auto pb-2 custom-scrollbar">
                                    <template x-for="(preview, index) in editImagePreviews" :key="index">
                                        <div class="relative shrink-0 w-24 h-24">
                                            <img :src="preview" class="w-full h-full rounded-xl object-cover shadow-sm border border-gray-100">
                                        </div>
                                    </template>
                                    <button @click="editImagePreviews = []; document.getElementById('editImageInput').value = ''" type="button" class="absolute top-1 right-1 bg-black/70 text-white rounded-full p-1.5 transition hover:bg-black shadow-md z-10"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <label class="cursor-pointer text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-xl transition inline-flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-bold">Rasmlarni yangilash</span>
                                        <input type="file" id="editImageInput" name="images[]" multiple="multiple" class="hidden" accept="image/*" 
                                               @change="
                                                    const files = Array.from($event.target.files).slice(0, 6);
                                                    if(files.length > 0) {
                                                        editImagePreviews = [];
                                                        files.forEach(file => {
                                                            const reader = new FileReader();
                                                            reader.onload = (e) => editImagePreviews.push(e.target.result);
                                                            reader.readAsDataURL(file);
                                                        });
                                                    }
                                               ">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .snap-mandatory { scroll-snap-type: both mandatory; }
        @keyframes slideUp { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }
        .slide-up-animation { animation: slideUp 0.3s ease-out forwards; }
        @keyframes zoomIn { 0% { opacity: 0; transform: scale(0.9); } 100% { opacity: 1; transform: scale(1); } }
        .animate-zoom-in { animation: zoomIn 0.2s ease-out forwards; }
    </style>
</x-app-layout>