<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Data Pendamping - KUA Mojo Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-[#131811] dark:text-white">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 bg-white dark:bg-[#1a2b15] border-r border-[#dee5dc] dark:border-white/10 flex flex-col h-full">
            <div class="p-6 flex flex-col gap-6">
                <div class="flex gap-3 items-center">
                    <div class="bg-primary/20 rounded-full p-2">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBo3mVkRH0Zq0yhiYSIMB95OUeIFijZn2pE5UTp5y91T4DlOKoNQfDKhKWb1gBhFISXSRwwxWONTtgw_azXSrUibqjZNqyvm3QU9vgdIbw5D7PO7w6Go2Frr3qtkWGlCfYMemJJhvI1b4xQUNnoKM8te_g6n3r2i8P5J7XOORxL3cHjulqiKMpvZLaXqDlKOIryUygNDtiRvPHXIe7PiupD6yK4Ft7HJCnmrrhTNCUud89Qrrjp3wB7IDqloq6lsDoPPAW1PAojVw");'></div>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-[#131811] dark:text-white text-base font-bold leading-none">KUA Mojo</h1>
                        <p class="text-[#6c8863] dark:text-[#a0c49d] text-xs font-medium mt-1 uppercase tracking-wider">E-Learning Admin</p>
                    </div>
                </div>
                <nav class="flex flex-col gap-1">
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0]" 
                       href="{{ route('admin.dashboard') }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="text-sm">Dashboard</span>
                    </a>

                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0]" 
                       href="{{ route('admin.catin.index') }}">
                        <span class="material-symbols-outlined">group</span>
                        <span class="text-sm">Data Catin</span>
                    </a>

                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary text-[#131811] font-semibold shadow-md" 
                       href="{{ route('admin.pendamping.index') }}">
                        <span class="material-symbols-outlined">verified_user</span>
                        <span class="text-sm">Data Pendamping</span>
                    </a>

                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0]" href="/materi">
                        <span class="material-symbols-outlined">auto_stories</span>
                        <span class="text-sm">Materi Edukasi</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0]" href="/jadwal">
                        <span class="material-symbols-outlined">calendar_today</span>
                        <span class="text-sm">Jadwal Bimbingan</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0]" href="/settings">
                        <span class="material-symbols-outlined">settings</span>
                        <span class="text-sm">Pengaturan</span>
                    </a>
                </nav>
            </div>
            
            <div class="mt-auto p-6 border-t border-[#dee5dc] dark:border-white/10">
                <div class="bg-primary/10 rounded-lg p-4">
                    <p class="text-xs text-[#131811] dark:text-white/70 font-medium">System Status</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="size-2 bg-primary rounded-full animate-pulse"></span>
                        <span class="text-xs font-bold text-[#131811] dark:text-white">Active Online</span>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-y-auto">
            <header class="sticky top-0 z-10 bg-white/80 dark:bg-[#1a2b15]/80 backdrop-blur-md border-b border-[#dee5dc] dark:border-white/10 px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <h2 class="text-[#131811] dark:text-white text-xl font-bold tracking-tight">Data Pendamping</h2>
                    <div class="relative w-72">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#6c8863] text-lg">search</span>
                        <input class="w-full h-10 pl-10 pr-4 bg-[#f1f4f0] dark:bg-white/5 border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/50 dark:placeholder-white/40" placeholder="Cari pendamping..." type="text"/>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 px-3 py-1.5 rounded-full border border-[#dee5dc] dark:border-white/10">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-8" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA64lk7Q7kjhIo_MwdXgeqR9PKeOK0boqq2CnWDA7-M8RtcG9gz1Lfy6krVEWYW_WA8n1YjdpvO5LZgy0z2rlzh_0V7IZyfqgD_bDJu1QILkC_UL0i3g0cjQKieRm8mIUVBrhZJmcu7HRVYrtePIZ-Xgu7URUrwGw1gy-Zv1jD8nAh-g3wCfO7HBihcGpif1NCv4Wmzetfb0F7WWvaYPf5EKWoFSUPGr2oZeGYdHz9z26NB9hLd4flixxytrmj8eHc_L-Cq76Cxog");'></div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold leading-none dark:text-white">Admin KUA Mojo</span>
                            <span class="text-[10px] text-[#6c8863] dark:text-white/60">Super Administrator</span>
                        </div>
                    </div>
                    <button class="bg-primary text-[#131811] px-5 py-2 rounded-lg text-sm font-bold transition-transform active:scale-95">
                        Logout
                    </button>
                </div>
            </header>

            <div class="p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-black tracking-tight">Manajemen Data Pendamping</h1>
                        <p class="text-[#6c8863] dark:text-white/60">Kelola dan pantau seluruh akun pendamping bimbingan.</p>
                    </div>
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-primary text-[#131811] font-bold rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-lg">person_add</span>
                        <span>Tambah Akun Pendamping</span>
                    </button>
                </div>

                <div class="bg-white dark:bg-[#1a2b15] rounded-xl border border-[#dee5dc] dark:border-white/10 overflow-hidden shadow-sm mb-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-4 gap-4 border-b border-[#dee5dc] dark:border-white/10">
                        <div class="flex gap-4">
                            <button class="px-4 py-2 border-b-2 border-primary text-primary font-bold text-sm">Semua Pendamping</button>
                            <button class="px-4 py-2 text-[#6c8863] dark:text-white/60 font-medium text-sm hover:text-primary transition-colors">Aktif</button>
                            <button class="px-4 py-2 text-[#6c8863] dark:text-white/60 font-medium text-sm hover:text-primary transition-colors">Nonaktif</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-background-light dark:bg-white/5 text-[#6c8863] dark:text-white/60 text-xs uppercase font-bold border-b border-[#dee5dc] dark:border-white/10">
                                    <th class="px-6 py-4">Nama Lengkap</th>
                                    <th class="px-6 py-4">Username</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#dee5dc] dark:divide-white/10">
                                <tr class="hover:bg-background-light dark:hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">SA</div>
                                            <span class="font-semibold dark:text-white">Siti Aminah, M.Ag</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#6c8863] dark:text-white/60">@siti_aminah</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-[10px] font-bold uppercase">Pendamping</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5 text-xs font-bold text-green-600">
                                            <span class="size-2 rounded-full bg-primary animate-pulse"></span> Aktif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button class="p-1.5 text-[#6c8863] hover:text-primary transition-colors"><span class="material-symbols-outlined text-lg">edit_square</span></button>
                                            <button class="p-1.5 text-[#6c8863] hover:text-rose-500 transition-colors"><span class="material-symbols-outlined text-lg">delete</span></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-background-light dark:hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="size-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 font-bold text-xs">AP</div>
                                            <span class="font-semibold dark:text-white">Agus Pratama, S.HI</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#6c8863] dark:text-white/60">@agus_pratama</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-[10px] font-bold uppercase">Pendamping</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5 text-xs font-bold text-gray-400">
                                            <span class="size-2 rounded-full bg-gray-300"></span> Nonaktif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button class="p-1.5 text-[#6c8863] hover:text-primary transition-colors"><span class="material-symbols-outlined text-lg">edit_square</span></button>
                                            <button class="p-1.5 text-[#6c8863] hover:text-rose-500 transition-colors"><span class="material-symbols-outlined text-lg">delete</span></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4 border-t border-[#dee5dc] dark:border-white/10 flex items-center justify-between">
                        <p class="text-xs text-[#6c8863] dark:text-white/60">Menampilkan 1 sampai 2 dari 40 entri</p>
                        <div class="flex gap-1">
                            <button class="p-2 border border-[#dee5dc] dark:border-white/10 rounded hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"><span class="material-symbols-outlined text-sm dark:text-white">chevron_left</span></button>
                            <button class="px-3 py-1 bg-primary text-[#131811] font-bold rounded text-sm">1</button>
                            <button class="px-3 py-1 dark:text-white hover:bg-gray-50 dark:hover:bg-white/5 rounded text-sm">2</button>
                            <button class="p-2 border border-[#dee5dc] dark:border-white/10 rounded hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"><span class="material-symbols-outlined text-sm dark:text-white">chevron_right</span></button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-[#1a2b15] p-5 rounded-xl border border-[#dee5dc] dark:border-white/10 flex items-center gap-4 shadow-sm">
                        <div class="size-12 rounded-lg bg-primary/20 flex items-center justify-center text-primary"><span class="material-symbols-outlined text-3xl">supervisor_account</span></div>
                        <div><p class="text-sm text-[#6c8863] dark:text-white/60">TOTAL PENDAMPING</p><p class="text-2xl font-black dark:text-white">48</p></div>
                    </div>
                    <div class="bg-white dark:bg-[#1a2b15] p-5 rounded-xl border border-[#dee5dc] dark:border-white/10 flex items-center gap-4 shadow-sm">
                        <div class="size-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600"><span class="material-symbols-outlined text-3xl">verified</span></div>
                        <div><p class="text-sm text-[#6c8863] dark:text-white/60">PENDAMPING AKTIF</p><p class="text-2xl font-black dark:text-white">42</p></div>
                    </div>
                    <div class="bg-white dark:bg-[#1a2b15] p-5 rounded-xl border border-[#dee5dc] dark:border-white/10 flex items-center gap-4 shadow-sm">
                        <div class="size-12 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600"><span class="material-symbols-outlined text-3xl">volunteer_activism</span></div>
                        <div><p class="text-sm text-[#6c8863] dark:text-white/60">TOTAL PENDAMPINGAN</p><p class="text-2xl font-black dark:text-white">210</p></div>
                    </div>
                </div>
            </div>

            <footer class="mt-auto py-8 text-center border-t border-[#dee5dc] dark:border-white/10 bg-white dark:bg-[#1a2b15]">
                <p class="text-sm text-[#6c8863] dark:text-white/40">© 2026 KUA Mojo E-Learning System. Ministry of Religious Affairs Republic of Indonesia.</p>
            </footer>
        </main>
    </div>
</body>
</html>