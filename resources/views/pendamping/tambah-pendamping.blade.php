@extends('layouts.app')

@section('title', 'Tambah Pendamping')
@section('page-title', 'Registrasi Pendamping')

@section('content')

<div class="flex justify-center py-10 px-4">
    <div class="w-full max-w-4xl bg-white dark:bg-dark-surface rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        
        {{-- HEADER FORM --}}
        <div class="bg-[#4ce619] p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-9xl">person_add</span>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('admin.pendamping.index') }}" 
                   class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-xl hover:bg-white/40 transition-all">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">Registrasi Pendamping</h2>
                    <p class="text-sm font-medium opacity-90">Tambahkan akun personil pendamping baru untuk sistem KUA Mojo</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.pendamping.store') }}" method="POST" id="formPendamping" class="p-8 space-y-8">
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

            {{-- INFORMASI PRIBADI --}}
            <div class="space-y-6">
                <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">badge</span> Informasi Identitas
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NAMA --}}
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Nama Lengkap</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">person</span>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700"
                                placeholder="Contoh: Ahmad Subarjo, S.Sos">
                        </div>
                    </div>

                    {{-- NIP --}}
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">NIP (Wajib 16 Digit)</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">fingerprint</span>
                            <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required
                                maxlength="16"
                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateNIP(this);"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700"
                                placeholder="Masukkan 16 digit NIP">
                        </div>
                        {{-- Pesan Peringatan NIP --}}
                        <p id="nip-warning" class="hidden text-[10px] font-bold text-red-500 mt-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">warning</span> NIP harus tepat 16 digit! (Saat ini: <span id="nip-count">0</span>)
                        </p>
                    </div>
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- KONTAK & AKSES --}}
            <div class="space-y-6">
                <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">lock_open</span> Kontak & Kredensial Akses
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- EMAIL --}}
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Alamat Email</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">mail</span>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700"
                                placeholder="pendamping@kua.com">
                        </div>
                    </div>

                    {{-- NO HP --}}
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Nomor WhatsApp</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">call</span>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700"
                                placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Password Baru</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">key</span>
                            <input type="password" name="password" required minlength="6"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold text-gray-700"
                                placeholder="Minimal 6 karakter unik">
                        </div>
                    </div>
                </div>
            </div>

            {{-- STATUS SELECTION --}}
            <div class="bg-gray-50 dark:bg-white/5 p-6 rounded-2xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <label class="font-black uppercase text-xs tracking-widest text-gray-700 block">Status Aktivasi Akun</label>
                    <p class="text-[10px] text-gray-400 mt-1 font-medium italic">Akun nonaktif tidak akan bisa login ke sistem.</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="setStatus('aktif')" id="btn-aktif"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-[#4ce619] text-white shadow-lg">
                        Aktif
                    </button>
                    <button type="button" onclick="setStatus('nonaktif')" id="btn-nonaktif"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-gray-400 border border-gray-200">
                        Nonaktif
                    </button>
                    <input type="hidden" name="status" id="status_input" value="aktif">
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-8 border-t border-gray-50">
                <a href="{{ route('admin.pendamping.index') }}"
                    class="w-full sm:w-auto text-center px-8 py-3 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit" id="submitBtn"
                    class="w-full sm:w-auto px-10 py-3 bg-[#4ce619] text-white font-black rounded-2xl shadow-xl shadow-green-200 hover:shadow-green-300 hover:-translate-y-1 transition-all uppercase text-sm tracking-widest active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                    Daftarkan Pendamping
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi Validasi NIP (16 Digit)
    function validateNIP(input) {
        const warning = document.getElementById('nip-warning');
        const countDisplay = document.getElementById('nip-count');
        const submitBtn = document.getElementById('submitBtn');
        const currentLength = input.value.length;

        countDisplay.innerText = currentLength;

        if (currentLength > 0 && currentLength !== 16) {
            warning.classList.remove('hidden');
            input.classList.add('border-red-500', 'focus:ring-red-200');
            submitBtn.disabled = true;
        } else {
            warning.classList.add('hidden');
            input.classList.remove('border-red-500', 'focus:ring-red-200');
            submitBtn.disabled = false;
        }
    }

    // Script Status Toggle
    function setStatus(val) {
        document.getElementById('status_input').value = val;
        const btnAktif = document.getElementById('btn-aktif');
        const btnNon = document.getElementById('btn-nonaktif');
        
        if(val === 'aktif') {
            btnAktif.className = "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-[#4ce619] text-white shadow-lg";
            btnNon.className = "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-gray-400 border border-gray-200";
        } else {
            btnNon.className = "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-gray-400 text-white shadow-lg";
            btnAktif.className = "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-white text-gray-400 border border-gray-200";
        }
    }

    // Tambahan: Pastikan saat form dikirim, NIP dicek kembali
    document.getElementById('formPendamping').onsubmit = function(e) {
        const nip = document.getElementById('nip').value;
        if (nip.length !== 16) {
            e.preventDefault();
            alert('NIP harus tepat 16 digit angka!');
        }
    };
</script>

@endsection