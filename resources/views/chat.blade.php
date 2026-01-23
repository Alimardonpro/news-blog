<x-layouts.app>
    <div class="grid grid-cols-12 gap-0 lg:border lg:border-gray-200 lg:rounded-2xl lg:overflow-hidden h-[calc(100vh-80px)] lg:h-[calc(100vh-100px)] bg-white">

        <div class="col-span-12 lg:col-span-4 flex flex-col border-r border-gray-100 h-full">
            
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">Chats</h1>
                    <button class="p-2 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                </div>
                
                <div class="relative">
                    <input type="text" placeholder="Search" class="w-full bg-gray-100 text-gray-900 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                
                <div class="flex gap-3 p-3 cursor-pointer hover:bg-gray-50 bg-blue-50/50 border-l-4 border-blue-500 transition">
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 bg-gray-300 rounded-full overflow-hidden">
                             <img src="https://ui-avatars.com/api/?name=Elon+Musk&background=000&color=fff" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-gray-900 truncate">Elon Musk</h3>
                            <span class="text-xs text-blue-500 font-medium">10:42</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">
                            <span class="text-blue-500">You:</span> Qachon Marsga uchamiz? 🚀
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 p-3 cursor-pointer hover:bg-gray-50 border-l-4 border-transparent transition">
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 bg-gray-300 rounded-full overflow-hidden">
                             <img src="https://ui-avatars.com/api/?name=Taylor+Swift&background=random" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-gray-900 truncate">Taylor Swift</h3>
                            <span class="text-xs text-gray-400">Yesterday</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-900 font-medium truncate">Yangi albomimni eshitdingmi?</p>
                            <span class="bg-blue-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">2</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 p-3 cursor-pointer hover:bg-gray-50 border-l-4 border-transparent transition">
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 bg-gray-300 rounded-full overflow-hidden">
                             <img src="https://ui-avatars.com/api/?name=Asomiddin&background=random" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-gray-900 truncate">Asomiddin</h3>
                            <span class="text-xs text-gray-400">Mon</span>
                        </div>
                        <p class="text-sm text-gray-500 truncate">Loyihani GitHubga yukladim.</p>
                    </div>
                </div>

                @foreach(range(1, 8) as $i)
                <div class="flex gap-3 p-3 cursor-pointer hover:bg-gray-50 border-l-4 border-transparent transition">
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-sm font-bold">U{{$i}}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-gray-900 truncate">User {{$i}}</h3>
                            <span class="text-xs text-gray-400">Sun</span>
                        </div>
                        <p class="text-sm text-gray-500 truncate">Lorem ipsum dolor sit amet...</p>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        <div class="hidden lg:flex col-span-8 flex-col h-full bg-[#f6f9fc]">
            
            <div class="bg-white/80 backdrop-blur border-b border-gray-100 p-3 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-300 rounded-full overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Elon+Musk&background=000&color=fff" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 leading-none">Elon Musk</h2>
                        <span class="text-xs text-blue-500">online</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 text-blue-500">
                    <button class="hover:bg-blue-50 p-2 rounded-full transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></button>
                    <button class="hover:bg-blue-50 p-2 rounded-full transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></button>
                    <button class="hover:bg-blue-50 p-2 rounded-full transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
                
                <div class="flex justify-center">
                    <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Today</span>
                </div>

                <div class="flex gap-3 max-w-[80%]">
                    <div class="w-8 h-8 bg-gray-300 rounded-full overflow-hidden flex-shrink-0 mt-auto">
                        <img src="https://ui-avatars.com/api/?name=Elon+Musk&background=000&color=fff" class="w-full h-full object-cover">
                    </div>
                    <div class="bg-white p-3 rounded-2xl rounded-bl-none shadow-sm text-gray-800 text-sm">
                        <p>Salom Asomiddin! Starship tayyor. 🚀</p>
                        <span class="text-[10px] text-gray-400 block text-right mt-1">10:30</span>
                    </div>
                </div>

                <div class="flex gap-3 max-w-[80%] ml-auto flex-row-reverse">
                    <div class="bg-blue-500 p-3 rounded-2xl rounded-br-none shadow-sm text-white text-sm">
                        <p>Zo'rku! Qachon uchamiz? Men Laravel loyihamni tugatib olay.</p>
                        <div class="flex items-center justify-end gap-1 mt-1">
                            <span class="text-[10px] text-blue-100">10:42</span>
                            <svg class="w-3 h-3 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 max-w-[80%]">
                    <div class="w-8 h-8 bg-gray-300 rounded-full overflow-hidden flex-shrink-0 mt-auto">
                        <img src="https://ui-avatars.com/api/?name=Elon+Musk&background=000&color=fff" class="w-full h-full object-cover">
                    </div>
                    <div class="bg-white p-3 rounded-2xl rounded-bl-none shadow-sm text-gray-800 text-sm">
                        <p>Shoshilma, hali vaqt bor. Twitterdagi (X) yangi o'zgarishlar qalay? Menga shunaqa dashboard kerak.</p>
                        <span class="text-[10px] text-gray-400 block text-right mt-1">10:45</span>
                    </div>
                </div>

            </div>

            <div class="p-3 bg-white border-t border-gray-100">
                <div class="flex items-end gap-2 bg-gray-50 p-2 rounded-2xl border border-gray-200">
                    <button class="p-2 text-gray-400 hover:text-blue-500 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg></button>
                    
                    <textarea rows="1" placeholder="Write a message..." class="w-full bg-transparent border-none outline-none text-sm text-gray-900 resize-none py-2 max-h-32 focus:ring-0"></textarea>
                    
                    <button class="p-2 bg-blue-500 text-white rounded-xl shadow-md hover:bg-blue-600 transition">
                        <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </div>
            </div>

        </div>

    </div>
</x-layouts.app>