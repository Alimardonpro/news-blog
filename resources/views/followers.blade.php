<x-app-layout>
    <div x-data="{ activeTab: 'followers' }" class="min-h-screen bg-[#f8fafc]">
        
        <div class="sticky top-0 z-40 bg-white/70 backdrop-blur-md border-b border-gray-100 shadow-sm">
            <div class="flex items-center gap-4 px-6 py-4">
                <a href="/profile" class="p-2 rounded-full hover:bg-white shadow-sm transition-all border border-transparent hover:border-gray-100">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white font-bold shadow-lg">
                        AS
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 leading-none"></h2>
                        <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold"></p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-2">
                <div class="bg-gray-200/50 p-1 rounded-2xl flex max-w-md">
                    <button @click="activeTab = 'followers'" 
                        class="flex-1 py-2 text-sm font-bold rounded-xl transition-all duration-300"
                        :class="activeTab === 'followers' ? 'bg-white text-black shadow-md scale-[1.02]' : 'text-gray-500 hover:text-gray-700'">
                        Followers <span class="ml-1 text-[10px] opacity-50">1.2K</span>
                    </button>
                    <button @click="activeTab = 'following'" 
                        class="flex-1 py-2 text-sm font-bold rounded-xl transition-all duration-300"
                        :class="activeTab === 'following' ? 'bg-white text-black shadow-md scale-[1.02]' : 'text-gray-500 hover:text-gray-700'">
                        Following <span class="ml-1 text-[10px] opacity-50">842</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="max-w-4xl"> <div x-show="activeTab === 'followers'" x-transition:enter="transition duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2">
                    @foreach(range(1, 10) as $i)
                    <div class="bg-white p-5 rounded-[24px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] transition-all duration-500 mb-4 flex items-center justify-between group">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-gray-100 to-gray-50 flex items-center justify-center text-lg font-bold text-gray-700 shadow-inner group-hover:rotate-3 transition-transform">
                                U{{ $i }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Foydalanuvchi {{ $i }}</h4>
                                <p class="text-sm text-gray-400 font-medium">@user_name_{{ $i }}</p>
                                <div class="flex items-center gap-1.5 mt-1.5">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full shadow-[0_0_8px_rgba(74,222,128,0.5)]"></span>
                                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-tighter">Sizni kuzatmoqda</p>
                                </div>
                            </div>
                        </div>
                        <button class="px-6 py-2.5 bg-black text-white text-xs font-black rounded-xl hover:bg-gray-800 shadow-[0_10px_20px_rgba(0,0,0,0.15)] active:scale-95 transition-all">
                            Follow Back
                        </button>
                    </div>
                    @endforeach
                </div>

                <div x-show="activeTab === 'following'" x-transition:enter="transition duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2">
                    @foreach(range(1, 5) as $i)
                    <div class="bg-white p-5 rounded-[24px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center justify-between mb-4 hover:border-blue-100 transition-colors">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-gray-900 flex items-center justify-center text-white font-black shadow-lg">
                                P{{ $i }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Pro Developer {{ $i }}</h4>
                                <p class="text-sm text-gray-400">@pro_coder_{{ $i }}</p>
                            </div>
                        </div>
                        <button class="px-6 py-2.5 border-2 border-gray-100 text-gray-500 text-xs font-bold rounded-xl hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all group">
                            <span class="group-hover:hidden">Following</span>
                            <span class="hidden group-hover:inline">Unfollow</span>
                        </button>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        /* Skrinshotdagi kabi silliq fon */
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
    </style>
</x-app-layout>