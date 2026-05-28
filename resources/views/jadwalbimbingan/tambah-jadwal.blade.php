@extends('layouts.app')

@section('title', 'Tambah Jadwal Bimbingan & Rapak')
@section('page-title', 'Buat Jadwal Baru')

@section('content')

<div class="flex justify-center py-6 px-4">
    <div class="w-full max-w-7xl bg-white dark:bg-dark-surface rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        
        {{-- HEADER FORM --}}
        <div class="bg-[#4ce619] p-6 lg:p-8 text-white relative overflow-hidden">
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
                    <p class="text-sm font-medium opacity-90">Input data sesi Bimbingan Pra-Nikah atau Pemeriksaan Rapak</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.jadwal.store') }}" method="POST" id="formJadwal" class="p-6 lg:p-8">
            @csrf

            {{-- ERROR VALIDASI SERVER --}}
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-600 p-4 rounded-xl text-sm animate-pulse mb-8">
                <p class="font-bold mb-1 italic uppercase tracking-widest text-[10px]">Terjadi Kesalahan:</p>
                <ul class="list-disc pl-5 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 lg:gap-10">
                
                {{-- KOLOM KIRI: FORM INPUT DATA --}}
                <div class="xl:col-span-7 space-y-8">
                    
                    {{-- JENIS KEGIATAN --}}
                    <div class="space-y-4">
                        <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 border-b border-gray-100 pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">category</span> Jenis Kegiatan
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative flex items-center justify-center gap-2 p-4 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-[#4ce619] has-[:checked]:bg-green-50/50 group">
                                <input type="radio" name="jenis_jadwal" value="Bimbingan" class="peer hidden" checked onchange="toggleJenisKegiatan()">
                                <span class="material-symbols-outlined text-gray-400 group-has-[:checked]:text-[#4ce619]">school</span>
                                <div class="text-center">
                                    <p class="font-bold text-gray-700 text-sm group-has-[:checked]:text-[#4ce619]">Bimbingan Pra-Nikah</p>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center justify-center gap-2 p-4 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-[#4ce619] has-[:checked]:bg-green-50/50 group">
                                <input type="radio" name="jenis_jadwal" value="Rapak" class="peer hidden" onchange="toggleJenisKegiatan()">
                                <span class="material-symbols-outlined text-gray-400 group-has-[:checked]:text-[#4ce619]">fact_check</span>
                                <div class="text-center">
                                    <p class="font-bold text-gray-700 text-sm group-has-[:checked]:text-[#4ce619]">Pemeriksaan Rapak</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- DETAIL JADWAL --}}
                    <div class="space-y-5">
                        <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-3 flex items-center gap-2 border-b border-gray-100 pb-2">
                            <span class="material-symbols-outlined text-sm">event_note</span> Informasi Waktu & Detail
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- TANGGAL --}}
                            <div class="md:col-span-1">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Tanggal Pelaksanaan</label>
                                <div class="relative mt-1">
                                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">calendar_month</span>
                                    <input type="date" name="tanggal" id="input-tanggal" value="{{ old('tanggal') }}" required
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700">
                                </div>
                                <p id="hint-rapak" class="hidden text-[10px] text-orange-500 mt-1 font-bold italic">* Sistem otomatis menyarankan batas H-10 jika Catin dipilih.</p>
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

                            {{-- TOPIK / MATERI (Hanya muncul jika Bimbingan) --}}
                            <div class="md:col-span-2 space-y-1" id="container-materi">
                                <label class="text-[10px] font-black uppercase text-gray-400 block px-1 tracking-[0.2em]">Topik Bimbingan / Materi</label>
                                <select name="topik" id="input-topik" required class="w-full border border-gray-100 bg-gray-50/50 rounded-2xl p-3.5 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-green-400/10 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Topik Materi --</option>
                                    @foreach($materis as $materi)
                                        <option value="{{ $materi->judul }}" 
                                            {{ (isset($jadwal) && $jadwal->topik == $materi->judul) ? 'selected' : '' }}>
                                            {{ $materi->judul }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- AGENDA RAPAK (Hanya muncul jika Rapak) --}}
                            <div class="md:col-span-2 space-y-1 hidden" id="container-agenda">
                                <label class="text-[10px] font-black uppercase text-gray-400 block px-1 tracking-[0.2em]">Agenda Pemeriksaan</label>
                                <input type="text" name="agenda_rapak" id="input-agenda" value="Pemeriksaan Berkas, Wali, dan Mahar" 
                                    class="w-full border border-gray-100 bg-gray-50/50 rounded-2xl p-3.5 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-green-400/10 outline-none transition-all">
                            </div>

                            {{-- LOKASI --}}
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Lokasi Pelaksanaan</label>
                                <div class="mt-1 flex gap-4 bg-gray-50 dark:bg-white/5 p-3.5 rounded-xl border border-gray-100">
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

                    {{-- FASILITATOR & PENDAMPING --}}
                    <div class="space-y-5">
                        <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-3 flex items-center gap-2 border-b border-gray-100 pb-2">
                            <span class="material-symbols-outlined text-sm">person_celebration</span> Fasilitator / Penghulu
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- PEMATERI / PENGHULU --}}
                            <div class="md:col-span-1">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1" id="label-petugas">Pilih Pemateri</label>
                                <div class="relative mt-1">
                                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">person</span>
                                    <select name="fasilitator" required
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700 appearance-none bg-white">
                                        <option value="" disabled selected>Pilih Petugas...</option>
                                        <option value="Ahmad Subarkah">Ust. Ahmad Subarkah</option>
                                        <option value="Siti Maryam">Hj. Siti Maryam</option>
                                        <option value="Dewi Rahayu">Ibu Dewi Rahayu</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            {{-- PENDAMPING --}}
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
                        <div class="bg-gray-50 dark:bg-white/5 p-5 rounded-2xl border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-2">
                            <div>
                                <label class="font-black uppercase text-xs tracking-widest text-gray-700 block">Status Publikasi Jadwal</label>
                                <p class="text-[10px] text-gray-400 mt-1 font-medium italic">Jadwal "Upcoming" tampil di aplikasi Catin.</p>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" onclick="setJadwalStatus('Upcoming')" id="btn-upcoming"
                                    class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-[#4ce619] text-white shadow-lg">
                                    Upcoming
                                </button>
                                <button type="button" onclick="setJadwalStatus('Completed')" id="btn-completed"
                                    class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-gray-400 border border-gray-200">
                                    Completed
                                </button>
                                <input type="hidden" name="status" id="status_jadwal_input" value="Upcoming">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: PILIH CATIN --}}
                <div class="xl:col-span-5 flex flex-col">
                    <div class="bg-gray-50 dark:bg-white/5 rounded-3xl border border-gray-100 flex-1 flex flex-col overflow-hidden">
                        
                        <div class="p-5 border-b border-gray-200 bg-white/50">
                            <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-600 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">groups</span> Pilih Peserta (Catin)
                            </h3>
                            <p class="text-[10px] text-gray-400 mt-1 font-medium italic flex items-center gap-1">
                                <span class="material-symbols-outlined text-[10px]">info</span> 
                                Centang catin yang dijadwalkan mengikuti sesi ini.
                            </p>
                        </div>

                        <div class="grid grid-cols-12 gap-2 px-5 py-3 bg-gray-100/50 dark:bg-white/10 text-[10px] font-black uppercase tracking-widest text-gray-500 border-b border-gray-200">
                            <div class="col-span-2 text-center">Pilih</div>
                            <div class="col-span-10">Data Calon Pengantin & Tgl Nikah</div>
                        </div>

                        <div class="flex-1 w-full bg-white">
                            @forelse($catins as $catin)
                            <label class="grid grid-cols-12 gap-4 px-5 py-4 hover:bg-green-50/30 dark:hover:bg-dark-surface transition-all items-center border-b border-gray-50 last:border-0 cursor-pointer group">
                                <div class="col-span-2 flex justify-center">
                                    {{-- PERHATIKAN: Menambahkan atribut data-tanggal-nikah di sini --}}
                                    <input type="checkbox" name="catin_ids[]" value="{{ $catin->id }}" 
                                        data-tanggal-nikah="{{ $catin->tanggal_nikah ?? '' }}"
                                        onchange="hitungTanggalRapak()"
                                        class="catin-checkbox w-5 h-5 rounded-md border-gray-300 text-[#4ce619] focus:ring-[#4ce619] transition-all cursor-pointer">
                                </div>
                                <div class="col-span-10">
                                    <p class="text-sm font-bold text-gray-700 group-hover:text-[#4ce619] transition-colors truncate">
                                        {{ $catin->nama_suami }} & {{ $catin->nama_istri }}
                                    </p>
                                    <p class="text-[10px] font-medium text-gray-400 mt-0.5">
                                        <span class="material-symbols-outlined text-[10px] align-middle">favorite</span>
                                        Tgl Nikah: <span class="text-gray-500 font-bold">{{ $catin->tanggal_nikah ? \Carbon\Carbon::parse($catin->tanggal_nikah)->format('d M Y') : 'Belum Diset' }}</span>
                                    </p>
                                </div>
                            </label>
                            @empty
                            <div class="p-10 text-center">
                                <span class="material-symbols-outlined text-3xl text-gray-300 mb-2">person_off</span>
                                <p class="text-xs text-gray-400 italic font-medium">Tidak ada data catin tersedia.</p>
                            </div>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>

            {{-- BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-8 mt-8 border-t border-gray-100">
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
    // Script untuk Status Jadwal
    function setJadwalStatus(val) {
        document.getElementById('status_jadwal_input').value = val;
        const btnUpcoming = document.getElementById('btn-upcoming');
        const btnCompleted = document.getElementById('btn-completed');
        
        if(val === 'Upcoming') {
            btnUpcoming.className = "px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-[#4ce619] text-white shadow-lg";
            btnCompleted.className = "px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-gray-400 border border-gray-200";
        } else {
            btnCompleted.className = "px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-blue-500 text-white shadow-lg";
            btnUpcoming.className = "px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-gray-400 border border-gray-200";
        }
    }

    // Script untuk Toggle Jenis Kegiatan (Bimbingan vs Rapak)
    function toggleJenisKegiatan() {
        const jenis = document.querySelector('input[name="jenis_jadwal"]:checked').value;
        const containerMateri = document.getElementById('container-materi');
        const inputTopik = document.getElementById('input-topik');
        const containerAgenda = document.getElementById('container-agenda');
        const labelPetugas = document.getElementById('label-petugas');
        const hintRapak = document.getElementById('hint-rapak');

        if (jenis === 'Bimbingan') {
            containerMateri.classList.remove('hidden');
            inputTopik.required = true;
            containerAgenda.classList.add('hidden');
            labelPetugas.innerText = "Pilih Pemateri";
            hintRapak.classList.add('hidden');
        } else {
            containerMateri.classList.add('hidden');
            inputTopik.required = false;
            containerAgenda.classList.remove('hidden');
            labelPetugas.innerText = "Pilih Penghulu / Pemeriksa";
            hintRapak.classList.remove('hidden');
            
            // Panggil fungsi hitung tanggal jaga-jaga kalau ada catin yg sudah tercentang
            hitungTanggalRapak(); 
        }
    }

    // KECERDASAN JS: Menghitung H-10 Otomatis
    function hitungTanggalRapak() {
        const jenis = document.querySelector('input[name="jenis_jadwal"]:checked').value;
        const inputTanggal = document.getElementById('input-tanggal');
        
        // Cek apakah jenisnya Rapak
        if (jenis === 'Rapak') {
            // Ambil semua checkbox catin yang dicentang
            const checkedCatins = document.querySelectorAll('.catin-checkbox:checked');
            
            // Jika ada catin yang dicentang dan dia punya tanggal nikah
            if (checkedCatins.length > 0) {
                // Ambil data dari Catin pertama yang dicentang
                const tglNikahRaw = checkedCatins[0].getAttribute('data-tanggal-nikah');
                
                if (tglNikahRaw) {
                    // Konversi string ke format Date
                    let tglNikah = new Date(tglNikahRaw);
                    
                    // Hitung H-10
                    tglNikah.setDate(tglNikah.getDate() - 10);
                    
                    // Format ke YYYY-MM-DD untuk dimasukkan ke input type="date"
                    let yyyy = tglNikah.getFullYear();
                    let mm = String(tglNikah.getMonth() + 1).padStart(2, '0');
                    let dd = String(tglNikah.getDate()).padStart(2, '0');
                    
                    // Masukkan hasil ke form Tanggal
                    inputTanggal.value = `${yyyy}-${mm}-${dd}`;
                    
                    // Memberikan efek highlight warna sementara agar admin sadar angkanya berubah otomatis
                    inputTanggal.classList.add('bg-green-50', 'ring-2', 'ring-green-400');
                    setTimeout(() => {
                        inputTanggal.classList.remove('bg-green-50', 'ring-2', 'ring-green-400');
                    }, 1000);
                }
            }
        }
    }

    // Jalankan sekali saat halaman dimuat pertama kali
    document.addEventListener('DOMContentLoaded', toggleJenisKegiatan);
</script>

@endsection