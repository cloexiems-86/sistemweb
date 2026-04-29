<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    {{-- Tambahkan CSRF Token (Sangat Penting untuk Logout & AJAX) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard - KUA Mojo')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    {{-- Alpine.js (PENTING: Agar Dropdown Profil & Sidebar Mobile bisa diklik tanpa JS manual yang ribet) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors:{
                    brand: "#065f46", 
                    primary: "#fbbf24", 
                    "background-light": "#f0fdf4", 
                    "background-dark": "#022c22"
                },
                fontFamily:{
                    display:["Public Sans","sans-serif"]
                }
            }
        }
    }
    </script>

    <style>
    .material-symbols-outlined {
        display: inline-block;
        vertical-align: middle;
        line-height: 1;
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    /* Custom Scrollbar biar makin cantik */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #065f4633; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #065f4666; }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-800 dark:text-emerald-50 transition-colors duration-300">

{{-- Tambahkan x-data di sini agar fitur Alpine.js aktif di seluruh halaman --}}
<div x-data="{ sidebarOpen: false, profileOpen: false }" class="flex h-screen overflow-hidden text-sm lg:text-base">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- MAIN CONTENT AREA --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-30 flex-none bg-white/80 dark:bg-brand/20 backdrop-blur-md border-b border-emerald-100 dark:border-white/5 shadow-sm">
            @include('layouts.topbar')
        </header>

        {{-- CONTENT BODY --}}
        <main class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar">
            <div class="p-6 lg:p-10 min-h-full flex flex-col">
                
                {{-- Container utama Content --}}
                <div class="flex-1 animate-fade-in">
                    {{-- Alert Success/Error Global (Biar kalau ada update profil muncul notifnya) --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @yield('content')
                </div>

                {{-- FOOTER --}}
                <footer class="mt-12 py-8 border-t border-emerald-100 dark:border-white/5">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-xs md:text-sm text-emerald-800/60 dark:text-emerald-400/40 font-medium tracking-wide text-center md:text-left">
                            © {{ date('Y') }} <span class="text-brand dark:text-emerald-400 font-bold">KUA Mojo</span> E-Learning System
                        </p>
                        <div class="flex gap-4">
                            <span class="text-[10px] bg-emerald-100 dark:bg-white/5 text-emerald-700 dark:text-emerald-300 px-3 py-1 rounded-full font-bold uppercase tracking-tighter">
                                Official Admin Panel
                            </span>
                        </div>
                    </div>
                </footer>
            </div>
        </main>
    </div>
</div>

@stack('scripts')

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
</style>

</body>
</html>