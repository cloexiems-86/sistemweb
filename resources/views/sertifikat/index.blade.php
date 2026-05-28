@extends('layouts.app')

@section('title','Sertifikat Digital')

@section('content')
{{-- Load Material Symbols untuk Icon --}}
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<div class="p-4 lg:p-8">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-800 dark:text-white">Sertifikat Digital</h1>
            <p class="text-gray-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Daftar catin yang telah dinyatakan lulus ujian
            </p>
        </div>

        {{-- BREADCRUMB GAYA MODERN --}}
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

    {{-- TABLE CONTAINER --}}
    <div class="bg-white rounded-[2rem] border overflow-hidden shadow-2xl shadow-gray-200/50">
        {{-- TABLE HEADER --}}
        <div class="p-8 border-b bg-gray-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center shadow-lg shadow-green-200">
                    <span class="material-symbols-outlined">workspace_premium</span>
                </div>
                <div>
                    <p class="font-black uppercase text-xs tracking-[0.2em] text-gray-400">Master Data</p>
                    <h2 class="font-black text-xl text-gray-800">Daftar Catin Lulus</h2>
                </div>
            </div>
            <span class="px-4 py-1 bg-green-100 text-green-700 rounded-full text-xs font-black">
                TOTAL: {{ $sertifikats->total() }} DATA
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-[11px] uppercase text-gray-400 font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-5">No</th>
                        <th class="px-6 py-5">Nama Pasangan Catin</th>
                        <th class="px-6 py-5 text-center">Skor Akhir</th>
                        <th class="px-6 py-5 text-center">Tanggal Lulus</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($sertifikats as $index => $data)
                    <tr class="group hover:bg-green-50/50 transition-all duration-300">
                        {{-- NO --}}
                        <td class="px-8 py-6">
                            <span class="text-sm font-black text-gray-400 group-hover:text-green-600 transition-colors">
                                {{ str_pad($sertifikats->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        {{-- NAMA CATIN --}}
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($data->catin->nama_lengkap) }}&background=22c55e&color=fff"
                                         class="w-12 h-12 rounded-2xl shadow-sm group-hover:scale-110 transition-transform duration-500"/>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div>
                                    <div class="font-black uppercase text-sm text-gray-800 tracking-tight">
                                        @if(isset($data->person) && in_array($data->person, ['suami','istri']))
                                            @if($data->person === 'suami')
                                                {{ $data->catin->nama_suami }} <span class="text-xs font-medium text-gray-400">(Suami)</span>
                                            @else
                                                {{ $data->catin->nama_istri }} <span class="text-xs font-medium text-gray-400">(Istri)</span>
                                            @endif
                                        @else
                                            {{ $data->catin->nama_lengkap }}
                                        @endif
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        ID: #{{ str_pad($data->catin->id, 4, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- SKOR --}}
                        <td class="px-6 py-6 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="text-lg font-black {{ $data->skor >= 75 ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $data->skor }}
                                </span>
                                <div class="w-16 h-1 bg-gray-100 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $data->skor }}%"></div>
                                </div>
                            </div>
                        </td>

                        {{-- TANGGAL --}}
                        <td class="px-6 py-6 text-center">
                            <span class="text-xs font-black text-gray-500 uppercase tracking-tighter">
                                <span class="material-symbols-outlined text-[14px] align-middle mr-1">calendar_today</span>
                                {{ $data->updated_at->format('d M Y') }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex gap-2">
                                @if($data->skor >= 70)
                                    @if(isset($data->person) && in_array($data->person, ['suami','istri']))
                                        <a href="{{ route('admin.sertifikat.download', [$data->id, $data->person]) }}" 
                                           class="inline-flex items-center gap-2 px-6 py-2 bg-green-600 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-green-700 transition-all">
                                            <span class="material-symbols-outlined text-sm">print</span>
                                            Cetak Sertifikat {{ strtoupper($data->person) }}
                                        </a>
                                    @else
                                        <a href="{{ route('admin.sertifikat.download', [$data->id, 'suami']) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-blue-700 transition-all">
                                            <span class="material-symbols-outlined text-sm">print</span>
                                            Suami
                                        </a>

                                        <a href="{{ route('admin.sertifikat.download', [$data->id, 'istri']) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-pink-700 transition-all">
                                            <span class="material-symbols-outlined text-sm">print</span>
                                            Istri
                                        </a>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 font-black">Skor belum memenuhi syarat</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
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

        {{-- PAGINATION --}}
        @if($sertifikats->hasPages())
        <div class="p-8 border-t bg-gray-50/30">
            {{ $sertifikats->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    /* Mengikuti font sistem yang bersih seperti di gambar */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
    }
    
    /* Custom Scrollbar untuk Table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>
@endsection