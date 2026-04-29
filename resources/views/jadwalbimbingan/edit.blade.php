@extends('layouts.app')

@section('title', 'Edit Jadwal Bimbingan')
@section('page-title', 'Edit Jadwal Bimbingan')

@section('content')

{{-- Inisialisasi data cadangan agar tidak error Undefined Variable --}}
@php
    $pendampings = $pendampings ?? [];
    $catins = $catins ?? [];
@endphp

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex justify-center py-8 px-4 bg-gray-50/50 min-h-screen">
    <div class="w-full max-w-6xl bg-white dark:bg-dark-surface rounded-[2.5rem] shadow-2xl border border-gray-100 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-[#4ce619] to-[#3db514] p-8 text-white relative overflow-hidden">
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('admin.jadwal.index') }}" 
                   class="w-12 h-12 flex items-center justify-center bg-white/20 backdrop-blur-md rounded-2xl hover:bg-white/40 transition-all">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-3xl font-black uppercase tracking-tight">Perbarui Jadwal</h2>
                    <p class="text-xs font-medium opacity-90 uppercase tracking-widest mt-1">Sesuaikan detail sesi bimbingan KUA Mojo</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            {{-- Menampilkan Pesan Error Validasi Jika Ada --}}
            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-2xl text-red-700 text-sm">
                    <p class="font-bold mb-2">Periksa kembali inputan Anda:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                {{-- KOLOM KIRI: INFO UTAMA --}}
                <div class="lg:col-span-5 space-y-6">
                    <h3 class="font-black text-xs uppercase tracking-[0.2em] text-[#4ce619] flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-lg">info</span> Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="group">
                            <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}"
                                class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-[#4ce619] focus:ring-4 focus:ring-[#4ce619]/10 outline-none font-bold text-gray-700 text-sm transition-all" required>
                        </div>
                        <div class="group">
                            <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1">Sesi Waktu</label>
                            <input type="text" name="sesi" value="{{ old('sesi', $jadwal->sesi) }}"
                                class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-[#4ce619] focus:ring-4 focus:ring-[#4ce619]/10 outline-none font-bold text-gray-700 text-sm transition-all" placeholder="Contoh: 08:00 - 10:00" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 block px-1 tracking-[0.2em]">Topik Bimbingan / Materi</label>
                        <select name="topik" required class="w-full border border-gray-100 bg-gray-50/50 rounded-2xl p-4 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-green-400/10 outline-none transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>-- Pilih Topik Materi --</option>
                            @foreach($materis as $materi)
                                <option value="{{ $materi->judul }}" 
                                    {{ (isset($jadwal) && $jadwal->topik == $materi->judul) ? 'selected' : '' }}>
                                    {{ $materi->judul }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PERBAIKAN: LOKASI BIMBINGAN DROPDOWN --}}
                    <div class="group">
                        <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1">Lokasi Bimbingan</label>
                        <select name="lokasi" class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-[#4ce619] focus:ring-4 focus:ring-[#4ce619]/10 outline-none font-bold text-gray-700 text-sm bg-white transition-all" required>
                            <option value="Kantor KUA" {{ old('lokasi', $jadwal->lokasi) == 'Kantor KUA' ? 'selected' : '' }}>Kantor KUA</option>
                            <option value="Rumah Catin" {{ old('lokasi', $jadwal->lokasi) == 'Rumah Catin' ? 'selected' : '' }}>Rumah Catin</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="group">
                            <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1">Fasilitator</label>
                            <select name="fasilitator" class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 outline-none font-bold text-gray-700 text-sm bg-white focus:border-[#4ce619] transition-all">
                                <option value="Ahmad Subarkah" {{ old('fasilitator', $jadwal->fasilitator) == 'Ahmad Subarkah' ? 'selected' : '' }}>Ahmad Subarkah</option>
                                <option value="Siti Maryam" {{ old('fasilitator', $jadwal->fasilitator) == 'Siti Maryam' ? 'selected' : '' }}>Siti Maryam</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1">Pendamping</label>
                            <select name="pendamping_id" class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 outline-none font-bold text-gray-700 text-sm bg-white focus:border-[#4ce619] transition-all">
                                <option value="">Tanpa Pendamping</option>
                                @foreach($pendampings as $p)
                                    <option value="{{ $p->id }}" {{ old('pendamping_id', $jadwal->pendamping_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1 mb-3">Status Sesi</label>
                        <div class="flex gap-3 p-1.5 bg-gray-100 rounded-2xl border border-gray-200">
                            <button type="button" onclick="setStatus('Upcoming')" id="btn-upcoming"
                                class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all {{ $jadwal->status == 'Upcoming' ? 'bg-white text-blue-500 shadow-md' : 'text-gray-400' }}">
                                Upcoming
                            </button>
                            <button type="button" onclick="setStatus('Completed')" id="btn-completed"
                                class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all {{ $jadwal->status == 'Completed' ? 'bg-white text-[#4ce619] shadow-md' : 'text-gray-400' }}">
                                Completed
                            </button>
                        </div>
                        <input type="hidden" name="status" id="status_input" value="{{ old('status', $jadwal->status) }}">
                    </div>
                </div>

                {{-- KOLOM KANAN: PESERTA CATIN --}}
                <div class="lg:col-span-7 flex flex-col">
                    <h3 class="font-black text-xs uppercase tracking-[0.2em] text-[#4ce619] mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">groups</span> Peserta Bimbingan (Catin)
                    </h3>
                    
                    <div class="flex-1 border border-gray-200 rounded-[2rem] overflow-hidden bg-gray-50/30 shadow-inner">
                        <div class="grid grid-cols-12 bg-gray-100 px-6 py-4 text-[10px] font-black uppercase text-gray-500 border-b">
                            <div class="col-span-1 text-center">Pilih</div>
                            <div class="col-span-11 ml-4">Nama Pasangan (Suami & Istri)</div>
                        </div>

                        <div class="max-h-[450px] overflow-y-auto divide-y divide-gray-100 bg-white">
                            @forelse($catins as $catin)
                            <label class="grid grid-cols-12 items-center px-6 py-5 hover:bg-green-50 transition-colors cursor-pointer group">
                                <div class="col-span-1 flex justify-center">
                                    <input type="checkbox" name="catin_ids[]" value="{{ $catin->id }}"
                                        {{ in_array($catin->id, old('catin_ids', $jadwal->catins->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded-lg border-gray-300 text-[#4ce619] focus:ring-[#4ce619] transition-all">
                                </div>
                                <div class="col-span-11 ml-4 flex flex-col">
                                    <span class="text-sm font-bold text-gray-700 group-hover:text-[#3db514] transition-colors">{{ $catin->nama_suami }} & {{ $catin->nama_istri }}</span>
                                    <span class="text-[10px] text-gray-400 uppercase tracking-widest mt-0.5">Terdaftar di KUA Mojo</span>
                                </div>
                            </label>
                            @empty
                            <div class="py-20 text-center">
                                <span class="material-symbols-outlined text-5xl text-gray-200 mb-2">person_off</span>
                                <p class="text-sm text-gray-400 italic">Tidak ada data Catin tersedia.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end items-center gap-6 mt-10 pt-8 border-t border-gray-100">
                <a href="{{ route('admin.jadwal.index') }}" class="text-xs font-black text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors">Batalkan</a>
                <button type="submit" class="px-10 py-4 bg-gradient-to-r from-[#4ce619] to-[#3db514] text-white font-black rounded-2xl shadow-xl shadow-green-200 hover:shadow-green-300 hover:-translate-y-1 transition-all uppercase text-xs tracking-widest">
                    Update Jadwal Bimbingan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function setStatus(val) {
        document.getElementById('status_input').value = val;
        const btnUp = document.getElementById('btn-upcoming');
        const btnComp = document.getElementById('btn-completed');
        
        if(val === 'Upcoming') {
            btnUp.className = "flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all bg-white text-blue-500 shadow-md";
            btnComp.className = "flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all text-gray-400";
        } else {
            btnComp.className = "flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all bg-white text-[#4ce619] shadow-md";
            btnUp.className = "flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all text-gray-400";
        }
    }
</script>

@endsection