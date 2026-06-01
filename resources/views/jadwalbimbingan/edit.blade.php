@extends('layouts.app')

@section('title', 'Edit Jadwal Bimbingan & Rapak')
@section('page-title', 'Edit Jadwal')

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
                    
                    {{-- PERBAIKAN: MENAMBAHKAN JENIS JADWAL YANG HILANG --}}
                    <div class="space-y-4 mb-2">
                        <h3 class="font-black text-xs uppercase tracking-[0.2em] text-[#4ce619] flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">category</span> Jenis Kegiatan
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative flex items-center justify-center gap-2 p-3 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-[#4ce619] has-[:checked]:bg-green-50/50 group">
                                <input type="radio" name="jenis_jadwal" value="Bimbingan" class="peer hidden" onchange="toggleJenisKegiatan()" {{ old('jenis_jadwal', $jadwal->jenis_jadwal ?? 'Bimbingan') == 'Bimbingan' ? 'checked' : '' }}>
                                <span class="material-symbols-outlined text-gray-400 group-has-[:checked]:text-[#4ce619]">school</span>
                                <div class="text-center">
                                    <p class="font-bold text-gray-700 text-[11px] group-has-[:checked]:text-[#4ce619]">Bimbingan Pra-Nikah</p>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center justify-center gap-2 p-3 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-[#4ce619] has-[:checked]:bg-green-50/50 group">
                                <input type="radio" name="jenis_jadwal" value="Rapak" class="peer hidden" onchange="toggleJenisKegiatan()" {{ old('jenis_jadwal', $jadwal->jenis_jadwal ?? '') == 'Rapak' ? 'checked' : '' }}>
                                <span class="material-symbols-outlined text-gray-400 group-has-[:checked]:text-[#4ce619]">fact_check</span>
                                <div class="text-center">
                                    <p class="font-bold text-gray-700 text-[11px] group-has-[:checked]:text-[#4ce619]">Pemeriksaan Rapak</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <h3 class="font-black text-xs uppercase tracking-[0.2em] text-[#4ce619] flex items-center gap-2 mb-4 pt-2 border-t border-gray-100">
                        <span class="material-symbols-outlined text-lg">event_note</span> Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="group">
                            <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1">Tanggal</label>
                            <input type="date" name="tanggal" id="input-tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}"
                                class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-[#4ce619] focus:ring-4 focus:ring-[#4ce619]/10 outline-none font-bold text-gray-700 text-sm transition-all" required>
                            <p id="hint-rapak" class="hidden text-[10px] text-orange-500 mt-1 font-bold italic">* Disarankan batas H-10 jika Catin dipilih.</p>
                        </div>
                        <div class="group">
                            <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1">Sesi Waktu</label>
                            <input type="text" name="sesi" value="{{ old('sesi', $jadwal->sesi) }}"
                                class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-[#4ce619] focus:ring-4 focus:ring-[#4ce619]/10 outline-none font-bold text-gray-700 text-sm transition-all" placeholder="Contoh: 08:00 - 10:00" required>
                        </div>
                    </div>

                    {{-- PERBAIKAN: TOPIK BIMBINGAN (Ditampilkan jika Bimbingan) --}}
                    <div class="space-y-2" id="container-materi">
                        <label class="text-[10px] font-black uppercase text-gray-400 block px-1 tracking-[0.2em]">Topik Bimbingan / Materi</label>
                        <select name="topik" id="input-topik" required class="w-full border border-gray-200 rounded-2xl p-4 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-green-400/10 outline-none transition-all appearance-none cursor-pointer">
                            <option value="" disabled>-- Pilih Topik Materi --</option>
                            @foreach($materis as $materi)
                                <option value="{{ $materi->judul }}" 
                                    {{ (old('topik', $jadwal->topik) == $materi->judul) ? 'selected' : '' }}>
                                    {{ $materi->judul }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PERBAIKAN: AGENDA RAPAK (Ditampilkan jika Rapak) --}}
                    <div class="space-y-2 hidden" id="container-agenda">
                        <label class="text-[10px] font-black uppercase text-gray-400 block px-1 tracking-[0.2em]">Agenda Pemeriksaan</label>
                        <input type="text" name="agenda_rapak" id="input-agenda" value="{{ old('agenda_rapak', $jadwal->topik ?? 'Pemeriksaan Berkas, Wali, dan Mahar') }}" 
                            class="w-full border border-gray-200 rounded-2xl p-4 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-green-400/10 outline-none transition-all">
                    </div>

                    <div class="group">
                        <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1">Lokasi Pelaksanaan</label>
                        <select name="lokasi" class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-[#4ce619] focus:ring-4 focus:ring-[#4ce619]/10 outline-none font-bold text-gray-700 text-sm bg-white transition-all" required>
                            <option value="Kantor KUA" {{ old('lokasi', $jadwal->lokasi) == 'Kantor KUA' ? 'selected' : '' }}>Kantor KUA</option>
                           
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="group">
                            <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1" id="label-petugas">Fasilitator</label>
                            <select name="fasilitator" class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 outline-none font-bold text-gray-700 text-sm bg-white focus:border-[#4ce619] transition-all" required>
                                <option value="Ahmad Subarkah" {{ old('fasilitator', $jadwal->fasilitator) == 'Ahmad Subarkah' ? 'selected' : '' }}>Ust. Ahmad Subarkah</option>
                                <option value="Siti Maryam" {{ old('fasilitator', $jadwal->fasilitator) == 'Siti Maryam' ? 'selected' : '' }}>Hj. Siti Maryam</option>
                                <option value="Dewi Rahayu" {{ old('fasilitator', $jadwal->fasilitator) == 'Dewi Rahayu' ? 'selected' : '' }}>Ibu Dewi Rahayu</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 block ml-1">Pendamping</label>
                            <select name="pendamping_id" class="w-full mt-2 px-5 py-3.5 rounded-2xl border border-gray-200 outline-none font-bold text-gray-700 text-sm bg-white focus:border-[#4ce619] transition-all" required>
                                <option value="" disabled>Pilih Pendamping...</option>
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
                                class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all {{ old('status', $jadwal->status) == 'Upcoming' ? 'bg-white text-blue-500 shadow-md' : 'text-gray-400' }}">
                                Upcoming
                            </button>
                            <button type="button" onclick="setStatus('Completed')" id="btn-completed"
                                class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase transition-all {{ old('status', $jadwal->status) == 'Completed' ? 'bg-white text-[#4ce619] shadow-md' : 'text-gray-400' }}">
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
                            <div class="col-span-11 ml-4">Nama Pasangan & Tgl Nikah</div>
                        </div>

                        <div class="max-h-[550px] overflow-y-auto divide-y divide-gray-100 bg-white">
                            @forelse($catins as $catin)
                            <label class="grid grid-cols-12 items-center px-6 py-5 hover:bg-green-50 transition-colors cursor-pointer group">
                                <div class="col-span-1 flex justify-center">
                                    {{-- PERBAIKAN: Tambahan data-tanggal-nikah dan event onchange --}}
                                    <input type="checkbox" name="catin_ids[]" value="{{ $catin->id }}"
                                        data-tanggal-nikah="{{ $catin->wedding_date ?? '' }}"
                                        onchange="hitungTanggalRapak()"
                                        {{ in_array($catin->id, old('catin_ids', $jadwal->catins->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="catin-checkbox w-5 h-5 rounded-lg border-gray-300 text-[#4ce619] focus:ring-[#4ce619] transition-all">
                                </div>
                                <div class="col-span-11 ml-4 flex flex-col">
                                    <span class="text-sm font-bold text-gray-700 group-hover:text-[#3db514] transition-colors">{{ $catin->nama_suami }} & {{ $catin->nama_istri }}</span>
                                    <span class="text-[10px] font-medium text-gray-400 mt-0.5">
                                        <span class="material-symbols-outlined text-[10px] align-middle">favorite</span>
                                        Tgl Nikah: <span class="text-gray-500 font-bold">{{ $catin->wedding_date ? \Carbon\Carbon::parse($catin->wedding_date)->format('d M Y') : 'Belum Diset' }}</span>
                                    </span>
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
    // 1. Script Status
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

    // 2. Script Jenis Kegiatan
    function toggleJenisKegiatan() {
        const jenisNode = document.querySelector('input[name="jenis_jadwal"]:checked');
        if (!jenisNode) return;
        
        const jenis = jenisNode.value;
        const containerMateri = document.getElementById('container-materi');
        const inputTopik = document.getElementById('input-topik');
        const containerAgenda = document.getElementById('container-agenda');
        const labelPetugas = document.getElementById('label-petugas');
        const hintRapak = document.getElementById('hint-rapak');

        if (jenis === 'Bimbingan') {
            containerMateri.classList.remove('hidden');
            inputTopik.required = true;
            containerAgenda.classList.add('hidden');
            labelPetugas.innerText = "Fasilitator";
            hintRapak.classList.add('hidden');
        } else {
            containerMateri.classList.add('hidden');
            inputTopik.required = false;
            containerAgenda.classList.remove('hidden');
            labelPetugas.innerText = "Penghulu / Pemeriksa";
            hintRapak.classList.remove('hidden');
            
            hitungTanggalRapak(); 
        }
    }

    // 3. Script Hitung H-10
    function hitungTanggalRapak() {
        const jenisNode = document.querySelector('input[name="jenis_jadwal"]:checked');
        if (!jenisNode) return;
        
        const jenis = jenisNode.value;
        const inputTanggal = document.getElementById('input-tanggal');
        
        if (jenis === 'Rapak') {
            const checkedCatins = document.querySelectorAll('.catin-checkbox:checked');
            
            if (checkedCatins.length > 0) {
                const tglNikahRaw = checkedCatins[0].getAttribute('data-tanggal-nikah');
                
                if (tglNikahRaw) {
                    let tglNikah = new Date(tglNikahRaw);
                    tglNikah.setDate(tglNikah.getDate() - 10);
                    
                    let yyyy = tglNikah.getFullYear();
                    let mm = String(tglNikah.getMonth() + 1).padStart(2, '0');
                    let dd = String(tglNikah.getDate()).padStart(2, '0');
                    
                    inputTanggal.value = `${yyyy}-${mm}-${dd}`;
                    
                    inputTanggal.classList.add('bg-green-50', 'ring-2', 'ring-green-400');
                    setTimeout(() => {
                        inputTanggal.classList.remove('bg-green-50', 'ring-2', 'ring-green-400');
                    }, 1000);
                }
            }
        }
    }

    // Eksekusi Javascript saat halaman selesai dimuat agar form menyesuaikan database lama
    document.addEventListener('DOMContentLoaded', toggleJenisKegiatan);
</script>

@endsection