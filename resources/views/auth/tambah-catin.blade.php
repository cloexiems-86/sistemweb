<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tambah Akun Catin - KUA Mojo Admin</title>
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
                },
            },
        }
    </script>
</head>
<body class="bg-background-light font-display text-[#131811]">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 bg-white border-r border-[#dee5dc] flex flex-col h-full">
            <div class="p-6 flex flex-col gap-6">
                <div class="flex gap-3 items-center">
                    <div class="bg-primary/20 rounded-full p-2">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://avatar.iran.liara.run/public/admin");'></div>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-[#131811] text-base font-bold leading-none">KUA Mojo</h1>
                        <p class="text-[#6c8863] text-xs font-medium mt-1 uppercase tracking-wider">E-Learning Admin</p>
                    </div>
                </div>
                <nav class="flex flex-col gap-1">
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] hover:bg-[#f1f4f0]" href="{{ route('admin.dashboard') }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="text-sm">Dashboard</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary text-[#131811] font-semibold shadow-md" href="{{ route('admin.catin.index') }}">
                        <span class="material-symbols-outlined">group</span>
                        <span class="text-sm">Data Catin</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#131811] hover:bg-[#f1f4f0]" href="{{ route('admin.pendamping.index') }}">
                        <span class="material-symbols-outlined">verified_user</span>
                        <span class="text-sm">Data Pendamping</span>
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-y-auto">
            <header class="bg-white/80 backdrop-blur-md border-b border-[#dee5dc] px-8 py-4 flex items-center justify-between">
                <h2 class="text-[#131811] text-xl font-bold">Tambah Akun Catin</h2>
                <button onclick="history.back()" class="text-sm font-bold text-[#6c8863] hover:text-[#131811] flex items-center gap-1">
                    <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
                </button>
            </header>

            <div class="p-8">
                <div class="max-w-4xl mx-auto">
                    <div class="mb-8">
                        <h1 class="text-3xl font-black tracking-tight">Registrasi Calon Pengantin</h1>
                        <p class="text-[#6c8863]">Lengkapi formulir di bawah ini untuk membuat akun baru.</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-[#dee5dc] shadow-sm overflow-hidden">
                        <form action="#" method="POST" class="p-8 space-y-6">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <h3 class="text-sm font-bold text-primary uppercase tracking-widest mb-4">Informasi Akun</h3>
                                    <hr class="border-[#dee5dc]">
                                </div>
                                
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-bold">Nama Lengkap</label>
                                    <input type="text" placeholder="Masukkan nama lengkap" class="rounded-xl border-[#dee5dc] focus:ring-primary focus:border-primary text-sm">
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-bold">Username</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">@</span>
                                        <input type="text" placeholder="username" class="w-full pl-8 rounded-xl border-[#dee5dc] focus:ring-primary focus:border-primary text-sm">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-bold">Email</label>
                                    <input type="email" placeholder="contoh@mail.com" class="rounded-xl border-[#dee5dc] focus:ring-primary focus:border-primary text-sm">
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-bold">Kata Sandi</label>
                                    <input type="password" placeholder="••••••••" class="rounded-xl border-[#dee5dc] focus:ring-primary focus:border-primary text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                                <div class="col-span-2">
                                    <h3 class="text-sm font-bold text-primary uppercase tracking-widest mb-4">Data Personal</h3>
                                    <hr class="border-[#dee5dc]">
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-bold">NIK</label>
                                    <input type="text" placeholder="16 digit nomor induk kependudukan" class="rounded-xl border-[#dee5dc] focus:ring-primary focus:border-primary text-sm">
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-bold">Nomor WhatsApp</label>
                                    <input type="text" placeholder="08xxxxxxxxxx" class="rounded-xl border-[#dee5dc] focus:ring-primary focus:border-primary text-sm">
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-bold">Jenis Kelamin</label>
                                    <select class="rounded-xl border-[#dee5dc] focus:ring-primary focus:border-primary text-sm">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-bold">Tanggal Rencana Pernikahan</label>
                                    <input type="date" class="rounded-xl border-[#dee5dc] focus:ring-primary focus:border-primary text-sm">
                                </div>
                            </div>

                            <div class="pt-6 flex justify-end gap-3">
                                <button type="button" onclick="history.back()" class="px-6 py-2.5 rounded-xl text-sm font-bold border border-[#dee5dc] hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-primary text-[#131811] rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                                    Simpan Akun Catin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>