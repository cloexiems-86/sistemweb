@extends('layouts.app')

@section('title','Data Jadwal')
@section('page-title','Jadwal Bimbingan')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    window.successMessage = "{{ session('success') }}";
</script>
@endif

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
    <div>
        <h1 class="text-3xl font-black tracking-tight uppercase">Manajemen Jadwal</h1>
        <p class="text-[#6c8863]">Kelola jadwal bimbingan calon pengantin KUA Mojo</p>
    </div>

    <div class="flex gap-3">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">search</span>
            <input type="text" id="search" placeholder="Cari topik..." 
                class="pl-10 pr-4 py-2 border border-[#dee5dc] rounded-lg text-sm focus:ring-primary focus:border-primary dark:bg-dark-surface w-64"
            />
        </div>
        <a href="{{ route('admin.jadwal.create') }}"
            class="flex items-center gap-2 px-6 py-2.5 bg-primary text-[#131811] font-bold rounded-xl shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            Tambah Jadwal
        </a>
    </div>
</div>

{{-- STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white dark:bg-dark-surface p-6 rounded-xl border border-[#dee5dc] shadow-sm">
        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Jadwal</p>
        {{-- Tetap menggunakan total() untuk menghitung seluruh baris di database --}}
        <h2 class="text-3xl font-black mt-1">{{ $jadwal->total() }}</h2>
    </div>

    <div class="bg-white dark:bg-dark-surface p-6 rounded-xl border border-[#dee5dc] shadow-sm border-l-4 border-l-green-500">
        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Upcoming</p>
        <h2 class="text-3xl font-black mt-1 text-green-600">
            {{-- Menggunakan App\Models\Jadwal langsung untuk menghitung seluruh data di DB, bukan cuma yang dipaginasi --}}
            {{ \App\Models\Jadwal::whereRaw('LOWER(status) = ?', ['upcoming'])->count() }}
        </h2>
    </div>

    <div class="bg-white dark:bg-dark-surface p-6 rounded-xl border border-[#dee5dc] shadow-sm border-l-4 border-l-blue-500">
        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Selesai</p>
        <h2 class="text-3xl font-black mt-1 text-blue-600">
            {{-- Menghitung status 'completed' atau 'selesai' dari seluruh database --}}
            {{ \App\Models\Jadwal::whereIn('status', ['Completed', 'Selesai', 'completed', 'selesai'])->count() }}
        </h2>
    </div>
</div>

{{-- TABLE DATA --}}
<div class="bg-white dark:bg-dark-surface rounded-2xl border border-[#dee5dc] shadow-sm overflow-hidden">
    <div class="p-4 bg-gray-50/50 border-b flex justify-between items-center">
        <p class="text-sm font-bold text-[#131811]">
            Menampilkan {{ $jadwal->firstItem() }} - {{ $jadwal->lastItem() }} dari {{ $jadwal->total() }} data
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-100 dark:bg-white/5 border-b border-[#dee5dc]">
                <tr class="text-[11px] font-black uppercase tracking-widest text-gray-500">
                    <th class="px-6 py-4">Tanggal & Lokasi</th> {{-- Tambah Lokasi --}}
                    <th class="px-6 py-4">Topik & Sesi</th>
                    <th class="px-6 py-4">Peserta (Catin)</th> {{-- Tambah Relasi Catin --}}
                    <th class="px-6 py-4 text-center">Fasilitator</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
    @forelse ($jadwal as $item)
    <tr class="border-t border-[#dee5dc] hover:bg-gray-50/80 transition-colors">
        <td class="px-6 py-4">
            <div class="flex flex-col">
                <span class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                <span class="text-[10px] text-primary font-bold uppercase flex items-center gap-1">
                    <span class="material-symbols-outlined text-[12px]">location_on</span>
                    {{ $item->lokasi ?? 'Lokasi Belum Diatur' }}
                </span>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="bg-primary/10 text-primary rounded-lg p-2.5">
                    <span class="material-symbols-outlined text-base">calendar_today</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm text-[#131811] uppercase tracking-tight">{{ $item->topik }}</span>
                    <p class="text-[10px] font-medium text-gray-500 uppercase">Sesi: {{ $item->sesi }}</p>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="flex flex-col gap-1">
                {{-- Pastikan relasi di Model Jadwal bernama 'catins' --}}
                @if($item->catins && $item->catins->count() > 0)
                    @foreach($item->catins as $catin)
                        <span class="text-[10px] bg-green-50 px-2 py-0.5 rounded border border-green-200 text-green-700 font-medium">
                            • {{ $catin->nama_suami }} & {{ $catin->nama_istri }}
                        </span>
                    @endforeach
                @else
                    <span class="text-[10px] text-red-400 italic font-medium">Belum ada peserta</span>
                @endif
            </div>
        </td>
        <td class="px-6 py-4 text-center text-sm font-medium text-gray-600">
            <div class="flex flex-col">
                <span class="font-bold">{{ $item->fasilitator }}</span>
                @if($item->pendamping)
                    <span class="text-[9px] text-amber-600 font-bold uppercase bg-amber-50 px-1 rounded">P: {{ $item->pendamping->nama }}</span>
                @endif
            </div>
        </td>
        <td class="px-6 py-4 text-center">
            {{-- Perbaikan pengecekan status --}}
            @if(strtolower($item->status) == 'upcoming')
                <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] rounded-full font-black uppercase">Upcoming</span>
            @else
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] rounded-full font-black uppercase">Completed</span>
            @endif
        </td>
        <td class="px-6 py-4 text-center">
            {{-- Tombol Aksi tetap sama --}}
            <div class="flex justify-center gap-2">

                {{-- Contoh penempatan di kolom Aksi pada table jadwal --}}
                <a href="{{ route('admin.jadwal.presensi', $item->id) }}" class="bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-600 hover:text-white transition-all" title="Lihat Presensi">
                    <span class="material-symbols-outlined text-sm">assignment_turned_in</span>
                </a>
                <a href="{{ route('admin.jadwal.edit',$item->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">edit_square</span>
                </a>
                <form action="{{ route('admin.jadwal.destroy',$item->id) }}" method="POST" class="form-hapus inline">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-hapus p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="text-center py-20">
            <span class="material-symbols-outlined text-4xl text-gray-300">event_busy</span>
            <p class="mt-2 text-gray-400 italic text-sm">Belum ada jadwal bimbingan terdaftar.</p>
        </td>
    </tr>
    @endforelse
</tbody>
        </table>
    </div>

    <div class="px-6 py-4 bg-gray-50/50 border-t border-[#dee5dc]">
        {{ $jadwal->links('partials.pagination') }}
    </div>
</div>

<script>
    // Notifikasi Berhasil
    if(window.successMessage){
        Swal.fire({
            icon:'success',
            title:'Berhasil!',
            text:window.successMessage,
            confirmButtonColor: '#4ce619',
        })
    }

    // Konfirmasi Hapus
    document.querySelectorAll('.btn-hapus').forEach(btn=>{
        btn.addEventListener('click',function(){
            let form = this.closest('.form-hapus')
            Swal.fire({
                title: 'Hapus jadwal ini?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if(result.isConfirmed) form.submit()
            })
        })
    })

    // Search Filter
    document.getElementById("search").addEventListener("keyup",function(){
        let value = this.value.toLowerCase()
        document.querySelectorAll("#tableBody tr").forEach(row=>{
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none"
        })
    })
</script>
@endsection