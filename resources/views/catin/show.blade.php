@extends('layouts.app')

@section('title', 'Detail Progres Catin')
@section('page-title', 'Detail Progres Pasangan')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Back Button --}}
    <a href="{{ route('admin.catin.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-[#4ce619] mb-6 transition-colors font-bold">
        <span class="material-symbols-outlined">arrow_back</span> Kembali ke Daftar
    </a>

    <div class="bg-white dark:bg-dark-surface rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        {{-- Header Detail --}}
        <div class="p-8 bg-gradient-to-r from-[#4ce619]/10 to-transparent border-b border-gray-50">
            <div class="flex items-center gap-6">
                <img src="https://ui-avatars.com/api/?name={{ $item->nama_suami }}&background=4ce619&color=fff&bold=true" class="w-20 h-20 rounded-2xl shadow-lg"/>
                <div>
                    <h1 class="text-2xl font-black text-gray-800 uppercase">{{ $item->nama_suami }} & {{ $item->nama_istri }}</h1>
                    <p class="text-gray-500 font-medium italic">ID Registrasi: #{{ $item->id }}{{ $item->created_at->format('Ymd') }}</p>
                    <div class="mt-3 flex gap-2">
                        @if($item->ujian && $item->ujian->status_kelulusan === 'lulus' && $item->ujian->skor >= 70)
                            <a href="{{ route('admin.sertifikat.download', [$item->ujian->id, 'suami']) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold">Cetak Sertifikat Suami</a>
                            <a href="{{ route('admin.sertifikat.download', [$item->ujian->id, 'istri']) }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg text-xs font-bold">Cetak Sertifikat Istri</a>
                        @else
                            <span class="text-xs text-gray-400">Sertifikat belum tersedia (skor < 70)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Progres Section --}}
        <div class="p-8">
            <h3 class="font-black text-gray-800 uppercase tracking-widest text-sm mb-8 flex items-center gap-2">
                <span class="w-2 h-6 bg-[#4ce619] rounded-full"></span> Tracking Alur Bimbingan
            </h3>

            @php
                $step = 1;
                if($item->is_lulus) { $step = 3; } 
                elseif($item->kuis_done) { $step = 2; }
            @endphp

            <div class="relative">
                {{-- Garis Progres --}}
                <div class="absolute left-6 top-0 h-full w-1 bg-gray-100 rounded-full"></div>
                <div class="absolute left-6 top-0 w-1 bg-[#4ce619] rounded-full transition-all duration-1000 shadow-[0_0_10px_#4ce619]" 
                     style="height: {{ $step == 3 ? '100' : ($step == 2 ? '50' : '0') }}%"></div>

                <div class="space-y-12">
                    {{-- Tahap 1 --}}
                    <div class="relative flex items-center gap-8 pl-4">
                        <div class="w-5 h-5 rounded-full z-10 flex items-center justify-center {{ $step >= 1 ? 'bg-[#4ce619] ring-4 ring-green-100' : 'bg-gray-200' }}">
                            @if($step >= 1) <span class="material-symbols-outlined text-white text-[12px]">done</span> @endif
                        </div>
                        <div>
                            <p class="font-black {{ $step >= 1 ? 'text-gray-800' : 'text-gray-400' }}">TAHAP 1: MATERI BIMBINGAN</p>
                            <p class="text-xs text-gray-500 italic">Catin mengakses video dan modul pembelajaran di aplikasi.</p>
                        </div>
                    </div>

                    {{-- Tahap 2 --}}
                    <div class="relative flex items-center gap-8 pl-4">
                        <div class="w-5 h-5 rounded-full z-10 flex items-center justify-center {{ $step >= 2 ? 'bg-[#4ce619] ring-4 ring-green-100' : 'bg-gray-200' }}">
                            @if($step >= 2) <span class="material-symbols-outlined text-white text-[12px]">done</span> @endif
                        </div>
                        <div>
                            <p class="font-black {{ $step >= 2 ? 'text-gray-800' : 'text-gray-400' }}">TAHAP 2: PENGERJAAN KUIS</p>
                            <p class="text-xs text-gray-500 italic">Mengerjakan soal kuis evaluasi pemahaman materi.</p>
                        </div>
                    </div>

                    {{-- Tahap 3 --}}
                    <div class="relative flex items-center gap-8 pl-4">
                        <div class="w-5 h-5 rounded-full z-10 flex items-center justify-center {{ $step >= 3 ? 'bg-[#4ce619] ring-4 ring-green-100' : 'bg-gray-200' }}">
                            @if($step >= 3) <span class="material-symbols-outlined text-white text-[12px]">workspace_premium</span> @endif
                        </div>
                        <div>
                            <p class="font-black {{ $step >= 3 ? 'text-[#4ce619]' : 'text-gray-400' }}">TAHAP 3: PENERBITAN SERTIFIKAT</p>
                            <p class="text-xs text-gray-500 italic">Pasangan dinyatakan lulus dan sertifikat dapat diunduh.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection