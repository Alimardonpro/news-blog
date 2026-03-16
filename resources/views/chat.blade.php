<x-app-layout>
    <div class="max-w-[1600px] mx-auto lg:p-6 h-screen">
        <div class="grid grid-cols-12 gap-0 lg:gap-6 h-full lg:h-[calc(100vh-120px)]">

            <div class="col-span-12 lg:col-span-4 flex flex-col bg-white lg:rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-slate-100 overflow-hidden h-full">
                
                <div class="p-6 space-y-5">
                    <div class="flex items-center justify-between">
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Xabarlar</h1>
                        <button class="w-11 h-11 flex items-center justify-center bg-slate-900 text-white rounded-2xl shadow-lg shadow-slate-200 hover:scale-105 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                    </div>
                    
                    <div class="relative group">
                        <input type="text" placeholder="Kontaktlarni izlash..." class="w-full bg-slate-50 border-none text-slate-900 text-sm rounded-[1.5rem] pl-12 pr-4 py-4 focus:ring-2 focus:ring-blue-500/20 transition-all group-hover:bg-slate-100">
                        <div class="absolute left-4 top-4 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-3 space-y-2 pb-6 custom-scrollbar">
                    
                    <div class="flex gap-4 p-4 cursor-pointer bg-slate-900 rounded-[2rem] shadow-xl shadow-slate-200 transition-all duration-300 group">
                        <div class="relative flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name=Elon+Musk&background=fff&color=000" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-800">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-slate-900 rounded-full"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h3 class="font-bold text-white truncate">Elon Musk</h3>
                                <span class="text-[10px] text-slate-400 font-bold">10:42</span>
                            </div>
                            <p class="text-sm text-slate-400 truncate">
                                <span class="text-blue-400 font-bold">Siz:</span> Qachon Marsga uchamiz? 🚀
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4 p-4 cursor-pointer hover:bg-slate-50 rounded-[2rem] transition-all border border-transparent hover:border-slate-100 group">
                        <div class="relative flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name=Taylor+Swift&background=random" class="w-14 h-14 rounded-2xl object-cover grayscale group-hover:grayscale-0 transition-all">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h3 class="font-bold text-slate-900 truncate">Taylor Swift</h3>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Kecha</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-slate-600 font-medium truncate italic">Yangi albomimni eshitdingmi?</p>
                                <span class="bg-blue-600 text-white text-[10px] font-black px-2 py-1 rounded-lg">2</span>
                            </div>
                        </div>
                    </div>

                    @foreach(range(1, 5) as $i)
                    <div class="flex gap-4 p-4 cursor-pointer hover:bg-slate-50 rounded-[2rem] transition-all opacity-70 hover:opacity-100">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 font-black">U{{$i}}</div>
                        <div class="flex-1 min-w-0 pt-1">
                            <div class="flex justify-between items-baseline mb-1">
                                <h3 class="font-bold text-slate-900 truncate">User {{$i}}</h3>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Dush</span>
                            </div>
                            <p class="text-sm text-slate-500 truncate">Dizayn bo'yicha yangi fikrlar bor...</p>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            <div class="hidden lg:flex col-span-8 flex-col bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-slate-100 overflow-hidden h-full relative">
                
                <div class="bg-white/80 backdrop-blur-xl border-b border-slate-50 p-5 flex justify-between items-center sticky top-0 z-20">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name=Elon+Musk&background=000&color=fff" class="w-12 h-12 rounded-[1rem] object-cover">
                            <div class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
                        </div>
                        <div>
                            <h2 class="font-black text-slate-900 leading-none">Elon Musk</h2>
                            <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">Hozir faol</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <button class="w-10 h-10 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </button>
                        <button class="w-10 h-10 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all text-xl font-bold">⋮</button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-8 space-y-8 bg-[#fdfeff] custom-scrollbar">
                    
                    <div class="flex justify-center">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-100/50 px-4 py-1.5 rounded-full">Bugun, 2-Fevral</span>
                    </div>

                    <div class="flex gap-4 max-w-[75%]">
                        <img src="https://ui-avatars.com/api/?name=Elon+Musk&background=000&color=fff" class="w-8 h-8 rounded-lg self-end shadow-md">
                        <div class="relative group">
                            <div class="bg-white border border-slate-100 p-5 rounded-[2rem] rounded-bl-none shadow-sm text-slate-800 text-sm leading-relaxed">
                                <p>Salom Asomiddin! Starship tayyor. 🚀 Sizga yangi dashboard dizayni kerak edi, uni Marsga uchishdan oldin ko'rib chiqaylik.</p>
                            </div>
                            <span class="text-[9px] font-bold text-slate-400 mt-2 block ml-2">10:30 AM</span>
                        </div>
                    </div>

                    <div class="flex gap-4 max-w-[75%] ml-auto flex-row-reverse">
                        <div class="relative">
                            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-5 rounded-[2rem] rounded-br-none shadow-xl shadow-blue-200 text-white text-sm leading-relaxed">
                                <p>Zo'rku! Qachon uchamiz? Men Laravel loyihamni tugatib, dizaynni premium darajaga olib chiqdim. ✨</p>
                                <div class="flex items-center justify-end gap-1 mt-2">
                                    <span class="text-[9px] text-blue-100 font-bold">10:42 AM</span>
                                    <svg class="w-3 h-3 text-blue-200" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="p-6 bg-white">
                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-100 p-2 rounded-[2rem] focus-within:bg-white focus-within:shadow-xl focus-within:shadow-slate-200/50 transition-all duration-500">
                        <button class="p-3 text-slate-400 hover:text-blue-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        </button>
                        
                        <input type="text" placeholder="Xabar yozing..." class="flex-1 bg-transparent border-none focus:ring-0 text-sm text-slate-900 py-3">
                        
                        <button class="w-12 h-12 flex items-center justify-center bg-blue-600 text-white rounded-full shadow-lg shadow-blue-300 hover:bg-blue-700 hover:scale-110 active:scale-95 transition-all">
                            <svg class="w-5 h-5 transform rotate-45 -translate-y-0.5 translate-x-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</x-app-layout>