@extends('layouts.app') {{-- Sesuaikan dengan layoutmu --}}

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Hasil Ujian</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Data Peserta --}}
                <div>
                    <h3 class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-4">Informasi Peserta</h3>
                    <p class="text-gray-500 text-xs">Nama Calon Pengantin</p>
                    <p class="text-lg font-medium text-gray-900 mb-4">{{ $ujian->catin->nama }}</p>
                    
                    <p class="text-gray-500 text-xs">Pendamping</p>
                    <p class="text-gray-900 font-medium">{{ $ujian->catin->pendamping->nama ?? 'Tidak ada pendamping' }}</p>
                </div>

                {{-- Hasil Skor --}}
                <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100 text-center">
                    <h3 class="text-sm font-semibold text-emerald-800 uppercase mb-2">Skor Akhir</h3>
                    <div class="text-5xl font-black text-emerald-700 mb-2">{{ $ujian->skor }}</div>
                    
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase {{ $ujian->status_kelulusan == 'lulus' ? 'bg-amber-400 text-white' : 'bg-red-500 text-white' }}">
                        {{ $ujian->status_kelulusan }}
                    </span>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100 flex gap-4">
                <a href="{{ route('admin.ujian.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-xl font-semibold hover:bg-gray-200 transition-all text-sm">Kembali</a>
                <button onclick="window.print()" class="px-6 py-2 bg-[#065f46] text-white rounded-xl font-semibold hover:bg-emerald-700 transition-all text-sm">Cetak Hasil</button>
            </div>
        </div>
    </div>
</div>
@endsection