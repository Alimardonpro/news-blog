<x-guest-layout>
    <style>
        /* Animatsiyalar - GPU tezlatgichi bilan */
        @keyframes orbit-rotation {
            from { transform: rotate(0deg) translateZ(0); }
            to { transform: rotate(360deg) translateZ(0); }
        }
        @keyframes counter-rotation {
            from { transform: rotate(0deg) translateZ(0); }
            to { transform: rotate(-360deg) translateZ(0); }
        }

        /* Konteyner sozlamalari */
        .orbit-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 0;
            overflow: hidden;
            background-color: #ffffff;
        }

        /* Video Orqa fon - 4x tezlik uchun tayyorlangan */
        .video-mp4 {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            z-index: -10;
            overflow: hidden;
        }

        .video-mp4 video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Brightness filteri o'rniga overlaydan foydalanish samaraliroq */
        }

        .video-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.05); /* Videoni biroz yumshatish uchun */
            z-index: -9;
        }

        /* Orbitalar */
        .orbit-circle {
            position: absolute;
            border: 2px dashed #373838af;
            border-radius: 50%;
            animation: orbit-rotation linear infinite;
        }

        .avatar-wrapper {
            position: absolute;
            animation: counter-rotation linear infinite;
        }

        .avatar-img {
            width: 60px; 
            height: 60px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .avatar-img:hover {
            transform: scale(1.15);
        }

        /* LOGIN KARTASI - Register bilan bir xil */
        .fixed-login-card {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 50;
            width: 100%;
            max-width: 420px;
        }

        .login-card-glass {
            background: rgba(226, 225, 225, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(3, 1, 1, 0.51);
            box-shadow: 0 20px 50px rgba(46, 46, 46, 0.12);
            border-radius: 2.5rem;
        }
    </style>

    <div class="min-h-screen relative overflow-hidden">
        
        <div class="orbit-container">
            <div class="video-mp4">
                <video id="loginVideo" autoplay muted loop playsinline preload="auto">
                    <source src="{{ asset('video/video.mp4') }}" type="video/mp4">
                </video>
                <div class="video-overlay"></div>
            </div>

            <div class="orbit-circle w-[850px] h-[850px]" style="animation-duration: 100s;">
                <div class="avatar-wrapper" style="top: 10%; left: 15%; animation-duration: 100s;"><img src="https://i.pravatar.cc/150?u=1" class="avatar-img"></div>
                <div class="avatar-wrapper" style="top: 10%; right: 15%; animation-duration: 100s;"><img src="https://i.pravatar.cc/150?u=2" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 10%; left: 15%; animation-duration: 100s;"><img src="https://i.pravatar.cc/150?u=3" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 10%; right: 15%; animation-duration: 100s;"><img src="https://i.pravatar.cc/150?u=4" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 10%; right: 15%; animation-duration: 100s;"><img src="" class="avatar-img"></div>
         
            </div>

            <div class="orbit-circle w-[650px] h-[650px]" style="animation-duration: 80s; animation-direction: reverse;">
                <div class="avatar-wrapper" style="top: 50%; left: -30px; animation-duration: 80s; animation-direction: reverse;"><img src="https://i.pravatar.cc/150?u=5" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 5%; right: 25%; animation-duration: 80s; animation-direction: reverse;"><img src="https://i.pravatar.cc/150?u=6" class="avatar-img"></div>
            </div> 

            <div class="orbit-circle w-[480px] h-[480px]" style="animation-duration: 60s;">
                <div class="avatar-wrapper" style="top: -30px; left: 50%; transform: translateX(-50%); animation-duration: 60s;"><img src="https://i.pravatar.cc/150?u=8" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 10%; left: 5%; animation-duration: 60s;"><img src="https://i.pravatar.cc/150?u=9" class="avatar-img"></div>
            </div>
        </div>

        <div class="fixed-login-card px-4">
            <div class="login-card-glass p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Log in</h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase mb-2 tracking-widest">E-mail</label>
                        <input type="email" name="email" :value="old('email')" required autofocus placeholder="example@mail.com"
                            class="w-full px-5 py-3.5 rounded-2xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-black focus:ring-0 transition-all outline-none text-sm">
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-bold text-gray-900 uppercase tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-gray-600 uppercase hover:text-black">Forgot?</a>
                            @endif
                        </div>
                        <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                            class="w-full px-5 py-3.5 rounded-2xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-black focus:ring-0 transition-all outline-none text-sm">
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-black shadow-sm focus:ring-0">
                            <span class="ml-2">Remember me</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-black text-white font-bold py-4 rounded-2xl shadow-lg transition-all transform active:scale-95">
                            Log in
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center border-t border-gray-100 pt-6">
                    <p class="text-sm text-gray-500">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-bold text-black hover:underline ml-1">Register now</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        /**
         * VIDEO TEZLIGINI 4X QILISH
         */
        const video = document.getElementById('loginVideo');

        const enforceSpeed = () => {
            video.playbackRate = 4.0;
        };

        // Video tayyor bo'lganda va ijro boshlanganda tezlikni o'rnatish
        video.addEventListener('loadedmetadata', enforceSpeed);
        video.addEventListener('play', enforceSpeed);
        
        // Brauzerlar avtomatik pasaytirib yubormasligi uchun har 1 soniyada tekshirish
        setInterval(() => {
            if (video.playbackRate !== 4.0) {
                video.playbackRate = 4.0;
            }
        }, 1000);
    </script>
</x-guest-layout>