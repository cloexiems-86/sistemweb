@extends('layouts.app')

@section('title', 'Tambah Jadwal Bimbingan')
@section('page-title', 'Buat Jadwal Baru')

@section('content')

<div class="flex justify-center py-10 px-4">
    <div class="w-full max-w-4xl bg-white dark:bg-dark-surface rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        
        {{-- HEADER FORM --}}
        <div class="bg-[#4ce619] p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-9xl">calendar_add_on</span>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('admin.jadwal.index') }}" 
                   class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-xl hover:bg-white/40 transition-all">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">Buat Jadwal Baru</h2>
                    <p class="text-sm font-medium opacity-90">Input data sesi bimbingan pernikahan untuk calon pengantin</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.jadwal.store') }}" method="POST" id="formJadwal" class="p-8 space-y-8">
            @csrf

            {{-- ERROR VALIDASI SERVER --}}
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-600 p-4 rounded-xl text-sm animate-pulse">
                <p class="font-bold mb-1 italic uppercase tracking-widest text-[10px]">Terjadi Kesalahan:</p>
                <ul class="list-disc pl-5 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- DETAIL JADWAL --}}
            <div class="space-y-6">
                <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">event_note</span> Informasi Waktu & Topik
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- TANGGAL --}}
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Tanggal Bimbingan</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">calendar_month</span>
                            <input type="date" name="tanggal" value="{{ old('tanggal') }}" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700">
                        </div>
                    </div>

                    {{-- SESI WAKTU --}}
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Sesi Waktu</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">schedule</span>
                            <input type="text" name="sesi" value="{{ old('sesi') }}" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700"
                                placeholder="Contoh: 08:30 - 10:30 WIB">
                        </div>
                    </div>

                    {{-- TOPIK --}}
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

                    {{-- LOKASI (LOGIKA TAMBAHAN) --}}
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Lokasi Pelaksanaan</label>
                        <div class="mt-2 flex gap-4 bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-100">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="lokasi" value="Kantor KUA" class="w-4 h-4 text-[#4ce619] focus:ring-[#4ce619]" checked>
                                <span class="text-sm font-bold text-gray-600 group-hover:text-gray-900 transition-colors">Kantor KUA</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="lokasi" value="Rumah Catin" class="w-4 h-4 text-[#4ce619] focus:ring-[#4ce619]">
                                <span class="text-sm font-bold text-gray-600 group-hover:text-gray-900 transition-colors">Rumah Catin</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- FASILITATOR & PENDAMPING --}}
            <div class="space-y-6">
                <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">person_celebration</span> Fasilitator & Petugas
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- PEMATERI --}}
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Pilih Pemateri</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">person</span>
                            <select name="fasilitator" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700 appearance-none bg-white">
                                <option value="" disabled selected>Pilih Pemateri...</option>
                                <option value="Ahmad Subarkah">Ust. Ahmad Subarkah</option>
                                <option value="Siti Maryam">Hj. Siti Maryam</option>
                                <option value="Dewi Rahayu">Ibu Dewi Rahayu</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    {{-- PENDAMPING (LOGIKA TAMBAHAN) --}}
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Petugas Pendamping</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">verified_user</span>
                            <select name="pendamping_id" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700 appearance-none bg-white">
                                <option value="" disabled selected>Pilih Pendamping...</option>
                                @forelse($pendampings as $pendamping)
                                    <option value="{{ $pendamping->id }}">{{ $pendamping->nama }}</option>
                                @empty
                                    <option value="" disabled>Data pendamping kosong</option>
                                @endforelse
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                </div>

                {{-- STATUS TOGGLE --}}
                <div class="bg-gray-50 dark:bg-white/5 p-6 rounded-2xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <label class="font-black uppercase text-xs tracking-widest text-gray-700 block">Status Publikasi Jadwal</label>
                        <p class="text-[10px] text-gray-400 mt-1 font-medium italic">Jadwal "Upcoming" akan langsung tampil di aplikasi Catin.</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="setJadwalStatus('Upcoming')" id="btn-upcoming"
                            class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-[#4ce619] text-white shadow-lg">
                            Upcoming
                        </button>
                        <button type="button" onclick="setJadwalStatus('Completed')" id="btn-completed"
                            class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-gray-400 border border-gray-200">
                            Completed
                        </button>
                        <input type="hidden" name="status" id="status_jadwal_input" value="Upcoming">
                    </div>
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

{{-- PEMILIHAN CATIN --}}
<div class="space-y-6">
    <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">groups</span> Pilih Peserta (Catin)
    </h3>

    <div class="bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 overflow-hidden">
        {{-- Header Tabel --}}
        <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-gray-100/50 dark:bg-white/10 text-[10px] font-black uppercase tracking-widest text-gray-500 border-b border-gray-200">
            <div class="col-span-2 text-center">Pilih</div>
            <div class="col-span-5">Nama Catin (Suami)</div>
            <div class="col-span-5">Nama Catin (Istri)</div>
        </div>

        {{-- Daftar Catin dengan Scroll --}}
        <div class="max-h-72 overflow-y-auto custom-scrollbar">
            @forelse($catins as $catin)
            <label class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-white dark:hover:bg-dark-surface transition-all items-center border-b border-gray-100 last:border-0 cursor-pointer group">
                <div class="col-span-2 flex justify-center">
                    <input type="checkbox" name="catin_ids[]" value="{{ $catin->id }}" 
                        class="w-5 h-5 rounded-lg border-gray-300 text-[#4ce619] focus:ring-[#4ce619] transition-all cursor-pointer">
                </div>
                <div class="col-span-5">
                    <p class="text-sm font-bold text-gray-700 group-hover:text-[#4ce619] transition-colors">
                        {{ $catin->nama_suami }}
                    </p>
                </div>
                <div class="col-span-5">
                    <p class="text-sm font-bold text-gray-700 group-hover:text-[#4ce619] transition-colors">
                        {{ $catin->nama_istri }}
                    </p>
                </div>
            </label>
            @empty
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">person_off</span>
                <p class="text-sm text-gray-400 italic font-medium">Tidak ada data calon pengantin tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>
    <p class="text-[10px] text-gray-400 mt-2 font-medium italic flex items-center gap-1">
        <span class="material-symbols-outlined text-xs">info</span> 
        * Centang calon pengantin yang dijadwalkan mengikuti sesi ini.
    </p>
</div>

            {{-- BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-8 border-t border-gray-50">
                <a href="{{ route('admin.jadwal.index') }}"
                    class="w-full sm:w-auto text-center px-8 py-3 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit"
                    class="w-full sm:w-auto px-10 py-3 bg-[#4ce619] text-white font-black rounded-2xl shadow-xl shadow-green-200 hover:shadow-green-300 hover:-translate-y-1 transition-all uppercase text-sm tracking-widest active:scale-95">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function setJadwalStatus(val) {
        document.getElementById('status_jadwal_input').value = val;
        const btnUpcoming = document.getElementById('btn-upcoming');
        const btnCompleted = document.getElementById('btn-completed');
        
        if(val === 'Upcoming') {
            btnUpcoming.className = "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-[#4ce619] text-white shadow-lg";
            btnCompleted.className = "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-gray-400 border border-gray-200";
        } else {
            btnCompleted.className = "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-blue-500 text-white shadow-lg";
            btnUpcoming.className = "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-gray-400 border border-gray-200";
        }
    }
</script>

@endsection