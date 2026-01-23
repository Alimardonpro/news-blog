<x-layouts.app>
    <header class="flex h-16 items-center justify-between px-8 py-4 border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur z-10 mb-5">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-medium text-gray-800">Posts</h2>
        </div>
        <button class="bg-black hover:bg-gray-800 text-white text-sm font-medium px-5 py-2.5 rounded-full transition shadow-sm">
            Create Post
        </button>
    </header>

    <div class="grid grid-cols-12 gap-6 px-4">
        
        <div class="col-span-12 lg:col-span-8">
            
            <div class="border border-gray-200 rounded-xl p-5 mb-6 hover:bg-gray-50 transition duration-200">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            AS
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-900">Asomiddin</span>
                            <span class="text-gray-500 text-sm">@asomiddin_dev</span>
                            <span class="text-gray-400 text-sm">· 2s</span>
                        </div>

                        <p class="text-gray-800 text-[15px] leading-normal mb-3">
                            Laravel 11 da papkalar strukturasi ancha soddalashdi. Endi config fayllar ham kamaytirildi. Sizga bu o'zgarishlar yoqdimi?
                            <br><br>
                            Menimcha bu juda qulay! 🔥 <span class="text-blue-500">#laravel #php</span>
                        </p>

                        <div class="mt-3 rounded-2xl overflow-hidden border border-gray-200">
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                 alt="Coding setup" 
                                 class="w-full h-auto object-cover max-h-[400px]">
                        </div>

                        <div class="flex items-center justify-between mt-4 pr-12 text-gray-500">
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span class="text-sm">12</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-green-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span class="text-sm">5</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-red-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span class="text-sm">48</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span class="text-sm">2.1K</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl p-5 mb-6 hover:bg-gray-50 transition duration-200">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            AS
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-900">Asomiddin</span>
                            <span class="text-gray-500 text-sm">@asomiddin_dev</span>
                            <span class="text-gray-400 text-sm">· 2s</span>
                        </div>

                        <p class="text-gray-800 text-[15px] leading-normal mb-3">
                            Laravel 11 da papkalar strukturasi ancha soddalashdi. Endi config fayllar ham kamaytirildi. Sizga bu o'zgarishlar yoqdimi?
                            <br><br>
                            Menimcha bu juda qulay! 🔥 <span class="text-blue-500">#laravel #php</span>
                        </p>

                        <div class="mt-3 rounded-2xl overflow-hidden border border-gray-200">
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                 alt="Coding setup" 
                                 class="w-full h-auto object-cover max-h-[400px]">
                        </div>

                        <div class="flex items-center justify-between mt-4 pr-12 text-gray-500">
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span class="text-sm">12</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-green-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span class="text-sm">5</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-red-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span class="text-sm">48</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span class="text-sm">2.1K</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="border border-gray-200 rounded-xl p-5 mb-6 hover:bg-gray-50 transition duration-200">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            AS
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-900">Asomiddin</span>
                            <span class="text-gray-500 text-sm">@asomiddin_dev</span>
                            <span class="text-gray-400 text-sm">· 2s</span>
                        </div>

                        <p class="text-gray-800 text-[15px] leading-normal mb-3">
                            Laravel 11 da papkalar strukturasi ancha soddalashdi. Endi config fayllar ham kamaytirildi. Sizga bu o'zgarishlar yoqdimi?
                            <br><br>
                            Menimcha bu juda qulay! 🔥 <span class="text-blue-500">#laravel #php</span>
                        </p>

                        <div class="mt-3 rounded-2xl overflow-hidden border border-gray-200">
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                 alt="Coding setup" 
                                 class="w-full h-auto object-cover max-h-[400px]">
                        </div>

                        <div class="flex items-center justify-between mt-4 pr-12 text-gray-500">
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span class="text-sm">12</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-green-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span class="text-sm">5</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-red-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span class="text-sm">48</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span class="text-sm">2.1K</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="border border-gray-200 rounded-xl p-5 mb-6 hover:bg-gray-50 transition duration-200">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            AS
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-900">Asomiddin</span>
                            <span class="text-gray-500 text-sm">@asomiddin_dev</span>
                            <span class="text-gray-400 text-sm">· 2s</span>
                        </div>

                        <p class="text-gray-800 text-[15px] leading-normal mb-3">
                            Laravel 11 da papkalar strukturasi ancha soddalashdi. Endi config fayllar ham kamaytirildi. Sizga bu o'zgarishlar yoqdimi?
                            <br><br>
                            Menimcha bu juda qulay! 🔥 <span class="text-blue-500">#laravel #php</span>
                        </p>

                        <div class="mt-3 rounded-2xl overflow-hidden border border-gray-200">
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                 alt="Coding setup" 
                                 class="w-full h-auto object-cover max-h-[400px]">
                        </div>

                        <div class="flex items-center justify-between mt-4 pr-12 text-gray-500">
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span class="text-sm">12</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-green-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span class="text-sm">5</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-red-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span class="text-sm">48</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span class="text-sm">2.1K</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="border border-gray-200 rounded-xl p-5 mb-6 hover:bg-gray-50 transition duration-200">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            AS
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-900">Asomiddin</span>
                            <span class="text-gray-500 text-sm">@asomiddin_dev</span>
                            <span class="text-gray-400 text-sm">· 2s</span>
                        </div>

                        <p class="text-gray-800 text-[15px] leading-normal mb-3">
                            Laravel 11 da papkalar strukturasi ancha soddalashdi. Endi config fayllar ham kamaytirildi. Sizga bu o'zgarishlar yoqdimi?
                            <br><br>
                            Menimcha bu juda qulay! 🔥 <span class="text-blue-500">#laravel #php</span>
                        </p>

                        <div class="mt-3 rounded-2xl overflow-hidden border border-gray-200">
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                 alt="Coding setup" 
                                 class="w-full h-auto object-cover max-h-[400px]">
                        </div>

                        <div class="flex items-center justify-between mt-4 pr-12 text-gray-500">
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span class="text-sm">12</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-green-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span class="text-sm">5</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-red-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span class="text-sm">48</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span class="text-sm">2.1K</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="border border-gray-200 rounded-xl p-5 mb-6 hover:bg-gray-50 transition duration-200">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            AS
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-900">Asomiddin</span>
                            <span class="text-gray-500 text-sm">@asomiddin_dev</span>
                            <span class="text-gray-400 text-sm">· 2s</span>
                        </div>

                        <p class="text-gray-800 text-[15px] leading-normal mb-3">
                            Laravel 11 da papkalar strukturasi ancha soddalashdi. Endi config fayllar ham kamaytirildi. Sizga bu o'zgarishlar yoqdimi?
                            <br><br>
                            Menimcha bu juda qulay! 🔥 <span class="text-blue-500">#laravel #php</span>
                        </p>

                        <div class="flex items-center justify-between mt-4 pr-12 text-gray-500">
                            <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M19 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span class="text-sm">12</span>
                            </button>
                             <button class="flex items-center gap-2 hover:text-green-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span class="text-sm">5</span>
                            </button>
                             <button class="flex items-center gap-2 hover:text-red-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span class="text-sm">48</span>
                            </button>
                             <button class="flex items-center gap-2 hover:text-blue-500 group transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span class="text-sm">2.1K</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="hidden lg:block col-span-4 pl-4">
            
            <div class="sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Izohlar (Tez orada)</h3>
                
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-center text-gray-500 py-10">
                    <p>Hozircha izohlar yo'q.</p>
                    <p class="text-sm mt-2">Bu joy o'ng tomonda ajratilgan.</p>
                </div>
            </div>

        </div>

    </div>
</x-layouts.app>