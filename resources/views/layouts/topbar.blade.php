{{-- TOPBAR: Menggunakan backdrop-blur agar terlihat modern saat di-scroll --}}
<header class="sticky top-0 z-10 bg-white/90 dark:bg-[#065f46]/90 backdrop-blur-md border-b border-emerald-100 dark:border-white/5 px-8 py-4 flex items-center justify-between shadow-sm">

    <div class="flex items-center gap-6">
        {{-- Page Title: Menggunakan warna hijau brand agar tegas --}}
        <h2 class="text-xl font-extrabold text-[#064e3b] dark:text-emerald-50 tracking-tight">
            @yield('page-title','Dashboard')
        </h2>
    </div>

    <div class="flex items-center gap-5">

        {{-- USER PROFILE CARD: Mengarah ke route 'admin.settings' yang memanggil fungsi profile --}}
        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2 rounded-2xl border border-emerald-100 dark:border-white/10 bg-emerald-50/50 dark:bg-white/5 transition-all hover:shadow-md hover:scale-[1.02] cursor-pointer">
            
            {{-- Avatar: Lingkaran hijau dengan inisial nama user --}}
            <div class="size-9 rounded-xl bg-[#065f46] text-amber-400 flex items-center justify-center font-black shadow-inner border border-emerald-700">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>

            <div class="flex flex-col">
                <span class="text-xs font-black text-[#064e3b] dark:text-emerald-50 uppercase tracking-tighter">
                    {{ Auth::user()->name ?? 'Admin Mojo' }}
                </span>
                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold tracking-widest uppercase">
                    {{ Auth::user()->role ?? 'Administrator' }}
                </span>
            </div>
        </a>

        {{-- LOGOUT BUTTON --}}
        <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" class="m-0">
            @csrf
            <button type="button" onclick="confirmLogout(event)" class="group relative flex items-center gap-2 bg-amber-400 hover:bg-amber-500 text-[#065f46] px-6 py-2.5 rounded-xl text-sm font-black transition-all duration-300 shadow-md shadow-amber-200/50 hover:-translate-y-0.5 active:scale-95">
                <span class="material-symbols-outlined text-[18px] font-bold group-hover:rotate-12 transition-transform">logout</span>
                Logout
            </button>
        </form>

    </div>

</header>

{{-- SweetAlert2 untuk Pop-up Konfirmasi Logout --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Sesi Anda akan berakhir!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#065f46', {{-- Hijau KUA --}}
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal',
            borderRadius: '1.25rem'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        })
    }
</script>