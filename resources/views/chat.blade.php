<x-app-layout>
    <!-- Asosiy konteyner Alpine.js ma'lumotlari bilan -->
    <div class="w-full h-[100dvh] lg:h-screen lg:p-6 bg-white lg:bg-slate-50/50 box-border flex flex-col" 
         x-data="chatApp({{ Auth::id() }})" 
         x-init="init()">
        
        <div class="flex flex-row w-full h-full min-h-0 bg-white lg:rounded-[2rem] lg:shadow-[0_8px_30px_rgb(0,0,0,0.04)] lg:border border-slate-200/60 overflow-hidden relative">

            <!-- =============================== -->
            <!-- CHAP PANEL (USER LIST) -->
            <!-- =============================== -->
            <div id="sidebar" 
                 :class="showChat ? 'hidden lg:flex' : 'flex'"
                 class="w-full lg:w-[380px] lg:min-w-[280px] lg:max-w-[50vw] shrink-0 flex-col bg-white h-full relative z-10 transition-all duration-300">
                
                <div class="p-4 lg:p-6 space-y-4 lg:space-y-6 shrink-0 border-b border-slate-50">
                    <div class="flex items-center justify-between">
                        <h1 class="text-xl lg:text-2xl font-black text-slate-900 tracking-tight">Xabarlar</h1>
                    </div>
                    
                    <div class="relative group">
                        <input type="text" x-model="searchQuery" @input.debounce.500ms="searchGlobal" placeholder="Foydalanuvchilarni izlash..." class="w-full bg-slate-100/70 border-none text-slate-900 text-sm rounded-2xl pl-10 lg:pl-12 pr-4 py-3 lg:py-3.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                        <div class="absolute left-3.5 lg:left-4 top-3 lg:top-3.5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-2 lg:p-3 space-y-1 custom-scrollbar bg-white lg:bg-slate-50/30">
                    
                    <!-- Mahalliy foydalanuvchilar -->
                    @forelse($users as $u)
                        <div @click="selectUser({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->avatar ? asset('storage/' . $u->avatar) : null }}', '{{ $u->username }}')" 
                             x-show="searchQuery.trim() === '' || '{{ strtolower($u->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($u->username) }}'.includes(searchQuery.toLowerCase())"
                             :class="selectedUserId === {{ $u->id }} ? 'bg-slate-900 shadow-lg shadow-slate-900/10' : 'hover:bg-slate-50 border border-transparent hover:border-slate-200'"
                             class="flex gap-3 lg:gap-4 p-3 lg:p-3.5 cursor-pointer rounded-[1.2rem] lg:rounded-[1.5rem] transition-all duration-300 group">
                            
                            <div class="relative flex-shrink-0">
                                @if($u->avatar)
                                    <img src="{{ asset('storage/' . $u->avatar) }}" class="w-12 h-12 rounded-[1rem] object-cover ring-2" :class="selectedUserId === {{ $u->id }} ? 'ring-slate-800' : 'ring-transparent'">
                                @else
                                    <div class="w-12 h-12 rounded-[1rem] flex items-center justify-center font-black text-lg" :class="selectedUserId === {{ $u->id }} ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-500'">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-4 rounded-full" :class="selectedUserId === {{ $u->id }} ? 'border-slate-900' : 'border-white'"></div>
                            </div>
                            
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <div class="flex justify-between items-baseline mb-0.5">
                                    <h3 class="font-bold truncate text-sm" :class="selectedUserId === {{ $u->id }} ? 'text-white' : 'text-slate-900'">{{ $u->name }}</h3>
                                </div>
                                <p class="text-xs truncate" :class="selectedUserId === {{ $u->id }} ? 'text-slate-400' : 'text-slate-500'">Chatni ochish...</p>
                            </div>
                        </div>
                    @empty
                        <div x-show="searchQuery.trim() === ''" class="p-6 text-center text-slate-500 text-sm flex flex-col items-center">
                            <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                            Siz hali hech kim bilan yozishmagansiz.<br>Yuqoridan do'stlaringizni qidiring.
                        </div>
                    @endforelse

                    <!-- Global Qidiruv -->
                    <div x-show="globalUsers.length > 0 && searchQuery.trim() !== ''" class="mt-4 pt-4 border-t border-slate-200/60" style="display: none;">
                        <p class="text-[10px] font-black text-indigo-500 mb-3 px-3 uppercase tracking-widest">Global qidiruv</p>
                        
                        <template x-for="gu in globalUsers" :key="'g' + gu.id">
                            <div @click="selectUser(gu.id, gu.name, gu.avatar ? '/storage/' + gu.avatar : null, gu.username)" 
                                 class="flex gap-3 lg:gap-4 p-3 lg:p-3.5 cursor-pointer hover:bg-indigo-50 border border-transparent hover:border-indigo-100 rounded-[1.2rem] lg:rounded-[1.5rem] transition-all duration-300 group mb-1">
                                
                                <div class="relative flex-shrink-0">
                                    <template x-if="gu.avatar">
                                        <img :src="'/storage/' + gu.avatar" class="w-12 h-12 rounded-[1rem] object-cover shadow-sm">
                                    </template>
                                    <template x-if="!gu.avatar">
                                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-[1rem] flex items-center justify-center font-black text-lg shadow-sm" x-text="gu.name.charAt(0)"></div>
                                    </template>
                                </div>
                                
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <div class="flex justify-between items-baseline mb-0.5">
                                        <h3 class="font-bold truncate text-sm text-slate-900 group-hover:text-indigo-900" x-text="gu.name"></h3>
                                    </div>
                                    <p class="text-xs truncate text-indigo-500 font-medium" x-text="'@' + gu.username"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                </div>
            </div>

            <!-- =============================== -->
            <!-- RESIZER -->
            <!-- =============================== -->
            <div id="resizer" class="hidden lg:flex w-1.5 bg-slate-100 hover:bg-indigo-400 active:bg-indigo-600 cursor-col-resize transition-colors duration-200 z-20 items-center justify-center group">
                <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="w-0.5 h-0.5 bg-white rounded-full"></div>
                    <div class="w-0.5 h-0.5 bg-white rounded-full"></div>
                    <div class="w-0.5 h-0.5 bg-white rounded-full"></div>
                </div>
            </div>

            <!-- =============================== -->
            <!-- O'NG PANEL (CHAT OYNASI) -->
            <!-- =============================== -->
            <div :class="showChat ? 'flex' : 'hidden lg:flex'" 
                 class="flex-1 flex-col bg-white lg:bg-[#f8fafc] h-full relative z-10 w-full lg:min-w-[300px]">
                
                <!-- Boshlang'ich Holat -->
                <div x-show="!selectedUserId" class="flex-1 flex flex-col items-center justify-center bg-slate-50 lg:bg-transparent p-6 text-center">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 text-indigo-500 shadow-xl shadow-slate-200/50">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2 tracking-tight">Xabarlaringiz shu yerda</h3>
                    <p class="text-sm text-slate-500 max-w-sm">Chap paneldan do'stlaringizdan birini tanlang yoki qidiruvdan yangi foydalanuvchini topib suhbatni boshlang.</p>
                </div>

                <div x-show="selectedUserId" class="flex-1 flex flex-col h-full w-full" style="display: none;">
                    
                    <!-- Chat Header -->
                    <div class="bg-white/90 backdrop-blur-md border-b border-slate-100 p-3 lg:p-4 px-4 lg:px-6 flex justify-between items-center sticky top-0 z-20 shrink-0 shadow-sm lg:shadow-none">
                        
                        <div class="flex items-center gap-3 lg:gap-4">
                            <button @click="closeChat()" class="lg:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            
                            <a :href="'/@' + selectedUserUsername" class="flex items-center gap-3 lg:gap-4 hover:opacity-80 transition-opacity">
                                <div class="relative">
                                    <template x-if="selectedUserAvatar">
                                        <img :src="selectedUserAvatar" class="w-10 h-10 lg:w-11 lg:h-11 rounded-xl object-cover shadow-sm">
                                    </template>
                                    <template x-if="!selectedUserAvatar">
                                        <div class="w-10 h-10 lg:w-11 lg:h-11 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-sm" x-text="selectedUserName.charAt(0)"></div>
                                    </template>
                                    <div class="absolute -top-1 -right-1 w-3 h-3 lg:w-3.5 lg:h-3.5 bg-emerald-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div>
                                    <h2 class="font-black text-slate-900 leading-none text-base lg:text-lg" x-text="selectedUserName"></h2>
                                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mt-1 block">Hozir onlayn</span>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Header Tugmalari -->
                        <div class="flex items-center gap-1 relative">
                            <!-- Lupa -->
                            <button @click="showMessageSearch = !showMessageSearch; if(showMessageSearch) setTimeout(() => $refs.msgSearch.focus(), 100)" title="Xabarlardan izlash" 
                                    class="w-10 h-10 flex items-center justify-center rounded-xl transition-all"
                                    :class="showMessageSearch ? 'bg-indigo-50 text-indigo-600' : 'text-slate-400 hover:bg-slate-100 hover:text-indigo-600'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>

                            <!-- 3 Nuqta -->
                            <button @click="showOptionsMenu = !showOptionsMenu" @click.away="showOptionsMenu = false" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-900 rounded-xl transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>

                            <div x-show="showOptionsMenu" x-transition.opacity.duration.200ms style="display: none;" 
                                 class="absolute top-12 right-0 w-48 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden z-50">
                                <button @click="clearHistory()" class="w-full text-left px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 flex items-center gap-3 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Tarixni tozalash
                                </button>
                                <button @click="deleteChat()" class="w-full text-left px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 flex items-center gap-3 transition-colors border-t border-slate-50">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Chatni o'chirish
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Ichidagi Qidiruv -->
                    <div x-show="showMessageSearch" x-transition style="display: none;" class="bg-slate-50 border-b border-slate-200/60 p-2 lg:p-3 px-4 lg:px-6 z-10 relative">
                        <div class="relative">
                            <input type="text" x-model="messageSearchQuery" x-ref="msgSearch" placeholder="Ushbu chatdan xabarni izlash..." class="w-full bg-white border border-slate-200 text-sm text-slate-900 rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 outline-none transition-all shadow-sm">
                            <div class="absolute left-3 top-2.5 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <button x-show="messageSearchQuery.length > 0" @click="messageSearchQuery = ''; $refs.msgSearch.focus()" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Xabarlar Oynasi -->
                    <div id="chat-messages" class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-4 lg:space-y-5 bg-slate-50 lg:bg-transparent custom-scrollbar relative">
                        
                        <div x-show="isLoading" class="flex justify-center my-4">
                            <svg class="animate-spin h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>

                        <div x-show="!isLoading && messages.length === 0" class="flex flex-col items-center justify-center h-full text-slate-400">
                            <span class="text-sm font-medium bg-white px-4 py-2 rounded-full shadow-sm">Xabarlar yo'q. Birinchi bo'lib yozing! 👋</span>
                        </div>

                        <template x-for="msg in messages" :key="msg.id">
                            <div x-show="messageSearchQuery.trim() === '' || (msg.message || '').toLowerCase().includes(messageSearchQuery.toLowerCase())"
                                 class="flex gap-2 lg:gap-3 max-w-[90%] lg:max-w-[80%] group/msg" 
                                 :class="msg.sender_id === myId ? 'ml-auto flex-row-reverse' : ''">
                                
                                <div class="relative group">
                                    <div class="p-3 lg:p-4 shadow-sm leading-relaxed text-[14.5px] lg:text-[15px] relative"
                                         :class="msg.sender_id === myId 
                                            ? 'bg-gradient-to-br from-indigo-500 to-blue-600 rounded-[1.2rem] lg:rounded-[1.5rem] rounded-br-none shadow-md shadow-indigo-500/20 text-white' 
                                            : 'bg-white border border-slate-200 rounded-[1.2rem] lg:rounded-[1.5rem] sm:rounded-bl-none text-slate-700'">
                                        
                                        <p x-text="msg.message" class="break-words whitespace-pre-wrap"></p>
                                        
                                        <div class="flex items-center gap-1 mt-1.5" :class="msg.sender_id === myId ? 'justify-end' : ''">
                                            <span class="text-[9px] lg:text-[10px] font-bold" :class="msg.sender_id === myId ? 'text-indigo-100' : 'text-slate-400'" x-text="formatTime(msg.created_at)"></span>
                                            <template x-if="msg.sender_id === myId">
                                                <svg class="w-3.5 h-3.5" :class="msg.is_read ? 'text-white' : 'text-indigo-200'" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7M5 13l4 4L19 7"></path></svg>
                                            </template>
                                        </div>
                                    </div>

                                    <template x-if="msg.sender_id === myId">
                                        <div class="absolute top-1/2 -translate-y-1/2 right-full mr-2 opacity-0 group-hover/msg:opacity-100 flex items-center gap-1 transition-opacity">
                                            <button @click="editMessage(msg)" title="Tahrirlash" class="p-1.5 bg-white hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-full transition shadow-sm border border-slate-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <button @click="deleteMessage(msg.id)" title="O'chirish" class="p-1.5 bg-white hover:bg-red-50 text-slate-400 hover:text-red-600 rounded-full transition shadow-sm border border-slate-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                    </div>

                    <!-- XAT YOZISH (INPUT) QISMI -->
                    <div class="p-3 mb-2 lg:p-4 bg-white border-t border-slate-100 shrink-0 pb-safe">
                        <form @submit.prevent="sendMessage" class="flex items-center gap-2 lg:gap-3 bg-slate-50 border border-slate-200 p-1 lg:p-1.5 rounded-[1.2rem] lg:rounded-2xl focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100 transition-all duration-300 shadow-sm">
                            <input type="text" x-model="newMessage" :placeholder="editingMessageId ? 'Xabarni tahrirlash...' : 'Xabar yozing...'" class="flex-1 bg-transparent border-none focus:ring-0 text-[14px] lg:text-[15px] text-slate-900 py-2 outline-none pl-4">
                            <template x-if="editingMessageId">
                                <button type="button" @click="cancelEdit" title="Bekor qilish" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </template>
                            <button type="submit" :disabled="isSending || newMessage.trim() === ''" 
                                    class="w-10 h-10 lg:w-11 lg:h-11 shrink-0 flex items-center justify-center text-white rounded-[1rem] lg:rounded-xl shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                    :class="editingMessageId ? 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-200' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200 active:scale-95'">
                                <svg x-show="!isSending && !editingMessageId" class="w-5 h-5 transform rotate-45 -translate-y-0.5 translate-x-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                                <svg x-show="!isSending && editingMessageId" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <svg x-show="isSending" class="animate-spin h-5 w-5 text-white" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- STYLELAR -->
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .no-select { user-select: none; }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 1rem); }
    </style>

    <!-- AXIOS ULANISHI -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- ALPINE.JS LOGIKASI -->
    <script>
        function chatApp(myId) {
            return {
                myId: myId,
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
                editingMessageId: null,

                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // YAP-YANGI: Profildan kelganda avtomatik chatni ochish
                    @if(isset($startWithUser) && $startWithUser)
                        setTimeout(() => {
                            this.selectUser(
                                {{ $startWithUser->id }}, 
                                '{!! addslashes($startWithUser->name) !!}', 
                                '{{ $startWithUser->avatar ? asset("storage/".$startWithUser->avatar) : "" }}', 
                                '{{ $startWithUser->username }}'
                            );
                        }, 100);
                    @endif

                    setInterval(() => {
                        if (this.selectedUserId && !this.editingMessageId) {
                            this.fetchMessages(false);
                        }
                    }, 4000);
                },

                searchGlobal() {
                    let q = this.searchQuery.trim();
                    if (q.length > 1) {
                        axios.get(`/chat/search?q=${q}`)
                            .then(response => {
                                this.globalUsers = response.data;
                            })
                            .catch(error => console.error("Qidiruvda xatolik:", error));
                    } else {
                        this.globalUsers = [];
                    }
                },

                selectUser(id, name, avatar, username) {
                    this.selectedUserId = id;
                    this.selectedUserName = name;
                    this.selectedUserAvatar = avatar;
                    this.selectedUserUsername = username;
                    
                    this.showChat = true;
                    this.showOptionsMenu = false;
                    this.showMessageSearch = false;
                    this.messageSearchQuery = '';
                    
                    this.messages = [];
                    this.editingMessageId = null;
                    this.newMessage = '';
                    
                    this.searchQuery = '';
                    this.globalUsers = [];
                    
                    this.fetchMessages(true);
                },

                closeChat() {
                    this.showChat = false;
                    this.selectedUserId = null;
                    this.editingMessageId = null;
                    this.showOptionsMenu = false;
                    this.showMessageSearch = false;
                },

                fetchMessages(showLoader = true) {
                    if (showLoader) this.isLoading = true;
                    
                    axios.get(`/chat/${this.selectedUserId}/messages`)
                        .then(response => {
                            let isNewMsg = this.messages.length !== response.data.length;
                            this.messages = response.data;
                            if (showLoader || isNewMsg) this.scrollToBottom();
                        })
                        .catch(error => { console.error("Xabarlarni olishda xatolik:", error); })
                        .finally(() => { this.isLoading = false; });
                },

                sendMessage() {
                    if (this.newMessage.trim() === '') return;
                    
                    this.isSending = true;
                    let msgContent = this.newMessage;
                    
                    if (this.editingMessageId) {
                        axios.patch(`/chat/message/${this.editingMessageId}`, { message: msgContent })
                            .then(response => {
                                let msg = this.messages.find(m => m.id === this.editingMessageId);
                                if(msg) msg.message = msgContent;
                                this.cancelEdit();
                            })
                            .catch(error => {
                                alert("Xatolik yuz berdi.");
                            })
                            .finally(() => { this.isSending = false; });
                    } else {
                        this.newMessage = ''; 
                        this.messages.push({
                            id: Date.now(),
                            sender_id: this.myId,
                            message: msgContent,
                            created_at: new Date().toISOString(),
                            is_read: false
                        });
                        this.scrollToBottom();

                        axios.post(`/chat/${this.selectedUserId}/send`, { message: msgContent })
                            .then(response => {
                                this.fetchMessages(false);
                            })
                            .catch(error => {
                                alert("Xabar bormadi. Qayta urinib ko'ring.");
                            })
                            .finally(() => { this.isSending = false; });
                    }
                },

                editMessage(msg) {
                    this.editingMessageId = msg.id;
                    this.newMessage = msg.message;
                    setTimeout(() => { document.querySelector('input[x-model="newMessage"]').focus(); }, 50);
                },

                cancelEdit() {
                    this.editingMessageId = null;
                    this.newMessage = '';
                },

                deleteMessage(msgId) {
                    if(!confirm("Ushbu xabarni haqiqatan ham o'chirmoqchimisiz?")) return;
                    axios.delete(`/chat/message/${msgId}`)
                        .then(() => {
                            this.messages = this.messages.filter(m => m.id !== msgId);
                        })
                        .catch(error => {
                            alert("Xatolik yuz berdi");
                        });
                },

                clearHistory() {
                    if(!confirm("Suhbat tarixini tozalashni xohlaysizmi?")) return;
                    this.showOptionsMenu = false;
                    axios.delete(`/chat/${this.selectedUserId}/clear`)
                        .then(() => {
                            this.messages = [];
                        })
                        .catch(error => { console.error("Chatni tozalashda xato:", error); });
                },

                deleteChat() {
                    if(!confirm("Chatni butunlay o'chirmoqchimisiz?")) return;
                    this.showOptionsMenu = false;
                    axios.delete(`/chat/${this.selectedUserId}/clear`)
                        .then(() => {
                            window.location.replace('/chat'); 
                        })
                        .catch(error => { console.error("Chatni tozalashda xato:", error); });
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const chatBox = document.getElementById('chat-messages');
                        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
                    }, 100);
                },

                formatTime(datetime) {
                    if (!datetime) return '';
                    const date = new Date(datetime);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                }
            }
        }

        // JS Resize
        document.addEventListener('DOMContentLoaded', function () {
            const resizer = document.getElementById('resizer');
            const sidebar = document.getElementById('sidebar');
            let isResizing = false;

            if(window.innerWidth > 1024) {
                resizer.addEventListener('mousedown', function (e) {
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