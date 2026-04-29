@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-black mb-6">Edit Pengumuman</h1>

    <div class="bg-white p-8 rounded-[2rem] border shadow-sm">
        {{-- Pastikan route ini sesuai dengan nama di web.php kamu --}}
        <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-bold mb-2 text-sm uppercase text-gray-400">Judul Pengumuman</label>
                <input type="text" name="judul" value="{{ $pengumuman->judul }}" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2 text-sm uppercase text-gray-400">Isi Pesan</label>
                <textarea name="isi" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none" rows="5" required>{{ $pengumuman->isi }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block font-bold mb-2 text-sm uppercase text-gray-400">Target Audience</label>
                <select name="target" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="catin" {{ $pengumuman->target == 'catin' ? 'selected' : '' }}>Catin</option>
                    <option value="pendamping" {{ $pengumuman->target == 'pendamping' ? 'selected' : '' }}>Pendamping</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-black uppercase text-xs tracking-widest transition-all">
                    Update Data
                </button>
                <a href="{{ route('admin.pengumuman.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-8 py-3 rounded-xl font-black uppercase text-xs tracking-widest transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection