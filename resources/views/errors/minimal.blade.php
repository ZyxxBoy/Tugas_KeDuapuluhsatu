<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }
        </style>
    </head>
    <body class="antialiased bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white/10 backdrop-blur-xl rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] overflow-hidden border border-white/20">
            <!-- Decorative elements -->
            <div class="absolute -top-32 -left-32 w-72 h-72 bg-indigo-500/30 rounded-full mix-blend-screen filter blur-[3rem] animate-pulse"></div>
            <div class="absolute -bottom-32 -right-32 w-72 h-72 bg-purple-500/30 rounded-full mix-blend-screen filter blur-[3rem] animate-pulse" style="animation-delay: 1s;"></div>
            
            <div class="relative p-10 sm:p-16 text-center z-10">
                <div class="inline-block mb-4 px-4 py-1 rounded-full bg-white/10 border border-white/20 text-white/80 text-sm font-semibold tracking-widest uppercase">
                    Oops! Error Terjadi
                </div>

                <h1 class="text-8xl sm:text-[10rem] font-extrabold tracking-tighter drop-shadow-2xl text-transparent bg-clip-text bg-gradient-to-b from-white to-white/50 leading-none">
                    @yield('code')
                </h1>
                
                <h2 class="mt-6 text-2xl sm:text-3xl font-bold tracking-wide text-white/90">
                    @yield('message')
                </h2>
                
                <p class="mt-4 text-base sm:text-lg text-white/70 max-w-md mx-auto leading-relaxed">
                    Maaf, sepertinya Anda tersesat atau tidak memiliki izin untuk mengakses halaman ini. Mari kita kembali ke jalan yang benar.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <button onclick="window.history.back()" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-white bg-white/10 border border-white/20 rounded-full hover:bg-white/20 transition-all duration-300 ease-in-out backdrop-blur-md">
                        Kembali
                    </button>
                    
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-bold text-indigo-900 bg-white rounded-full shadow-[0_0_20px_rgba(255,255,255,0.4)] hover:shadow-[0_0_30px_rgba(255,255,255,0.6)] hover:scale-105 transition-all duration-300 ease-in-out">
                        Beranda
                    </a>

                    @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-red-100 bg-red-500/20 border border-red-500/40 rounded-full hover:bg-red-500/40 transition-all duration-300 ease-in-out backdrop-blur-md hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Log Out
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </body>
</html>
