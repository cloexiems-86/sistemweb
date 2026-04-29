@extends('layouts.app')

@section('title', 'Log Akses Materi')
@section('page-title', 'Monitoring Progres Catin')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
    <div>
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.materi.index') }}" class="text-gray-400 hover:text-[#4ce619] transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-3xl font-black tracking-tight text-gray-800 dark:text-white uppercase">Log Akses Materi</h1>
        </div>
        <p class="text-gray-500 font-medium flex items-center gap-2">
            <span class="material-symbols-outlined text-[#4ce619]">menu_book</span>
            Materi: <span class="text-gray-800 dark:text-gray-200 font-bold">"{{ $materi->judul }}"</span>
        </p>
    </div>

    {{-- STATISTIK RINGKAS --}}
    <div class="bg-[#4ce619]/10 border border-[#4ce619]/20 p-4 rounded-2xl flex items-center gap-4">
        <div class="bg-[#4ce619] text-white p-2 rounded-xl">
            <span class="material-symbols-outlined text-xl">group</span>
        </div>
        <div>
            <p class="text-[10px] font-black uppercase text-gray-500 leading-none">Total Pembaca</p>
            <p class="text-2xl font-black text-gray-800 dark:text-white">{{ $materi->logs->count() }}</p>
        </div>
    </div>
</div>

{{-- TABLE SECTION --}}
<div class="bg-white dark:bg-dark-surface rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-xl">
    <div class="p-6 border-b border-gray-50 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/50">
        <p class="font-black text-gray-700 dark:text-white uppercase text-xs tracking-widest">Daftar Catin yang Mempelajari</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/80 dark:bg-gray-800 border-b dark:border-gray-700 text-[11px] uppercase text-gray-400 font-black tracking-widest">
                <tr>
                    <th class="px-6 py-4">Nama Catin</th>
                    <th class="px-6 py-4">Waktu Pertama Akses</th>
                    <th class="px-6 py-4">Status Bimbingan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($materi->logs as $log)
                <tr class="group hover:bg-gray-50 transition-all">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#4ce619] to-green-300 flex items-center justify-center text-white font-bold">
                                {{ substr($log->user->name ?? 'C', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-black text-gray-800 dark:text-white text-sm uppercase leading-none mb-1">
                                    {{ $log->user->name ?? 'Catin Tidak Ditemukan' }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-medium">{{ $log->user->email ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                {{ $log->created_at->translatedFormat('d F Y') }}
                            </span>
                            <span class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">
                                Pukul {{ $log->created_at->format('H:i') }} WIB
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 bg-blue-100 text-blue-600 text-[9px] font-black uppercase rounded-full border border-blue-200">
                            Sedang Belajar
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-20 text-center">
                        <div class="flex flex-col items-center opacity-30">
                            <span class="material-symbols-outlined text-6xl mb-2">person_search</span>
                            <p class="font-black uppercase tracking-widest text-sm">Belum ada Catin yang mengakses materi ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection