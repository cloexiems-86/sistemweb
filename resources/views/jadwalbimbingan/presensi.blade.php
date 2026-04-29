@extends('layouts.app')

@section('content')
<div class="p-8 antialiased">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-blue-600 w-2 h-8 rounded-full"></span>
                <h1 class="text-3xl font-black text-slate-800">Detail Presensi</h1>
            </div>
            <p class="text-slate-500 font-medium">
                Sesi: <span class="text-blue-600">{{ $jadwal->nama_sesi }}</span> • 
                <span class="bg-slate-100 px-2 py-1 rounded text-xs">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}</span>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.jadwal.index') }}" class="flex items-center gap-2 bg-white border border-slate-200 px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Peserta</p>
            <p class="text-2xl font-black text-slate-800">{{ $presensi->count() }} <span class="text-sm font-medium text-slate-400">Orang</span></p>
        </div>
        <div class="bg-green-50 p-6 rounded-[1.5rem] border border-green-100">
            <p class="text-green-600 text-xs font-bold uppercase tracking-wider mb-1">Hadir Tepat Waktu</p>
            <p class="text-2xl font-black text-green-700">{{ $presensi->where('status', 'hadir')->count() }}</p>
        </div>
        <div class="bg-blue-50 p-6 rounded-[1.5rem] border border-blue-100">
            <p class="text-blue-600 text-xs font-bold uppercase tracking-wider mb-1">Metode Absensi</p>
            <p class="text-sm font-bold text-blue-700">QR-Code / Manual System</p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-black uppercase text-slate-400 tracking-widest">Informasi Catin</th>
                        <th class="px-8 py-5 text-xs font-black uppercase text-slate-400 tracking-widest text-center">Waktu Hadir</th>
                        <th class="px-8 py-5 text-xs font-black uppercase text-slate-400 tracking-widest text-right">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($presensi as $row)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-black text-slate-500 text-xs">
                                    {{ strtoupper(substr($row->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $row->user->name }}</p>
                                    <p class="text-xs text-slate-400">ID Peserta: #{{ str_pad($row->user->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center text-slate-600 font-medium">
                            <div class="inline-flex items-center gap-2 bg-slate-100 px-3 py-1 rounded-lg text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $row->created_at->format('H:i') }} WIB
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            @php
                                $statusClasses = [
                                    'hadir' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'izin'  => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'alfa'  => 'bg-rose-100 text-rose-700 border-rose-200',
                                ][$row->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="inline-block px-4 py-1.5 rounded-xl border text-[10px] font-black uppercase tracking-widest {{ $statusClasses }}">
                                {{ $row->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-slate-50 p-4 rounded-full mb-4 text-slate-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Data Masih Kosong</p>
                                <p class="text-slate-400 text-sm italic">Belum ada Catin yang melakukan absensi pada sesi ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection