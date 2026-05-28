<aside class="w-72 bg-[#065f46] dark:bg-[#042f2e] border-r border-white/10 flex flex-col h-full shadow-2xl">

    <div class="p-6 flex flex-col gap-8">

        {{-- LOGO SECTION --}}
        <div class="flex flex-col items-center text-center gap-3 py-2">
            <div class="relative">
                {{-- Container Logo tetap putih agar logo PNG terlihat jelas --}}
                <div class="size-20 rounded-2xl bg-white p-2 shadow-lg rotate-3 transition-transform hover:rotate-0 flex items-center justify-center border-2 border-amber-400">
                    <img src="{{ asset('kua.png') }}" 
                         alt="Logo KUA Mojo" 
                         class="w-full h-full object-contain">
                </div>
                {{-- Status Indicator Kuning --}}
                <span class="absolute -bottom-1 -right-1 size-5 bg-amber-400 border-4 border-[#065f46] rounded-full shadow-sm"></span>
            </div>

            <div class="flex flex-col">
                <h1 class="text-white text-xl font-black tracking-tight leading-none">
                    KUA MOJO
                </h1>
                <p class="text-amber-400 text-[10px] font-bold uppercase tracking-[0.25em] mt-1.5">
                    Admin Dashboard
                </p>
            </div>
        </div>

        {{-- NAVIGATION MENU --}}
        <nav class="flex flex-col gap-7">
            
            {{-- Kategori: Utama --}}
            <div>
                <p class="px-4 text-[11px] font-bold text-emerald-200/50 uppercase tracking-widest mb-3">Utama</p>
                <div class="flex flex-col gap-1.5">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400 text-[#065f46] shadow-lg shadow-amber-900/20 font-bold' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110">dashboard</span>
                        <span class="text-sm">Dashboard</span>
                    </a>
                </div>
            </div>

            {{-- Kategori: Manajemen Data --}}
            <div>
                <p class="px-4 text-[11px] font-bold text-emerald-200/50 uppercase tracking-widest mb-3">Manajemen Data</p>
                <div class="flex flex-col gap-1.5">
                    <a href="{{ route('admin.catin.index') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.catin.*') ? 'bg-amber-400 text-[#065f46] shadow-lg shadow-amber-900/20 font-bold' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110">group</span>
                        <span class="text-sm">Data Catin</span>
                    </a>

                    <a href="{{ route('admin.pendamping.index') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.pendamping.*') ? 'bg-amber-400 text-[#065f46] shadow-lg shadow-amber-900/20 font-bold' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110">verified_user</span>
                        <span class="text-sm">Pendamping</span>
                    </a>

                    <a href="{{ route('admin.materi.index') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.materi.*') ? 'bg-amber-400 text-[#065f46] shadow-lg shadow-amber-900/20 font-bold' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110">auto_stories</span>
                        <span class="text-sm">Materi Edukasi</span>
                    </a>
                </div>
            </div>

            {{-- Kategori: Layanan --}}
            <div>
                <p class="px-4 text-[11px] font-bold text-emerald-200/50 uppercase tracking-widest mb-3">Layanan</p>
                <div class="flex flex-col gap-1.5">
                    <a href="{{ route('admin.jadwal.index') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.jadwal.*') ? 'bg-amber-400 text-[#065f46] shadow-lg shadow-amber-900/20 font-bold' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110">calendar_today</span>
                        <span class="text-sm">Jadwal Bimbingan</span>
                    </a>

                    <a href="{{ route('admin.ujian.index') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.ujian.*') ? 'bg-amber-400 text-[#065f46] shadow-lg shadow-amber-900/20 font-bold' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110">quiz</span>
                        <span class="text-sm">Ujian</span>
                    </a>

                    <a href="{{ route('admin.sertifikat.index') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.sertifikat.*') ? 'bg-amber-400 text-[#065f46] shadow-lg shadow-amber-900/20 font-bold' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110">workspace_premium</span>
                        <span class="text-sm">Sertifikat</span>
                    </a>

                    <a href="{{ route('admin.pengumuman.index') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.pengumuman.*') ? 'bg-amber-400 text-[#065f46] shadow-lg shadow-amber-900/20 font-bold' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110">announcement</span>
                        <span class="text-sm">Pengumuman</span>
                    </a>
                </div>
            </div>

            {{-- Kategori: Laporan --}}
            <div>
                <p class="px-4 text-[11px] font-bold text-emerald-200/50 uppercase tracking-widest mb-3">Laporan</p>
                <div class="flex flex-col gap-1.5">
                    <a href="{{ route('admin.report.index') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.report.*') ? 'bg-amber-400 text-[#065f46] shadow-lg shadow-amber-900/20 font-bold' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110">assessment</span>
                        <span class="text-sm">Laporan Catin</span>
                    </a>
                </div>
            </div>

        </nav>

    </div>

    {{-- SYSTEM STATUS --}}
    <div class="mt-auto p-6">
        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-emerald-200 uppercase tracking-wider">System Health</p>
                <div class="flex items-center gap-1.5">
                    <span class="relative flex size-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full size-2 bg-amber-400"></span>
                    </span>
                    <span class="text-[10px] font-bold text-amber-400">ONLINE</span>
                </div>
            </div>
            <div class="h-1.5 w-full bg-black/20 rounded-full overflow-hidden border border-white/5">
                <div class="h-full bg-amber-400 w-[98%] transition-all duration-500"></div>
            </div>
            <p class="text-[9px] text-emerald-200/50 mt-2 text-center font-medium">KUA Mojo Digital Services v2.0</p>
        </div>
    </div>

</aside>