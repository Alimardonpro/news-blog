<x-layouts.app>
    <div class="grid grid-cols-12 gap-6">

        <div class="col-span-12 lg:col-span-8 border-x border-gray-100 min-h-screen">
            
            <div class="sticky top-0 bg-white/90 backdrop-blur z-30 border-b border-gray-100">
                <div class="flex justify-between items-center px-4 py-3">
                    <h2 class="text-xl font-bold text-gray-900">Notifications</h2>
                    <button class="p-2 hover:bg-gray-100 rounded-full transition text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </button>
                </div>

                <div class="flex items-center">
                    <a href="#" class="flex-1 text-center py-3 hover:bg-gray-50 transition relative">
                        <span class="font-bold text-gray-900 text-sm">All</span>
                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-12 h-1 bg-blue-500 rounded-full"></div>
                    </a>
                    <a href="#" class="flex-1 text-center py-3 hover:bg-gray-50 transition text-gray-500 font-medium text-sm">
                        Verified
                    </a>
                    <a href="#" class="flex-1 text-center py-3 hover:bg-gray-50 transition text-gray-500 font-medium text-sm">
                        Mentions
                    </a>
                </div>
            </div>

            <div class="divide-y divide-gray-100">

                <div class="flex gap-4 p-4 hover:bg-gray-50 transition cursor-pointer bg-blue-50/30">
                    <div class="flex-shrink-0 w-8 text-right">
                        <svg class="w-7 h-7 text-pink-500 ml-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="w-8 h-8 rounded-full overflow-hidden mb-2">
                             <img src="https://ui-avatars.com/api/?name=Elon+Musk&background=000&color=fff" class="w-full h-full object-cover">
                        </div>
                        <p class="text-gray-900 text-[15px]">
                            <span class="font-bold">Elon Musk</span> liked your post
                        </p>
                        <p class="text-gray-500 text-sm mt-1 line-clamp-2">
                            Laravel 11 da papkalar strukturasi ancha soddalashdi. Endi config fayllar ham...
                        </p>
                    </div>
                </div>

                <div class="flex gap-4 p-4 hover:bg-gray-50 transition cursor-pointer">
                    <div class="flex-shrink-0 w-8 text-right">
                        <svg class="w-7 h-7 text-blue-500 ml-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                             <div class="w-8 h-8 rounded-full overflow-hidden mb-2">
                                <img src="https://ui-avatars.com/api/?name=Taylor+Swift&background=random" class="w-full h-full object-cover">
                            </div>
                            <button class="px-4 py-1.5 border border-gray-300 rounded-full text-sm font-bold text-gray-900 hover:bg-gray-100 transition">
                                Follow
                            </button>
                        </div>
                        <p class="text-gray-900 text-[15px]">
                            <span class="font-bold">Taylor Swift</span> followed you
                        </p>
                    </div>
                </div>

                <div class="flex gap-4 p-4 hover:bg-gray-50 transition cursor-pointer">
                    <div class="flex-shrink-0 w-8 text-right">
                        <svg class="w-7 h-7 text-green-500 ml-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="w-8 h-8 rounded-full overflow-hidden mb-2">
                             <img src="https://ui-avatars.com/api/?name=Asomiddin&background=random" class="w-full h-full object-cover">
                        </div>
                        <p class="text-gray-900 text-[15px]">
                            <span class="font-bold">Asomiddin</span> replied to your post
                        </p>
                        <p class="text-gray-500 text-sm mt-1 text-gray-600">
                            "Ajoyib maqola bo'libdi! Davomini kutamiz 🔥"
                        </p>
                    </div>
                </div>

                <div class="flex gap-4 p-4 hover:bg-gray-50 transition cursor-pointer">
                    <div class="flex-shrink-0 w-8 text-right">
                        <div class="bg-black text-white p-0.5 rounded ml-auto w-6 h-6 flex items-center justify-center">
                           <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-900 text-[15px]">
                            There was a login to your account @asomiddin_dev from a new device on Jan 22, 2026. Review it now.
                        </p>
                    </div>
                </div>

                @foreach(range(1, 5) as $i)
                    <div class="flex gap-4 p-4 hover:bg-gray-50 transition cursor-pointer">
                        <div class="flex-shrink-0 w-8 text-right">
                            <svg class="w-7 h-7 text-pink-500 ml-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="w-8 h-8 rounded-full overflow-hidden mb-2">
                                <img src="https://ui-avatars.com/api/?name=User+{{$i}}&background=random" class="w-full h-full object-cover">
                            </div>
                            <p class="text-gray-900 text-[15px]">
                                <span class="font-bold">User {{$i}}</span> liked your reply
                            </p>
                            <p class="text-gray-500 text-sm mt-1 line-clamp-2">
                                Men ham xuddi shunday fikrdaman...
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

        <div class="hidden lg:block col-span-4 pl-4 pt-4">
             <div class="bg-gray-100 rounded-full flex items-center px-4 py-2.5 mb-6 focus-within:ring-1 focus-within:ring-blue-500 focus-within:bg-white border border-transparent focus-within:border-blue-500 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search" class="bg-transparent border-none outline-none text-sm ml-3 w-full placeholder-gray-500 text-gray-900">
            </div>

            <div class="bg-gray-50 rounded-2xl p-4">
                <h3 class="font-bold text-xl mb-4 text-gray-900">Trends for you</h3>
                
                <div class="space-y-4">
                    <div class="cursor-pointer hover:bg-gray-100 p-2 -mx-2 rounded-lg transition">
                        <p class="text-xs text-gray-500">Trending in Uzbekistan</p>
                        <p class="font-bold text-gray-900">#Laravel11</p>
                        <p class="text-xs text-gray-500">2,453 posts</p>
                    </div>
                     <div class="cursor-pointer hover:bg-gray-100 p-2 -mx-2 rounded-lg transition">
                        <p class="text-xs text-gray-500">Technology · Trending</p>
                        <p class="font-bold text-gray-900">#Livewire</p>
                        <p class="text-xs text-gray-500">12k posts</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>