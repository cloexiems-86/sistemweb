@extends('layouts.app')

@section('title','Laporan Catin')
@section('page-title','Laporan Lengkap Data Catin')

@section('content')

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
    <div>
        <h1 class="text-2xl font-black text-[#4ce619]">📊 Laporan Hasil Bimbingan</h1>
        <p class="text-gray-600 text-sm mt-1">Rekapan data kependudukan pasangan calon pengantin dan hasil evaluasi.</p>
    </div>

    <div class="flex gap-2 items-center">
        <form action="{{ route('admin.report.export') }}" method="GET" class="inline">
            @foreach(request()->query() as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <button type="submit" class="px-4 py-2 bg-[#4ce619] text-white rounded-xl font-semibold hover:bg-[#3eb316] transition">
                <span class="material-symbols-outlined align-text-bottom text-[18px]">download</span>
                Export CSV
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white border border-green-200 p-6 rounded-3xl shadow-sm flex items-center justify-between hover:-translate-y-1 transition-transform">
        <div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Registrasi</p>
            <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $totalPasangan ?? 0 }} <span class="text-sm text-gray-500 font-bold">Pasangan</span></h3>
        </div>
        <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-600">
            <span class="material-symbols-outlined text-2xl">group</span>
        </div>
    </div>

    <div class="bg-white border border-blue-200 p-6 rounded-3xl shadow-sm flex items-center justify-between hover:-translate-y-1 transition-transform">
        <div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Lulus Bimbingan (S&I)</p>
            <h3 class="text-3xl font-black text-blue-700 mt-1">{{ $lulusKeduanya ?? 0 }} <span class="text-sm text-gray-500 font-bold">Pasangan</span></h3>
        </div>
        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
            <span class="material-symbols-outlined text-2xl">verified</span>
        </div>
    </div>

    <div class="bg-white border border-amber-200 p-6 rounded-3xl shadow-sm flex items-center justify-between hover:-translate-y-1 transition-transform">
        <div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Tingkat Kelulusan</p>
            <h3 class="text-3xl font-black text-amber-600 mt-1">{{ $persentase ?? 0 }}<span class="text-xl">%</span></h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center text-amber-600">
            <span class="material-symbols-outlined text-2xl">trending_up</span>
        </div>
    </div>
</div>

<div class="bg-white border border-green-200 rounded-3xl p-6 mb-6 shadow-sm">
    <h3 class="font-black text-[#4ce619] mb-4">🔍 Filter Data</h3>

    <form method="GET" action="{{ route('admin.report.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Akun</label>
            <select name="status" class="w-full border border-gray-300 rounded p-2 text-sm">
                <option value="">-- Semua --</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Ujian</label>
            <select name="status_ujian" class="w-full border border-gray-300 rounded p-2 text-sm">
                <option value="">-- Semua --</option>
                <option value="lulus" {{ request('status_ujian') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="tidak_lulus" {{ request('status_ujian') == 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                <option value="belum" {{ request('status_ujian') == 'belum' ? 'selected' : '' }}>Belum Ujian</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-300 rounded p-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-300 rounded p-2 text-sm">
        </div>

        <div class="flex gap-2 lg:col-span-5">
            <button type="submit" class="px-4 py-2 bg-[#4ce619] text-white rounded-xl font-semibold hover:bg-[#3eb316] transition">
                <span class="material-symbols-outlined text-[18px] align-text-bottom">search</span>
                Filter
            </button>
            <a href="{{ route('admin.report.index') }}" class="px-4 py-2 border border-green-200 rounded-xl font-semibold text-green-700 hover:bg-green-50 transition flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white border border-green-100 rounded-3xl shadow-sm overflow-hidden">
    <div class="p-4 border-b border-green-100 bg-gradient-to-r from-green-50 to-green-100">
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 bg-[#4ce619] text-white flex items-center justify-center rounded text-xs font-bold">{{ $catins->total() }}</span>
            <p class="font-semibold text-[#1f6f20] text-sm uppercase">Daftar Pasangan Catin</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#4ce619] border-b border-green-600 text-xs uppercase text-white font-semibold">
                <tr>
                    <th class="px-4 py-3 text-center">No</th>
                    <th class="px-4 py-3">Nama Pasangan</th>
                    <th class="px-4 py-3">NIK</th>
                    <th class="px-4 py-3">Alamat Domisili</th>
                    <th class="px-4 py-3">Rencana Nikah</th>
                    <th class="px-4 py-3 text-center">Ujian Suami</th>
                    <th class="px-4 py-3 text-center">Ujian Istri</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @php
                    $allExams = \App\Models\Ujian::orderBy('created_at', 'desc')->get();
                @endphp

                @forelse($catins as $key => $catin)
                    @php
                        // PERBAIKAN MUTLAK: Menggunakan nama_peserta karena kolom person bernilai NULL
                        $ujianSuami = $allExams->filter(function($item) use ($catin) {
                            $matchNama = $item->nama_peserta && strtolower(trim($item->nama_peserta)) == strtolower(trim($catin->nama_suami));
                            $matchCatin = ($item->catin_id == $catin->id && $item->person && strtolower(trim($item->person)) == 'suami');
                            return $matchNama || $matchCatin;
                        })->first();

                        $ujianIstri = $allExams->filter(function($item) use ($catin) {
                            $matchNama = $item->nama_peserta && strtolower(trim($item->nama_peserta)) == strtolower(trim($catin->nama_istri));
                            $matchCatin = ($item->catin_id == $catin->id && $item->person && strtolower(trim($item->person)) == 'istri');
                            return $matchNama || $matchCatin;
                        })->first();
                    @endphp
                    
                    <tr class="hover:bg-green-50 transition">
                        <td class="px-4 py-4 text-center text-gray-500 font-medium">
                            {{ ($catins->currentPage() - 1) * $catins->perPage() + $key + 1 }}
                        </td>

                        <td class="px-4 py-4">
                            <div class="font-bold text-gray-800 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px] text-blue-500">male</span> 
                                {{ $catin->nama_suami }}
                            </div>
                            <div class="font-bold text-gray-400 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[16px] text-pink-500">female</span> 
                                {{ $catin->nama_istri }}
                            </div>
                        </td>

                        <td class="px-4 py-4 text-gray-600">
                            <div><span class="font-semibold">S:</span> {{ $catin->nik_suami }}</div>
                            <div class="mt-1"><span class="font-semibold">I:</span> {{ $catin->nik_istri }}</div>
                        </td>

                        <td class="px-4 py-4 max-w-xs truncate" title="{{ $catin->alamat_suami }}">
                            {{ $catin->alamat_suami }}
                        </td>

                        <td class="px-4 py-4">
                            <div class="font-medium text-gray-800">
                                {{ $catin->wedding_date ? \Carbon\Carbon::parse($catin->wedding_date)->translatedFormat('d M Y') : '-' }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                Daftar: {{ $catin->created_at->format('d/m/Y') }}
                            </div>
                        </td>

                        <td class="px-4 py-4 text-center">
                            @if($ujianSuami)
                                <span class="inline-flex flex-col items-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ strtolower($ujianSuami->status_kelulusan) == 'lulus' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $ujianSuami->status_kelulusan)) }}
                                    </span>
                                    <span class="text-xs font-bold mt-1 text-gray-600">Skor: {{ $ujianSuami->skor }}</span>
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">Belum Ujian</span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-center">
                            @if($ujianIstri)
                                <span class="inline-flex flex-col items-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ strtolower($ujianIstri->status_kelulusan) == 'lulus' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $ujianIstri->status_kelulusan)) }}
                                    </span>
                                    <span class="text-xs font-bold mt-1 text-gray-600">Skor: {{ $ujianIstri->skor }}</span>
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">Belum Ujian</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-500">
                            <span class="material-symbols-outlined text-4xl mb-2 text-gray-300">folder_off</span>
                            <p class="text-sm">Tidak ada data bimbingan yang sesuai dengan filter.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($catins->hasPages())
    <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-center">
        {{ $catins->links() }}
    </div>
    @endif
</div>

@endsection