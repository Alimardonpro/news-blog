<x-app-layout>
    <div class="py-12 bg-[#f8fafc] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 space-y-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('profile.show', Auth::user()->username) }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2"/></svg>
                </a>
                <h2 class="text-2xl font-black text-slate-900">Profil tahriri</h2>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="h-44 bg-slate-800 relative cursor-pointer" onclick="document.getElementById('banner_in').click()">
                        @if($user->banner) <img src="{{ asset('storage/' . $user->banner) }}" class="w-full h-full object-cover opacity-60"> @endif
                        <div class="absolute inset-0 flex items-center justify-center text-white text-[10px] font-black uppercase tracking-widest">Banner yuklash</div>
                        <input type="file" name="banner" id="banner_in" class="hidden">
                    </div>

                    <div class="p-8 pt-16 relative">
                        <div class="absolute -top-12 left-8 w-24 h-24 bg-white p-1 rounded-3xl shadow-lg cursor-pointer" onclick="document.getElementById('avatar_in').click()">
                            @if($user->avatar) <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full rounded-[1.2rem] object-cover"> @else <div class="w-full h-full bg-slate-900 rounded-[1.2rem]"></div> @endif
                            <input type="file" name="avatar" id="avatar_in" class="hidden">
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ism</label>
                                <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none focus:border-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Username</label>
                                <input type="text" name="username" value="{{ $user->username }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none focus:border-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Bio</label>
                                <textarea name="bio" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none focus:border-indigo-500 transition-all resize-none">{{ $user->bio }}</textarea>
                            </div>
                            <button type="submit" class="bg-indigo-600 text-white font-black text-xs px-10 py-4 rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">O'zgarishlarni saqlash</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>