<div>
    <aside class="hidden lg:flex fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 h-screen flex-col justify-between">
        <div>
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <div class="flex items-center gap-2 text-gray-900 font-bold text-xl">
                    <div class="bg-gray-900 text-white p-1 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    BlogHub
                </div>
            </div>
            <nav class="p-4 space-y-1">
                <a href="/" class="flex items-center gap-3 px-3 py-2 text-gray-900 bg-gray-100 rounded-lg font-medium">Dashboard</a>
                <a href="/followers" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-lg font-medium">Followers</a>
                <a href="/chat" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-lg font-medium">Chat</a>
                <a href="/notification" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-lg font-medium">Notifications</a>
                <a href="/profile" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-lg font-medium">Profile</a>
            </nav>
        </div>
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                <div class="h-9 w-9 bg-gray-300 rounded-full flex items-center justify-center text-sm font-bold text-gray-600">A</div>
                <div class="flex-1 min-w-0"><p class="text-sm font-medium text-gray-900 truncate">Asomiddin</p></div>
            </div>
        </div>
    </aside>

    <div class="lg:hidden fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 w-[94%] max-w-lg">
        
        <div class="flex items-center justify-around gap-1 p-2.5 rounded-full bg-white/85 backdrop-blur-2xl shadow-xl border border-gray-200/50">

            <a href="/" class="flex flex-col items-center justify-center w-16 h-12 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition group">
                <svg class="w-6 h-6 group-hover:scale-105 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </a>

            <a href="/chat" class="flex flex-col items-center justify-center w-16 h-12 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition group">
                <svg class="w-6 h-6 group-hover:scale-105 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </a>

             <a href="/notification" class="flex flex-col items-center justify-center w-16 h-12 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition group">
                <div class="relative">
                    <svg class="w-6 h-6 group-hover:scale-105 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white"></span>
                    </span>
                </div>
            </a>

            <a href="/profile" class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-gray-100 text-gray-900 border border-gray-200 shadow-sm">
                <div class="w-6 h-6 rounded-full overflow-hidden border border-gray-300">
                     <div class="w-full h-full bg-gradient-to-tr from-gray-200 to-gray-300 flex items-center justify-center text-[8px] font-bold text-gray-700">A</div>
                </div>
                <span class="text-xs font-semibold tracking-wide">Profile</span>
            </a>

        </div>
    </div>
</div>