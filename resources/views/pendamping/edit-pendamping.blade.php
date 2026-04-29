@extends('layouts.app')

@section('title', 'Edit Data Pendamping')
@section('page-title', 'Edit Data Pendamping')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex justify-center py-10 px-4">
    <div class="w-full max-w-4xl bg-white dark:bg-dark-surface rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-[#4ce619] p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-9xl">person_edit</span>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('admin.pendamping.index') }}" 
                   class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-xl hover:bg-white/40 transition-all">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">Edit Data Pendamping</h2>
                    <p class="text-sm font-medium opacity-90 flex items-center gap-2">
                        Perbarui informasi akun dan kredensial pendamping
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.pendamping.update', $pendamping->id) }}"
            method="POST"
            class="p-8 space-y-8">

            @csrf
            @method('PUT')

            {{-- ERROR VALIDASI --}}
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-600 p-4 rounded-xl text-sm animate-pulse">
                <p class="font-bold mb-1">Terjadi Kesalahan:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- INFORMASI AKUN --}}
            <div class="space-y-6">
                <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">badge</span> Informasi Akun
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NAMA --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Nama Lengkap</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">person</span>
                            <input type="text" name="nama" 
                                value="{{ old('nama', $pendamping->nama) }}"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold"
                                required>
                        </div>
                    </div>

                    {{-- NIP --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">NIP</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">fingerprint</span>
                            <input type="text" name="nip" 
                                value="{{ old('nip', $pendamping->nip) }}"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold">
                        </div>
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Email Address</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">mail</span>
                            <input type="email" name="email" 
                                value="{{ old('email', $pendamping->email) }}"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold"
                                required>
                        </div>
                    </div>

                    {{-- NO HP --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Nomor WhatsApp</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">call</span>
                            <input type="text" name="no_hp" 
                                value="{{ old('no_hp', $pendamping->no_hp) }}"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold">
                        </div>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Ganti Password (Opsional)</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">lock</span>
                            <input type="password" name="password" id="password"
                                placeholder="Kosongkan jika tidak ingin mengganti password"
                                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all">
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-2.5 text-gray-400 hover:text-[#4ce619]">
                                <span class="material-symbols-outlined text-sm" id="passIcon">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- STATUS --}}
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <label class="font-black uppercase text-xs tracking-widest text-gray-700 block">Status Akun</label>
                    <p class="text-[10px] text-gray-400">Nonaktifkan untuk membekukan akses login pendamping ini</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="setStatus('aktif')" id="btn-aktif"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $pendamping->status == 'aktif' ? 'bg-[#4ce619] text-white shadow-lg' : 'bg-white text-gray-400 border border-gray-200' }}">
                        Aktif
                    </button>
                    <button type="button" onclick="setStatus('nonaktif')" id="btn-nonaktif"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $pendamping->status == 'nonaktif' ? 'bg-gray-400 text-white shadow-lg' : 'bg-white text-gray-400 border border-gray-200' }}">
                        Nonaktif
                    </button>
                    <input type="hidden" name="status" id="status_input" value="{{ $pendamping->status }}">
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-8 border-t border-gray-50">
                <a href="{{ route('admin.pendamping.index') }}"
                    class="w-full sm:w-auto text-center px-8 py-3 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                    BATALKAN
                </a>
                <button type="submit"
                    class="w-full sm:w-auto px-10 py-3 bg-[#4ce619] text-white font-black rounded-2xl shadow-xl shadow-green-200 hover:shadow-green-300 hover:-translate-y-1 transition-all uppercase text-sm tracking-widest">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    // TOGGLE PASSWORD
    function togglePassword() {
        let input = document.getElementById("password")
        let icon = document.getElementById("passIcon")
        if (input.type === "password") {
            input.type = "text"
            icon.innerText = "visibility_off"
        } else {
            input.type = "password"
            icon.innerText = "visibility"
        }
    }

    // STATUS SELECTOR (Aktif / Nonaktif)
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
</script>

@endsection