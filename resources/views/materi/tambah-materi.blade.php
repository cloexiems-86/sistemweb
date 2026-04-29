@extends('layouts.app')

@section('title','Tambah Materi')

@section('page-title','Tambah Materi Edukasi')

@section('content')

<div class="max-w-3xl mx-auto">

<div class="mb-8">

<h1 class="text-3xl font-black tracking-tight">
Tambah Materi Baru
</h1>

<p class="text-gray-500">
Lengkapi data materi bimbingan perkawinan.
</p>

</div>


<div class="bg-white dark:bg-dark-surface border rounded-2xl shadow-sm">

<form action="{{ route('admin.materi.store') }}"
method="POST"
enctype="multipart/form-data"
class="p-6 space-y-6">

@csrf


{{-- JUDUL --}}
<div class="flex flex-col gap-2">

<label class="text-sm font-bold">
Judul Materi
</label>

<input
type="text"
name="judul"
required
placeholder="Contoh: Persiapan Mental Pernikahan"
class="rounded-lg border-gray-300 focus:ring-primary focus:border-primary text-sm"
/>

</div>


{{-- DESKRIPSI --}}
<div class="flex flex-col gap-2">

<label class="text-sm font-bold">
Deskripsi Materi
</label>

<textarea
name="deskripsi"
rows="4"
required
placeholder="Masukkan deskripsi materi..."
class="rounded-lg border-gray-300 focus:ring-primary focus:border-primary text-sm"
></textarea>

</div>


{{-- FILE --}}
<div class="flex flex-col gap-2">

<label class="text-sm font-bold">
File Materi (PDF / Video)
</label>

<input
type="file"
name="file"
accept=".pdf,video/*"
class="rounded-lg border-gray-300 text-sm"
/>

<p class="text-xs text-gray-500">
Format: PDF / MP4 (Opsional)
</p>

</div>


{{-- STATUS --}}
<div class="flex flex-col gap-2">

<label class="text-sm font-bold">
Status Materi
</label>

<select
name="status"
required
class="rounded-lg border-gray-300 focus:ring-primary focus:border-primary text-sm"
>

<option value="aktif">Aktif</option>
<option value="nonaktif">Nonaktif</option>

</select>

</div>


{{-- BUTTON --}}
<div class="flex justify-end gap-3 pt-4">

<a href="{{ route('admin.materi.index') }}"
class="px-6 py-2 border rounded-lg text-sm font-bold hover:bg-gray-50">

Batal

</a>

<button
type="submit"
class="px-6 py-2 bg-primary text-[#131811] rounded-lg text-sm font-bold shadow hover:scale-105 transition"
>

Simpan Materi

</button>

</div>

</form>

</div>

</div>

@endsection