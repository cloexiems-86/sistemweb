@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-black uppercase">Monitoring Nilai Kuis</h1>
        <a href="{{ route('admin.materi.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-xl text-xs uppercase font-bold">Kembali</a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400">
                <tr>
                    <th class="px-6 py-4">Nama Catin</th>
                    <th class="px-6 py-4 text-center">Nilai</th>
                    <th class="px-6 py-4">Waktu Pengerjaan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($kuis->logs as $log)
                <tr>
                    <td class="px-6 py-4 font-bold uppercase text-sm">{{ $log->user->name }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full font-black {{ $log->nilai >= 70 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $log->nilai }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500 italic">{{ $log->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection