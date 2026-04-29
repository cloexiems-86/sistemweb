@extends('layouts.app')

@section('title','Input Hasil Ujian')
@section('page-title','Tambah Data Ujian')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex justify-center py-10 px-4">
<div class="w-full max-w-3xl">

    {{-- HEADER STYLE --}}
    <div class="bg-[#4ce619] p-6 text-white rounded-2xl mb-6 shadow-lg flex items-center gap-4">
        <a href="{{ route('admin.ujian.index') }}" 
           class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-xl hover:bg-white/40 transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tight">Input Hasil Ujian</h1>
            <p class="text-sm opacity-90">Masukkan nilai evaluasi calon pengantin</p>
        </div>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 relative overflow-hidden">

        <div class="absolute top-0 right-0 w-32 h-32 bg-[#4ce619]/5 rounded-bl-full -mr-16 -mt-16"></div>

        {{-- ERROR --}}
        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-600 p-4 rounded-xl text-sm mb-6">
            @foreach ($errors->all() as $error)
                <p>⚠️ {{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('admin.ujian.store') }}" method="POST">
            @csrf

            <div class="space-y-6">

                {{-- PILIH CATIN --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Pilih Catin</label>
                    <select name="catin_id"
                        class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#4ce619]/20 font-bold">
                        @foreach($catins as $catin)
                            <option value="{{ $catin->id }}">
                                {{ $catin->nama_suami }} & {{ $catin->nama_istri }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- INPUT NILAI --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Jawaban Benar</label>
                        <input type="number" name="jawaban_benar" id="benar"
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Jawaban Salah</label>
                        <input type="number" name="jawaban_salah" id="salah"
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold">
                    </div>

                </div>

                {{-- SKOR --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Skor Akhir (0-100)</label>
                    <input type="number" name="skor" id="skor"
                        class="w-full p-4 bg-gray-800 text-[#4ce619] rounded-2xl font-black text-2xl text-center">

                    {{-- PROGRESS --}}
                    <div class="mt-3">
                        <div class="flex justify-between text-[10px] font-bold mb-1">
                            <span class="text-[#4ce619]">Progress Nilai</span>
                            <span id="scorePercent">0%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div id="scoreBar"
                                class="h-full bg-[#4ce619] transition-all duration-700"
                                style="width: 0%">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STATUS OTOMATIS --}}
                <div class="bg-gray-50 p-4 rounded-2xl border">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Status Kelulusan</label>

                    <div id="statusBadge"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black uppercase">
                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                        MENUNGGU NILAI
                    </div>

                    <input type="hidden" name="status_kelulusan" id="status_input">
                </div>

                {{-- BUTTON --}}
                <div class="pt-6 flex gap-3">
                    <a href="{{ route('admin.ujian.index') }}"
                        class="w-1/2 text-center py-3 rounded-2xl text-gray-400 font-bold">
                        BATAL
                    </a>

                    <button type="submit"
                        class="w-1/2 bg-[#4ce619] text-white font-black py-3 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition">
                        SIMPAN HASIL
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
</div>

{{-- SCRIPT --}}
<script>

// AUTO HITUNG SKOR
let benar = document.getElementById('benar')
let salah = document.getElementById('salah')
let skor = document.getElementById('skor')

function hitungSkor(){
    let b = parseInt(benar.value) || 0
    let s = parseInt(salah.value) || 0
    let total = b + s

    if(total > 0){
        let nilai = Math.round((b / total) * 100)
        skor.value = nilai
        updateUI(nilai)
    }
}

benar.addEventListener('input', hitungSkor)
salah.addEventListener('input', hitungSkor)


// UPDATE UI
function updateUI(val){
    document.getElementById('scoreBar').style.width = val + '%'
    document.getElementById('scorePercent').innerText = val + '%'

    let badge = document.getElementById('statusBadge')
    let input = document.getElementById('status_input')

    if(val >= 75){
        badge.className = "inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black bg-green-50 text-green-600"
        badge.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full"></span> LULUS'
        input.value = "lulus"
    } else if(val > 0){
        badge.className = "inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black bg-red-50 text-red-600"
        badge.innerHTML = '<span class="w-2 h-2 bg-red-500 rounded-full"></span> REMEDIAL'
        input.value = "tidak_lulus"
    } else {
        badge.className = "inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black bg-gray-100 text-gray-400"
        badge.innerHTML = '<span class="w-2 h-2 bg-gray-400 rounded-full"></span> MENUNGGU'
        input.value = ""
    }
}

// MANUAL INPUT SKOR
skor.addEventListener('input', function(){
    updateUI(this.value)
})

</script>

@endsection