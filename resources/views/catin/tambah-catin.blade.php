@extends('layouts.app')

@section('title','Registrasi Catin')
@section('page-title','Tambah Data Catin')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Memastikan Icon Material Symbols Terload -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<div class="max-w-5xl mx-auto">

<h1 class="text-2xl font-bold mb-6">📋 Registrasi Calon Pengantin</h1>

{{-- ALERT SUCCESS --}}
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

{{-- ERROR --}}
@if ($errors->any())
<div class="bg-red-100 text-red-700 p-4 rounded mb-6">
    @foreach ($errors->all() as $error)
        <p>⚠️ {{ $error }}</p>
    @endforeach
</div>
@endif

<form method="POST" 
action="{{ route('admin.catin.store') }}" 
enctype="multipart/form-data" 
class="space-y-6">

@csrf

{{-- ================= AKUN ================= --}}
<div class="bg-white dark:bg-[#1a2b15] p-6 rounded-xl border">
<h3 class="font-bold text-primary mb-4">Akun Login</h3>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<div>
<label>Username</label>
<input type="text" name="username" id="username" value="{{ old('username') }}" 
class="w-full border rounded-xl p-2" required>
<p id="usernameError" class="text-red-500 text-xs hidden">Minimal 3 karakter</p>
</div>

<div>
<label>Password</label>
<div class="flex gap-2">
<input type="password" name="password" id="password" 
class="w-full border rounded-xl p-2" required>
<button type="button" onclick="generatePassword()" class="bg-gray-200 px-3 rounded-xl shadow-sm hover:bg-gray-300">Auto</button>
<button type="button" onclick="togglePassword()" class="px-2">👁️</button>
</div>
</div>

</div>
</div>

{{-- ================= SUAMI ================= --}}
<div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-xl border border-blue-100">
<h3 class="font-bold text-blue-600 mb-4 flex items-center gap-2">
    <span class="material-symbols-outlined">man</span> Data Suami
</h3>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<input type="text" name="nama_suami" placeholder="Nama Lengkap Suami" value="{{ old('nama_suami') }}" class="border rounded-xl p-2" required>

<input type="text" name="phone_suami" id="phone_suami" placeholder="No WA Suami (08...)" value="{{ old('phone_suami') }}" class="border rounded-xl p-2" required>

<input type="text" name="nik_suami" id="nik_suami" placeholder="NIK Suami (16 digit)" value="{{ old('nik_suami') }}" class="border rounded-xl p-2" required maxlength="16">

<input type="email" name="email_suami" placeholder="Email Suami" value="{{ old('email_suami') }}" class="border rounded-xl p-2" required>

{{-- DROPDOWN ALAMAT (DESA) SUAMI --}}
<select name="desa_suami" class="border rounded-xl p-2 bg-white" required>
    <option value="">-- Pilih Desa Suami --</option>
    @foreach(['Blimbing','Jugo','Kedawung','Keniten','Kranding','Kraton','Maesan','Mlati','Mojo','Mondo','Ngadi','Ngetrep','Pamongan','Petok','Petungroto','Ploso','Ponggok','Sukoanyar','Surat','Tambibendo'] as $desa)
        <option value="{{ $desa }}" {{ old('desa_suami') == $desa ? 'selected' : '' }}>{{ $desa }}</option>
    @endforeach
</select>

{{-- STATUS PERKAWINAN SUAMI --}}
<select name="status_suami" id="status_suami" class="border rounded-xl p-2 bg-white" onchange="toggleSuamiDoc()" required>
    <option value="Perjaka" {{ old('status_suami') == 'Perjaka' ? 'selected' : '' }}>Perjaka</option>
    <option value="Duda Cerai" {{ old('status_suami') == 'Duda Cerai' ? 'selected' : '' }}>Duda Cerai</option>
    <option value="Duda Mati" {{ old('status_suami') == 'Duda Mati' ? 'selected' : '' }}>Duda Mati</option>
</select>

<div class="md:col-span-2">
    <textarea name="alamat_suami" placeholder="Alamat Detail (RT/RW/Jalan) Suami" class="w-full border rounded-xl p-2" rows="2" required>{{ old('alamat_suami') }}</textarea>
</div>

</div>

<p id="nikErrorSuami" class="text-red-500 text-xs mt-2 hidden">NIK Suami harus 16 digit</p>
</div>

{{-- ================= ISTRI ================= --}}
<div class="bg-pink-50 dark:bg-pink-900/20 p-6 rounded-xl border border-pink-100">
<h3 class="font-bold text-pink-600 mb-4 flex items-center gap-2">
    <span class="material-symbols-outlined">woman</span> Data Istri
</h3>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<input type="text" name="nama_istri" placeholder="Nama Lengkap Istri" value="{{ old('nama_istri') }}" class="border rounded-xl p-2" required>

<input type="text" name="phone_istri" id="phone_istri" placeholder="No WA Istri (08...)" value="{{ old('phone_istri') }}" class="border rounded-xl p-2" required>

<input type="text" name="nik_istri" id="nik_istri" placeholder="NIK Istri (16 digit)" value="{{ old('nik_istri') }}" class="border rounded-xl p-2" required maxlength="16">

<input type="email" name="email_istri" placeholder="Email Istri" value="{{ old('email_istri') }}" class="border rounded-xl p-2" required>

{{-- DROPDOWN ALAMAT (DESA) ISTRI --}}
<select name="desa_istri" class="border rounded-xl p-2 bg-white" required>
    <option value="">-- Pilih Desa Istri --</option>
    @foreach(['Blimbing','Jugo','Kedawung','Keniten','Kranding','Kraton','Maesan','Mlati','Mojo','Mondo','Ngadi','Ngetrep','Pamongan','Petok','Petungroto','Ploso','Ponggok','Sukoanyar','Surat','Tambibendo'] as $desa)
        <option value="{{ $desa }}" {{ old('desa_istri') == $desa ? 'selected' : '' }}>{{ $desa }}</option>
    @endforeach
</select>

{{-- STATUS PERKAWINAN ISTRI --}}
<select name="status_istri" id="status_istri" class="border rounded-xl p-2 bg-white" onchange="toggleIstriDoc()" required>
    <option value="Gadis" {{ old('status_istri') == 'Gadis' ? 'selected' : '' }}>Gadis</option>
    <option value="Janda Cerai" {{ old('status_istri') == 'Janda Cerai' ? 'selected' : '' }}>Janda Cerai</option>
    <option value="Janda Mati" {{ old('status_istri') == 'Janda Mati' ? 'selected' : '' }}>Janda Mati</option>
</select>

<div class="md:col-span-2">
    <textarea name="alamat_istri" placeholder="Alamat Detail (RT/RW/Jalan) Istri" class="w-full border rounded-xl p-2" rows="2" required>{{ old('alamat_istri') }}</textarea>
</div>

</div>

<p id="nikErrorIstri" class="text-red-500 text-xs mt-2 hidden">NIK Istri harus 16 digit</p>
</div>

{{-- ================= JADWAL ================= --}}
<div class="bg-gray-50 dark:bg-white/5 p-6 rounded-xl border">
<h3 class="font-bold mb-4">Jadwal Pernikahan</h3>

<input type="date" name="wedding_date" value="{{ old('wedding_date') }}" 
class="w-full border rounded-xl p-2" required>

</div>

{{-- ================= UPLOAD ================= --}}
<div class="bg-white dark:bg-[#1a2b15] p-6 rounded-xl border">
<h3 class="font-bold mb-6">Upload Dokumen (PDF/JPG)</h3>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

{{-- Looping Dokumen Standar --}}
@foreach (['ktp_suami','ktp_istri','kk_suami','kk_istri'] as $file)
<div class="flex flex-col items-center">
    <div class="upload-box w-full" data-input="{{ $file }}">
        <span class="material-symbols-outlined block text-3xl mb-1">cloud_upload</span>
        <span class="text-xs font-bold">{{ strtoupper(str_replace('_',' ', $file)) }}</span>
    </div>
    <input type="file" id="{{ $file }}" name="{{ $file }}" hidden required>
    <img id="preview_{{ $file }}" class="hidden w-24 h-24 object-cover mt-2 rounded-lg border shadow-sm">
</div>
@endforeach

{{-- Dokumen Tambahan Suami (Duda) --}}
<div id="box_doc_suami" class="hidden flex flex-col items-center">
    <div class="upload-box w-full border-blue-400 bg-blue-50" data-input="doc_tambahan_suami">
        <span class="material-symbols-outlined block text-3xl mb-1 text-blue-600">file_present</span>
        <span class="text-xs font-bold text-blue-600">SURAT CERAI/MATI (S)</span>
    </div>
    <input type="file" id="doc_tambahan_suami" name="doc_tambahan_suami" hidden>
    <img id="preview_doc_tambahan_suami" class="hidden w-24 h-24 object-cover mt-2 rounded-lg border shadow-sm">
</div>

{{-- Dokumen Tambahan Istri (Janda) --}}
<div id="box_doc_istri" class="hidden flex flex-col items-center">
    <div class="upload-box w-full border-pink-400 bg-pink-50" data-input="doc_tambahan_istri">
        <span class="material-symbols-outlined block text-3xl mb-1 text-pink-600">file_present</span>
        <span class="text-xs font-bold text-pink-600">SURAT CERAI/MATI (I)</span>
    </div>
    <input type="file" id="doc_tambahan_istri" name="doc_tambahan_istri" hidden>
    <img id="preview_doc_tambahan_istri" class="hidden w-24 h-24 object-cover mt-2 rounded-lg border shadow-sm">
</div>

</div>
</div>

{{-- AREA BUTTON --}}
<div class="flex justify-end gap-3">
    <a href="{{ route('admin.catin.index') }}" 
       class="px-10 py-4 bg-gray-200 text-gray-700 font-black rounded-2xl hover:bg-gray-300 transition-all shadow-sm">
        Batal
    </a>
    <button type="submit" 
            class="px-10 py-4 bg-primary text-white font-black rounded-2xl hover:scale-105 transition-all shadow-lg">
        Daftarkan Catin & Simpan Data
    </button>
</div>

</form>
</div>

{{-- STYLE --}}
<style>
.upload-box{
    border:2px dashed #cbd5e1;
    padding:15px;
    text-align:center;
    border-radius:15px;
    cursor:pointer;
    transition: all 0.3s;
    background: #f8fafc;
}
.upload-box:hover{
    border-color: #0e6731;
    background: #f1f5f9;
}
</style>

{{-- SCRIPT --}}
<script>

// PASSWORD AUTO
function generatePassword(){
    let pass = Math.random().toString(36).slice(-8)
    document.getElementById("password").value = pass
}

// TOGGLE PASSWORD
function togglePassword(){
    let p = document.getElementById("password")
    p.type = p.type === "password" ? "text" : "password"
}

// VALIDASI USERNAME
document.getElementById("username").addEventListener("input",function(){
    document.getElementById("usernameError")
    .classList.toggle("hidden", this.value.length >= 3)
})

// VALIDASI NIK SUAMI & ISTRI
function validateNIK(idInput, idError) {
    document.getElementById(idInput).addEventListener("input", function(){
        document.getElementById(idError).classList.toggle("hidden", this.value.length == 16)
    });
}
validateNIK("nik_suami", "nikErrorSuami");
validateNIK("nik_istri", "nikErrorIstri");

// FORMAT HP (62)
function formatPhone(idInput) {
    document.getElementById(idInput).addEventListener("input", function(){
        if(this.value.startsWith("08")){
            this.value = "628" + this.value.slice(2)
        }
    });
}
formatPhone("phone_suami");
formatPhone("phone_istri");

// LOGIKA TOGGLE DOKUMEN TAMBAHAN
function toggleSuamiDoc() {
    let status = document.getElementById('status_suami').value;
    let box = document.getElementById('box_doc_suami');
    let input = document.getElementById('doc_tambahan_suami');
    
    if(status === 'Perjaka') {
        box.classList.add('hidden');
        input.required = false;
    } else {
        box.classList.remove('hidden');
        input.required = true;
    }
}

function toggleIstriDoc() {
    let status = document.getElementById('status_istri').value;
    let box = document.getElementById('box_doc_istri');
    let input = document.getElementById('doc_tambahan_istri');

    if(status === 'Gadis') {
        box.classList.add('hidden');
        input.required = false;
    } else {
        box.classList.remove('hidden');
        input.required = true;
    }
}

// INISIALISASI SAAT HALAMAN DIBUKA (Agar box muncul jika old status adalah Duda/Janda)
document.addEventListener("DOMContentLoaded", function() {
    toggleSuamiDoc();
    toggleIstriDoc();
});

// UPLOAD PREVIEW
document.querySelectorAll(".upload-box").forEach(box=>{
    let input = document.getElementById(box.dataset.input)
    let preview = document.getElementById("preview_"+box.dataset.input)

    box.onclick = ()=> input.click()

    input.onchange = ()=>{
        let file = input.files[0]
        if (file) {
            let reader = new FileReader()
            reader.onload = e=>{
                preview.src = e.target.result
                preview.classList.remove("hidden")
            }
            reader.readAsDataURL(file)
        }
    }
})

</script>

@endsection