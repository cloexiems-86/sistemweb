<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - E-Learning KUA Mojo</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#065f46", 
                        "accent": "#fbbf24",
                        "background-light": "#f0fdf4",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        .bg-custom-pattern {
            background-color: #f0fdf4;
            background-image: radial-gradient(#065f46 0.5px, transparent 0.5px), radial-gradient(#065f46 0.5px, #f0fdf4 0.5px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
            opacity: 0.4;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-shake { animation: shake 0.2s ease-in-out 0s 2; }
    </style>
</head>
<body class="bg-emerald-50 min-h-screen flex items-center justify-center p-4 font-display relative overflow-hidden">

    <div class="absolute inset-0 bg-custom-pattern z-0"></div>

    <div class="w-full max-w-[480px] relative z-10">
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-emerald-200/50 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-amber-100/50 rounded-full blur-3xl"></div>

        <div class="relative bg-white/90 backdrop-blur-xl shadow-[0_32px_64px_-12px_rgba(6,95,70,0.2)] rounded-[40px] overflow-hidden p-10 border border-white">
            
            <div class="flex flex-col items-center mb-10">
                <div class="relative group">
                    <div class="absolute inset-0 bg-amber-400 rounded-3xl blur transition group-hover:blur-md opacity-30"></div>
                    <div class="relative w-24 h-24 bg-white rounded-3xl flex items-center justify-center mb-6 shadow-xl border border-emerald-50 rotate-3 transition-transform group-hover:rotate-0">
                        <img src="{{ asset('kua.png') }}" alt="Logo KUA Mojo" class="w-16 h-16 object-contain">
                    </div>
                </div>
                
                <h1 class="text-[#064e3b] tracking-tighter text-3xl font-black text-center leading-none uppercase">
                    E-LEARNING <br><span class="text-amber-500">KUA MOJO</span>
                </h1>
                <p class="text-emerald-700/60 text-[10px] font-extrabold mt-3 uppercase tracking-[0.4em]">Bimbingan Perkawinan Digital</p>
                
                <div class="flex items-center gap-2 mt-6 px-4 py-1.5 bg-emerald-50 rounded-full border border-emerald-100">
                    <span class="material-symbols-outlined text-[16px] text-emerald-600">verified_user</span>
                    <h2 class="text-emerald-900 text-[11px] font-black uppercase tracking-wider">Admin Portal Access</h2>
                </div>
            </div>

            {{-- NOTIFIKASI ERROR --}}
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 animate-shake">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <p class="text-red-700 text-[11px] font-black uppercase tracking-tight">
                        {{ $errors->first() }}
                    </p>
                </div>
            @endif

            <form action="{{ route('admin.login.process') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-[#064e3b] text-[11px] font-black uppercase tracking-widest pl-1">Username / Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-emerald-600 group-focus-within:text-amber-500 transition-colors">person</span>
                        </div>
                        <input name="username" value="{{ old('username') }}" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/30 h-14 pl-12 text-sm font-semibold focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10 transition-all placeholder:text-emerald-900/30 text-emerald-900" placeholder="Masukkan username" type="text" required autofocus/>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center pl-1">
                        <label class="text-[#064e3b] text-[11px] font-black uppercase tracking-widest">Password</label>
                        <a class="text-[10px] text-emerald-600 font-bold hover:text-amber-600 transition underline decoration-emerald-200 underline-offset-4" href="#">Lupa Password?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-emerald-600 group-focus-within:text-amber-500 transition-colors">lock</span>
                        </div>
                        <input name="password" id="password" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/30 h-14 pl-12 pr-12 text-sm font-semibold focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10 transition-all placeholder:text-emerald-900/30 text-emerald-900" placeholder="••••••••" type="password" required/>
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-emerald-400 hover:text-amber-500 transition-colors" onclick="togglePassword()">
                            <span class="material-symbols-outlined text-[20px]" id="pass-icon">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-3 px-1">
                    <div class="relative flex items-center">
                        <input name="remember" id="remember" type="checkbox" class="w-5 h-5 rounded-lg border-emerald-200 text-amber-500 focus:ring-amber-500/20 cursor-pointer bg-emerald-50"/>
                    </div>
                    <label class="text-xs text-emerald-800 font-bold cursor-pointer select-none" for="remember">Ingat sesi login saya</label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full group relative bg-[#065f46] hover:bg-[#064e3b] text-white font-black text-sm py-4 rounded-2xl shadow-xl shadow-emerald-900/20 transition-all flex items-center justify-center gap-3 overflow-hidden active:scale-[0.98]">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-400/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <span class="relative tracking-[0.2em]">MASUK DASHBOARD</span>
                        <span class="material-symbols-outlined relative text-amber-400 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 flex flex-col items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="h-[1px] w-8 bg-emerald-200"></div>
                <div class="flex gap-2">
                    <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></div>
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                    <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                </div>
                <div class="h-[1px] w-8 bg-emerald-200"></div>
            </div>
            
            <p class="text-[10px] text-emerald-900/60 font-black text-center leading-relaxed uppercase tracking-[0.2em]">
                © 2026 Kantor Urusan Agama (KUA) Mojo<br/>
                <span class="text-emerald-800">Kabupaten Kediri • Jawa Timur</span>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passInput = document.getElementById('password');
            const passIcon = document.getElementById('pass-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                passIcon.innerText = 'visibility_off';
            } else {
                passInput.type = 'password';
                passIcon.innerText = 'visibility';
            }
        }
    </script>
</body>
</html>