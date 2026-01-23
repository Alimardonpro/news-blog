<x-layouts.app>
    <div class="flex items-center gap-6 px-4 py-2 border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur z-30">
        <a href="/profile" class="p-2 -ml-2 rounded-full hover:bg-gray-100 transition">
            <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-lg font-bold text-gray-900 leading-tight">Asomiddin</h2>
            <p class="text-xs text-gray-500">@asomiddin_dev</p>
        </div>
    </div>

    <div class="flex border-b border-gray-200">
        <a href="#" class="flex-1 text-center py-4 hover:bg-gray-50 transition relative">
            <span class="font-bold text-gray-900">Followers</span>
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-blue-500 rounded-full"></div>
        </a>

        <a href="#" class="flex-1 text-center py-4 hover:bg-gray-50 transition text-gray-500 font-medium">
            Following
        </a>
    </div>

    <div class="divide-y divide-gray-100">
        
        @foreach(range(1, 10) as $i)
        <div class="flex items-center justify-between px-4 py-4 hover:bg-gray-50 transition cursor-pointer">
            
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-200 to-gray-400 rounded-full overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=User+{{$i}}&background=random" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                </div>

                <div>
                    <div class="font-bold text-sm text-gray-900 leading-none hover:underline">
                        Foydalanuvchi {{ $i }}
                    </div>
                    <div class="text-gray-500 text-sm">@user_{{ $i }}</div>
                    @if($i % 3 == 0)
                        <p class="text-gray-600 text-xs mt-1 line-clamp-1">Senior Developer. Laravel & Vuejs enthusiast.</p>
                    @endif
                </div>
            </div>

            @if($i % 2 == 0)
                <button class="px-4 py-1.5 border border-gray-300 rounded-full text-sm font-bold text-gray-900 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition group">
                    <span class="group-hover:hidden">Following</span>
                    <span class="hidden group-hover:inline">Unfollow</span>
                </button>
            @else
                <button class="px-4 py-1.5 bg-black text-white rounded-full text-sm font-bold hover:bg-gray-800 transition">
                    Follow
                </button>
            @endif

        </div>
        @endforeach

    </div>

    <div class="h-20 lg:hidden"></div>
</x-layouts.app>