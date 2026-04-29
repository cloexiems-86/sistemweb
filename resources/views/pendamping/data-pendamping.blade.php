@extends('layouts.app')

@section('title','Data Pendamping')
@section('page-title','Manajemen Akun Pendamping')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    window.successMessage = "{{ session('success') }}";
</script>
@endif

{{-- HEADER SECTION --}}
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-gray-800 dark:text-white uppercase">Manajemen Pendamping</h1>
        <p class="text-gray-500 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
            Monitoring dan kelola akun pendamping bimbingan KUA Mojo
        </p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 group-focus-within:text-primary transition-colors">search</span>
            <input type="text" id="search" placeholder="Cari nama / email / NIP..."
                class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-full sm:w-64 dark:bg-dark-surface focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm"/>
        </div>

        <a href="{{ route('admin.pendamping.create') }}"
            class="flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-[#131811] font-bold rounded-xl shadow-lg shadow-green-200 hover:shadow-green-300 hover:-translate-y-1 transition-all active:scale-95">
            <span class="material-symbols-outlined">person_add</span>
            Tambah Pendamping
        </a>
    </div>
</div>

{{-- STATISTIK CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    {{-- Total Pendamping --}}
    <div class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-gray-100 shadow-sm hover:border-primary/30 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-xs font-black uppercase tracking-wider">Total Pendamping</p>
                {{-- total() milik paginator sudah mengambil jumlah riil seluruh baris database --}}
                <h2 class="text-4xl font-black mt-1 text-gray-800 dark:text-white">{{ $pendamping->total() }}</h2>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl">
                <span class="material-symbols-outlined text-gray-400">group</span>
            </div>
        </div>
    </div>

    {{-- Pendamping Aktif --}}
    <div class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-gray-100 shadow-sm hover:border-primary/30 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-green-600 text-xs font-black uppercase tracking-wider text-opacity-70">Pendamping Aktif</p>
                <h2 class="text-4xl font-black mt-1 text-green-600">
                    {{-- Menghitung langsung ke database --}}
                    {{ \App\Models\Pendamping::where('status', 'aktif')->count() }}
                </h2>
            </div>
            <div class="p-3 bg-green-50 rounded-xl text-green-600">
                <span class="material-symbols-outlined">verified_user</span>
            </div>
        </div>
    </div>

    {{-- Nonaktif --}}
    <div class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-gray-100 shadow-sm hover:border-gray-200 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-xs font-black uppercase tracking-wider text-opacity-70">Nonaktif</p>
                <h2 class="text-4xl font-black mt-1 text-gray-400">
                    {{-- Menghitung langsung ke database --}}
                    {{ \App\Models\Pendamping::where('status', '!=', 'aktif')->count() }}
                </h2>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl text-gray-400">
                <span class="material-symbols-outlined">person_off</span>
            </div>
        </div>
    </div>
</div>

{{-- TABLE SECTION --}}
<div class="bg-white dark:bg-dark-surface rounded-3xl border border-gray-100 overflow-hidden shadow-xl shadow-gray-200/50">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-primary text-[#131811] flex items-center justify-center font-bold text-xs">{{ $pendamping->count() }}</span>
            <p class="font-black text-gray-700 dark:text-white uppercase text-xs tracking-widest">Daftar Akun Pendamping</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50/80 border-b text-[11px] uppercase text-gray-400 font-black tracking-widest">
                <tr>
                    <th class="px-6 py-4">Informasi Pendamping</th>
                    <th class="px-6 py-4">Kontak & Akun</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="tableBody" class="divide-y divide-gray-50">
                @forelse ($pendamping as $item)
                <tr class="group hover:bg-primary/5 transition-all duration-300">
                    {{-- PROFIL --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=16a34a&color=fff&bold=true"
                                    class="w-12 h-12 rounded-2xl shadow-sm border-2 border-white group-hover:scale-110 transition-transform"/>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-black text-gray-800 dark:text-white uppercase text-sm tracking-tight leading-none mb-1">
                                    {{ $item->nama }}
                                </span>
                                <span class="text-[11px] font-bold text-primary uppercase tracking-tighter opacity-80">
                                    NIP: {{ $item->nip ?? '-' }}
                                </span>
                                <div class="flex items-center gap-1 mt-1.5 opacity-60">
                                    <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                    <p class="text-[10px] font-medium italic">
                                        Terdaftar: {{ optional($item->created_at)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- KONTAK --}}
                    <td class="px-6 py-5">
                        <div class="space-y-1">
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-primary/10 text-primary text-[11px] font-black uppercase">
                                {{ $item->email }}
                            </div>
                            <div class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-gray-400">
                                <span class="material-symbols-outlined text-[16px] text-green-500">call</span>
                                {{ $item->no_hp ?? '-' }}
                            </div>
                        </div>
                    </td>

                    {{-- RUANGAN
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase border border-blue-100">
                            <span class="material-symbols-outlined text-[14px] mr-1">meeting_room</span>
                            {{ $item->ruangan ?? 'Belum ditentukan' }}
                        </span>
                    </td> --}}

                    {{-- STATUS --}}
                    <td class="px-6 py-5 text-center">
                        <span class="inline-flex items-center px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border-2
                            {{ $item->status == 'aktif'
                                ? 'bg-green-50 text-green-600 border-green-100'
                                : 'bg-gray-50 text-gray-400 border-gray-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $item->status == 'aktif' ? 'bg-green-600' : 'bg-gray-400' }}"></span>
                            {{ $item->status }}
                        </span>
                    </td>

                    {{-- AKSI --}}
                    <td class="px-6 py-5 text-center">
                        <div class="flex justify-center items-center gap-1">
                            <a href="{{ route('admin.pendamping.edit',$item->id) }}"
                                class="w-9 h-9 flex items-center justify-center text-amber-500 hover:bg-amber-500 hover:text-white rounded-xl transition-all shadow-sm hover:shadow-amber-200"
                                title="Edit Data">
                                <span class="material-symbols-outlined text-[20px]">edit_note</span>
                            </a>

                            <form action="{{ route('admin.pendamping.destroy',$item->id) }}" method="POST" class="form-hapus inline">
                                @csrf @method('DELETE')
                                <button type="button" 
                                    class="btn-hapus w-9 h-9 flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all shadow-sm hover:shadow-red-200"
                                    title="Hapus Data">
                                    <span class="material-symbols-outlined text-[20px]">delete_sweep</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20">
                        <div class="flex flex-col items-center justify-center opacity-30">
                            <span class="material-symbols-outlined text-6xl">folder_off</span>
                            <p class="font-black italic mt-2 uppercase tracking-widest text-sm">Belum ada data pendamping</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if ($pendamping->hasPages())
    <div class="p-6 border-t border-gray-100 bg-gray-50/30 flex justify-center items-center">
        {{ $pendamping->links('partials.pagination') }}
    </div>
    @endif
</div>

{{-- SCRIPT --}}
<script>
    // Sweetalert Success
    if(window.successMessage){
        Swal.fire({
            icon: 'success',
            title: '<span class="font-black uppercase text-xl">Berhasil</span>',
            text: window.successMessage,
            timer: 2500,
            showConfirmButton: false,
            borderRadius: '20px',
            confirmButtonColor: '#16a34a'
        })
    }

    // Delete Confirmation
    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function() {
            let form = this.closest('.form-hapus')
            Swal.fire({
                title: '<span class="font-black uppercase text-red-600">Hapus Pendamping?</span>',
                text: "Akun pendamping ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                borderRadius: '20px'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            })
        })
    })

    // Real-time Search
    document.getElementById("search").addEventListener("keyup", function() {
        let value = this.value.toLowerCase()
        document.querySelectorAll("#tableBody tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none"
        })
    })
</script>

@endsection