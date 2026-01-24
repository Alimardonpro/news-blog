<x-app-layout>
    
    @php
        $isMyProfile = Auth::id() === $user->id;
    @endphp

    <div class="grid grid-cols-12 gap-6" 
         x-data="{ 
            openEditModal: {{ $isMyProfile && ($errors->has('name') || $errors->has('username') || $errors->has('bio')) ? 'true' : 'false' }}, 
            openSettingsModal: {{ $isMyProfile && ($errors->updatePassword->any() || $errors->userDeletion->any()) ? 'true' : 'false' }} 
         }">

        <div class="col-span-12 lg:col-span-8">

            <div class="flex items-center gap-4 px-4 py-2 mb-2 sticky top-0 bg-white/90 backdrop-blur z-30 border-b border-gray-100">
                <a href="{{ route('dashboard') }}" class="p-2 rounded-full hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 leading-none">{{ $user->name }}</h2>
                    <span class="text-sm text-gray-500">{{ $user->posts->count() ?? 0 }} posts</span>
                </div>
            </div>

            <div class="border-b border-gray-200 pb-4 bg-white">
                
                <div class="h-48 bg-gradient-to-r from-blue-400 to-purple-500 w-full relative"></div>

                <div class="px-4 flex justify-between items-start relative">
                    <div class="-mt-16">
                        <div class="w-32 h-32 bg-white rounded-full p-1 relative group cursor-pointer">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full rounded-full object-cover border-4 border-white">
                            @else
                                <div class="w-full h-full bg-blue-600 rounded-full flex items-center justify-center text-white text-4xl font-bold border-4 border-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-3">
                        @if($isMyProfile)
                            <button @click="openEditModal = true" class="border border-gray-300 text-gray-900 font-bold py-1.5 px-4 rounded-full hover:bg-gray-100 transition">
                                Edit Profile
                            </button>
                            <button @click="openSettingsModal = true" class="p-2 border border-gray-300 text-gray-900 rounded-full hover:bg-gray-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </button>
                        @else
                            <button class="bg-black text-white font-bold py-1.5 px-4 rounded-full hover:bg-gray-800 transition">
                                Follow
                            </button>
                        @endif
                    </div>
                </div>

                <div class="px-4 mt-3">
                    <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-500 text-[15px]">{{ '@' . $user->username }}</p>
                    <p class="mt-3 text-gray-900 whitespace-pre-wrap">{{ $user->bio ?? "Hali ma'lumot kiritilmagan." }}</p>

                    <div class="flex items-center gap-4 mt-3 text-gray-500 text-[14px]">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Tashkent, Uzbekistan</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Joined {{ $user->created_at->format('F Y') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-3 text-[14px]">
                        <a href="#" class="flex items-center gap-1 hover:underline cursor-pointer group">
                            <span class="font-bold text-gray-900 group-hover:text-blue-600 transition">{{ $user->following()->count() }}</span> 
                            <span class="text-gray-500">Following</span>
                        </a>
                        <a href="{{ route('followers') }}" class="flex items-center gap-1 hover:underline cursor-pointer group">
                            <span class="font-bold text-gray-900 group-hover:text-blue-600 transition">{{ $user->followers()->count() }}</span> 
                            <span class="text-gray-500">Followers</span>
                        </a>
                    </div>
                </div>

                <div class="flex mt-4 border-b border-gray-200">
                    <button class="flex-1 hover:bg-gray-100 transition py-4 text-center relative">
                        <span class="font-bold text-gray-900">Posts</span>
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-blue-500 rounded-full"></div>
                    </button>
                    </div>
            </div>

            <div class="mt-0">
                @forelse($user->posts as $post)
                    <div class="border-b border-gray-100 p-4 hover:bg-gray-50 transition cursor-pointer bg-white">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-gray-900">{{ $user->name }}</span>
                                    <span class="text-gray-500 text-sm">{{ '@' . $user->username }}</span>
                                    <span class="text-gray-400 text-sm">· {{ $post->created_at->diffForHumans(null, true, true) }}</span>
                                </div>
                                <p class="text-gray-900">{{ $post->body }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">Hali postlar yo'q.</div>
                @endforelse
            </div>
            
        </div>
        <div class="hidden lg:block col-span-4 pl-4"></div>

        @if($isMyProfile)
            
            <div x-show="openEditModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
                 x-transition.opacity>
                
                <div @click.away="openEditModal = false" class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden relative">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <button @click="openEditModal = false" class="text-gray-900 hover:bg-gray-100 p-2 rounded-full transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <h2 class="text-lg font-bold text-gray-900">Edit Profile</h2>
                        </div>
                        <button form="profileUpdateForm" class="bg-black text-white text-sm font-bold px-5 py-1.5 rounded-full hover:bg-gray-800 transition">Save</button>
                    </div>

                    <div class="p-4 max-h-[80vh] overflow-y-auto">
                        <form id="profileUpdateForm" method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                            @csrf @method('PATCH')
                            
                            <div class="relative mb-8">
                                <div class="h-32 bg-gray-200 w-full rounded-md flex items-center justify-center"><span class="text-gray-400 text-xs">Banner</span></div>
                                <div class="absolute -bottom-6 left-4">
                                    <div class="w-20 h-20 rounded-full bg-white p-1 border border-gray-100">
                                        <div class="w-full h-full bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full border-gray-300 rounded-lg focus:ring-black focus:border-black" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>

                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" class="w-full border-gray-300 rounded-lg focus:ring-black focus:border-black" required>
                            @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>

                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                            <textarea name="bio" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-black focus:border-black resize-none">{{ old('bio', Auth::user()->bio) }}</textarea>
                            @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                            
                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full border-gray-300 rounded-lg focus:ring-black focus:border-black" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                        </form>
                    </div>
                </div>
            </div>

            <div x-show="openSettingsModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4" x-transition.opacity>
                <div @click.away="openSettingsModal = false" class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden relative">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <button @click="openSettingsModal = false" class="text-gray-900 hover:bg-gray-100 p-2 rounded-full transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <h2 class="text-lg font-bold text-gray-900">Settings</h2>
                        </div>
                    </div>
                    <div class="p-6 max-h-[80vh] overflow-y-auto">
                        <h3 class="text-md font-bold text-gray-900 mb-4">Update Password</h3>
                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf @method('put')
                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" class="w-full border-gray-300 rounded-lg"></div>
                            
                            <div><label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" class="w-full border-gray-300 rounded-lg"></div>

                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-lg"></div>

                            <div class="flex justify-end"><button type="submit" class="bg-black text-white text-sm font-bold px-5 py-2 rounded-full">Update Password</button></div>
                        </form>
                        <hr class="my-6 border-gray-200">
                        
                        <h3 class="text-md font-bold text-red-600 mb-2">Delete Account</h3>
                        <form method="post" action="{{ route('profile.destroy') }}">
                            @csrf @method('delete')
                            <div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-1">Enter Password to Confirm</label>
                            <input type="password" name="password" class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"></div>
                            <button type="submit" onclick="return confirm('Are you sure?')" class="w-full border border-red-200 bg-red-50 text-red-600 font-bold py-2 rounded-full hover:bg-red-100 transition">Delete Account</button>
                        </form>
                    </div>
                </div>
            </div>

        @endif

    </div>
</x-app-layout>