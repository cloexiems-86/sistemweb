@extends('layouts.app')

@section('title', 'Edit Materi Edukasi')
@section('page-title', 'Edit Materi Edukasi')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex justify-center py-10 px-4">
    <div class="w-full max-w-4xl bg-white dark:bg-dark-surface rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-[#4ce619] p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-9xl">auto_stories</span>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('admin.materi.index') }}" 
                   class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-xl hover:bg-white/40 transition-all">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">Edit Materi Edukasi</h2>
                    <p class="text-sm font-medium opacity-90 flex items-center gap-2">
                        Perbarui informasi materi bimbingan dan dokumen lampiran
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.materi.update', $materi->id) }}" 
            method="POST" 
            enctype="multipart/form-data"
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

            {{-- INFORMASI MATERI --}}
            <div class="space-y-6">
                <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">info</span> Informasi Konten
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- JUDUL --}}
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Judul Materi</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">edit_note</span>
                            <input type="text" name="judul" 
                                value="{{ old('judul', $materi->judul) }}"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold"
                                required>
                        </div>
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Deskripsi Singkat</label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-3 text-gray-400 text-sm">description</span>
                            <textarea name="deskripsi" rows="4"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-medium"
                                required>{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- FILE SECTION --}}
            <div class="space-y-4">
                <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">attachment</span> Dokumen Materi
                </h3>
                
                <div class="p-6 bg-gray-50 dark:bg-white/5 rounded-2xl border-2 border-dashed border-gray-200">
                    <div class="flex flex-col items-center justify-center text-center">
                        <input type="file" name="file" accept=".pdf,video/*" id="file_input" class="hidden">
                        <label for="file_input" class="cursor-pointer group">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-400 group-hover:text-[#4ce619] transition-all mb-3 mx-auto">
                                <span class="material-symbols-outlined">upload_file</span>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-gray-500">Pilih File Baru</p>
                            <p class="text-[10px] text-gray-400 mt-1">PDF atau Video (Maks. 20MB)</p>
                        </label>
                    </div>

                    @if($materi->file)
                        <div class="mt-6 flex items-center gap-4 p-4 bg-white dark:bg-dark-surface rounded-xl border border-[#4ce619]/20 shadow-sm">
                            <div class="w-10 h-10 bg-[#4ce619]/10 rounded-lg flex items-center justify-center text-[#4ce619]">
                                <span class="material-symbols-outlined">verified</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black uppercase text-gray-400 leading-none">File Tersimpan</p>
                                <p class="text-xs font-bold text-gray-700 dark:text-white truncate mt-1">Materi_{{ Str::slug($materi->judul) }}</p>
                            </div>
                            <a href="{{ asset('storage/'.$materi->file) }}" target="_blank"
                                class="px-4 py-2 bg-[#4ce619] text-white text-[10px] font-black uppercase rounded-lg hover:bg-green-600 transition-all">
                                Lihat
                            </a>
                        </div>
                    @endif
                </div>
                <p class="text-[10px] text-gray-400 italic font-medium">*Kosongkan jika tidak ingin mengganti file yang sudah ada</p>
            </div>

            {{-- STATUS --}}
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <label class="font-black uppercase text-xs tracking-widest text-gray-700 block">Status Publikasi</label>
                    <p class="text-[10px] text-gray-400">Tentukan apakah materi ini dapat diakses oleh Catin</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="setStatus('aktif')" id="btn-aktif"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $materi->status == 'aktif' ? 'bg-[#4ce619] text-white shadow-lg' : 'bg-white text-gray-400 border border-gray-200' }}">
                        Aktif
                    </button>
                    <button type="button" onclick="setStatus('nonaktif')" id="btn-nonaktif"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $materi->status == 'nonaktif' ? 'bg-gray-400 text-white shadow-lg' : 'bg-white text-gray-400 border border-gray-200' }}">
                        Nonaktif
                    </button>
                    <input type="hidden" name="status" id="status_input" value="{{ old('status', $materi->status) }}">
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-8 border-t border-gray-50">
                <a href="{{ route('admin.materi.index') }}"
                    class="w-full sm:w-auto text-center px-8 py-3 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest">
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

    // Feedback file selection
    document.getElementById('file_input').onchange = function() {
        if(this.files.length > 0) {
            Swal.fire({
                icon: 'info',
                title: 'File Terpilih',
                text: this.files[0].name,
                timer: 2000,
                showConfirmButton: false
            });
        }
    };
</script>

@endsection