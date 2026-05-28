@extends('layouts.app')

@section('title','Data Ujian')
@section('page-title','Manajemen Hasil Ujian')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- HEADER --}}
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-gray-800 dark:text-white">Hasil Ujian Catin</h1>
        <p class="text-gray-500 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            Monitoring kelulusan & nilai ujian
        </p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        {{-- SEARCH --}}
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400">search</span>
            <input type="text" id="search" placeholder="Cari nama catin..."
                class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-full sm:w-64 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm"/>
        </div>

        <div class="flex gap-2">

        {{-- FILTER --}}
        <select id="filterStatus"
            class="px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20">
            <option value="">Semua</option>
            <option value="lulus">Lulus</option>
            <option value="remedial">Remedial</option>
        </select>

        {{-- EXPORT --}}
        <a href="{{ route('admin.ujian.export.excel') }}" class="px-4 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700">
            Export Excel
        </a>

        <a href="{{ route('admin.ujian.export.pdf') }}" class="px-4 py-2.5 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700">
            Export PDF
        </a>

        <a href="{{ route('admin.ujian.soal') }}" "class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2">
            <span class="material-symbols-outlined">quiz</span>
            Kelola Bank Soal (15 Soal)
        </a>

    </div>

        <a href="{{ route('admin.ujian.create') }}"
            class="flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:-translate-y-1 transition-all">
            <span class="material-symbols-outlined">add</span>
            Input Nilai
        </a>
    </div>
</div>

{{-- STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-gray-500 text-xs font-black uppercase">Total Data</p>
        <h2 class="text-4xl font-black">{{ $ujianResults->count() }}</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-green-500 text-xs font-black uppercase">Lulus</p>
        <h2 class="text-4xl font-black text-green-500">
            {{ $ujianResults->where('status_kelulusan','lulus')->count() }}
        </h2>
    </div>

    <div class="bg-white p-6 rounded-2xl border shadow-sm">
        <p class="text-red-500 text-xs font-black uppercase">Remedial</p>
        <h2 class="text-4xl font-black text-red-500">
            {{ $ujianResults->where('status_kelulusan','!=','lulus')->count() }}
        </h2>
    </div>
</div>

{{-- TABLE --}}
@if(method_exists($ujianResults, 'links'))
<div class="p-6 border-t flex justify-center">
    {{ $ujianResults->links('partials.pagination') }}
</div>
@endif

<div class="bg-white rounded-3xl border overflow-hidden shadow-xl">
    <div class="p-6 border-b bg-gray-50 flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
            {{ $ujianResults->count() }}
        </span>
        <p class="font-black uppercase text-xs tracking-widest">Daftar Hasil Ujian</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-[11px] uppercase text-gray-400 font-black tracking-widest">
                <tr>
                    <th class="px-6 py-4">Peserta Ujian</th>
                    <th class="px-6 py-4">Pendamping</th>
                    <th class="px-6 py-4 text-center">Skor</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="tableBody" class="divide-y divide-gray-50">
                @foreach($ujianResults as $ujian)
                <tr class="group hover:bg-blue-50 transition-all duration-300 hover:scale-[1.01]">

                @if($ujianResults->isEmpty())
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="opacity-30 flex flex-col items-center">
                                <span class="material-symbols-outlined text-6xl">folder_off</span>
                                <p class="font-black uppercase text-sm mt-2">Belum ada data ujian</p>
                            </div>
                        </td>
                    </tr>
                    @endif

                    {{-- PESERTA UJIAN --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($ujian->display_name) }}&background=0D8ABC&color=fff"
                                 class="w-12 h-12 rounded-xl"/>

                            <div>
                                <div class="font-black uppercase text-sm">
                                    {{ $ujian->display_name }}
                                </div>
                                <div class="text-[10px] text-gray-400">
                                    {{ $ujian->display_peran ? $ujian->display_peran . ' • ' : '' }}
                                    NIK: {{ $ujian->display_nik ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- PENDAMPING --}}
                    <td class="px-6 py-5">
                        <span class="text-sm text-gray-600">
                            {{ $ujian->catin->pendamping_assigned ?? 'Tanpa Pendamping' }}
                        </span>
                    </td>

                    {{-- SKOR --}}
                    <td class="px-6 py-5 text-center">
                        <div class="flex flex-col items-center gap-2">

                            {{-- ANGKA --}}
                            <span class="font-black text-lg {{ $ujian->skor >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $ujian->skor }}
                            </span>

                            {{-- PROGRESS --}}
                            <div class="w-full max-w-[100px] h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700
                                    {{ $ujian->skor >= 75 ? 'bg-green-500' : 'bg-red-500' }}"
                                    style="width: {{ $ujian->skor }}%">
                                </div>
                            </div>

                        </div>
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-5 text-center">
                        @if($ujian->status_kelulusan == 'lulus')
                            <span class="inline-flex items-center gap-1 px-4 py-1 bg-green-50 text-green-600 rounded-full text-xs font-black">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                LULUS
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-4 py-1 bg-red-50 text-red-600 rounded-full text-xs font-black">
                                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                REMEDIAL
                            </span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td class="px-6 py-5 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.ujian.edit', $ujian->id) }}"
                               class="w-9 h-9 flex items-center justify-center text-amber-500 hover:bg-amber-500 hover:text-white rounded-xl transition">
                                <span class="material-symbols-outlined">edit</span>
                            </a>

                            <form action="{{ route('admin.ujian.destroy', $ujian->id) }}"
                                  method="POST" class="form-hapus">
                                @csrf @method('DELETE')
                                <button type="button"
                                    class="btn-hapus w-9 h-9 flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    // SEARCH
const searchInput = document.getElementById("search")
const filterStatus = document.getElementById("filterStatus")

function filterTable(){
    let search = searchInput.value.toLowerCase()
    let status = filterStatus.value

    document.querySelectorAll("#tableBody tr").forEach(row => {
        let text = row.innerText.toLowerCase()
        let isMatchSearch = text.includes(search)

        let isMatchStatus = true
        if(status){
            isMatchStatus = text.includes(status)
        }

        row.style.display = (isMatchSearch && isMatchStatus) ? "" : "none"
    })
}

searchInput.addEventListener("keyup", filterTable)
filterStatus.addEventListener("change", filterTable)

    // DELETE ALERT
    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function() {
            let form = this.closest('.form-hapus')
            Swal.fire({
                title: 'Hapus Data?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            })
        })
    })
</script>

@endsection