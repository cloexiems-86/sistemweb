{{-- @extends('layouts.app')

@section('title','Dashboard - KUA Mojo')
@section('page-title','Pusat Data Statistik')

@section('content')

<!-- HEADER INFO -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-extrabold text-[#131811]">Selamat Datang, Admin 👋</h1>
        <p class="text-sm text-[#6c8863] font-medium">Sistem Informasi Digitalisasi Bimbingan Perkawinan KUA Mojo</p>
    </div>
    <div class="hidden md:block text-right">
        <p class="text-xs font-bold text-primary uppercase tracking-widest">Status Server</p>
        <p class="text-sm font-semibold text-green-600 flex items-center justify-end gap-1">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Terhubung ke Database
        </p>
    </div>
</div>

<!-- STATS CARDS (Data Riil dari Variabel PHP) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    <!-- Bimwin -->
    <div class="bg-white border-b-4 border-primary p-6 rounded-2xl shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jadwal Bimwin</p>
                <h2 class="text-3xl font-black text-[#131811] mt-1">{{ $totalBimwin }}</h2>
            </div>
            <div class="p-2 bg-emerald-50 text-primary rounded-lg">
                <span class="material-symbols-outlined">event_note</span>
            </div>
        </div>
        <p class="text-[10px] mt-4 font-bold text-primary bg-emerald-50 inline-block px-2 py-1 rounded">AKTIF MINGGU INI</p>
    </div>

    <!-- Video -->
    <div class="bg-white border-b-4 border-blue-600 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Materi Edukasi</p>
                <h2 class="text-3xl font-black text-[#131811] mt-1">{{ $totalVideo }}</h2>
            </div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                <span class="material-symbols-outlined">play_circle</span>
            </div>
        </div>
        <p class="text-[10px] mt-4 font-bold text-blue-600 bg-blue-50 inline-block px-2 py-1 rounded">VIDEO TUTORIAL</p>
    </div>

    <!-- Catin -->
    <div class="bg-white border-b-4 border-orange-500 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Catin</p>
                <h2 class="text-3xl font-black text-[#131811] mt-1">{{ number_format($totalCatin) }}</h2>
            </div>
            <div class="p-2 bg-orange-50 text-orange-600 rounded-lg">
                <span class="material-symbols-outlined">groups</span>
            </div>
        </div>
        <p class="text-[10px] mt-4 font-bold text-orange-600 bg-orange-50 inline-block px-2 py-1 rounded">TERDAFTAR DI SISTEM</p>
    </div>

    <!-- Sertifikat -->
    <div class="bg-white border-b-4 border-yellow-500 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sertifikat Terbit</p>
                <h2 class="text-3xl font-black text-[#131811] mt-1">{{ number_format($totalSertifikat) }}</h2>
            </div>
            <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg">
                <span class="material-symbols-outlined">verified</span>
            </div>
        </div>
        <p class="text-[10px] mt-4 font-bold text-yellow-600 bg-yellow-50 inline-block px-2 py-1 rounded">SIAP UNDUH</p>
    </div>

</div>


<!-- CHART DESA (Bar Chart) -->
<div class="bg-white rounded-2xl mt-8 shadow-sm border border-[#dee5dc]">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
        <div>
            <h3 class="font-black text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">map</span> 
                SEBARAN CATIN PER DESA
            </h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Kecamatan Mojo, Kabupaten Kediri</p>
        </div>
    </div>

    <div class="p-6">
        <div class="relative w-full overflow-x-auto">
            <div style="min-width:1200px; height:400px;">
                <canvas id="desaChart"></canvas>
            </div>
        </div>
    </div>
</div>


<!-- CHART BAWAH (Trend & Global) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">

    <!-- TREND LINE -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#dee5dc]">
        <div class="flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-primary">trending_up</span>
            <h3 class="font-black text-[#131811]">TREND REGISTRASI BULANAN</h3>
        </div>
        <div class="h-[300px]">
            <canvas id="bulanChart"></canvas>
        </div>
    </div>

    <!-- GLOBAL DOUGHNUT -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#dee5dc]">
        <div class="flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-primary">pie_chart</span>
            <h3 class="font-black text-[#131811]">RASIO PENYELESAIAN BIMWIN</h3>
        </div>
        <div class="h-[300px] flex justify-center">
            <canvas id="globalChart"></canvas>
        </div>
    </div>

</div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // Warna Resmi Kemenag/KUA
    const colorPrimary = '#0e6731'; // Hijau Tua
    const colorSecondary = '#d4af37'; // Emas/Gold
    const colorLight = '#eefdee';

    // 1. DESA CHART (DATA RIIL DARI PHP)
    const ctxDesa = document.getElementById('desaChart').getContext('2d');
    new Chart(ctxDesa, {
        type: 'bar',
        data: {
            labels: {!! json_encode($desaLabels) !!},
            datasets: [
                {
                    label: 'Catin Terdaftar',
                    data: {!! json_encode($desaCatinData) !!},
                    backgroundColor: colorPrimary,
                    borderRadius: 8,
                    barThickness: 25,
                },
                {
                    label: 'Sertifikat Selesai',
                    data: {!! json_encode($desaSertifikatData) !!},
                    backgroundColor: colorSecondary,
                    borderRadius: 8,
                    barThickness: 25,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', align: 'end', labels: { usePointStyle: true, font: { weight: 'bold' } } }
            },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. BULAN CHART
    const ctxBulan = document.getElementById('bulanChart').getContext('2d');
    new Chart(ctxBulan, {
        type: 'line',
        data: {
            labels: {!! json_encode($bulanLabels) !!},
            datasets: [{
                label: 'Pendaftaran',
                data: {!! json_encode($bulanCatinData) !!},
                borderColor: colorPrimary,
                backgroundColor: 'rgba(14, 103, 49, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: colorPrimary,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // 3. GLOBAL CHART
    const ctxGlobal = document.getElementById('globalChart').getContext('2d');
    new Chart(ctxGlobal, {
        type: 'doughnut',
        data: {
            labels: ['Selesai Bimwin', 'Belum Selesai'],
            datasets: [{
                data: [{{ $totalSertifikat }}, {{ $totalCatin - $totalSertifikat }}],
                backgroundColor: [colorPrimary, '#e2e8f0'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20, font: { weight: 'bold' } } }
            }
        }
    });

});
</script>
@endpush --}}




@extends('layouts.app')

@section('title','Dashboard - KUA Mojo')
@section('page-title','Pusat Data Statistik')

@section('content')

<!-- HEADER INFO -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-extrabold text-[#131811]">Selamat Datang, Admin 👋</h1>
        <p class="text-sm text-[#6c8863] font-medium">Sistem Informasi Digitalisasi Bimbingan Perkawinan KUA Mojo</p>
    </div>
    <div class="hidden md:block text-right">
        <p class="text-xs font-bold text-primary uppercase tracking-widest">Status Server</p>
        <p class="text-sm font-semibold text-green-600 flex items-center justify-end gap-1">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Terhubung ke Database
        </p>
    </div>
</div>

<!-- STATS CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    <!-- Bimwin -->
    <div class="bg-white border-b-4 border-primary p-6 rounded-2xl shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jadwal Bimwin</p>
                <h2 class="text-3xl font-black text-[#131811] mt-1">{{ $totalBimwin ?? 0 }}</h2>
            </div>
            <div class="p-2 bg-emerald-50 text-primary rounded-lg">
                <span class="material-symbols-outlined">event_note</span>
            </div>
        </div>
        <p class="text-[10px] mt-4 font-bold text-primary bg-emerald-50 inline-block px-2 py-1 rounded">AKTIF MINGGU INI</p>
    </div>

    <!-- Video -->
    <div class="bg-white border-b-4 border-blue-600 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Materi Edukasi</p>
                <h2 class="text-3xl font-black text-[#131811] mt-1">{{ $totalVideo ?? 0 }}</h2>
            </div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                <span class="material-symbols-outlined">play_circle</span>
            </div>
        </div>
        <p class="text-[10px] mt-4 font-bold text-blue-600 bg-blue-50 inline-block px-2 py-1 rounded">VIDEO TUTORIAL</p>
    </div>

    <!-- Catin -->
    <div class="bg-white border-b-4 border-orange-500 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Catin</p>
                <h2 class="text-3xl font-black text-[#131811] mt-1">{{ number_format($totalCatin ?? 0) }}</h2>
            </div>
            <div class="p-2 bg-orange-50 text-orange-600 rounded-lg">
                <span class="material-symbols-outlined">groups</span>
            </div>
        </div>
        <p class="text-[10px] mt-4 font-bold text-orange-600 bg-orange-50 inline-block px-2 py-1 rounded">TERDAFTAR DI SISTEM</p>
    </div>

    <!-- Sertifikat -->
    <div class="bg-white border-b-4 border-yellow-500 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sertifikat Terbit</p>
                <h2 class="text-3xl font-black text-[#131811] mt-1">{{ number_format($totalSertifikat ?? 0) }}</h2>
            </div>
            <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg">
                <span class="material-symbols-outlined">verified</span>
            </div>
        </div>
        <p class="text-[10px] mt-4 font-bold text-yellow-600 bg-yellow-50 inline-block px-2 py-1 rounded">SIAP UNDUH</p>
    </div>

</div>

<!-- CHART DESA -->
<div class="bg-white rounded-2xl mt-8 shadow-sm border border-[#dee5dc]">
    <div class="p-6 border-b flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
        <div>
            <h3 class="font-black text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">map</span> 
                SEBARAN CATIN PER DESA
            </h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Kecamatan Mojo, Kabupaten Kediri</p>
        </div>
    </div>

    <div class="p-6">
        <div class="relative w-full overflow-x-auto">
            <div style="min-width:1200px; height:400px;">
                <canvas id="desaChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- CHART TREND -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#dee5dc]">
        <div class="flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-primary">trending_up</span>
            <h3 class="font-black text-[#131811]">TREND REGISTRASI BULANAN</h3>
        </div>
        <div class="h-[300px]">
            <canvas id="bulanChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#dee5dc]">
        <div class="flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-primary">pie_chart</span>
            <h3 class="font-black text-[#131811]">RASIO PENYELESAIAN BIMWIN</h3>
        </div>
        <div class="h-[300px] flex justify-center">
            <canvas id="globalChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // Warna
    const colorPrimary = '#0e6731';
    const colorSecondary = '#d4af37';

    // 1. DESA CHART (Check if variable exists)
    const ctxDesa = document.getElementById('desaChart').getContext('2d');
    new Chart(ctxDesa, {
        type: 'bar',
        data: {
            labels: {!! json_encode($desaLabels ?? []) !!},
            datasets: [
                {
                    label: 'Catin Terdaftar',
                    data: {!! json_encode($desaCatinData ?? []) !!},
                    backgroundColor: colorPrimary,
                    borderRadius: 8,
                    barThickness: 25,
                },
                {
                    label: 'Sertifikat Selesai',
                    data: {!! json_encode($desaSertifikatData ?? []) !!},
                    backgroundColor: colorSecondary,
                    borderRadius: 8,
                    barThickness: 25,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });

    // 2. BULAN CHART
    const ctxBulan = document.getElementById('bulanChart').getContext('2d');
    new Chart(ctxBulan, {
        type: 'line',
        data: {
            labels: {!! json_encode($bulanLabels ?? ['Jan','Feb','Mar','Apr','Mei','Jun']) !!},
            datasets: [{
                label: 'Pendaftaran',
                data: {!! json_encode($bulanCatinData ?? []) !!},
                borderColor: colorPrimary,
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // 3. GLOBAL CHART
    const ctxGlobal = document.getElementById('globalChart').getContext('2d');
    new Chart(ctxGlobal, {
        type: 'doughnut',
        data: {
            labels: ['Selesai Bimwin', 'Belum Selesai'],
            datasets: [{
                data: [{{ $totalSertifikat ?? 0 }}, {{ ($totalCatin ?? 0) - ($totalSertifikat ?? 0) }}],
                backgroundColor: [colorPrimary, '#e2e8f0'],
            }]
        },
        options: { cutout: '70%', plugins: { legend: { position: 'bottom' } } }
    });
});
</script>
@endpush