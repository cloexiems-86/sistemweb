@extends('layouts.app')

@section('title','Manajemen Materi & Kuis')
@section('page-title','Edukasi & Ujian Catin')

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
        <h1 class="text-3xl font-black tracking-tight text-gray-800 dark:text-white uppercase text-shadow-sm">Materi & Kuis Kelulusan</h1>
        <p class="text-gray-500 flex items-center gap-2 font-medium">
            <span class="w-2 h-2 rounded-full bg-[#4ce619] animate-pulse"></span>
            Kelola materi bimbingan beserta bank soal kuisnya di sini
        </p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 group-focus-within:text-[#4ce619] transition-colors">search</span>
            <input type="text" id="search" placeholder="Cari judul atau status..."
                class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-full sm:w-64 dark:bg-dark-surface focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all shadow-sm dark:border-gray-700"/>
        </div>

        <a href="{{ route('admin.materi.create') }}"
            class="flex items-center justify-center gap-2 px-6 py-2.5 bg-[#4ce619] text-white font-bold rounded-xl shadow-lg shadow-green-200 hover:shadow-green-300 hover:-translate-y-1 transition-all active:scale-95">
            <span class="material-symbols-outlined">add_circle</span>
            Tambah Materi
        </a>
    </div>
</div>

{{-- STATISTIK CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    {{-- Total Materi --}}
    <div class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm hover:border-[#4ce619]/30 transition-all group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-xs font-black uppercase tracking-wider">Total Materi</p>
                {{-- total() pada pagination sudah benar mengambil total riil database --}}
                <h2 class="text-4xl font-black mt-1 text-gray-800 dark:text-white">{{ $materi->total() }}</h2>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl text-gray-400 group-hover:text-[#4ce619] transition-colors">
                <span class="material-symbols-outlined">library_books</span>
            </div>
        </div>
    </div>

    {{-- Materi Aktif --}}
    <div class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm hover:border-[#4ce619]/30 transition-all border-l-4 border-l-[#4ce619]">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[#4ce619] text-xs font-black uppercase tracking-wider">Materi Aktif</p>
                <h2 class="text-4xl font-black mt-1 text-[#4ce619]">
                    {{ \App\Models\Materi::where('status', 'aktif')->count() }}
                </h2>
            </div>
            <div class="p-3 bg-green-50 dark:bg-[#4ce619]/10 rounded-xl text-[#4ce619]">
                <span class="material-symbols-outlined">visibility</span>
            </div>
        </div>
    </div>

    {{-- Total Bank Soal --}}
    <div class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm hover:border-blue-200 transition-all border-l-4 border-l-blue-400">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-blue-500 text-xs font-black uppercase tracking-wider">Total Bank Soal</p>
                <h2 class="text-4xl font-black mt-1 text-blue-500">
                    {{-- Menghitung seluruh baris di tabel kuis/soal --}}
                    {{ \App\Models\Kuis::count() }}
                </h2>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-blue-500">
                <span class="material-symbols-outlined">quiz</span>
            </div>
        </div>
    </div>

    {{-- Non-Aktif --}}
    <div class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm hover:border-red-200 transition-all border-l-4 border-l-red-400">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-red-500 text-xs font-black uppercase tracking-wider text-opacity-70">Non-Aktif</p>
                <h2 class="text-4xl font-black mt-1 text-red-500">
                    {{ \App\Models\Materi::where('status', 'nonaktif')->count() }}
                </h2>
            </div>
            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl text-red-500">
                <span class="material-symbols-outlined">visibility_off</span>
            </div>
        </div>
    </div>


    {{-- Letakkan di samping card Non-Aktif --}}
    <div class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm hover:border-purple-200 transition-all border-l-4 border-l-purple-400">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-purple-500 text-xs font-black uppercase tracking-wider">Total Akses</p>
                <h2 class="text-4xl font-black mt-1 text-purple-500">{{ $counts['total_akses'] }}</h2>
            </div>
            <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl text-purple-500">
                <span class="material-symbols-outlined">analytics</span>
            </div>
        </div>
    </div>
</div>

{{-- TABLE SECTION --}}
<div class="bg-white dark:bg-dark-surface rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-xl shadow-gray-200/50 dark:shadow-none">
    <div class="p-6 border-b border-gray-50 dark:border-gray-800 flex justify-between items-center bg-gray-50/30 dark:bg-gray-800/50">
        <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-[#4ce619] text-white flex items-center justify-center font-bold text-xs">{{ $materi->total() }}</span>
            <p class="font-black text-gray-700 dark:text-white uppercase text-xs tracking-widest">Daftar Materi & Paket Kuis</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/80 dark:bg-gray-800 border-b dark:border-gray-700 text-[11px] uppercase text-gray-400 font-black tracking-widest">
                <tr>
                    <th class="px-6 py-4">Informasi Materi</th>
                    <th class="px-6 py-4">Status & File</th>
                    <th class="px-6 py-4 text-center">Integrasi Kuis</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="tableBody" class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse ($materi as $item)
                <tr class="group hover:bg-[#4ce619]/5 dark:hover:bg-[#4ce619]/5 transition-all duration-300 {{ $item->status == 'nonaktif' ? 'bg-gray-50/50 dark:bg-dark-surface/50' : '' }}">
                    
                    {{-- MATERI --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="bg-[#4ce619]/10 dark:bg-[#4ce619]/20 rounded-2xl p-3 {{ $item->status == 'aktif' ? 'group-hover:rotate-6 group-hover:scale-110' : 'opacity-50' }} transition-all">
                                <span class="material-symbols-outlined text-[#4ce619]">description</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-black text-gray-800 dark:text-white uppercase text-sm tracking-tight leading-none mb-1">
                                    {{ $item->judul }}
                                </span>
                                <div class="flex items-center gap-1 opacity-60 mb-1 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                    <p class="text-[10px] font-medium">Rilis: {{ optional($item->created_at)->translatedFormat('d M Y') }}</p>
                                </div>
                                <p class="text-[10px] text-gray-400 truncate max-w-[250px] italic">
                                    "{{ Str::limit($item->deskripsi, 60) }}"
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- STATUS & FILE --}}
                    <td class="px-6 py-5">
                        <div class="flex flex-col gap-2">
                            @if($item->status == 'aktif')
                                <span class="w-fit px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-[9px] font-black uppercase rounded-full border border-green-200 dark:border-green-800 inline-flex items-center gap-1">
                                    <span class="w-1 h-1 rounded-full bg-green-600 animate-pulse"></span> Aktif
                                </span>
                            @else
                                <span class="w-fit px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-400 text-[9px] font-black uppercase rounded-full border border-gray-200 dark:border-gray-700 inline-flex items-center gap-1">
                                    <span class="w-1 h-1 rounded-full bg-gray-400"></span> Diarsipkan
                                </span>
                            @endif

                            @if($item->file)
                                <a href="{{ asset('storage/'.$item->file) }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-blue-500 dark:text-blue-400 text-[10px] font-black uppercase hover:underline group/file">
                                    <span class="material-symbols-outlined text-[14px] group-hover/file:translate-x-0.5 transition-transform">visibility</span> Lihat Dokumen
                                </a>
                            @else
                                <span class="text-gray-300 dark:text-gray-600 text-[10px] font-black uppercase italic">Tanpa Lampiran</span>
                            @endif
                            {{-- TAMBAHKAN INI --}}
                            <div class="flex items-center gap-1 text-[10px] font-bold text-gray-500 uppercase">
                                <span class="material-symbols-outlined text-[14px]">visibility</span>
                                <span>{{ $item->logs_count }} Catin Mempelajari</span>
                            </div>


                        </div>
                    </td>

                    {{-- KELOLA KUIS --}}
                    <td class="px-6 py-5 text-center">
                        <div class="inline-flex flex-col items-center p-3 rounded-2xl bg-gray-50 dark:bg-gray-800 group-hover:bg-white dark:group-hover:bg-dark-surface border border-transparent group-hover:border-blue-100 dark:group-hover:border-blue-900 transition-all shadow-sm">
                            <span class="text-xl font-black text-blue-600 dark:text-blue-400 leading-none">
                                {{-- PERBAIKAN: Gunakan optional() untuk menghindari error null --}}
                                {{ optional($item->kuis)->count() ?? 0 }}
                            </span>
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter mb-2">Pertanyaan</span>
                            
                            <a href="{{ route('admin.materi.kuis.manage', $item->id) }}" 
                               class="px-3 py-1 bg-blue-600 text-white text-[9px] font-bold rounded-lg hover:bg-blue-700 transition-all hover:scale-105 flex items-center gap-1 active:scale-95 shadow-md shadow-blue-100 dark:shadow-none">
                                <span class="material-symbols-outlined text-[12px]">edit_square</span>
                                Kelola Soal
                            </a>
                        </div>
                    </td>

                    {{-- AKSI --}}
                    <td class="px-6 py-5 text-center">
                        <div class="flex justify-center items-center gap-2">

                                                {{-- TOMBOL BARU: MONITORING LOGS --}}
                            <a href="{{ route('admin.materi.logs', $item->id) }}"
                                class="w-10 h-10 flex items-center justify-center text-purple-500 hover:bg-purple-500 hover:text-white rounded-xl transition-all shadow-sm hover:shadow-purple-200 dark:border dark:border-purple-500/30"
                                title="Lihat Siapa Saja yang Sudah Belajar">
                                <span class="material-symbols-outlined text-[20px]">leaderboard</span>
                            </a>


                            <a href="{{ route('admin.materi.edit', $item->id) }}"
                                class="w-10 h-10 flex items-center justify-center text-amber-500 hover:bg-amber-500 hover:text-white rounded-xl transition-all shadow-sm hover:shadow-amber-200 dark:border dark:border-amber-500/30"
                                title="Edit Materi">
                                <span class="material-symbols-outlined text-[20px]">edit_note</span>
                            </a>

                            <form action="{{ route('admin.materi.destroy', $item->id) }}"
                                method="POST" class="form-hapus inline">
                                @csrf @method('DELETE')
                                <button type="button"
                                    class="btn-hapus w-10 h-10 flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all shadow-sm hover:shadow-red-200 dark:border dark:border-red-500/30"
                                    title="Hapus Materi">
                                    <span class="material-symbols-outlined text-[20px]">delete_sweep</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-24 text-center">
                        <div class="flex flex-col items-center justify-center opacity-30">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-6xl text-gray-400">menu_book</span>
                            </div>
                            <p class="font-black italic uppercase tracking-widest text-sm text-gray-500">Belum ada materi tersedia</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($materi->hasPages())
    <div class="p-6 border-t border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/30 flex justify-center items-center">
        {{ $materi->links('partials.pagination') }}
    </div>
    @endif
</div>

{{-- SCRIPT --}}
<script>
    // SweetAlert Success
    if(window.successMessage){
        Swal.fire({
            icon: 'success',
            title: '<span class="font-black uppercase text-xl text-gray-800">Berhasil</span>',
            text: window.successMessage,
            timer: 3000,
            showConfirmButton: false,
            background: '#ffffff',
            borderRadius: '20px',
            iconColor: '#4ce619',
        })
    }

    // Confirmation Delete
    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('.form-hapus');
            Swal.fire({
                title: '<span class="font-black uppercase text-red-600">Hapus Permanen?</span>',
                text: "Seluruh data materi dan bank soal di dalamnya akan dihapus selamanya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'YA, HAPUS SEKARANG',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                borderRadius: '24px',
                backdrop: `rgba(239, 68, 68, 0.1)`
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        })
    })

    // Real-time Search Logic (Improved)
    document.getElementById("search").addEventListener("input", function() {
        let value = this.value.toLowerCase().trim();
        let rows = document.querySelectorAll("#tableBody tr:not(:has(td[colspan]))");
        let hasVisible = false;

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if(text.includes(value)) {
                row.style.display = "";
                hasVisible = true;
            } else {
                row.style.display = "none";
            }
        });

        // Optional: Tampilkan baris "Tidak ditemukan" jika search kosong
    });
</script>

@endsection