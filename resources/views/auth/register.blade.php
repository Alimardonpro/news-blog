
<x-guest-layout>
    <style>
        /* Animatsiyalar */
        @keyframes orbit-rotation {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes counter-rotation {
            from { transform: rotate(0deg); }
            to { transform: rotate(-360deg); }
        }

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

    background-color: #f8fafb;
    background-size: cover;        
    background-position: center;  
    background-repeat: no-repeat;  
}

.video-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: -10;
    overflow: hidden;
}

.video-bg video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}



        .orbit-circle {
            position: absolute;
            border: 2px dashed #656566;
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
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.87);
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .avatar-img:hover {
            transform: scale(1.15);
        }

        /* REGISTER KARTASI */
        .fixed-register-card {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 50;
            width: 100%;
            max-width: 450px; /* Register uchun biroz kengroq */
        }

        .register-card-glass {
           background: rgba(238, 234, 234, 0.37);
            backdrop-filter: blur(10px);
            border:  1px solid rgba(3, 1, 1, 0.51);
            box-shadow: 0 20px 50px rgba(46, 46, 46, 0.12);
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Scrollbar dizayni */
        .register-card-glass::-webkit-scrollbar {
            width: 4px;
        }
        .register-card-glass::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }



         .video-mp4 {
              position: fixed;
              inset: 0;              /* top:0; right:0; bottom:0; left:0 */
              width: 100vw;
              height: 100vh;
               z-index: -10;          /* hamma narsadan orqada */
               overflow: hidden;
            }

           .video-mp4 video {
               width: 100%;
               height: 100%;
               object-fit: cover;     /* MUHIM: videoni to‘liq ekran qiladi */
             }
    </style>


    <div class="min-h-screen relative overflow-y-hidden">
        
        <div class="orbit-container">
                <div class="orbit-container">
                 <div class="video-mp4">
              <video autoplay muted loop playsinline>
               <source src="{{ asset('video/video.mp4') }}" type="video/mp4">
               </video>
        </div>
            <div class="orbit-circle w-[850px] h-[850px]" style="animation-duration: 100s;">
                <div class="avatar-wrapper" style="top: 10%; left: 15%; animation-duration: 100s;"><img src="https://i.pravatar.cc/150?u=11" class="avatar-img"></div>
                <div class="avatar-wrapper" style="top: 10%; right: 15%; animation-duration: 100s;"><img src="https://i.pravatar.cc/150?u=12" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 10%; left: 15%; animation-duration: 100s;"><img src="https://i.pravatar.cc/150?u=13" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 10%; right: 15%; animation-duration: 100s;"><img src="https://i.pravatar.cc/150?u=14" class="avatar-img"></div>
            </div>

            <div class="orbit-circle w-[650px] h-[650px]" style="animation-duration: 80s; animation-direction: reverse;">
                <div class="avatar-wrapper" style="top: 50%; left: -30px; animation-duration: 80s; animation-direction: reverse;"><img src="https://i.pravatar.cc/150?u=15" class="avatar-img"></div>
                <div class="avatar-wrapper" style="top: 5%; right: 25%; animation-duration: 80s; animation-direction: reverse;"><img src="https://i.pravatar.cc/150?u=16" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 5%; right: 25%; animation-duration: 80s; animation-direction: reverse;"><img src="https://i.pravatar.cc/150?u=17" class="avatar-img"></div>
            </div>

            <div class="orbit-circle w-[480px] h-[480px]" style="animation-duration: 60s;">
                <div class="avatar-wrapper" style="top: -30px; left: 50%; transform: translateX(-50%); animation-duration: 60s;"><img src="https://i.pravatar.cc/150?u=18" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 10%; left: 5%; animation-duration: 60s;"><img src="https://i.pravatar.cc/150?u=19" class="avatar-img"></div>
                <div class="avatar-wrapper" style="bottom: 10%; right: 5%; animation-duration: 60s;"><img src="https://i.pravatar.cc/150?u=20" class="avatar-img"></div>
            </div>
        </div>
  
          

        <div class="fixed-register-card px-4 overflow-y-hidden">
            <div class="register-card-glass p-8 rounded-[2.5rem]">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Create Account</h2>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase mb-1.5 tracking-widest">Full Name</label>
                        <input type="text" name="name" :value="old('name')" required autofocus placeholder="John Doe"
                        class="w-full px-5 py-3.5 rounded-2xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-black focus:ring-0 transition-all outline-none text-sm">
                </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase mb-1.5 tracking-widest">Userame</label>
                        <input type="text" name="username" :value="old('username')" required autofocus placeholder="John Doe"
                            class="w-full px-5 py-3.5 rounded-2xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-black focus:ring-0 transition-all outline-none text-sm">
                 </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase mb-1.5 tracking-widest">E-mail</label>
                        <input type="email" name="email" :value="old('email')" required placeholder="example@mail.com"
                            class="w-full px-5 py-3.5 rounded-2xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-black focus:ring-0 transition-all outline-none text-sm">
                  </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase mb-1.5 tracking-widest">Password</label>
                        <input type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                            class="w-full px-5 py-3.5 rounded-2xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-black focus:ring-0 transition-all outline-none text-sm">
                     </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase mb-1.5 tracking-widest">Confirm Password</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                             class="w-full px-5 py-3.5 rounded-2xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-black focus:ring-0 transition-all outline-none text-sm">
                   </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[black] text-white font-bold py-4 rounded-2xl shadow-lg  transition-all transform active:scale-95">
                            Create Account
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center border-t border-gray-100 pt-5">
                    <p class="text-sm text-gray-500">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-bold text-[black] hover:underline ml-1">Log in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

