@extends('layouts.app')

@section('title','Sertifikat Digital')

@section('content')
{{-- Load Material Symbols --}}
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<div class="p-4 lg:p-8">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-800 dark:text-white">Sertifikat Digital</h1>
            <p class="text-gray-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Daftar pasangan Catin dan hasil kelulusan ujian
            </p>
        </div>

        {{-- BREADCRUMB --}}
        <nav class="flex px-5 py-2 text-gray-700 bg-white border rounded-2xl shadow-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-blue-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">dashboard</span> Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-gray-300">chevron_right</span>
                        <span class="ml-1 text-sm font-black text-gray-800">Sertifikat</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    {{-- PESAN NOTIFIKASI --}}
    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE CONTAINER --}}
    <div class="bg-white rounded-[2rem] border overflow-hidden shadow-2xl shadow-gray-200/50">
        <div class="p-8 border-b bg-gray-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center shadow-lg shadow-green-200">
                    <span class="material-symbols-outlined">workspace_premium</span>
                </div>
                <div>
                    <p class="font-black uppercase text-xs tracking-[0.2em] text-gray-400">Master Data</p>
                    <h2 class="font-black text-xl text-gray-800">Daftar Kelulusan Catin</h2>
                </div>
            </div>
            <span class="px-4 py-1 bg-green-100 text-green-700 rounded-full text-xs font-black">
                TOTAL: {{ $sertifikats->total() }} PASANGAN
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-[11px] uppercase text-gray-400 font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-5">No</th>
                        <th class="px-6 py-5">Nama Pasangan Catin</th>
                        <th class="px-6 py-5 text-center border-l border-gray-200 bg-blue-50/30">Nilai Suami</th>
                        <th class="px-6 py-5 text-center border-r border-gray-200 bg-pink-50/30">Nilai Istri</th>
                        <th class="px-8 py-5 text-center">Aksi Preview / Cetak</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($sertifikats as $index => $catin)
                    
                    {{-- PENCARIAN CERDAS (Cari person ATAU nama_peserta) --}}
                    @php
                        $ujian_suami = \App\Models\Ujian::where('catin_id', $catin->id)
                            ->where(function($q) use ($catin) {
                                $q->where('person', 'LIKE', '%suami%')
                                  ->orWhere('nama_peserta', 'LIKE', '%' . $catin->nama_suami . '%');
                            })
                            ->where('status_kelulusan', 'LIKE', '%lulus%')
                            ->where('skor', '>=', 70)
                            ->latest()->first();
                        
                        $ujian_istri = \App\Models\Ujian::where('catin_id', $catin->id)
                            ->where(function($q) use ($catin) {
                                $q->where('person', 'LIKE', '%istri%')
                                  ->orWhere('nama_peserta', 'LIKE', '%' . $catin->nama_istri . '%');
                            })
                            ->where('status_kelulusan', 'LIKE', '%lulus%')
                            ->where('skor', '>=', 70)
                            ->latest()->first();
                    @endphp

                    <tr class="group hover:bg-green-50/50 transition-all duration-300">
                        <td class="px-8 py-6">
                            <span class="text-sm font-black text-gray-400 group-hover:text-green-600 transition-colors">
                                {{ str_pad($sertifikats->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td class="px-6 py-6">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($catin->nama_suami ?? 'Catin') }}&background=22c55e&color=fff"
                                         class="w-12 h-12 rounded-2xl shadow-sm group-hover:scale-110 transition-transform duration-500"/>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div>
                                    <div class="font-black uppercase text-sm text-gray-800 tracking-tight">
                                        {{ $catin->nama_suami ?? 'Nama Suami' }} & {{ $catin->nama_istri ?? 'Nama Istri' }}
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                                        ID Pasangan: #{{ str_pad($catin->id, 4, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- KOLOM NILAI SUAMI --}}
                        <td class="px-6 py-6 text-center border-l border-gray-100">
                            @if($ujian_suami)
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-xl font-black text-blue-600">{{ $ujian_suami->skor }}</span>
                                    <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mt-1 bg-blue-50 px-2 py-0.5 rounded">LULUS</span>
                                </div>
                            @else
                                <span class="text-[11px] font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full italic">Belum Lulus</span>
                            @endif
                        </td>

                        {{-- KOLOM NILAI ISTRI --}}
                        <td class="px-6 py-6 text-center border-r border-gray-100">
                            @if($ujian_istri)
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-xl font-black text-pink-600">{{ $ujian_istri->skor }}</span>
                                    <span class="text-[10px] font-bold text-pink-500 uppercase tracking-widest mt-1 bg-pink-50 px-2 py-0.5 rounded">LULUS</span>
                                </div>
                            @else
                                <span class="text-[11px] font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full italic">Belum Lulus</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex gap-2">
                                @if($ujian_suami)
                                    <a href="{{ route('admin.sertifikat.preview', ['id' => $catin->id, 'person' => 'suami']) }}" 
                                       class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-blue-700 transition-all shadow-sm shadow-blue-200">
                                        <span class="material-symbols-outlined text-sm">visibility</span> Suami
                                    </a>
                                @endif
                                
                                @if($ujian_istri)
                                    <a href="{{ route('admin.sertifikat.preview', ['id' => $catin->id, 'person' => 'istri']) }}" 
                                       class="inline-flex items-center gap-1 px-4 py-2 bg-pink-600 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-pink-700 transition-all shadow-sm shadow-pink-200">
                                        <span class="material-symbols-outlined text-sm">visibility</span> Istri
                                    </a>
                                @endif

                                @if(!$ujian_suami && !$ujian_istri)
                                    <span class="text-xs text-gray-400 italic">Belum ada aksi</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <span class="material-symbols-outlined text-8xl">verified_user</span>
                                <p class="font-black uppercase text-sm mt-4 tracking-[0.3em]">Belum ada data kelulusan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sertifikats->hasPages())
        <div class="p-8 border-t bg-gray-50/30">
            {{ $sertifikats->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f1f1f1; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection