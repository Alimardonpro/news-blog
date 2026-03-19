<x-app-layout>
    <div class="w-full h-[100dvh] lg:h-screen lg:p-6 bg-white lg:bg-slate-50/50 box-border flex flex-col"
         x-data="chatApp({{ Auth::id() }}, @js($users))"
         x-init="init()">

        <!-- MEDIA MODAL -->
        <div x-show="mediaModalOpen"
             x-transition.opacity.duration.200ms
             x-cloak
             class="fixed inset-0 z-[150] flex items-center justify-center bg-slate-950/90 backdrop-blur-md p-4">

            <button @click="closeModal()"
                    class="absolute top-5 right-5 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="max-w-[92vw] max-h-[88vh] flex flex-col items-center gap-4">
                <template x-if="modalMediaType === 'image'">
                    <img :src="modalMediaSrc" class="max-w-full max-h-[75vh] rounded-[1.5rem] shadow-2xl object-contain border border-white/10">
                </template>

                <template x-if="modalMediaType === 'video'">
                    <video :src="modalMediaSrc" controls autoplay class="max-w-full max-h-[75vh] rounded-[1.5rem] shadow-2xl border border-white/10"></video>
                </template>

                <a :href="modalMediaSrc"
                   download
                   class="inline-flex items-center gap-2 bg-white text-slate-900 px-5 py-3 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l4-4m-4 4l-4-4M5 21h14"></path>
                    </svg>
                    Saqlash
                </a>
            </div>
        </div>

        <div class="flex flex-row w-full h-full min-h-0 bg-white lg:rounded-[2rem] lg:shadow-[0_8px_30px_rgb(0,0,0,0.04)] lg:border border-slate-200/60 overflow-hidden relative">

            <!-- SIDEBAR -->
            <div id="sidebar"
                 :class="showChat ? 'hidden lg:flex' : 'flex'"
                 class="w-full lg:w-[380px] lg:min-w-[280px] lg:max-w-[50vw] shrink-0 flex-col bg-white h-full relative z-10 transition-all duration-300">

                <div class="p-4 lg:p-6 space-y-4 lg:space-y-6 shrink-0 border-b border-slate-50">
                    <div class="flex items-center justify-between">
                        <h1 class="text-xl lg:text-2xl font-black text-slate-900 tracking-tight">Xabarlar</h1>
                    </div>

                    <div class="relative group">
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.300ms="searchGlobal"
                               placeholder="Foydalanuvchilarni izlash..."
                               class="w-full bg-slate-100/70 border-none text-slate-900 text-sm rounded-2xl pl-10 lg:pl-12 pr-4 py-3 lg:py-3.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                        <div class="absolute left-3.5 lg:left-4 top-3 lg:top-3.5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-2 lg:p-3 space-y-1 custom-scrollbar bg-white lg:bg-slate-50/30">
                    <template x-for="u in filteredLocalUsers()" :key="'local-'+u.id">
                        <div @click="selectUser(u.id, u.name, u.avatar || null, u.unread_count || 0, u.username || '')"
                             :class="selectedUserId === u.id ? 'bg-slate-900 shadow-lg shadow-slate-900/10' : 'hover:bg-slate-50 border border-transparent hover:border-slate-200'"
                             class="flex gap-3 lg:gap-4 p-3 lg:p-3.5 cursor-pointer rounded-[1.2rem] lg:rounded-[1.5rem] transition-all duration-300 group">

                            <div class="relative flex-shrink-0">
                                <template x-if="u.avatar">
                                    <img :src="storageUrl(u.avatar)"
                                         class="w-12 h-12 rounded-[1rem] object-cover ring-2"
                                         :class="selectedUserId === u.id ? 'ring-slate-800' : 'ring-transparent'">
                                </template>

                                <template x-if="!u.avatar">
                                    <div class="w-12 h-12 rounded-[1rem] flex items-center justify-center font-black text-lg"
                                         :class="selectedUserId === u.id ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-500'"
                                         x-text="(u.name || '?').charAt(0)">
                                    </div>
                                </template>

                                <template x-if="onlineUsers.includes(u.id)">
                                    <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-4 rounded-full"
                                         :class="selectedUserId === u.id ? 'border-slate-900' : 'border-white'"></div>
                                </template>

                                <template x-if="unreadCounts[u.id] > 0">
                                    <div class="absolute -top-1.5 -right-1.5 min-w-[22px] h-[22px] px-1 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2"
                                         :class="selectedUserId === u.id ? 'border-slate-900' : 'border-white'"
                                         x-text="unreadCounts[u.id]">
                                    </div>
                                </template>
                            </div>

                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <div class="flex justify-between items-baseline mb-0.5">
                                    <h3 class="font-bold truncate text-sm"
                                        :class="selectedUserId === u.id ? 'text-white' : 'text-slate-900'"
                                        x-text="u.name">
                                    </h3>
                                </div>
                                <p class="text-xs truncate"
                                   :class="selectedUserId === u.id ? 'text-slate-400' : 'text-slate-500'">
                                    <template x-if="u.username">
                                        <span x-text="'@' + u.username"></span>
                                    </template>
                                    <template x-if="!u.username">
                                        <span>Chatni ochish...</span>
                                    </template>
                                </p>
                            </div>
                        </div>
                    </template>

                    <div x-show="filteredLocalUsers().length === 0 && globalUsers.length === 0"
                         class="p-6 text-center text-slate-500 text-sm flex flex-col items-center"
                         x-cloak>
                        <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                        </svg>
                        Hech kim topilmadi.
                    </div>

                    <div x-show="globalUsers.length > 0 && searchQuery.trim() !== ''"
                         class="mt-4 pt-4 border-t border-slate-200/60"
                         x-cloak>
                        <p class="text-[10px] font-black text-indigo-500 mb-3 px-3 uppercase tracking-widest">Global qidiruv</p>

                        <template x-for="gu in globalUsers" :key="'global-'+gu.id">
                            <div @click="selectUser(gu.id, gu.name, gu.avatar || null, 0, gu.username || '')"
                                 class="flex gap-3 lg:gap-4 p-3 lg:p-3.5 cursor-pointer hover:bg-indigo-50 border border-transparent hover:border-indigo-100 rounded-[1.2rem] lg:rounded-[1.5rem] transition-all duration-300 group mb-1">

                                <div class="relative flex-shrink-0">
                                    <template x-if="gu.avatar">
                                        <img :src="storageUrl(gu.avatar)" class="w-12 h-12 rounded-[1rem] object-cover shadow-sm">
                                    </template>
                                    <template x-if="!gu.avatar">
                                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-[1rem] flex items-center justify-center font-black text-lg shadow-sm"
                                             x-text="(gu.name || '?').charAt(0)"></div>
                                    </template>
                                </div>

                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <div class="flex justify-between items-baseline mb-0.5">
                                        <h3 class="font-bold truncate text-sm text-slate-900 group-hover:text-indigo-900" x-text="gu.name"></h3>
                                    </div>
                                    <p class="text-xs truncate text-indigo-500 font-medium" x-text="'@' + (gu.username || '')"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- RESIZER -->
            <div id="resizer" class="hidden lg:flex w-1.5 bg-slate-100 hover:bg-indigo-400 active:bg-indigo-600 cursor-col-resize transition-colors duration-200 z-20 items-center justify-center group">
                <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="w-0.5 h-0.5 bg-white rounded-full"></div>
                    <div class="w-0.5 h-0.5 bg-white rounded-full"></div>
                    <div class="w-0.5 h-0.5 bg-white rounded-full"></div>
                </div>
            </div>

            <!-- CHAT AREA -->
            <div :class="showChat ? 'flex' : 'hidden lg:flex'"
                 class="flex-1 flex-col bg-white lg:bg-[#f8fafc] h-full relative z-10 w-full lg:min-w-[300px]">

                <div x-show="!selectedUserId" class="flex-1 flex flex-col items-center justify-center bg-slate-50 lg:bg-transparent p-6 text-center">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 text-indigo-500 shadow-xl shadow-slate-200/50">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2 tracking-tight">Xabarlaringiz shu yerda</h3>
                    <p class="text-sm text-slate-500 max-w-sm">Chap paneldan foydalanuvchini tanlang yoki qidiruv orqali yangi suhbat boshlang.</p>
                </div>

                <div x-show="selectedUserId" class="flex-1 flex flex-col h-full w-full" x-cloak>
                    <!-- Header -->
                    <div class="bg-white/90 backdrop-blur-md border-b border-slate-100 p-3 lg:p-4 px-4 lg:px-6 flex justify-between items-center sticky top-0 z-20 shrink-0 shadow-sm lg:shadow-none">
                        <div class="flex items-center gap-3 lg:gap-4">
                            <button @click="closeChat()" class="lg:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>

                            <a :href="selectedUserUsername ? '/@' + selectedUserUsername : '#'" class="flex items-center gap-3 lg:gap-4 hover:opacity-80 transition-opacity">
                                <div class="relative">
                                    <template x-if="selectedUserAvatar">
                                        <img :src="storageUrl(selectedUserAvatar)" class="w-10 h-10 lg:w-11 lg:h-11 rounded-xl object-cover shadow-sm">
                                    </template>
                                    <template x-if="!selectedUserAvatar">
                                        <div class="w-10 h-10 lg:w-11 lg:h-11 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-sm"
                                             x-text="(selectedUserName || '?').charAt(0)">
                                        </div>
                                    </template>

                                    <template x-if="onlineUsers.includes(selectedUserId)">
                                        <div class="absolute -top-1 -right-1 w-3 h-3 lg:w-3.5 lg:h-3.5 bg-emerald-500 border-2 border-white rounded-full"></div>
                                    </template>
                                </div>

                                <div>
                                    <h2 class="font-black text-slate-900 leading-none text-base lg:text-lg" x-text="selectedUserName"></h2>

                                    <span x-show="onlineUsers.includes(selectedUserId)"
                                          class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mt-1 block"
                                          x-cloak>
                                        Hozir onlayn
                                    </span>
                                    <span x-show="!onlineUsers.includes(selectedUserId)"
                                          class="text-[10px] font-bold text-slate-400 tracking-wider mt-1 block lowercase"
                                          x-text="getLastSeenText(selectedUserId)"
                                          x-cloak>
                                    </span>
                                </div>
                            </a>
                        </div>

                        <div class="flex items-center gap-1 relative">
                            <button @click="showMessageSearch = !showMessageSearch; if(showMessageSearch) setTimeout(() => $refs.msgSearch.focus(), 80)"
                                    title="Xabarlardan izlash"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl transition-all"
                                    :class="showMessageSearch ? 'bg-indigo-50 text-indigo-600' : 'text-slate-400 hover:bg-slate-100 hover:text-indigo-600'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>

                            <button @click="showOptionsMenu = !showOptionsMenu"
                                    @click.away="showOptionsMenu = false"
                                    class="w-10 h-10 flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-900 rounded-xl transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>

                            <div x-show="showOptionsMenu"
                                 x-transition.opacity.duration.150ms
                                 x-cloak
                                 class="absolute top-12 right-0 w-52 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden z-50">
                                <button @click="clearChat(false); showOptionsMenu = false"
                                        class="w-full text-left px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 flex items-center gap-3 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M10 11v6M14 11v6M6 7l1 12a2 2 0 002 2h6a2 2 0 002-2l1-12M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"></path>
                                    </svg>
                                    Tarixni tozalash
                                </button>

                                <button @click="clearChat(true); showOptionsMenu = false"
                                        class="w-full text-left px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 flex items-center gap-3 transition-colors border-t border-slate-50">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Chatni o‘chirish
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Chat search -->
                    <div x-show="showMessageSearch"
                         x-transition
                         x-cloak
                         class="bg-slate-50 border-b border-slate-200/60 p-2 lg:p-3 px-4 lg:px-6 z-10 relative">
                        <div class="relative">
                            <input type="text"
                                   x-model="messageSearchQuery"
                                   x-ref="msgSearch"
                                   placeholder="Ushbu chatdan xabarni izlash..."
                                   class="w-full bg-white border border-slate-200 text-sm text-slate-900 rounded-xl pl-10 pr-10 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 outline-none transition-all shadow-sm">
                            <div class="absolute left-3 top-2.5 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button x-show="messageSearchQuery.length > 0"
                                    @click="messageSearchQuery = ''; $refs.msgSearch.focus()"
                                    class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div id="chat-messages" class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-4 lg:space-y-5 bg-slate-50 lg:bg-transparent custom-scrollbar relative">
                        <div x-show="isLoading" class="flex justify-center my-4" x-cloak>
                            <svg class="animate-spin h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <div x-show="!isLoading && filteredMessages().length === 0" class="flex flex-col items-center justify-center h-full text-slate-400" x-cloak>
                            <span class="text-sm font-medium bg-white px-4 py-2 rounded-full shadow-sm">Xabarlar yo‘q. Birinchi bo‘lib yozing 👋</span>
                        </div>

                        <template x-for="msg in filteredMessages()" :key="msg.id">
                            <div class="flex gap-2 lg:gap-3 max-w-[95%] lg:max-w-[85%] group/msg"
                                 :class="msg.sender_id === myId ? 'ml-auto flex-row-reverse' : ''">

                                <div class="relative flex flex-col gap-2">
                                    <!-- Image -->
                                    <template x-if="msg.image">
                                        <div class="overflow-hidden rounded-[1.3rem] border border-slate-200 bg-white shadow-sm max-w-[320px]">
                                            <img :src="storageUrl(msg.image)"
                                                 @click="openModal(storageUrl(msg.image), 'image')"
                                                 class="w-full max-h-[320px] object-cover cursor-pointer hover:scale-[1.02] transition-transform">
                                        </div>
                                    </template>

                                    <!-- Video -->
                                    <template x-if="msg.video">
                                        <div class="overflow-hidden rounded-[1.3rem] border border-slate-200 bg-white shadow-sm max-w-[360px]"
                                             x-data="{ fileSize: '...' }"
                                             x-init="
                                                fetch(storageUrl(msg.video), { method: 'HEAD' })
                                                    .then(r => {
                                                        let b = r.headers.get('content-length');
                                                        if (b) fileSize = (b / (1024 * 1024)).toFixed(2) + ' MB';
                                                        else fileSize = '';
                                                    })
                                                    .catch(() => fileSize = '');
                                             ">

                                            <video x-ref="inlineVideo" controls class="w-full max-h-[320px] bg-black">
                                                <source :src="storageUrl(msg.video)">
                                            </video>

                                            <div class="p-2.5 lg:p-3 border-t border-slate-100 bg-white flex justify-between items-center">
                                                <button @click="$refs.inlineVideo.pause(); openModal(storageUrl(msg.video), 'video')"
                                                        class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                                    </svg>
                                                    Katta ochish
                                                </button>

                                                <span x-show="fileSize" x-text="fileSize" class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-cloak></span>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Audio -->
                                    <template x-if="msg.audio">
                                        <div class="relative p-3 lg:p-4 shadow-sm flex items-center gap-3 lg:gap-4 min-w-[240px] max-w-[320px]"
                                             x-data="{ 
                                                playing: false, 
                                                duration: '0:00', 
                                                totalDuration: '0:00',
                                                progress: 0,
                                                fileSize: '...'
                                             }"
                                             x-init="
                                                fetch(storageUrl(msg.audio), { method: 'HEAD' })
                                                    .then(r => {
                                                        let b = r.headers.get('content-length');
                                                        if (b) fileSize = (b / (1024 * 1024)).toFixed(2) + ' MB';
                                                        else fileSize = '';
                                                    })
                                                    .catch(() => fileSize = '');
                                             "
                                             :class="msg.sender_id === myId
                                                ? 'bg-gradient-to-br from-indigo-500 to-blue-600 rounded-[1.2rem] lg:rounded-[1.5rem] rounded-br-none shadow-md shadow-indigo-500/20 text-white border-transparent'
                                                : 'bg-white border border-slate-200 text-slate-700 rounded-[1.2rem] lg:rounded-[1.5rem] rounded-bl-none'">

                                            <button @click="if(playing) { $refs.audioPlayer.pause(); playing = false; } else { $refs.audioPlayer.play(); playing = true; }"
                                                    class="w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center shrink-0 shadow-sm transition-transform active:scale-95"
                                                    :class="msg.sender_id === myId ? 'bg-white text-indigo-600' : 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100'">
                                                <svg x-show="!playing" class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                                <svg x-show="playing" x-cloak class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                                </svg>
                                            </button>

                                            <div class="flex-1 flex flex-col gap-1.5">
                                                <div class="flex items-end gap-[2px] h-6 lg:h-7 opacity-70">
                                                    <template x-for="i in 30" :key="i">
                                                        <div class="w-[2.5px] bg-current rounded-full transition-all duration-75"
                                                             :style="'height:' + (20 + Math.random() * 80) + '%; opacity:' + (progress > (i * 3.33) ? '1' : '0.3')">
                                                        </div>
                                                    </template>
                                                </div>

                                                <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider opacity-80">
                                                    <div class="flex items-center gap-2">
                                                        <span x-text="playing ? duration : totalDuration"></span>

                                                        <template x-if="fileSize">
                                                            <div class="flex items-center gap-2">
                                                                <span class="w-1 h-1 bg-current rounded-full opacity-50"></span>
                                                                <span x-text="fileSize" class="opacity-75"></span>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <a :href="storageUrl(msg.audio)" download class="hover:text-indigo-200 transition-colors" title="Yuklab olish">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>

                                            <audio x-ref="audioPlayer"
                                                   :src="storageUrl(msg.audio)"
                                                   @loadedmetadata="
                                                        totalDuration = formatAudioTime($el.duration);
                                                        duration = formatAudioTime(0);
                                                   "
                                                   @timeupdate="
                                                        progress = $el.duration ? ($el.currentTime / $el.duration) * 100 : 0;
                                                        duration = formatAudioTime($el.currentTime);
                                                   "
                                                   @ended="
                                                        playing = false;
                                                        progress = 0;
                                                        duration = formatAudioTime(0);
                                                   "
                                                   class="hidden">
                                            </audio>
                                        </div>
                                    </template>

                                    <!-- Text -->
                                    <template x-if="msg.message">
                                        <div class="p-3 lg:p-4 shadow-sm leading-relaxed text-[14.5px] lg:text-[15px] relative"
                                             :class="msg.sender_id === myId
                                                ? 'bg-gradient-to-br from-indigo-500 to-blue-600 rounded-[1.2rem] lg:rounded-[1.5rem] rounded-br-none shadow-md shadow-indigo-500/20 text-white'
                                                : 'bg-white border border-slate-200 rounded-[1.2rem] lg:rounded-[1.5rem] rounded-bl-none text-slate-700'">

                                            <p x-text="msg.message" class="break-words whitespace-pre-wrap"></p>

                                            <div class="flex items-center gap-1 mt-1.5"
                                                 :class="msg.sender_id === myId ? 'justify-end' : ''">
                                                <span class="text-[9px] lg:text-[10px] font-bold"
                                                      :class="msg.sender_id === myId ? 'text-indigo-100' : 'text-slate-400'"
                                                      x-text="formatTime(msg.created_at)">
                                                </span>

                                                <template x-if="msg.sender_id === myId">
                                                    <div class="flex items-center">
                                                        <!-- 1 chek -->
                                                        <template x-if="!msg.is_read">
                                                            <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5l3 3L16 7.5"></path>
                                                            </svg>
                                                        </template>

                                                        <!-- 2 chek -->
                                                        <template x-if="msg.is_read">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2 12.5l3 3L13 7.5"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5l3 3L20 7.5"></path>
                                                            </svg>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Time for media-only messages -->
                                    <template x-if="!msg.message && (msg.image || msg.video || msg.audio)">
                                        <div class="px-1 flex items-center gap-1"
                                             :class="msg.sender_id === myId ? 'justify-end' : ''">
                                            <span class="text-[10px] font-bold text-slate-400" x-text="formatTime(msg.created_at)"></span>

                                            <template x-if="msg.sender_id === myId">
                                                <div class="flex items-center">
                                                    <template x-if="!msg.is_read">
                                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5l3 3L16 7.5"></path>
                                                        </svg>
                                                    </template>

                                                    <template x-if="msg.is_read">
                                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12.5l3 3L13 7.5"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5l3 3L20 7.5"></path>
                                                        </svg>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Actions -->
                                    <div class="absolute top-1/2 -translate-y-1/2 opacity-0 group-hover/msg:opacity-100 flex items-center gap-1 transition-opacity"
                                         :class="msg.sender_id === myId ? 'right-full mr-2' : 'left-full ml-2'">

                                        <template x-if="msg.sender_id === myId && msg.message">
                                            <button @click="startEdit(msg)"
                                                    title="Tahrirlash"
                                                    class="p-1.5 bg-white hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-full transition shadow-sm border border-slate-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </button>
                                        </template>

                                        <button @click="deleteMsg(msg.id)"
                                                title="O‘chirish"
                                                class="p-1.5 bg-white hover:bg-red-50 text-slate-400 hover:text-red-600 rounded-full transition shadow-sm border border-slate-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Footer -->
                    <div class="bg-white border-t border-slate-100 shrink-0 pb-safe">
                        <div x-show="editingMsgId" x-transition x-cloak class="px-4 lg:px-6 py-3 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-indigo-600 text-xs font-bold uppercase tracking-wider">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                <span>Xabar tahrirlanmoqda</span>
                            </div>
                            <button @click="cancelEdit()" class="text-slate-400 hover:text-red-500 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div x-show="selectedFile && !isRecording" x-transition x-cloak class="px-4 lg:px-6 pt-3">
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <template x-if="mediaType === 'image' && previewUrl">
                                        <img :src="previewUrl" class="w-14 h-14 rounded-xl object-cover border border-slate-200">
                                    </template>

                                    <template x-if="mediaType === 'video'">
                                        <div class="w-14 h-14 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="mediaType === 'audio'">
                                        <div class="w-14 h-14 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-2v13"></path>
                                                <circle cx="6" cy="18" r="3"></circle>
                                                <circle cx="18" cy="16" r="3"></circle>
                                            </svg>
                                        </div>
                                    </template>

                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate" x-text="selectedFileName"></p>
                                        <p class="text-xs text-slate-500" x-text="mediaTypeLabel()"></p>
                                    </div>
                                </div>

                                <button type="button" @click="clearSelectedFile()" class="w-9 h-9 rounded-full hover:bg-red-50 text-slate-400 hover:text-red-500 flex items-center justify-center transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div x-show="isRecording" x-transition x-cloak class="px-4 lg:px-6 pt-3">
                            <div class="bg-red-50 border border-red-100 rounded-2xl p-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 text-red-600">
                                    <span class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></span>
                                    <span class="text-sm font-bold uppercase tracking-wider">Ovoz yozilmoqda...</span>
                                    <span class="text-sm font-black" x-text="recordingTimeText"></span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" @click="cancelRecording()" class="px-3 py-2 text-xs font-bold rounded-xl bg-white border border-red-100 text-red-500 hover:bg-red-50 transition">
                                        Bekor qilish
                                    </button>
                                    <button type="button" @click="stopRecording()" class="px-3 py-2 text-xs font-bold rounded-xl bg-red-500 text-white hover:bg-red-600 transition">
                                        Yuborish
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 mb-2 lg:p-4">
                            <form @submit.prevent="sendMessage"
                                  class="flex items-center gap-2 lg:gap-3 bg-slate-50 border border-slate-200 p-1 lg:p-1.5 rounded-[1.2rem] lg:rounded-2xl focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100 transition-all duration-300 shadow-sm">

                                <input type="file"
                                       x-ref="mediaInput"
                                       @change="handleMediaUpload"
                                       class="hidden"
                                       accept="image/*,video/*,audio/*">

                                <button type="button"
                                        @click="$refs.mediaInput.click()"
                                        class="w-10 h-10 lg:w-11 lg:h-11 shrink-0 flex items-center justify-center rounded-xl text-slate-500 hover:bg-white hover:text-indigo-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>

                                <input x-ref="msgInput"
                                       type="text"
                                       x-model="newMessage"
                                       :disabled="isRecording"
                                       :placeholder="editingMsgId ? 'Xabarni tahrirlash...' : 'Xabar yozing...'"
                                       class="flex-1 bg-transparent border-none focus:ring-0 text-[14px] lg:text-[15px] text-slate-900 py-2 outline-none pl-1 disabled:opacity-50">

                                <template x-if="editingMsgId">
                                    <button type="button"
                                            @click="cancelEdit()"
                                            title="Bekor qilish"
                                            class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </template>

                                <template x-if="!editingMsgId && !newMessage.trim() && !selectedFile && !isRecording">
                                    <button type="button"
                                            @click="startRecording()"
                                            class="w-10 h-10 lg:w-11 lg:h-11 shrink-0 flex items-center justify-center rounded-xl text-slate-500 hover:bg-white hover:text-indigo-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v3m-4 0h8M9 5a3 3 0 116 0v7a3 3 0 11-6 0V5z"></path>
                                        </svg>
                                    </button>
                                </template>

                                <button type="submit"
                                        :disabled="isSending || isRecording || (!editingMsgId && newMessage.trim() === '' && !selectedFile)"
                                        class="w-10 h-10 lg:w-11 lg:h-11 shrink-0 flex items-center justify-center text-white rounded-[1rem] lg:rounded-xl shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="editingMsgId ? 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-200' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200 active:scale-95'">

                                    <svg x-show="!isSending && !editingMsgId" class="w-5 h-5 transform rotate-45 -translate-y-0.5 translate-x-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                                    </svg>

                                    <svg x-show="!isSending && editingMsgId" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>

                                    <svg x-show="isSending" class="animate-spin h-5 w-5 text-white" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .no-select { user-select: none; }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 1rem); }
        audio::-webkit-media-controls-panel { background-color: rgba(255,255,255,.85); }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        function chatApp(myId, initialUsers = []) {
            return {
                myId: myId,
                initialUsers: Array.isArray(initialUsers) ? initialUsers : [],
                showChat: false,

                selectedUserId: null,
                selectedUserName: '',
                selectedUserAvatar: '',
                selectedUserUsername: '',

                showOptionsMenu: false,
                showMessageSearch: false,
                messageSearchQuery: '',

                messages: [],
                newMessage: '',
                searchQuery: '',
                globalUsers: [],

                isLoading: false,
                isSending: false,
                editingMsgId: null,

                unreadCounts: {},
                onlineUsers: [],

                selectedFile: null,
                selectedFileName: '',
                mediaType: null,
                previewUrl: '',

                isRecording: false,
                recordingTime: 0,
                recordingInterval: null,
                mediaRecorder: null,
                audioChunks: [],
                cancelRecordingUpload: false,

                mediaModalOpen: false,
                modalMediaSrc: '',
                modalMediaType: '',

                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] =
                        document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    this.initialUsers.forEach(u => {
                        this.unreadCounts[u.id] = u.unread_count || 0;
                    });

                    if (window.Echo) {
                        window.Echo.private(`chat.${this.myId}`)
                            .listen('MessageSent', (e) => {
                                const incoming = e.message;

                                if (this.selectedUserId === incoming.sender_id) {
                                    const exists = this.messages.some(m => Number(m.id) === Number(incoming.id));
                                    if (!exists) {
                                        this.messages.push(incoming);
                                        this.scrollToBottom();
                                    }

                                    axios.get(`/chat/${this.selectedUserId}/messages`)
                                        .then((res) => {
                                            this.messages = Array.isArray(res.data) ? res.data : [];
                                            this.scrollToBottom();
                                        })
                                        .catch((err) => {
                                            console.error('Read qilishda xatolik:', err);
                                        });
                                } else {
                                    if (this.unreadCounts[incoming.sender_id] !== undefined) {
                                        this.unreadCounts[incoming.sender_id]++;
                                    } else {
                                        this.unreadCounts[incoming.sender_id] = 1;
                                    }
                                }
                            })
                            .listen('.MessageRead', (e) => {
                                const msg = this.messages.find(m => Number(m.id) === Number(e.message_id));
                                if (msg) {
                                    msg.is_read = true;
                                }
                            });

                        window.Echo.join('online')
                            .here((users) => {
                                this.onlineUsers = users.map(u => u.id);
                            })
                            .joining((user) => {
                                if (!this.onlineUsers.includes(user.id)) {
                                    this.onlineUsers.push(user.id);
                                }
                            })
                            .leaving((user) => {
                                this.onlineUsers = this.onlineUsers.filter(id => id !== user.id);
                                this.updateLastSeenLocal(user.id);
                            });
                    }

                    @if(isset($startUser))
                        this.selectUser(
                            {{ $startUser->id }},
                            `{!! addslashes($startUser->name) !!}`,
                            `{!! $startUser->avatar !!}`,
                            0,
                            `{!! $startUser->username !!}`
                        );
                    @endif
                },

                filteredLocalUsers() {
                    const q = (this.searchQuery || '').trim().toLowerCase();
                    if (!q) return this.initialUsers;

                    return this.initialUsers.filter(u => {
                        const name = (u.name || '').toLowerCase();
                        const username = (u.username || '').toLowerCase();
                        return name.includes(q) || username.includes(q);
                    });
                },

                filteredMessages() {
                    const q = (this.messageSearchQuery || '').trim().toLowerCase();
                    if (!q) return this.messages;

                    return this.messages.filter(msg => {
                        const text = (msg.message || '').toLowerCase();
                        return text.includes(q);
                    });
                },

                mediaTypeLabel() {
                    if (this.mediaType === 'image') return 'Rasm tayyor';
                    if (this.mediaType === 'video') return 'Video tayyor';
                    if (this.mediaType === 'audio') return 'Audio tayyor';
                    return 'Fayl tanlangan';
                },

                get recordingTimeText() {
                    const m = Math.floor(this.recordingTime / 60).toString().padStart(2, '0');
                    const s = (this.recordingTime % 60).toString().padStart(2, '0');
                    return `${m}:${s}`;
                },

                storageUrl(path) {
                    if (!path) return '';
                    if (typeof path !== 'string') return '';
                    if (path.startsWith('http://') || path.startsWith('https://')) return path;
                    if (path.startsWith('/storage/')) return path;
                    if (path.startsWith('storage/')) return '/' + path;
                    return '/storage/' + path.replace(/^\/+/, '');
                },

                formatAudioTime(seconds) {
                    if (!isFinite(seconds) || seconds < 0) return '0:00';
                    const mins = Math.floor(seconds / 60);
                    const secs = Math.floor(seconds % 60).toString().padStart(2, '0');
                    return `${mins}:${secs}`;
                },

                async searchGlobal() {
                    const q = (this.searchQuery || '').trim();
                    if (q.length < 2) {
                        this.globalUsers = [];
                        return;
                    }

                    try {
                        const response = await axios.get(`/chat/search?q=${encodeURIComponent(q)}`);
                        const localIds = new Set(this.initialUsers.map(u => Number(u.id)));
                        this.globalUsers = (response.data || []).filter(u => !localIds.has(Number(u.id)));
                    } catch (error) {
                        console.error('Qidiruvda xatolik:', error);
                        this.globalUsers = [];
                    }
                },

                selectUser(id, name, avatar = null, unread = 0, username = '') {
                    this.selectedUserId = id;
                    this.selectedUserName = name || '';
                    this.selectedUserAvatar = avatar || '';
                    this.selectedUserUsername = username || '';

                    this.showChat = true;
                    this.showOptionsMenu = false;
                    this.showMessageSearch = false;
                    this.messageSearchQuery = '';
                    this.editingMsgId = null;
                    this.newMessage = '';
                    this.clearSelectedFile(false);

                    if (this.unreadCounts[id] !== undefined) {
                        this.unreadCounts[id] = 0;
                    }

                    this.fetchMessages(true);

                    const newurl = window.location.protocol + '//' + window.location.host + window.location.pathname + '?user_id=' + id;
                    window.history.pushState({ path: newurl }, '', newurl);
                },

                closeChat() {
                    this.showChat = false;
                    this.selectedUserId = null;
                    this.selectedUserName = '';
                    this.selectedUserAvatar = '';
                    this.selectedUserUsername = '';
                    this.editingMsgId = null;
                    this.showOptionsMenu = false;
                    this.showMessageSearch = false;
                    this.messageSearchQuery = '';
                    this.newMessage = '';
                    this.clearSelectedFile(false);

                    const newurl = window.location.protocol + '//' + window.location.host + window.location.pathname;
                    window.history.pushState({ path: newurl }, '', newurl);
                },

                async fetchMessages(showLoader = true) {
                    if (!this.selectedUserId) return;

                    if (showLoader) this.isLoading = true;

                    try {
                        const oldLength = this.messages.length;
                        const response = await axios.get(`/chat/${this.selectedUserId}/messages`);
                        this.messages = Array.isArray(response.data) ? response.data : [];

                        if (showLoader || oldLength !== this.messages.length) {
                            this.scrollToBottom();
                        }
                    } catch (error) {
                        console.error('Xabarlarni olishda xatolik:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                startEdit(msg) {
                    this.editingMsgId = msg.id;
                    this.newMessage = msg.message || '';
                    this.clearSelectedFile(false);

                    setTimeout(() => {
                        if (this.$refs.msgInput) this.$refs.msgInput.focus();
                    }, 80);
                },

                cancelEdit() {
                    this.editingMsgId = null;
                    this.newMessage = '';
                },

                async updateMessageRequest(id, payload) {
                    try {
                        return await axios.put(`/chat/messages/${id}`, payload);
                    } catch (e1) {
                        try {
                            return await axios.patch(`/chat/message/${id}`, payload);
                        } catch (e2) {
                            throw e2;
                        }
                    }
                },

                async deleteMessageRequest(id) {
                    try {
                        return await axios.delete(`/chat/messages/${id}`);
                    } catch (e1) {
                        try {
                            return await axios.delete(`/chat/message/${id}`);
                        } catch (e2) {
                            throw e2;
                        }
                    }
                },

                async deleteMsg(id) {
                    if (!confirm("Ushbu xabarni o‘chirishni xohlaysizmi?")) return;

                    try {
                        await this.deleteMessageRequest(id);
                        this.messages = this.messages.filter(m => m.id !== id);
                    } catch (error) {
                        console.error(error);
                        alert("Xatolik yuz berdi.");
                    }
                },

                async clearChat(deleteCompletely = false) {
                    const text = deleteCompletely
                        ? "Chatni butunlay o‘chirmoqchimisiz?"
                        : "Suhbat tarixini tozalamoqchimisiz?";

                    if (!confirm(text)) return;

                    try {
                        await axios.delete(`/chat/${this.selectedUserId}/clear`);
                        this.messages = [];

                        if (deleteCompletely) {
                            window.location.replace('/chat');
                        }
                    } catch (error) {
                        console.error('Chatni tozalashda xatolik:', error);
                        alert("Xatolik yuz berdi.");
                    }
                },

                async sendMessage() {
                    if (this.isSending || this.isRecording) return;

                    const hasText = (this.newMessage || '').trim() !== '';
                    const hasFile = !!this.selectedFile;

                    if (!this.editingMsgId && !hasText && !hasFile) return;
                    if (this.editingMsgId && !hasText) return;

                    this.isSending = true;

                    try {
                        if (this.editingMsgId) {
                            await this.updateMessageRequest(this.editingMsgId, { message: this.newMessage });
                            this.cancelEdit();
                            await this.fetchMessages(false);
                        } else {
                            const fd = new FormData();

                            if (hasText) fd.append('message', this.newMessage);

                            if (hasFile) {
                                const field = this.mediaType === 'audio'
                                    ? 'audio'
                                    : (this.mediaType === 'video' ? 'video' : 'image');

                                fd.append(field, this.selectedFile, this.selectedFileName || 'file');
                            }

                            await axios.post(`/chat/${this.selectedUserId}/send`, fd, {
                                headers: { 'Content-Type': 'multipart/form-data' }
                            });

                            this.newMessage = '';
                            this.clearSelectedFile(false);
                            await this.fetchMessages(true);
                        }
                    } catch (error) {
                        console.error('Yuborishda xatolik:', error);
                        alert("Xabar yuborilmadi.");
                    } finally {
                        this.isSending = false;
                    }
                },

                handleMediaUpload(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    this.clearSelectedFile(false);

                    this.selectedFile = file;
                    this.selectedFileName = file.name || 'file';

                    if (file.type.startsWith('image/')) {
                        this.mediaType = 'image';
                        this.previewUrl = URL.createObjectURL(file);
                    } else if (file.type.startsWith('video/')) {
                        this.mediaType = 'video';
                    } else {
                        this.mediaType = 'audio';
                    }

                    if (this.$refs.mediaInput) {
                        this.$refs.mediaInput.value = '';
                    }
                },

                clearSelectedFile(resetInput = true) {
                    if (this.previewUrl) {
                        URL.revokeObjectURL(this.previewUrl);
                    }

                    this.selectedFile = null;
                    this.selectedFileName = '';
                    this.mediaType = null;
                    this.previewUrl = '';

                    if (resetInput && this.$refs.mediaInput) {
                        this.$refs.mediaInput.value = '';
                    }
                },

                async startRecording() {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

                        this.isRecording = true;
                        this.cancelRecordingUpload = false;
                        this.audioChunks = [];
                        this.recordingTime = 0;

                        this.mediaRecorder = new MediaRecorder(stream);

                        this.mediaRecorder.ondataavailable = (e) => {
                            if (e.data && e.data.size > 0) {
                                this.audioChunks.push(e.data);
                            }
                        };

                        this.mediaRecorder.onstop = async () => {
                            this.cleanupRecorder();

                            if (this.cancelRecordingUpload) return;
                            if (!this.audioChunks.length) return;

                            const blob = new Blob(this.audioChunks, { type: 'audio/webm' });
                            const file = new File([blob], `voice-${Date.now()}.webm`, { type: 'audio/webm' });

                            this.selectedFile = file;
                            this.selectedFileName = file.name;
                            this.mediaType = 'audio';

                            await this.sendMessage();
                        };

                        this.mediaRecorder.start();

                        this.recordingInterval = setInterval(() => {
                            this.recordingTime++;
                        }, 1000);

                    } catch (error) {
                        console.error(error);
                        alert("Mikrofon ishlamadi yoki ruxsat berilmadi.");
                    }
                },

                stopRecording() {
                    if (!this.mediaRecorder || !this.isRecording) return;
                    this.mediaRecorder.stop();
                    this.isRecording = false;
                },

                cancelRecording() {
                    if (!this.mediaRecorder || !this.isRecording) return;
                    this.cancelRecordingUpload = true;
                    this.mediaRecorder.stop();
                    this.isRecording = false;
                },

                cleanupRecorder() {
                    clearInterval(this.recordingInterval);
                    this.recordingInterval = null;

                    if (this.mediaRecorder && this.mediaRecorder.stream) {
                        this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
                    }
                },

                openModal(src, type) {
                    this.modalMediaSrc = src;
                    this.modalMediaType = type;
                    this.mediaModalOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeModal() {
                    this.mediaModalOpen = false;
                    this.modalMediaSrc = '';
                    this.modalMediaType = '';
                    document.body.classList.remove('overflow-hidden');
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const chatBox = document.getElementById('chat-messages');
                        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
                    }, 80);
                },

                formatTime(datetime) {
                    if (!datetime) return '';
                    const date = new Date(datetime);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                },

                updateLastSeenLocal(userId) {
                    let user = this.initialUsers.find(u => u.id === userId);
                    if (user) {
                        user.last_seen = new Date().toISOString();
                    }
                },

                getLastSeenText(id) {
                    let u = this.initialUsers.find(user => user.id === id) || this.globalUsers.find(user => user.id === id);

                    if (!u || !u.last_seen) return "oxirgi marta yaqinda";

                    let dateString = u.last_seen;
                    if (!dateString.includes('T')) dateString = dateString.replace(' ', 'T');
                    if (!dateString.endsWith('Z')) dateString += 'Z';

                    const date = new Date(dateString);
                    const now = new Date();
                    const diffMs = now - date;

                    if (diffMs <= 0) return "yaqinda onlayn edi";

                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMins / 60);
                    const diffDays = Math.floor(diffHours / 24);

                    if (diffMins < 1) return "yaqinda onlayn edi";
                    if (diffMins < 60) return `oxirgi marta ${diffMins} daqiqa oldin`;
                    if (diffHours < 24) return `oxirgi marta ${diffHours} soat oldin`;
                    if (diffDays === 1) return "oxirgi marta kecha";

                    return `oxirgi marta ${date.toLocaleDateString('uz-UZ', { day: 'numeric', month: 'short' })}`;
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            const resizer = document.getElementById('resizer');
            const sidebar = document.getElementById('sidebar');
            let isResizing = false;

            if (resizer && sidebar && window.innerWidth > 1024) {
                resizer.addEventListener('mousedown', function () {
                    isResizing = true;
                    document.body.classList.add('no-select');
                    document.body.style.cursor = 'col-resize';
                });

                document.addEventListener('mousemove', function (e) {
                    if (!isResizing) return;

                    const containerOffset = sidebar.parentElement.getBoundingClientRect().left;
                    let newWidth = e.clientX - containerOffset;

                    if (newWidth < 280) newWidth = 280;
                    if (newWidth > window.innerWidth / 2) newWidth = window.innerWidth / 2;

                    sidebar.style.width = newWidth + 'px';
                });

                document.addEventListener('mouseup', function () {
                    if (isResizing) {
                        isResizing = false;
                        document.body.classList.remove('no-select');
                        document.body.style.cursor = '';
                    }
                });
            }
        });
    </script>
</x-app-layout>