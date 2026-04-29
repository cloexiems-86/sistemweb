@extends('layouts.app')

@section('title','Edit Data Catin')
@section('page-title','Edit Data Calon Pengantin')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex justify-center py-10 px-4">
    <div class="w-full max-w-4xl bg-white dark:bg-dark-surface rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-[#4ce619] p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-9xl">edit_document</span>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('admin.catin.index') }}" 
                   class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-xl hover:bg-white/40 transition-all">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">Edit Data Pasangan</h2>
                    <p class="text-sm font-medium opacity-90 flex items-center gap-2">
                        Update informasi dan dokumen persyaratan nikah
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.catin.update', $catin->id) }}"
            method="POST" enctype="multipart/form-data"
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- USERNAME --}}
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Username Akun</label>
                    <div class="relative mt-1">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">alternate_email</span>
                        <input type="text" name="username" id="username"
                            value="{{ old('username', $catin->username) }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all font-bold"
                            required>
                    </div>
                    <p class="text-red-500 text-[10px] mt-1 hidden" id="usernameError">Minimal 3 karakter</p>
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Ganti Password (Opsional)</label>
                    <div class="relative mt-1">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-sm">lock</span>
                        <input type="password" name="password" id="password"
                            placeholder="Isi hanya jika ingin ganti"
                            class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none transition-all">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-[#4ce619]">
                            <span class="material-symbols-outlined text-sm" id="passIcon">visibility</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- DATA SUAMI --}}
                <div class="space-y-4 p-6 bg-blue-50/30 rounded-3xl border border-blue-100/50">
                    <div class="flex items-center gap-2 border-b border-blue-100 pb-3 mb-4">
                        <span class="material-symbols-outlined text-blue-500">male</span>
                        <h3 class="font-black text-blue-600 uppercase text-xs tracking-widest">Identitas Suami</h3>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">Nama Lengkap</label>
                        <input type="text" name="nama_suami" value="{{ old('nama_suami', $catin->nama_suami) }}" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-blue-400 focus:ring-0 text-sm font-medium">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">NIK Suami</label>
                        <input type="text" name="nik_suami" value="{{ old('nik_suami', $catin->nik_suami) }}" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-blue-400 focus:ring-0 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">WhatsApp Suami</label>
                        <input type="text" name="phone_suami" value="{{ old('phone_suami', $catin->phone_suami) }}" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-blue-400 focus:ring-0 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">Email Suami</label>
                        <input type="email" name="email_suami" value="{{ old('email_suami', $catin->email_suami) }}" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-blue-400 focus:ring-0 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">Alamat Lengkap</label>
                        <textarea name="alamat_suami" rows="2" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-blue-400 focus:ring-0 text-sm">{{ old('alamat_suami', $catin->alamat_suami) }}</textarea>
                    </div>
                </div>

                {{-- DATA ISTRI --}}
                <div class="space-y-4 p-6 bg-pink-50/30 rounded-3xl border border-pink-100/50">
                    <div class="flex items-center gap-2 border-b border-pink-100 pb-3 mb-4">
                        <span class="material-symbols-outlined text-pink-500">female</span>
                        <h3 class="font-black text-pink-600 uppercase text-xs tracking-widest">Identitas Istri</h3>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">Nama Lengkap</label>
                        <input type="text" name="nama_istri" value="{{ old('nama_istri', $catin->nama_istri) }}" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-pink-400 focus:ring-0 text-sm font-medium">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">NIK Istri</label>
                        <input type="text" name="nik_istri" value="{{ old('nik_istri', $catin->nik_istri) }}" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-pink-400 focus:ring-0 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">WhatsApp Istri</label>
                        <input type="text" name="phone_istri" value="{{ old('phone_istri', $catin->phone_istri) }}" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-pink-400 focus:ring-0 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">Email Istri</label>
                        <input type="email" name="email_istri" value="{{ old('email_istri', $catin->email_istri) }}" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-pink-400 focus:ring-0 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-500">Alamat Lengkap</label>
                        <textarea name="alamat_istri" rows="2" class="w-full border-gray-200 rounded-xl p-2.5 mt-1 focus:border-pink-400 focus:ring-0 text-sm">{{ old('alamat_istri', $catin->alamat_istri) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- JADWAL --}}
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-gray-400">calendar_month</span>
                    <label class="font-black uppercase text-xs tracking-widest text-gray-700">Rencana Tanggal Akad Nikah</label>
                </div>
                <input type="date" name="wedding_date" value="{{ old('wedding_date', $catin->wedding_date) }}" 
                       class="w-full border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-[#4ce619]/20 focus:border-[#4ce619] outline-none font-bold text-[#4ce619]">
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- UPLOAD DOKUMEN --}}
            <div>
                <h3 class="font-black text-xs uppercase tracking-[0.2em] text-gray-400 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">folder_open</span> Dokumen Persyaratan
                </h3>
                
                {{-- Grid Dokumen --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach (['ktp_suami','ktp_istri','kk_suami','kk_istri','surat_kematian'] as $file)
                    <div class="flex flex-col">
                        <label class="text-[9px] font-black uppercase text-gray-400 mb-2 truncate">
                            {{ str_replace('_',' ',$file) }}
                        </label>
                        <div class="upload-box flex flex-col items-center justify-center min-h-[100px] border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-[#4ce619] hover:bg-green-50 transition-all group" 
                             data-input="{{ $file }}">
                            <span class="material-symbols-outlined text-gray-300 group-hover:text-[#4ce619] transition-colors">cloud_upload</span>
                            <span class="text-[9px] font-bold text-gray-400 mt-1">Klik / Drop</span>
                        </div>
                        <input type="file" name="{{ $file }}" id="{{ $file }}" hidden accept="image/*,application/pdf">
                        
                        {{-- Indikator File Sudah Ada --}}
                        <div class="mt-2 flex items-center justify-between px-1">
                            @if($catin->$file)
                                <span class="text-[9px] text-green-600 font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">check_circle</span> Terunggah
                                </span>
                                <a href="{{ asset('storage/'.$catin->$file) }}" target="_blank" class="text-[9px] text-blue-500 hover:underline font-bold uppercase">Lihat</a>
                            @else
                                <span class="text-[9px] text-gray-300 italic">Belum ada file</span>
                            @endif
                        </div>

                        {{-- Preview New Image --}}
                        <img id="preview_{{ $file }}" class="mt-2 hidden rounded-xl w-full h-24 object-cover border-2 border-[#4ce619]/20 shadow-sm animate-fade-in"/>
                        <div id="pdf_preview_{{ $file }}" class="mt-2 hidden p-2 bg-gray-100 rounded-xl text-center">
                            <span class="material-symbols-outlined text-red-500 text-2xl">picture_as_pdf</span>
                            <p class="text-[8px] font-bold text-gray-500">PDF Terpilih</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- STATUS --}}
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <label class="font-black uppercase text-xs tracking-widest text-gray-700 block">Status Akun</label>
                    <p class="text-[10px] text-gray-400">Nonaktifkan akun jika ingin membekukan akses login catin</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="setStatus('aktif')" id="btn-aktif"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $catin->status == 'aktif' ? 'bg-[#4ce619] text-white shadow-lg' : 'bg-white text-gray-400 border border-gray-200' }}">
                        Aktif
                    </button>
                    <button type="button" onclick="setStatus('nonaktif')" id="btn-nonaktif"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $catin->status == 'nonaktif' ? 'bg-gray-400 text-white shadow-lg' : 'bg-white text-gray-400 border border-gray-200' }}">
                        Nonaktif
                    </button>
                    <input type="hidden" name="status" id="status_input" value="{{ $catin->status }}">
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-8 border-t border-gray-50">
                <a href="{{ route('admin.catin.index') }}"
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

    // STATUS SELECTOR
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

    // REALTIME VALIDATION USERNAME
    document.getElementById("username").addEventListener("input", function() {
        let error = document.getElementById("usernameError")
        if (this.value.length > 0 && this.value.length < 3) {
            error.classList.remove("hidden")
        } else {
            error.classList.add("hidden")
        }
    })

    // DRAG DROP + PREVIEW
    document.querySelectorAll(".upload-box").forEach(box => {
        let inputId = box.dataset.input
        let input = document.getElementById(inputId)
        let previewImg = document.getElementById("preview_" + inputId)
        let previewPdf = document.getElementById("pdf_preview_" + inputId)

        box.addEventListener("click", () => input.click())

        box.addEventListener("dragover", (e) => {
            e.preventDefault()
            box.classList.add("border-[#4ce619]", "bg-green-50")
        })

        box.addEventListener("dragleave", () => {
            box.classList.remove("border-[#4ce619]", "bg-green-50")
        })

        box.addEventListener("drop", (e) => {
            e.preventDefault()
            box.classList.remove("border-[#4ce619]", "bg-green-50")
            input.files = e.dataTransfer.files
            handlePreview(input, previewImg, previewPdf)
        })

        input.addEventListener("change", () => {
            handlePreview(input, previewImg, previewPdf)
        })
    })

    function handlePreview(input, previewImg, previewPdf) {
        let file = input.files[0]
        if (file) {
            if (file.type.startsWith('image/')) {
                let reader = new FileReader()
                reader.onload = function(e) {
                    previewImg.src = e.target.result
                    previewImg.classList.remove("hidden")
                    previewPdf.classList.add("hidden")
                }
                reader.readAsDataURL(file)
            } else if (file.type === 'application/pdf') {
                previewPdf.classList.remove("hidden")
                previewImg.classList.add("hidden")
            }
        }
    }
</script>

@endsection