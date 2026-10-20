<x-app-layout>
    <div x-data="{ 
        openPostModal: false,
        openEditModal: false,
        openNotifications: false,
        imagePreviews: [], 
        editImagePreviews: [], 
        imageModal: null,
        editingPost: { id: null, body: '', images: [], theme: 'cosmic' },
        selectedTheme: 'cosmic',
        editSelectedTheme: 'cosmic',
        themes: [
            { key: 'cosmic', name: 'Cosmic', bg: 'linear-gradient(135deg, #1e1b4b, #312e81, #0f172a)' },
            { key: 'sunset', name: 'Sunset', bg: 'linear-gradient(135deg, #881337, #be123c, #4c0519)' },
            { key: 'emerald', name: 'Emerald', bg: 'linear-gradient(135deg, #022c22, #064e3b, #021d17)' },
            { key: 'ocean', name: 'Ocean', bg: 'linear-gradient(135deg, #0c4a6e, #0369a1, #082f49)' },
            { key: 'cyber', name: 'Cyber', bg: 'linear-gradient(135deg, #4c1d95, #6d28d9, #1e1b4b)' },
            { key: 'midnight', name: 'Midnight', bg: 'linear-gradient(135deg, #0f172a, #1e293b, #020617)' }
        ]
    }"
    @open-create-post.window="openPostModal = true"
    x-init="
        if (new URLSearchParams(window.location.search).get('create') === '1') {
            openPostModal = true;
            // Clean URL param without page reload
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    "
    class="h-[100dvh] lg:h-screen flex flex-col bg-[#F8FAFC] font-sans overflow-hidden overscroll-none"> 

        <header class="sticky top-0 inset-x-0 flex h-[60px] sm:h-[70px] shrink-0 items-center justify-between px-4 sm:px-6 lg:px-10 border-b border-gray-200 bg-white/95 backdrop-blur-md z-40 shadow-sm select-none">
            <h2 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight">Postlar Lentasi</h2>
            
            <div class="flex items-center gap-2 sm:gap-3">
                <button @click="openNotifications = true" class="relative p-2 sm:p-2.5 text-gray-700 hover:bg-gray-100 rounded-full transition group" title="Bildirishnomalar">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-1.5 sm:top-2 right-2 sm:right-2.5 w-2 sm:w-2.5 h-2 sm:h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                </button>

                <button @click.stop="openPostModal = true" class="bg-black hover:bg-gray-800 text-white text-xs sm:text-sm font-bold px-3.5 sm:px-6 py-2 sm:py-2.5 rounded-full transition shadow-lg flex items-center gap-1.5 sm:gap-2 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Yangi Post</span>
                </button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto snap-y snap-mandatory no-scrollbar relative w-full overscroll-contain">
            
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
                        showHints: true,
                        updateActiveSlide() {
                            let container = this.$refs.slider;
                            if(!container || container.clientWidth === 0) return;
                            this.activeSlide = Math.round(container.scrollLeft / container.clientWidth);
                            if (this.activeSlide > 0) {
                                this.showHints = false;
                            }
                        },
                        goToSlide(index) {
                            let container = this.$refs.slider;
                            if(!container) return;
                            container.scrollTo({ left: index * container.clientWidth, behavior: 'smooth' });
                            this.activeSlide = index;
                            if (index > 0) {
                                this.showHints = false;
                            }
                        }
                     }" 
                     x-init="
                        setTimeout(() => { showHints = false; }, 6000);
                        let observer = new IntersectionObserver((entries) => {
                            if(!entries[0].isIntersecting) {
                                if ($refs.slider) {
                                    $refs.slider.scrollLeft = 0;
                                }
                                activeSlide = 0;
                                showHints = true;
                            }
                        }, { threshold: 0.1 });
                        observer.observe($el);
                     "
                     class="w-full h-[calc(100dvh-60px-64px)] sm:h-[calc(100dvh-70px-64px)] lg:h-[calc(100vh-70px)] snap-start flex items-center justify-center relative py-2 sm:py-6 lg:py-8">
                    
                    <div x-ref="slider" 
                         @scroll.debounce.10ms="updateActiveSlide" 
                         class="w-full h-full flex overflow-x-auto snap-x snap-mandatory no-scrollbar items-center">
                        
                        @foreach($horizontalPosts as $index => $p)
                            @php
                                $imgData = [];
                                if($p->image) {
                                    $decoded = json_decode($p->image, true);
                                    $imgData = is_array($decoded) ? $decoded : [$p->image];
                                }

                                $bodyLen = mb_strlen($p->body);
                                $fontClass = $bodyLen < 90 
                                    ? 'text-2xl sm:text-3xl md:text-4xl font-black text-center' 
                                    : ($bodyLen < 220 
                                        ? 'text-xl sm:text-2xl md:text-3xl font-bold text-center' 
                                        : ($bodyLen < 450 
                                            ? 'text-lg sm:text-xl font-semibold text-center' 
                                            : 'text-base sm:text-lg font-medium text-left'));
                            @endphp

                            <div class="w-full h-full shrink-0 flex items-center justify-center snap-center p-2 sm:p-4">
                                <div class="w-[92vw] sm:w-[90vw] md:w-[650px] h-full transition-all duration-500 ease-out"
                                     :class="activeSlide === {{ $index }} ? 'scale-100 opacity-100 z-20 pointer-events-auto' : 'scale-[0.85] opacity-40 blur-[2px] z-10 pointer-events-none'">
                                 
                                <div x-data="{ 
                                        openComments: false,
                                        expandedText: false,
                                        viewsCount: {{ $p->views_count ?? 0 }},
                                        viewed: false,
                                        isLiked: {{ !empty($p->is_liked_by_me) ? 'true' : 'false' }},
                                        likesCount: {{ $p->likes_count ?? 0 }},
                                        isDisliked: false,
                                        dislikesCount: 0,
                                        newComment: '',
                                        currentImg: 0,
                                        imgs: {{ json_encode($imgData) }},
                                        comments: [
                                            @foreach($p->comments as $c)
                                                { id: {{ $c->id }}, name: @js($c->user->name ?? 'User'), avatar: @js($c->user && $c->user->avatar ? asset('storage/'.$c->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($c->user->name ?? 'U')), body: @js($c->body), time: '{{ $c->created_at->diffForHumans() }}', can_delete: {{ (Auth::id() === $c->user_id || Auth::id() === $p->user_id) ? 'true' : 'false' }} },
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
                                     class="w-full h-full rounded-[1.8rem] sm:rounded-[2.5rem] shadow-2xl relative overflow-hidden group"
                                     style="background: {{ count($imgData) === 0 ? $p->gradient_style : '#111' }};">
                                        
                                        @if($horizontalPosts->count() > 1)
                                            <!-- Story-like Horizontal Progress Segments -->
                                            <div class="absolute top-3 left-4 sm:left-6 right-4 sm:right-6 z-40 flex items-center gap-1.5 pointer-events-auto">
                                                @for($seg = 0; $seg < $horizontalPosts->count(); $seg++)
                                                    <button type="button" 
                                                            @click.stop="goToSlide({{ $seg }})"
                                                            title="Post {{ $seg + 1 }}"
                                                            class="h-1 flex-1 rounded-full transition-all duration-300 py-1 -my-1 group/seg focus:outline-none">
                                                        <div class="h-1 w-full rounded-full transition-all duration-300"
                                                             :class="activeSlide === {{ $seg }} ? 'bg-white shadow-[0_0_8px_rgba(255,255,255,0.8)]' : (activeSlide > {{ $seg }} ? 'bg-white/60' : 'bg-white/20 group-hover/seg:bg-white/40')"></div>
                                                    </button>
                                                @endfor
                                            </div>

                                            <!-- Top Floating Author Collection Pill / Back Button -->
                                            <div class="absolute top-6 left-4 sm:left-6 z-40 flex items-center gap-2">
                                                @if($index === 0)
                                                    <button type="button" 
                                                            @click.stop="goToSlide(1)"
                                                            class="group/hint flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/50 hover:bg-black/75 backdrop-blur-md border border-white/20 text-white shadow-lg transition-all transform hover:scale-105 active:scale-95">
                                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                                        <span class="text-xs font-semibold tracking-wide">Muallif postlari</span>
                                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-white/20 text-white/90">{{ $horizontalPosts->count() }} ta</span>
                                                        <svg class="w-3.5 h-3.5 text-white/70 group-hover/hint:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            @click.stop="goToSlide(0)"
                                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/60 hover:bg-black/80 backdrop-blur-md border border-white/25 text-white text-xs font-bold shadow-lg transition-all transform hover:scale-105 active:scale-95">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                                        <span>Asosiyga qaytish</span>
                                                    </button>
                                                    <span class="px-2.5 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/15 text-[11px] font-semibold text-white/90">
                                                        {{ $feedPost->user->name }}: {{ $index + 1 }}/{{ $horizontalPosts->count() }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        @if(count($imgData) > 0)
                                            <!-- Image post carousel -->
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

                                            <div class="absolute inset-0 bg-gradient-to-t pointer-events-none transition-all duration-300 z-20"
                                                 :class="expandedText ? 'from-black/95 via-black/80 to-transparent' : 'from-black/90 via-black/30 to-transparent'"></div>
                                        @else
                                            <!-- Dedicated Text-Only Slide Ambient Watermark Background -->
                                            <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
                                                <div class="absolute -top-12 -right-12 text-white/[0.08]">
                                                    <svg class="w-64 h-64 rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                                                </div>
                                                <div class="absolute -bottom-12 -left-12 text-white/[0.08]">
                                                    <svg class="w-64 h-64 -rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                                                </div>
                                            </div>
                                        @endif

                                        @if(Auth::id() === $p->user_id)
                                            <div class="absolute top-6 right-4 sm:right-6 z-40 flex gap-2">
                                                <button type="button" 
                                                    @click.stop="
                                                        editingPost = { id: {{ $p->id }}, body: @js($p->body), images: imgs, theme: @js($p->theme ?? 'cosmic') };
                                                        editSelectedTheme = @js($p->theme ?? 'cosmic');
                                                        editImagePreviews = (editingPost.images || []).map(img => '/storage/' + img);
                                                        openEditModal = true;
                                                    " 
                                                    class="text-white/70 hover:text-white p-2.5 bg-black/40 hover:bg-black/60 backdrop-blur rounded-full transition border border-white/10 shadow-lg">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </button>
                                                <form action="{{ route('posts.destroy', $p) }}" method="POST" onsubmit="return confirm('O\'chirmoqchimisiz?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-white/70 hover:text-white p-2.5 bg-black/40 hover:bg-black/60 backdrop-blur rounded-full transition border border-white/10 shadow-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                                </form>
                                            </div>
                                        @endif

                                        @if(count($imgData) > 0)
                                            <!-- Image post bottom overlay -->
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
                                        @else
                                            <!-- Dedicated Text-Only Slide Layout (Centered Story / Quote Presentation) -->
                                            <div class="absolute inset-0 w-full h-full p-8 md:p-12 flex flex-col justify-between z-30 {{ $horizontalPosts->count() > 1 ? 'pt-16 md:pt-16' : '' }}">
                                                <!-- Top: Author Profile Info -->
                                                <div class="flex items-center gap-4">
                                                    <a href="{{ route('profile.show', ['username' => $p->user->username]) }}" class="shrink-0 relative">
                                                        @if($p->user->avatar)
                                                            <img src="{{ asset('storage/' . $p->user->avatar) }}" class="h-12 w-12 rounded-full object-cover border-2 border-white/30 shadow-lg">
                                                        @else
                                                            <div class="h-12 w-12 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white font-bold text-xl border-2 border-white/30">{{ substr($p->user->name, 0, 1) }}</div>
                                                        @endif
                                                    </a>
                                                    <div class="text-white drop-shadow-md">
                                                        <a href="{{ route('profile.show', ['username' => $p->user->username]) }}" class="font-bold text-xl hover:underline block leading-tight">{{ $p->user->name }}</a>
                                                        <span class="text-xs text-white/70">{{ $p->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>

                                                <!-- Middle: Centered Dynamic Quote -->
                                                <div class="flex-1 my-auto flex items-center justify-center py-6 px-2 sm:px-6">
                                                    <div class="w-full">
                                                        <p class="{{ $fontClass }} text-white leading-relaxed tracking-tight drop-shadow-lg whitespace-pre-wrap max-h-[46vh] overflow-y-auto custom-scrollbar pr-2">
                                                            {{ $p->body }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Bottom: Glassy Floating Action Bar -->
                                                <div class="flex items-center justify-between px-5 py-3.5 bg-black/30 backdrop-blur-md rounded-2xl border border-white/10 shadow-lg">
                                                    <div class="flex items-center gap-7 text-white">
                                                        <button type="button" @click="toggleLike(); countView()" class="flex items-center gap-2 transition hover:scale-105 active:scale-125" :class="isLiked ? 'text-red-400' : 'text-white'">
                                                            <svg class="w-7 h-7" :fill="isLiked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                                            <span class="text-base font-bold" x-text="likesCount"></span>
                                                        </button>
                                                        <button type="button" @click="toggleDislike(); countView()" class="flex items-center gap-2 transition hover:scale-105 active:scale-125" :class="isDisliked ? 'text-gray-400' : 'text-white'">
                                                            <svg class="w-7 h-7" :fill="isDisliked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a3 3 0 013 3v8m-6 4v2a3 3 0 003 3h4.018a3 3 0 002.684-1.631l3.5-7A2 2 0 0019 10h-5.236a2 2 0 00-1.789 2.894z"></path></svg>
                                                        </button>
                                                        <button @click="openComments = true; countView()" class="flex items-center gap-2 hover:text-blue-300 transition hover:scale-105">
                                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                                            <span class="text-base font-bold" x-text="comments.length"></span>
                                                        </button>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 text-white/80">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        <span class="text-sm font-bold" x-text="viewsCount"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

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
                        </div>
                    @endforeach
                </div>

                    <!-- Floating 2D Navigation Cues (Disappears after 6s or when scrolled) -->
                    @if($horizontalPosts->count() > 1)
                        <div x-show="showHints && activeSlide === 0" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-x-4"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-x-0"
                             x-transition:leave-end="opacity-0 translate-x-4"
                             class="hidden sm:flex absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-30 items-center pointer-events-auto">
                            <button type="button"
                                    @click="goToSlide(1)"
                                    class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-black/75 hover:bg-black/90 text-white backdrop-blur-xl border border-white/20 shadow-2xl transition transform hover:scale-105 active:scale-95 group">
                                <div class="text-left">
                                    <p class="text-[10px] uppercase tracking-wider text-emerald-400 font-extrabold">O'ngga suring</p>
                                    <p class="text-xs font-bold text-white/90">Muallifniki &rarr;</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center group-hover:translate-x-1 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </div>
                            </button>
                        </div>
                    @endif

                    <!-- Bottom Vertical Hint: Pastga suring: Keyingisi -->
                    <div x-show="showHints && activeSlide === 0"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         class="absolute bottom-2 left-1/2 -translate-x-1/2 z-30 pointer-events-none">
                        <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/60 backdrop-blur-md border border-white/15 text-white/80 shadow-lg text-[11px] font-medium animate-bounce">
                            <span>Pastga suring: Keyingisi</span>
                            <svg class="w-3.5 h-3.5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </div>
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
                            <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white font-bold shrink-0">M</div>
                            <div>
                                <p class="text-sm text-gray-800"><span class="font-bold">Minnagram</span> sizni xush ko'rdik!</p>
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

        <template x-teleport="body">
            <div x-show="openPostModal" 
                 x-cloak
                 style="display: none;" 
                 class="fixed inset-0 z-[120] flex items-center justify-center p-4 overflow-y-auto">
                <div @click="openPostModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-transition.opacity></div>
                <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 my-auto border border-gray-100 overflow-hidden" x-transition>
                    <form id="createPostForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="theme" :value="selectedTheme">

                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 bg-white">
                            <button type="button" @click="openPostModal = false" class="text-gray-400 hover:bg-gray-100 p-2 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            <h2 class="text-lg font-bold text-gray-900">Yangi post</h2>
                            <button type="submit" class="text-white text-sm font-bold px-6 py-2 rounded-full transition shadow-md bg-black hover:bg-gray-800">Ulashish</button>
                        </div>
                        
                        <div class="p-6">
                            <!-- Text Area with Dynamic Live Theme Background -->
                            <div class="rounded-2xl p-4 transition-all duration-300 relative overflow-hidden"
                                 :style="imagePreviews.length === 0 ? 'background: ' + (themes.find(t => t.key === selectedTheme)?.bg || '#111') : 'background: #f8fafc'">
                                
                                <div x-show="imagePreviews.length === 0" class="absolute -top-3 -right-3 text-white/5 pointer-events-none select-none">
                                    <svg class="w-20 h-20 rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                                </div>
                                
                                <div class="flex gap-3 relative z-10">
                                    <div class="shrink-0">
                                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="h-9 w-9 rounded-full object-cover border" :class="imagePreviews.length === 0 ? 'border-white/30' : 'border-gray-200'">
                                    </div>
                                    <div class="flex-1">
                                        <textarea name="body" rows="4" 
                                                  class="w-full border-none focus:ring-0 text-[16px] resize-none p-0 bg-transparent outline-none transition-colors"
                                                  :class="imagePreviews.length === 0 ? 'text-white placeholder-white/60 font-semibold' : 'text-gray-900 placeholder-gray-400'"
                                                  placeholder="Fikringizni yozing..." required></textarea>
                                    </div>
                                </div>

                                <!-- Image previews if uploaded -->
                                <div x-show="imagePreviews.length > 0" style="display:none;" class="relative mt-3 flex gap-2 overflow-x-auto pb-2 custom-scrollbar">
                                    <template x-for="(preview, index) in imagePreviews" :key="index">
                                        <div class="relative shrink-0 w-24 h-24">
                                            <img :src="preview" class="w-full h-full rounded-xl object-cover shadow-sm border border-gray-100">
                                        </div>
                                    </template>
                                    <button @click="imagePreviews = []; document.getElementById('imageInput').value = ''" type="button" class="absolute top-1 right-1 bg-black/70 text-white rounded-full p-1.5 transition hover:bg-black shadow-md z-10"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>
                            </div>

                            <!-- Theme Selector (Shown when no images) -->
                            <div x-show="imagePreviews.length === 0" class="mt-4 flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Mavzuni tanlang:</span>
                                <div class="flex items-center gap-2">
                                    <template x-for="t in themes" :key="t.key">
                                        <button type="button" 
                                                @click="selectedTheme = t.key"
                                                class="w-7 h-7 rounded-full transition-transform duration-200 border-2"
                                                :class="selectedTheme === t.key ? 'scale-125 border-gray-900 shadow-md ring-2 ring-indigo-400' : 'border-transparent opacity-80 hover:opacity-100 hover:scale-110'"
                                                :style="`background: ${t.bg};`"
                                                :title="t.name">
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Add Images Action -->
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <label class="cursor-pointer text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-xl transition inline-flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm font-bold" x-text="imagePreviews.length > 0 ? imagePreviews.length + ' ta rasm tanlandi' : 'Rasm qo\'shish (ixtiyoriy)'"></span>
                                    <input type="file" id="imageInput" name="images[]" multiple="multiple" class="hidden" accept="image/*" 
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
                    </form>
                </div>
            </div>
        </template>

        <template x-teleport="body">
            <div x-show="openEditModal" 
                 x-cloak
                 style="display: none;" 
                 class="fixed inset-0 z-[120] flex items-center justify-center p-4 overflow-y-auto">
                <div @click="openEditModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-transition.opacity></div>
                <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 my-auto border border-gray-100 animate-zoom-in overflow-hidden" x-transition>
                    <form id="editPostForm" :action="`/posts/${editingPost.id}`" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="theme" :value="editSelectedTheme">

                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 bg-white">
                            <button type="button" @click="openEditModal = false" class="text-gray-400 hover:bg-gray-100 p-2 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            <h2 class="text-lg font-bold text-gray-900">Postni tahrirlash</h2>
                            <button type="submit" class="bg-black hover:bg-gray-800 text-white text-sm font-bold px-6 py-2 rounded-full transition shadow-md">Saqlash</button>
                        </div>
                        
                        <div class="p-6">
                            <!-- Text Area with Dynamic Live Theme Background -->
                            <div class="rounded-2xl p-4 transition-all duration-300 relative overflow-hidden"
                                 :style="editImagePreviews.length === 0 ? 'background: ' + (themes.find(t => t.key === editSelectedTheme)?.bg || '#111') : 'background: #f8fafc'">
                                
                                <div x-show="editImagePreviews.length === 0" class="absolute -top-3 -right-3 text-white/5 pointer-events-none select-none">
                                    <svg class="w-20 h-20 rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                                </div>

                                <div class="flex gap-3 relative z-10">
                                    <div class="shrink-0">
                                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="h-9 w-9 rounded-full object-cover border" :class="editImagePreviews.length === 0 ? 'border-white/30' : 'border-gray-200'">
                                    </div>
                                    <div class="flex-1">
                                        <textarea name="body" x-model="editingPost.body" rows="4" 
                                                  class="w-full border-none focus:ring-0 text-[16px] resize-none p-0 bg-transparent outline-none transition-colors"
                                                  :class="editImagePreviews.length === 0 ? 'text-white placeholder-white/60 font-semibold' : 'text-gray-900 placeholder-gray-400'"
                                                  placeholder="Matn yozing..." required></textarea>
                                    </div>
                                </div>

                                <!-- Image previews if present -->
                                <div x-show="editImagePreviews.length > 0" style="display:none;" class="relative mt-3 flex gap-2 overflow-x-auto pb-2 custom-scrollbar">
                                    <template x-for="(preview, index) in editImagePreviews" :key="index">
                                        <div class="relative shrink-0 w-24 h-24">
                                            <img :src="preview" class="w-full h-full rounded-xl object-cover shadow-sm border border-gray-100">
                                        </div>
                                    </template>
                                    <button @click="editImagePreviews = []; document.getElementById('editImageInput').value = ''" type="button" class="absolute top-1 right-1 bg-black/70 text-white rounded-full p-1.5 transition hover:bg-black shadow-md z-10"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>
                            </div>

                            <!-- Theme Selector (Shown when no images) -->
                            <div x-show="editImagePreviews.length === 0" class="mt-4 flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Mavzuni tanlang:</span>
                                <div class="flex items-center gap-2">
                                    <template x-for="t in themes" :key="t.key">
                                        <button type="button" 
                                                @click="editSelectedTheme = t.key"
                                                class="w-7 h-7 rounded-full transition-transform duration-200 border-2"
                                                :class="editSelectedTheme === t.key ? 'scale-125 border-gray-900 shadow-md ring-2 ring-indigo-400' : 'border-transparent opacity-80 hover:opacity-100 hover:scale-110'"
                                                :style="`background: ${t.bg};`"
                                                :title="t.name">
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Images Upload/Change Action -->
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <label class="cursor-pointer text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-xl transition inline-flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm font-bold" x-text="editImagePreviews.length > 0 ? editImagePreviews.length + ' ta rasm tanlangan' : 'Rasmlarni yangilash (ixtiyoriy)'"></span>
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
                    </form>
                </div>
            </div>
        </template>

    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        @keyframes slideUp { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }
        .slide-up-animation { animation: slideUp 0.3s ease-out forwards; }
        @keyframes zoomIn { 0% { opacity: 0; transform: scale(0.9); } 100% { opacity: 1; transform: scale(1); } }
        .animate-zoom-in { animation: zoomIn 0.2s ease-out forwards; }
    </style>
</x-app-layout>