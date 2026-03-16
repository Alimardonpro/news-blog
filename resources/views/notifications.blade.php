<x-app-layout>
    <div x-data="{ tab: 'all' }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-[#f9fafb] min-h-screen">
        <div class="grid grid-cols-12 gap-8">

            <div class="col-span-12 lg:col-span-8 space-y-8">
                
                <div class="bg-white/60 backdrop-blur-xl border border-white shadow-[0_20px_50px_rgba(0,0,0,0.04)] rounded-[2.5rem] p-2.5 sticky top-8 z-50 flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <button @click="tab = 'all'" 
                            :class="tab === 'all' ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-500 hover:bg-white'"
                            class="px-8 py-3.5 rounded-full font-black text-[13px] uppercase tracking-wider transition-all duration-500">
                            Hammasi
                        </button>
                        <button @click="tab = 'verified'" 
                            :class="tab === 'verified' ? 'bg-blue-600 text-white shadow-xl shadow-blue-200' : 'text-slate-500 hover:bg-white'"
                            class="px-8 py-3.5 rounded-full font-black text-[13px] uppercase tracking-wider transition-all duration-500">
                            Tasdiqlanganlar
                        </button>
                        <button @click="tab = 'mentions'" 
                            :class="tab === 'mentions' ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-200' : 'text-slate-500 hover:bg-white'"
                            class="px-8 py-3.5 rounded-full font-black text-[13px] uppercase tracking-wider transition-all duration-500">
                            Eslatmalar
                        </button>
                    </div>
                    <div class="hidden sm:flex pr-4 items-center gap-3">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Filtrlar</span>
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-slate-100 cursor-pointer hover:rotate-90 transition-transform duration-500">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m12 0a2 2 0 100-4m0 4a2 2 0 110-4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m-6 0H4m5.08 0H20m-5.08 0H4"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">

                    <div x-show="tab === 'all' || tab === 'verified'" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-y-8"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="group bg-white border border-slate-100 p-8 rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-500">
                        <div class="flex items-start gap-6">
                            <div class="relative">
                                <img src="https://ui-avatars.com/api/?name=Pavel+Durov&background=0088cc&color=fff" class="w-20 h-20 rounded-[2rem] shadow-2xl ring-4 ring-blue-50">
                                <div class="absolute -bottom-2 -right-2 bg-blue-500 text-white p-1.5 rounded-full border-[4px] border-white">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-xl font-black text-slate-900">Pavel Durov <span class="text-blue-500 font-bold text-sm ml-2">@durov</span></h4>
                                    <span class="text-[10px] font-black uppercase bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full tracking-tighter">Verified Priority</span>
                                </div>
                                <p class="text-slate-500 leading-relaxed font-medium">Sizning <span class="text-blue-600 font-bold">#PrivacyFirst</span> maqolangizga qiziqish bildirdi va shaxsiy xabar qoldirdi.</p>
                                <div class="mt-5 p-5 bg-slate-50 border border-slate-100 rounded-[1.5rem] italic text-slate-700">
                                    "This implementation of UI is incredibly smooth. High quality work!"
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'all' || tab === 'mentions'" 
                         x-transition:enter="transition ease-out duration-500 delay-100"
                         class="group bg-white border border-slate-100 p-8 rounded-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)]">
                        <div class="flex gap-6">
                            <div class="w-16 h-16 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-[1.5rem] flex items-center justify-center shadow-xl shadow-indigo-200">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-lg text-slate-900 leading-snug">
                                    <span class="font-black">Asomiddin_Dev</span> sizni <span class="bg-indigo-50 text-indigo-600 px-2 py-1 rounded-lg font-bold">#AlphaProject</span> loyihasida esladi.
                                </p>
                                <div class="mt-4 border-l-4 border-indigo-500 pl-6 py-2">
                                    <p class="text-slate-500 italic">"Bu dizaynni @asomiddin_dev bilan maslahatlashsak zo'r bo'lardi, ularda g'oya ko'p."</p>
                                </div>
                                <div class="mt-6 flex gap-4">
                                    <button class="px-6 py-2.5 bg-slate-900 text-white text-xs font-black rounded-xl hover:scale-105 transition-transform">Javob qaytarish</button>
                                    <button class="px-6 py-2.5 border border-slate-200 text-slate-500 text-xs font-black rounded-xl hover:bg-slate-50 transition">Arxivlash</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'all'" 
                         x-transition:enter="transition ease-out duration-500 delay-200"
                         class="bg-gradient-to-r from-orange-500 to-pink-600 p-[1px] rounded-[3rem] shadow-2xl shadow-orange-200">
                        <div class="bg-white rounded-[2.95rem] p-8 flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 bg-orange-100 rounded-3xl flex items-center justify-center text-orange-600">
                                    <svg class="w-9 h-9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-black text-slate-900 italic">Trenddagi yangilik!</h4>
                                    <p class="text-slate-500 font-medium">Sizning "Modern UI" darsligingiz bugun 5,000 marta ko'rildi!</p>
                                </div>
                            </div>
                            <button class="bg-slate-900 text-white px-8 py-3.5 rounded-2xl font-black text-xs hover:shadow-xl transition-all">Statistika</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="hidden lg:block col-span-4 space-y-8 sticky top-8 h-fit">
                <div class="bg-white border border-slate-100 rounded-[3rem] p-10 shadow-xl shadow-slate-200/50">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Trendlar</h3>
                        <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                    </div>
                    
                    <div class="space-y-10">
                        <div class="group cursor-pointer">
                            <span class="text-[10px] font-black text-blue-500 uppercase tracking-[0.3em]">Hafta mavzusi</span>
                            <p class="text-lg font-black text-slate-800 group-hover:text-blue-600 transition mt-1">#AlpineJS_Magic</p>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="flex -space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 border-2 border-white"></div>
                                    <div class="w-6 h-6 rounded-full bg-slate-300 border-2 border-white"></div>
                                </div>
                                <span class="text-xs text-slate-400 font-bold">+1.2k muhokama</span>
                            </div>
                        </div>

                        <div class="group cursor-pointer border-t border-slate-50 pt-8">
                            <span class="text-[10px] font-black text-purple-500 uppercase tracking-[0.3em]">Loyihalar</span>
                            <p class="text-lg font-black text-slate-800 group-hover:text-purple-600 transition mt-1">#BladeComponents</p>
                            <p class="text-xs text-slate-400 mt-1 font-medium">842 kishi ishtirok etmoqda</p>
                        </div>
                    </div>

                    <button class="w-full mt-12 py-5 bg-slate-50 hover:bg-slate-900 hover:text-white rounded-[2rem] text-sm font-black transition-all duration-500">
                        Barchasini ko'rish
                    </button>
                </div>

                <div class="bg-indigo-600 rounded-[3rem] p-8 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-lg font-black mb-2">Pro Maslahat:</h4>
                        <p class="text-indigo-100 text-sm leading-relaxed opacity-80 italic">"Tasdiqlangan foydalanuvchilar bilan ko'proq muloqot qiling, bu sizning reytingingizni oshiradi."</p>
                    </div>
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white opacity-10 rounded-full"></div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>