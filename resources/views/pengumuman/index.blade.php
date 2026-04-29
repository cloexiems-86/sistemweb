@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')

@section('content')
{{-- Load Material Symbols & SweetAlert2 --}}
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="p-4 lg:p-8">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-800 dark:text-white">Data Pengumuman</h1>
            <p class="text-gray-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                Kelola pesan dan informasi untuk Catin & Pendamping
            </p>
        </div>

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
                        <span class="ml-1 text-sm font-black text-gray-800">Pengumuman</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="bg-white rounded-[2rem] border overflow-hidden shadow-2xl shadow-gray-200/50">
        {{-- TABLE HEADER --}}
        <div class="p-8 border-b bg-gray-50/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                    <span class="material-symbols-outlined">campaign</span>
                </div>
                <div>
                    <p class="font-black uppercase text-xs tracking-[0.2em] text-gray-400">Pusat Informasi</p>
                    <h2 class="font-black text-xl text-gray-800">Daftar Pengumuman Aktif</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="hidden md:block px-4 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-black">
                    {{-- Tambahkan isset untuk keamanan --}}
                    TOTAL: {{ isset($pengumumans) ? $pengumumans->count() : 0 }} DATA
                </span>
                <a href="{{ route('admin.pengumuman.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-green-700 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-200 transition-all duration-300">
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    Tambah Pengumuman
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-[11px] uppercase text-gray-400 font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-5">No</th>
                        <th class="px-6 py-5">Isi Pengumuman</th>
                        <th class="px-6 py-5 text-center">Target Audience</th>
                        <th class="px-6 py-5 text-center">Tanggal Dibuat</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($pengumumans as $index => $p)
                    <tr class="group hover:bg-blue-50/30 transition-all duration-300">
                        <td class="px-8 py-6">
                            <span class="text-sm font-black text-gray-400 group-hover:text-blue-600 transition-colors">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td class="px-6 py-6">
                            <div class="flex flex-col">
                                <span class="font-black text-gray-800 text-sm uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                                    {{ $p->judul }}
                                </span>
                                <span class="text-xs text-gray-400 line-clamp-1">
                                    {{ Str::limit($p->isi, 50) }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-6 text-center">
                            @php
                                $color = match($p->target) {
                                    'catin' => 'bg-pink-100 text-pink-700',
                                    'pendamping' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-indigo-100 text-indigo-700',
                                };
                            @endphp
                            <span class="px-4 py-1.5 {{ $color }} rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ $p->target }}
                            </span>
                        </td>

                        <td class="px-6 py-6 text-center">
                            <span class="text-xs font-black text-gray-500 uppercase tracking-tighter">
                                <span class="material-symbols-outlined text-[14px] align-middle mr-1">history</span>
                                {{ $p->created_at->format('d/m/Y') }}
                            </span>
                        </td>

                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.pengumuman.edit', $p->id) }}" 
                                   class="p-2 bg-gray-100 text-gray-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </a>
                                
                                <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" id="delete-form-{{ $p->id }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $p->id }}')"
                                            class="p-2 bg-gray-100 text-gray-400 rounded-xl hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <span class="material-symbols-outlined text-8xl text-gray-400">notifications_off</span>
                                <p class="font-black uppercase text-sm mt-4 tracking-[0.3em] text-gray-500">Belum ada pengumuman</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2500,
            customClass: { popup: 'rounded-[2rem]' }
        });
    @endif

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Pengumuman?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-black uppercase tracking-widest text-xs px-6 py-3',
                cancelButton: 'rounded-xl font-black uppercase tracking-widest text-xs px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
</style>
@endsection