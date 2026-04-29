@extends('layouts.app')

@section('title','Edit Hasil Ujian')
@section('page-title','Manajemen Hasil Ujian')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


{{-- SUCCESS ALERT --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    })
</script>
@endif

<div class="flex justify-center py-10 px-4">
<div class="w-full max-w-3xl">

    {{-- HEADER STYLE CATIN --}}
    <div class="bg-[#4ce619] p-6 text-white rounded-2xl mb-6 shadow-lg flex items-center gap-4">
        <a href="{{ route('admin.ujian.index') }}" 
           class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-xl hover:bg-white/40 transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tight">Edit Hasil Ujian</h1>
            <p class="text-sm opacity-90">Perbarui nilai & status kelulusan peserta</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 relative overflow-hidden">

        <div class="absolute top-0 right-0 w-32 h-32 bg-[#4ce619]/5 rounded-bl-full -mr-16 -mt-16"></div>

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-600 p-4 rounded-xl text-sm mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.ujian.update', $ujian->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                {{-- INFO CATIN --}}
                <div class="p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200 flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ $ujian->catin->nama_suami }}&background=4ce619&color=fff"
                         class="w-12 h-12 rounded-xl">

                    <div>
                        <p class="font-black text-gray-800 uppercase">
                            {{ $ujian->catin->nama_suami }}
                        </p>
                        <p class="text-[10px] text-gray-500">
                            Pendamping: {{ $ujian->catin->pendamping->nama ?? 'Tidak ada' }}
                        </p>
                    </div>
                </div>

                {{-- INPUT NILAI --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Jawaban Benar</label>
                        <input type="number" name="jawaban_benar" value="{{ $ujian->jawaban_benar }}" 
                            class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#4ce619]/20 font-bold">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Jawaban Salah</label>
                        <input type="number" name="jawaban_salah" value="{{ $ujian->jawaban_salah }}" 
                            class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#4ce619]/20 font-bold">
                    </div>
                </div>

                {{-- SKOR --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Skor Akhir</label>
                    <input type="number" name="skor" id="skor" value="{{ $ujian->skor }}" 
                        class="w-full p-4 bg-gray-800 text-[#4ce619] rounded-2xl font-black text-2xl text-center">

                    {{-- PROGRESS --}}
                    <div class="mt-3">
                        <div class="flex justify-between text-[10px] font-bold mb-1">
                            <span class="text-[#4ce619]">Progress Nilai</span>
                            <span id="scorePercent">{{ $ujian->skor }}%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div id="scoreBar"
                                class="h-full bg-[#4ce619] transition-all duration-700"
                                style="width: {{ $ujian->skor }}%">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Status Kelulusan</label>

                    {{-- SELECT (tetap ada untuk backend) --}}
                    <select name="status_kelulusan" id="status_select"
                        class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl font-bold">
                        <option value="lulus" {{ $ujian->status_kelulusan == 'lulus' ? 'selected' : '' }}>LULUS</option>
                        <option value="tidak_lulus" {{ $ujian->status_kelulusan == 'tidak_lulus' ? 'selected' : '' }}>REMEDIAL</option>
                    </select>

                    {{-- TOGGLE BUTTON --}}
                    <div class="flex gap-2 mt-3">
                        <button type="button" onclick="setStatus('lulus')" id="btn-lulus"
                            class="px-4 py-2 rounded-xl text-xs font-black uppercase transition-all">
                            Lulus
                        </button>

                        <button type="button" onclick="setStatus('tidak_lulus')" id="btn-remedial"
                            class="px-4 py-2 rounded-xl text-xs font-black uppercase transition-all">
                            Remedial
                        </button>
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="pt-4 flex gap-3">
                    <a href="{{ route('admin.ujian.index') }}"
                        class="w-1/2 text-center py-3 rounded-2xl text-gray-400 font-bold">
                        BATAL
                    </a>

                    <button type="submit"
                        class="w-1/2 bg-[#4ce619] text-white font-black py-3 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition">
                        UPDATE DATA
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
let benar = document.querySelector('input[name="jawaban_benar"]')
let salah = document.querySelector('input[name="jawaban_salah"]')
let skor = document.getElementById('skor')

function hitungSkor(){
    let b = parseInt(benar.value) || 0
    let s = parseInt(salah.value) || 0
    let total = b + s

    if(total > 0){
        let nilai = Math.round((b / total) * 100)
        skor.value = nilai
        updateProgress(nilai)
    }
}

benar.addEventListener('input', hitungSkor)
salah.addEventListener('input', hitungSkor)


// UPDATE PROGRESS
function updateProgress(val){
    document.getElementById('scoreBar').style.width = val + '%'
    document.getElementById('scorePercent').innerText = val + '%'
}

skor.addEventListener('input', function(){
    updateProgress(this.value)
})


// STATUS TOGGLE
function setStatus(val){
    document.getElementById('status_select').value = val

    let btnLulus = document.getElementById('btn-lulus')
    let btnRemedial = document.getElementById('btn-remedial')

    if(val === 'lulus'){
        btnLulus.className = "px-4 py-2 rounded-xl text-xs font-black uppercase bg-green-500 text-white"
        btnRemedial.className = "px-4 py-2 rounded-xl text-xs font-black uppercase bg-gray-100 text-gray-400"
    } else {
        btnRemedial.className = "px-4 py-2 rounded-xl text-xs font-black uppercase bg-red-500 text-white"
        btnLulus.className = "px-4 py-2 rounded-xl text-xs font-black uppercase bg-gray-100 text-gray-400"
    }
}

// INIT STATUS BUTTON
setStatus("{{ $ujian->status_kelulusan }}")

</script>

@endsection