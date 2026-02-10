<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - E-Learning KUA Mojo</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#4ce619",
                        "background-light": "#f6f8f6",
                        "background-dark": "#152111",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        body { font-family: "Public Sans", sans-serif; }
        /* Pola titik-titik halus sesuai gambar */
        .bg-pattern {
            background-color: #f6f8f6;
            background-image: radial-gradient(#dee5dc 0.8px, transparent 0.8px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-[450px]">
    <div class="bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-[24px] overflow-hidden p-10">
        
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 bg-[#eefdee] rounded-full flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary text-4xl">account_balance</span>
            </div>
            <h1 class="text-[#131811] tracking-tight text-2xl font-bold text-center">
                E-Learning <span class="text-primary">KUA Mojo</span>
            </h1>
            <p class="text-[#6c8863] text-[10px] font-bold mt-1 uppercase tracking-[0.2em]">Bimbingan Perkawinan</p>
            <div class="w-10 h-[3px] bg-primary mt-4 rounded-full"></div>
            
            <h2 class="text-[#131811] text-sm font-bold mt-8">Admin Portal Access</h2>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div class="flex flex-col gap-1.5">
                <label class="text-[#131811] text-xs font-bold pl-1">Username</label>
                <div class="relative flex items-center">
                    <input name="username" class="w-full rounded-xl border-[#dee5dc] bg-white h-12 pl-12 text-sm focus:border-primary focus:ring-1 focus:ring-primary/20 placeholder:text-gray-300" placeholder="Enter your admin username" type="text" required autofocus/>
                    <div class="absolute left-4 flex items-center text-gray-400">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <div class="flex justify-between items-center pl-1">
                    <label class="text-[#131811] text-xs font-bold">Password</label>
                    <a class="text-[10px] text-primary font-bold hover:underline" href="#">Forgot?</a>
                </div>
                <div class="relative flex items-center">
                    <input name="password" class="w-full rounded-xl border-[#dee5dc] bg-white h-12 pl-12 pr-12 text-sm focus:border-primary focus:ring-1 focus:ring-primary/20 placeholder:text-gray-300" placeholder="Enter your security password" type="password" required/>
                    <div class="absolute left-4 flex items-center text-gray-400">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                    </div>
                    <div class="absolute right-4 flex items-center text-gray-400 cursor-pointer hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 px-1 pt-1">
                <input name="remember" id="remember" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"/>
                <label class="text-[11px] text-gray-500 font-medium cursor-pointer" for="remember">Remember this session</label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-[#152111] font-bold text-xs py-4 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 tracking-wider">
                    <span>MASUK DASHBOARD</span>
                    <span class="material-symbols-outlined text-lg">login</span>
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 flex flex-col items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-500">
                <span class="material-symbols-outlined text-xl">shield</span>
            </div>
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-500">
                <span class="material-symbols-outlined text-xl">verified_user</span>
            </div>
        </div>
        
        <p class="text-[10px] text-[#6c8863] font-medium text-center leading-relaxed">
            © 2024 Kantor Urusan Agama (KUA) Mojo.<br/>
            Sistem Informasi Digitalisasi Bimbingan Perkawinan.
        </p>
    </div>
</div>

</body>
</html>