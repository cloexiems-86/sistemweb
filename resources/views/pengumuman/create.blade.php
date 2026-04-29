@extends('layouts.app')

@section('title', 'Tambah Pengumuman Baru')

@section('content')
{{-- Load Material Symbols untuk Icon --}}
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<div class="p-4 lg:p-8">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-800 dark:text-white">Kirim Pengumuman</h1>
            <p class="text-gray-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Buat pesan baru untuk diinformasikan ke user
            </p>
        </div>

        {{-- BREADCRUMB --}}
        <nav class="flex px-5 py-2 text-gray-700 bg-white border rounded-2xl shadow-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-green-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">dashboard</span> Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-gray-300">chevron_right</span>
                        <a href="{{ route('admin.pengumuman.index') }}" class="ml-1 text-sm font-bold text-gray-400 hover:text-green-600">Pengumuman</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-gray-300">chevron_right</span>
                        <span class="ml-1 text-sm font-black text-gray-800">Buat Baru</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    {{-- FORM CONTAINER --}}
    <div class="max-w-4xl mx-auto bg-white rounded-[2rem] border overflow-hidden shadow-2xl shadow-gray-200/50">
        {{-- CARD HEADER --}}
        <div class="p-8 border-b bg-gray-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center shadow-lg shadow-green-200">
                    <span class="material-symbols-outlined">post_add</span>
                </div>
                <div>
                    <p class="font-black uppercase text-xs tracking-[0.2em] text-gray-400">Formulir</p>
                    <h2 class="font-black text-xl text-gray-800">Detail Pengumuman</h2>
                </div>
            </div>
        </div>

        {{-- FORM BODY --}}
        <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="p-8 space-y-8">
            @csrf
            
            {{-- INPUT JUDUL --}}
            <div class="space-y-2">
                <label class="text-xs font-black uppercase tracking-widest text-gray-400 ml-1">Judul Pengumuman</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-600 transition-colors">
                        <span class="material-symbols-outlined">title</span>
                    </div>
                    <input type="text" name="judul" required placeholder="Contoh: Jadwal Bimbingan Terbaru"
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-green-100 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-800">
                </div>
            </div>

            {{-- INPUT TARGET --}}
            <div class="space-y-2">
                <label class="text-xs font-black uppercase tracking-widest text-gray-400 ml-1">Target Penerima</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-600 transition-colors">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <select name="target" required
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-green-100 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-800 appearance-none">
                        {{-- <option value="semua">Semua User</option> --}}
                        <option value="catin">Khusus Calon Pengantin (Catin)</option>
                        <option value="pendamping">Khusus Pendamping</option>
                    </select>
                </div>
            </div>

            {{-- INPUT ISI --}}
            <div class="space-y-2">
                <label class="text-xs font-black uppercase tracking-widest text-gray-400 ml-1">Isi Pengumuman</label>
                <div class="relative group">
                    <div class="absolute top-4 left-0 pl-4 pointer-events-none text-gray-400 group-focus-within:text-green-600 transition-colors">
                        <span class="material-symbols-outlined">notes</span>
                    </div>
                    <textarea name="isi" rows="6" required placeholder="Tuliskan pesan lengkap di sini..."
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-green-100 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-800"></textarea>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <button type="submit" 
                    class="flex-1 inline-flex items-center justify-center gap-2 px-8 py-4 bg-gray-900 text-white font-black uppercase tracking-[0.2em] text-xs rounded-2xl hover:bg-green-600 hover:-translate-y-1 hover:shadow-xl hover:shadow-green-200 transition-all duration-300">
                    <span class="material-symbols-outlined">send</span>
                    Kirim Pengumuman
                </button>
                <a href="{{ route('admin.pengumuman.index') }}" 
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-gray-400 font-black uppercase tracking-[0.2em] text-xs border rounded-2xl hover:bg-gray-50 transition-all duration-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
    
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
    }
</style>
@endsection