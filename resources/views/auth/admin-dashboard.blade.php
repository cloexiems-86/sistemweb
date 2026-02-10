<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Admin Dashboard Home - KUA Mojo</title>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Google Font: Public Sans -->
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
<!-- Sidebar Navigation -->
<aside class="w-72 bg-white dark:bg-[#1a2b15] border-r border-[#dee5dc] dark:border-white/10 flex flex-col h-full">
<div class="p-6 flex flex-col gap-6">
<div class="flex gap-3 items-center">
<div class="bg-primary/20 rounded-full p-2">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="KUA Mojo official logo graphic" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBo3mVkRH0Zq0yhiYSIMB95OUeIFijZn2pE5UTp5y91T4DlOKoNQfDKhKWb1gBhFISXSRwwxWONTtgw_azXSrUibqjZNqyvm3QU9vgdIbw5D7PO7w6Go2Frr3qtkWGlCfYMemJJhvI1b4xQUNnoKM8te_g6n3r2i8P5J7XOORxL3cHjulqiKMpvZLaXqDlKOIryUygNDtiRvPHXIe7PiupD6yK4Ft7HJCnmrrhTNCUud89Qrrjp3wB7IDqloq6lsDoPPAW1PAojVw");'></div>
</div>
<div class="flex flex-col">
<h1 class="text-[#131811] dark:text-white text-base font-bold leading-none">KUA Mojo</h1>
<p class="text-[#6c8863] dark:text-[#a0c49d] text-xs font-medium mt-1 uppercase tracking-wider">E-Learning Admin</p>
</div>
</div>
<nav class="flex flex-col gap-1">
<a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary text-[#131811] font-semibold" href="#">
<span class="material-symbols-outlined">dashboard</span>
<span class="text-sm">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0] dark:hover:bg-white/5 transition-colors" href="#">
<span class="material-symbols-outlined">group</span>
<span class="text-sm">Data Catin</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0] dark:hover:bg-white/5 transition-colors" href="#">
<span class="material-symbols-outlined">verified_user</span>
<span class="text-sm">Data Pendamping</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0] dark:hover:bg-white/5 transition-colors" href="#">
<span class="material-symbols-outlined">auto_stories</span>
<span class="text-sm">Materi Edukasi</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0] dark:hover:bg-white/5 transition-colors" href="#">
<span class="material-symbols-outlined">calendar_today</span>
<span class="text-sm">Jadwal Bimbingan</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] dark:text-white/80 hover:bg-[#f1f4f0] dark:hover:bg-white/5 transition-colors" href="#">
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
<!-- Main Content Area -->
<main class="flex-1 flex flex-col overflow-y-auto">
<!-- Top Navigation Bar -->
<header class="sticky top-0 z-10 bg-white/80 dark:bg-[#1a2b15]/80 backdrop-blur-md border-b border-[#dee5dc] dark:border-white/10 px-8 py-4 flex items-center justify-between">
<div class="flex items-center gap-6">
<h2 class="text-[#131811] dark:text-white text-xl font-bold tracking-tight">Admin Dashboard Home</h2>
<div class="relative w-72">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#6c8863] text-lg">search</span>
<input class="w-full h-10 pl-10 pr-4 bg-[#f1f4f0] dark:bg-white/5 border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/50 dark:placeholder-white/40" placeholder="Search data, catin, or activities..." type="text"/>
</div>
</div>
<div class="flex items-center gap-4">
<div class="flex items-center gap-3 px-3 py-1.5 rounded-full border border-[#dee5dc] dark:border-white/10">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-8" data-alt="Administrator user profile picture" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA64lk7Q7kjhIo_MwdXgeqR9PKeOK0boqq2CnWDA7-M8RtcG9gz1Lfy6krVEWYW_WA8n1YjdpvO5LZgy0z2rlzh_0V7IZyfqgD_bDJu1QILkC_UL0i3g0cjQKieRm8mIUVBrhZJmcu7HRVYrtePIZ-Xgu7URUrwGw1gy-Zv1jD8nAh-g3wCfO7HBihcGpif1NCv4Wmzetfb0F7WWvaYPf5EKWoFSUPGr2oZeGYdHz9z26NB9hLd4flixxytrmj8eHc_L-Cq76Cxog");'></div>
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
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
<div class="bg-white dark:bg-[#1a2b15] p-6 rounded-xl border border-[#dee5dc] dark:border-white/10 shadow-sm">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-primary/20 rounded-lg text-primary">
<span class="material-symbols-outlined">groups</span>
</div>
<span class="text-[#078821] text-xs font-bold px-2 py-1 bg-[#078821]/10 rounded-full">+12%</span>
</div>
<p class="text-[#6c8863] dark:text-white/60 text-sm font-medium">Total Calon Pengantin</p>
<p class="text-3xl font-bold dark:text-white mt-1">1,240</p>
</div>
<div class="bg-white dark:bg-[#1a2b15] p-6 rounded-xl border border-[#dee5dc] dark:border-white/10 shadow-sm">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600">
<span class="material-symbols-outlined">supervisor_account</span>
</div>
<span class="text-[#6c8863] text-xs font-bold px-2 py-1 bg-[#6c8863]/10 rounded-full">Stable</span>
</div>
<p class="text-[#6c8863] dark:text-white/60 text-sm font-medium">Total Pendamping</p>
<p class="text-3xl font-bold dark:text-white mt-1">45</p>
</div>
<div class="bg-white dark:bg-[#1a2b15] p-6 rounded-xl border border-[#dee5dc] dark:border-white/10 shadow-sm">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg text-amber-600">
<span class="material-symbols-outlined">menu_book</span>
</div>
<span class="text-[#078821] text-xs font-bold px-2 py-1 bg-[#078821]/10 rounded-full">+2%</span>
</div>
<p class="text-[#6c8863] dark:text-white/60 text-sm font-medium">Materi Edukasi</p>
<p class="text-3xl font-bold dark:text-white mt-1">12 Modules</p>
</div>
<div class="bg-white dark:bg-[#1a2b15] p-6 rounded-xl border border-[#dee5dc] dark:border-white/10 shadow-sm">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-rose-100 dark:bg-rose-900/30 rounded-lg text-rose-600">
<span class="material-symbols-outlined">event_available</span>
</div>
<span class="text-rose-600 text-xs font-bold px-2 py-1 bg-rose-100 dark:bg-rose-900/40 rounded-full">-5%</span>
</div>
<p class="text-[#6c8863] dark:text-white/60 text-sm font-medium">Jadwal Aktif</p>
<p class="text-3xl font-bold dark:text-white mt-1">8 Sessions</p>
</div>
</div>
<!-- Recent Activities Table Section -->
<div class="bg-white dark:bg-[#1a2b15] rounded-xl border border-[#dee5dc] dark:border-white/10 overflow-hidden shadow-sm">
<div class="px-6 py-5 border-b border-[#dee5dc] dark:border-white/10 flex justify-between items-center">
<h3 class="text-[#131811] dark:text-white text-lg font-bold">Recent Activities</h3>
<button class="text-primary text-sm font-bold flex items-center gap-1 hover:underline">
                            View All Activity <span class="material-symbols-outlined text-sm">arrow_forward</span>
</button>
</div>
<div class="overflow-x-auto @container">
<table class="w-full text-left">
<thead>
<tr class="bg-background-light dark:bg-white/5">
<th class="px-6 py-3 text-[#6c8863] dark:text-white/60 text-xs font-bold uppercase tracking-wider">User Name</th>
<th class="px-6 py-3 text-[#6c8863] dark:text-white/60 text-xs font-bold uppercase tracking-wider">Activity Type</th>
<th class="px-6 py-3 text-[#6c8863] dark:text-white/60 text-xs font-bold uppercase tracking-wider">Date/Time</th>
<th class="px-6 py-3 text-[#6c8863] dark:text-white/60 text-xs font-bold uppercase tracking-wider">Status</th>
</tr>
</thead>
<tbody class="divide-y divide-[#dee5dc] dark:divide-white/10">
<tr class="hover:bg-background-light dark:hover:bg-white/5 transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-xs font-bold text-primary">AF</div>
<span class="text-sm font-medium dark:text-white">Ahmad Fauzi</span>
</div>
</td>
<td class="px-6 py-4 text-sm text-[#131811] dark:text-white/80">Completed Module 1: Pra-Nikah</td>
<td class="px-6 py-4 text-sm text-[#6c8863] dark:text-white/60">2023-10-24 10:15</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Success
                                        </span>
</td>
</tr>
<tr class="hover:bg-background-light dark:hover:bg-white/5 transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="size-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-xs font-bold text-blue-600">SA</div>
<span class="text-sm font-medium dark:text-white">Siti Aminah</span>
</div>
</td>
<td class="px-6 py-4 text-sm text-[#131811] dark:text-white/80">New Registration (Kab. Mojokerto)</td>
<td class="px-6 py-4 text-sm text-[#6c8863] dark:text-white/60">2023-10-24 09:30</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                            Pending
                                        </span>
</td>
</tr>
<tr class="hover:bg-background-light dark:hover:bg-white/5 transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="size-8 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-xs font-bold text-rose-600">BS</div>
<span class="text-sm font-medium dark:text-white">Budi Santoso</span>
</div>
</td>
<td class="px-6 py-4 text-sm text-[#131811] dark:text-white/80">Post-Test Submission</td>
<td class="px-6 py-4 text-sm text-[#6c8863] dark:text-white/60">2023-10-23 16:45</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Success
                                        </span>
</td>
</tr>
<tr class="hover:bg-background-light dark:hover:bg-white/5 transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="size-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-xs font-bold text-purple-600">RW</div>
<span class="text-sm font-medium dark:text-white">Rina Wijaya</span>
</div>
</td>
<td class="px-6 py-4 text-sm text-[#131811] dark:text-white/80">Profile Information Update</td>
<td class="px-6 py-4 text-sm text-[#6c8863] dark:text-white/60">2023-10-23 14:20</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Success
                                        </span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Quick Navigation Section -->
<div class="mt-8">
<h3 class="text-[#131811] dark:text-white text-lg font-bold mb-4">Quick Navigation</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<button class="flex items-center gap-4 p-4 bg-white dark:bg-[#1a2b15] rounded-xl border border-[#dee5dc] dark:border-white/10 hover:border-primary transition-all group">
<div class="size-12 rounded-lg bg-primary/20 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined">add_circle</span>
</div>
<div class="text-left">
<p class="text-sm font-bold dark:text-white leading-tight">Add New Material</p>
<p class="text-xs text-[#6c8863] dark:text-white/60">Upload education module</p>
</div>
</button>
<button class="flex items-center gap-4 p-4 bg-white dark:bg-[#1a2b15] rounded-xl border border-[#dee5dc] dark:border-white/10 hover:border-primary transition-all group">
<div class="size-12 rounded-lg bg-primary/20 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined">event</span>
</div>
<div class="text-left">
<p class="text-sm font-bold dark:text-white leading-tight">Schedule Session</p>
<p class="text-xs text-[#6c8863] dark:text-white/60">Book guidance time slot</p>
</div>
</button>
<button class="flex items-center gap-4 p-4 bg-white dark:bg-[#1a2b15] rounded-xl border border-[#dee5dc] dark:border-white/10 hover:border-primary transition-all group">
<div class="size-12 rounded-lg bg-primary/20 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined">description</span>
</div>
<div class="text-left">
<p class="text-sm font-bold dark:text-white leading-tight">Export Reports</p>
<p class="text-xs text-[#6c8863] dark:text-white/60">Download monthly statistics</p>
</div>
</button>
</div>
</div>
</div>
<!-- Footer -->
<footer class="mt-auto px-8 py-6 text-center border-t border-[#dee5dc] dark:border-white/10 bg-white dark:bg-[#1a2b15]">
<p class="text-sm text-[#6c8863] dark:text-white/40">© 2023 KUA Mojo E-Learning System. Ministry of Religious Affairs Republic of Indonesia.</p>
</footer>
</main>
</div>
</body></html>