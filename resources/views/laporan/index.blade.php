@extends('layouts.app')

@section('title','Laporan Catin')
@section('page-title','Laporan Lengkap Data Catin')

@section('content')

<!-- HEADER -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
    <div>
        <h1 class="text-2xl font-black text-[#4ce619]">📊 Laporan Catin</h1>
        <p class="text-gray-600 text-sm mt-1">Rekapan lengkap data registrasi, ujian, dan kehadiran per peserta.</p>
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

<!-- FILTER SECTION -->
<div class="bg-white border border-green-200 rounded-3xl p-6 mb-6 shadow-sm">
    <h3 class="font-black text-[#4ce619] mb-4">🔍 Filter Data</h3>

    <form method="GET" action="{{ route('admin.report.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Status Akun -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Akun</label>
            <select name="status" class="w-full border border-gray-300 rounded p-2 text-sm">
                <option value="">-- Semua --</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <!-- Status Ujian -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Ujian</label>
            <select name="status_ujian" class="w-full border border-gray-300 rounded p-2 text-sm">
                <option value="">-- Semua --</option>
                <option value="lulus" {{ request('status_ujian') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="tidak_lulus" {{ request('status_ujian') == 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                <option value="belum" {{ request('status_ujian') == 'belum' ? 'selected' : '' }}>Belum Ujian</option>
            </select>
        </div>

        <!-- Tanggal Dari -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-300 rounded p-2 text-sm">
        </div>

        <!-- Tanggal Sampai -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-300 rounded p-2 text-sm">
        </div>

        <!-- Button -->
        <div class="flex gap-2 lg:col-span-5">
            <button type="submit" class="px-4 py-2 bg-[#4ce619] text-white rounded-xl font-semibold hover:bg-[#3eb316] transition">
                <span class="material-symbols-outlined text-[18px] align-text-bottom">search</span>
                Filter
            </button>
            <a href="{{ route('admin.report.index') }}" class="px-4 py-2 border border-green-200 rounded-xl font-semibold text-green-700 hover:bg-green-50 transition">
                <span class="material-symbols-outlined text-[18px] align-text-bottom">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- TABLE SECTION -->
<div class="bg-white border border-green-100 rounded-3xl shadow-sm overflow-hidden">
    <div class="p-4 border-b border-green-100 bg-gradient-to-r from-green-50 to-green-100">
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 bg-[#4ce619] text-white flex items-center justify-center rounded text-xs font-bold">{{ $catins->total() * 2 }}</span>
            <p class="font-semibold text-[#1f6f20] text-sm uppercase">Daftar Peserta</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#4ce619] border-b border-green-600 text-xs uppercase text-white font-semibold">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama Peserta</th>
                    <th class="px-4 py-3">Peran</th>
                    <th class="px-4 py-3">NIK</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">No WA</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3 text-center">Status Akun</th>
                    <th class="px-4 py-3 text-center">Skor</th>
                    <th class="px-4 py-3 text-center">Status Ujian</th>
                    <th class="px-4 py-3 text-center">Tgl Daftar</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @php $rowNumber = ($catins->currentPage() - 1) * $catins->perPage() * 2; @endphp
                @forelse($catins as $catin)
                    @php
                        $rowNumber++;
                        $suamiRow = [
                            'name' => $catin->nama_suami,
                            'role' => 'Suami',
                            'nik' => $catin->nik_suami,
                            'email' => $catin->email_suami,
                            'phone' => $catin->phone_suami,
                            'alamat' => $catin->alamat_suami,
                            'score' => $catin->ujianSuami?->skor,
                            'status' => $catin->ujianSuami?->status_kelulusan,
                        ];
                        $istriRow = [
                            'name' => $catin->nama_istri,
                            'role' => 'Istri',
                            'nik' => $catin->nik_istri,
                            'email' => $catin->email_istri,
                            'phone' => $catin->phone_istri,
                            'alamat' => $catin->alamat_istri,
                            'score' => $catin->ujianIstri?->skor,
                            'status' => $catin->ujianIstri?->status_kelulusan,
                        ];
                    @endphp

                    <tr class="hover:bg-green-50 transition">
                        <td class="px-4 py-3">{{ $rowNumber }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-800">{{ $suamiRow['name'] }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $suamiRow['role'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $suamiRow['nik'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $suamiRow['email'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $suamiRow['phone'] }}</td>
                        <td class="px-4 py-3 text-sm max-w-xs truncate" title="{{ $suamiRow['alamat'] }}">{{ $suamiRow['alamat'] }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold">{{ $catin->username }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                {{ $catin->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($catin->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($suamiRow['score'] !== null)
                                <span class="font-bold text-lg text-green-700">{{ $suamiRow['score'] }}</span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($suamiRow['status'])
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $suamiRow['status'] == 'lulus' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $suamiRow['status'])) }}
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Belum</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-sm">{{ $catin->created_at->format('d/m/Y') }}</td>
                    </tr>

                    @php $rowNumber++; @endphp
                    <tr class="hover:bg-green-50 transition">
                        <td class="px-4 py-3">{{ $rowNumber }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-800">{{ $istriRow['name'] }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $istriRow['role'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $istriRow['nik'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $istriRow['email'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $istriRow['phone'] }}</td>
                        <td class="px-4 py-3 text-sm max-w-xs truncate" title="{{ $istriRow['alamat'] }}">{{ $istriRow['alamat'] }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold">{{ $catin->username }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                {{ $catin->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($catin->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($istriRow['score'] !== null)
                                <span class="font-bold text-lg text-green-700">{{ $istriRow['score'] }}</span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($istriRow['status'])
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $istriRow['status'] == 'lulus' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $istriRow['status'])) }}
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Belum</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-sm">{{ $catin->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                <tr>
                    <td colspan="11" class="py-12 text-center text-gray-500">
                        <p class="text-sm">Tidak ada data yang sesuai dengan filter</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if($catins->hasPages())
    <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-center">
        {{ $catins->links('partials.pagination') }}
    </div>
    @endif
</div>

@endsection
