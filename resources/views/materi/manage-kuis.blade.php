@extends('layouts.app')

@section('title', 'Kelola Kuis')

@section('content')

{{-- Tambahkan SweetAlert2 dari CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex flex-col gap-8 p-6 md:p-8">
    {{-- Header --}}
{{-- Perbaikan: Menambahkan container mx-auto dan padding yang lebih lega --}}
<div class="container mx-auto max-w-7xl flex flex-col gap-8 p-6 md:p-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-black uppercase text-gray-800 dark:text-white tracking-tight">Kelola Kuis</h1>
            <p class="text-base text-gray-500">Materi: <span class="font-bold text-[#4ce619]">{{ $materi->judul }}</span></p>
        </div>
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <a href="{{ route('admin.kuis.logs', $materi->id) }}" class="px-5 py-2.5 bg-purple-500 text-white rounded-xl text-xs font-bold uppercase hover:bg-purple-600 transition-all flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-sm">monitoring</span>
                Lihat Hasil Kuis
            </a>

            <a href="{{ route('admin.materi.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-2xl font-bold text-sm hover:bg-gray-50 transition-all text-center">Kembali</a>
            
            <button onclick="openModalTambah()" class="flex-1 md:flex-none px-8 py-2.5 bg-[#4ce619] text-white rounded-2xl font-bold text-sm shadow-lg shadow-green-500/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-lg">add_circle</span> Tambah Soal
            </button>
        </div>
    </div>

{{-- Indikator Syarat Sertifikat --}}
    @php $jumlahSoal = $materi->kuis ? $materi->kuis->soals->count() : 0; @endphp
    <div class="bg-gradient-to-r from-blue-600 to-blue-400 p-[1px] rounded-[2rem] shadow-md">
        <div class="bg-white dark:bg-dark-surface p-6 rounded-[31px] flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex flex-col items-center justify-center leading-none">
                    <span class="text-2xl font-black">{{ $jumlahSoal }}</span>
                    <span class="text-[10px] uppercase font-bold">Soal</span>
                </div>
                <div>
                    <h4 class="text-lg font-black text-gray-800 dark:text-white uppercase">Status Kuis Sertifikat</h4>
                    <p class="text-sm text-gray-500">Minimal 10 soal untuk mengaktifkan fitur sertifikat otomatis.</p>
                </div>
            </div>
            @if($jumlahSoal >= 10)
                <div class="hidden md:flex items-center gap-2 px-5 py-2.5 bg-green-50 text-green-600 rounded-xl font-black text-xs uppercase border border-green-100">
                    <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span> Siap Ujian
                </div>
            @else
                <div class="hidden md:block px-5 py-2.5 bg-amber-50 text-amber-600 rounded-xl font-black text-xs uppercase border border-amber-100">
                    Kurang {{ 10 - $jumlahSoal }} Soal
                </div>
            @endif
        </div>
    </div>
{{-- Daftar Soal --}}
    <div class="bg-white dark:bg-dark-surface rounded-[2.5rem] border border-gray-100 dark:border-gray-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/80 dark:bg-gray-800/50 text-xs uppercase text-gray-400 font-black tracking-[0.15em]">
                    <tr>
                        <th class="px-8 py-6 w-20 text-center">↕</th>
                        <th class="px-8 py-6 w-20 text-center">No</th>
                        <th class="px-6 py-6">Detail Pertanyaan & Opsi</th>
                        <th class="px-6 py-6 text-center w-28">Kunci</th>
                        <th class="px-8 py-6 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800" id="sortableSoal">
                    @if($materi->kuis && $materi->kuis->soals->count() > 0)
                        @foreach($materi->kuis->soals->sortBy('urutan') as $index => $soal)
                            <tr draggable="true" data-id="{{ $soal->id }}" class="sortable-item hover:bg-gray-50/50 transition-all group cursor-move">
                                <td class="px-4 py-8 text-center opacity-20 group-hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-gray-400">drag_indicator</span>
                                </td>
                                <td class="px-8 py-8 font-black text-gray-300 text-center text-xl group-hover:text-gray-500 transition-colors">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-8">
                                    <p contenteditable="true" 
                                       onblur="updateSoalInline({{ $soal->id }}, 'pertanyaan', this.innerText)"
                                       class="font-bold text-gray-800 dark:text-white mb-4 text-base leading-relaxed outline-none focus:bg-yellow-50 focus:px-2 rounded transition-all">
                                       {{ $soal->pertanyaan }}
                                    </p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                                         @foreach(['a','b','c','d'] as $opsi)
                                            <div class="flex items-center gap-3 text-sm {{ $soal->jawaban_benar == $opsi ? 'text-green-600 font-bold' : 'text-gray-500 font-medium' }}">
                                                <span class="w-6 h-6 rounded-lg flex items-center justify-center border text-[10px] {{ $soal->jawaban_benar == $opsi ? 'bg-green-500 border-green-500 text-white' : 'border-gray-200' }}">
                                                    {{ strtoupper($opsi) }}
                                                </span>
                                                <span contenteditable="true" 
                                                      onblur="updateSoalInline({{ $soal->id }}, 'opsi_{{ $opsi }}', this.innerText)"
                                                      class="outline-none focus:text-blue-600 transition-colors">
                                                    {{ $soal->{'opsi_' . $opsi} }}
                                                </span>
                                            </div>
                                         @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-8 text-center">
                                    <button onclick="changeKey({{ $soal->id }}, '{{ $soal->jawaban_benar }}')" 
                                            class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-white flex items-center justify-center font-black mx-auto uppercase border border-gray-100 dark:border-gray-700 shadow-sm hover:bg-black hover:text-white transition-all text-lg">
                                        {{ strtoupper($soal->jawaban_benar) }}
                                    </button>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex justify-center gap-3">
                                        <button onclick="openModalEdit({{ json_encode($soal) }})" class="w-11 h-11 flex items-center justify-center text-blue-500 bg-blue-50 hover:bg-blue-500 hover:text-white rounded-xl transition-all shadow-sm">
                                            <span class="material-symbols-outlined text-xl">edit</span>
                                        </button>

                                        <button type="button" onclick="confirmDelete({{ $soal->id }})" class="w-11 h-11 flex items-center justify-center text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl transition-all">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                        <form id="delete-form-{{ $soal->id }}" action="{{ route('admin.kuis.destroySoal', $soal->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="py-32 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <span class="material-symbols-outlined text-8xl mb-4">quiz</span>
                                    <p class="text-lg font-bold uppercase tracking-widest">Belum ada soal kuis untuk materi ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH SOAL --}}
<div id="modalTambah" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-md flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 w-full h-full" onclick="closeModal()"></div>
    <form id="formTambahSoal" action="{{ route('admin.kuis.storeSoal') }}" method="POST" class="relative bg-white dark:bg-dark-surface w-full max-w-5xl mx-4 rounded-[3rem] shadow-2xl flex flex-col max-h-[85vh] overflow-hidden border border-white/20 animate-soal">
        @csrf
        <input type="hidden" name="materi_id" value="{{ $materi->id }}">
        <div class="px-10 py-7 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center shrink-0 bg-white dark:bg-dark-surface z-10">
            <div>
                <h3 class="font-black uppercase text-2xl text-gray-800 dark:text-white leading-none mb-1">Tambah Soal Baru</h3>
                <p class="text-xs text-gray-400 font-bold tracking-tight uppercase">Materi: {{ $materi->judul }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="tambahBarisSoal()" class="px-5 py-2.5 bg-blue-50 text-blue-600 rounded-2xl font-black text-[10px] uppercase flex items-center gap-2 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                    <span class="material-symbols-outlined text-lg font-bold">add_circle</span> Tambah Baris
                </button>
                <button type="button" onclick="closeModal()" class="w-12 h-12 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all group">
                    <span class="material-symbols-outlined group-hover:rotate-90 transition-transform">close</span>
                </button>
            </div>
        </div>
        <div class="px-10 py-8 overflow-y-auto grow custom-scrollbar bg-gray-50/40" id="containerSoal">
            <div class="flex flex-col gap-10">
                <div class="soal-item group relative bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm transition-all">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-[#4ce619] shadow-lg shadow-green-500/30 rounded-2xl flex items-center justify-center font-black text-white text-sm">01</div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <div class="lg:col-span-7 space-y-6">
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 block mb-3 px-1 tracking-[0.2em]">Pertanyaan Utama</label>
                                <textarea name="soal[0][pertanyaan]" rows="4" required placeholder="Apa yang dimaksud dengan..." class="w-full border border-gray-100 bg-gray-50/50 rounded-[1.5rem] p-5 text-sm focus:bg-white focus:ring-4 focus:ring-green-400/10 focus:border-[#4ce619] outline-none transition-all resize-none shadow-inner"></textarea>
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 block mb-3 px-1 tracking-[0.2em]">Jawaban Yang Benar</label>
                                <div class="relative">
                                    <select name="soal[0][jawaban_benar]" class="w-full border border-gray-100 bg-gray-50/50 rounded-2xl p-4 text-sm font-bold appearance-none focus:bg-white focus:ring-4 focus:ring-green-400/10 outline-none cursor-pointer transition-all">
                                        <option value="a">Opsi A</option><option value="b">Opsi B</option><option value="c">Opsi C</option><option value="d">Opsi D</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-5 flex flex-col gap-4">
                            <label class="text-[10px] font-black uppercase text-gray-400 block px-1 tracking-[0.2em]">Pilihan Ganda</label>
                            @foreach(['a','b','c','d'] as $o)
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-xs text-gray-300 uppercase">{{ $o }}</span>
                                <input type="text" name="soal[0][opsi_{{ $o }}]" placeholder="Ketik opsi di sini..." required class="w-full border border-gray-100 bg-gray-50/50 rounded-xl py-3.5 pl-10 pr-4 text-sm focus:bg-white focus:ring-2 focus:ring-green-400 outline-none transition-all">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-10 py-8 bg-white dark:bg-dark-surface border-t border-gray-100 dark:border-gray-800 flex justify-end items-center gap-4 shrink-0 z-10">
            <button type="button" onclick="closeModal()" class="px-8 py-3 text-xs font-black uppercase text-gray-400 hover:text-red-500 transition-colors tracking-widest">Batalkan</button>
            <button type="submit" class="bg-gray-900 text-white px-10 py-4 rounded-[1.5rem] font-black text-xs uppercase shadow-xl hover:bg-[#4ce619] hover:scale-105 active:scale-95 transition-all tracking-widest flex items-center gap-3">
                <span class="material-symbols-outlined text-sm">save</span> Simpan Semua Soal
            </button>
        </div>
    </form>
</div>

{{-- MODAL EDIT SOAL --}}
<div id="modalEdit" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-md flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 w-full h-full" onclick="closeModalEdit()"></div>
    <form id="formEditSoal" method="POST" class="relative bg-white dark:bg-dark-surface w-full max-w-3xl mx-4 rounded-[3rem] shadow-2xl flex flex-col border border-white/20 animate-soal">
        @csrf @method('PUT')
        <div class="px-10 py-7 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-white dark:bg-dark-surface rounded-t-[3rem]">
            <h3 class="font-black uppercase text-2xl text-gray-800 dark:text-white leading-none">Edit Soal</h3>
            <button type="button" onclick="closeModalEdit()" class="w-12 h-12 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center hover:bg-red-50 transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="px-10 py-8 space-y-6">
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 block mb-2 px-1">Pertanyaan</label>
                <textarea id="edit_pertanyaan" name="pertanyaan" rows="3" required class="w-full border border-gray-100 bg-gray-50 rounded-[1.5rem] p-5 text-sm focus:ring-4 focus:ring-blue-400/10 outline-none transition-all resize-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach(['a','b','c','d'] as $o)
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 block mb-1 px-1">Opsi {{ strtoupper($o) }}</label>
                    <input type="text" id="edit_opsi_{{ $o }}" name="opsi_{{ $o }}" required class="w-full border border-gray-100 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                </div>
                @endforeach
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 block mb-2 px-1">Jawaban Benar</label>
                <select id="edit_jawaban_benar" name="jawaban_benar" class="w-full border border-gray-100 bg-gray-50 rounded-xl p-3 text-sm font-bold outline-none cursor-pointer">
                    <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
                </select>
            </div>
        </div>
        <div class="px-10 py-8 bg-white border-t border-gray-100 flex justify-end gap-4 rounded-b-[3rem]">
            <button type="button" onclick="closeModalEdit()" class="text-xs font-black uppercase text-gray-400">Batal</button>
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase shadow-lg hover:bg-blue-700 transition-all">Update Soal</button>
        </div>
    </form>
</div>

<style>
    /* Styling Scrollbar & Animasi sama seperti sebelumnya */
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.02); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; border: 2px solid transparent; background-clip: content-box; }
    @keyframes slideIn {
        from { opacity: 0; transform: scale(0.95) translateY(30px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-soal { animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<script>
    let soalCount = 1;

    // --- POPUP SUCCESS HELPER ---
    function showSuccessPopup(message) {
        Swal.fire({
            title: 'BERHASIL',
            text: message,
            icon: 'success',
            confirmButtonColor: '#4ce619',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl px-10 font-black'
            }
        });
    }

    // --- DELETE POPUP (Foto image_23e71d.png) ---
    function confirmDelete(id) {
        Swal.fire({
            title: 'HAPUS PERMANEN?',
            text: "Seluruh data materi dan bank soal di dalamnya akan dihapus selamanya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'YA, HAPUS SEKARANG',
            cancelButtonText: 'BATAL',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-black',
                cancelButton: 'rounded-xl font-black'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // --- TAMBAH SOAL ACTION ---
    document.getElementById('formTambahSoal').onsubmit = function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan soal baru?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4ce619',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    }

    // --- EDIT SOAL ACTION ---
    document.getElementById('formEditSoal').onsubmit = function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan perubahan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Ya, Update',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    }

    // --- MODAL CONTROL ---
    function openModalTambah() {
        document.getElementById('modalTambah').classList.remove('hidden');
        document.getElementById('modalTambah').classList.add('flex');
    }
    function closeModal() {
        document.getElementById('modalTambah').classList.add('hidden');
    }
    function openModalEdit(soal) {
        const form = document.getElementById('formEditSoal');
        // PASTIKAN ROUTE INI BENAR SESUAI web.php
        form.action = `/admin/kuis/update-soal/${soal.id}`;
        
        document.getElementById('edit_pertanyaan').value = soal.pertanyaan;
        document.getElementById('edit_opsi_a').value = soal.opsi_a;
        document.getElementById('edit_opsi_b').value = soal.opsi_b;
        document.getElementById('edit_opsi_c').value = soal.opsi_c;
        document.getElementById('edit_opsi_d').value = soal.opsi_d;
        document.getElementById('edit_jawaban_benar').value = soal.jawaban_benar;

        document.getElementById('modalEdit').classList.remove('hidden');
        document.getElementById('modalEdit').classList.add('flex');
    }
    function closeModalEdit() {
        document.getElementById('modalEdit').classList.add('hidden');
    }

    // Tambah Baris Dinamis
    function tambahBarisSoal() {
        const container = document.querySelector('#containerSoal > div');
        const num = (soalCount + 1).toString().padStart(2, '0');
        const html = `
            <div class="soal-item group relative bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm transition-all">
                <div class="absolute -top-4 -left-4 w-12 h-12 bg-blue-500 shadow-lg shadow-blue-500/30 rounded-2xl flex items-center justify-center font-black text-white text-sm">${num}</div>
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-4 -right-4 w-10 h-10 bg-white text-red-500 shadow-xl rounded-2xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all border border-red-50">
                    <span class="material-symbols-outlined text-sm">delete</span>
                </button>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <div class="lg:col-span-7 space-y-6">
                        <textarea name="soal[${soalCount}][pertanyaan]" rows="4" required class="w-full border border-gray-100 bg-gray-50 rounded-[1.5rem] p-5 text-sm outline-none"></textarea>
                        <select name="soal[${soalCount}][jawaban_benar]" class="w-full border border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold">
                            <option value="a">Opsi A</option><option value="b">Opsi B</option><option value="c">Opsi C</option><option value="d">Opsi D</option>
                        </select>
                    </div>
                    <div class="lg:col-span-5 flex flex-col gap-4">
                        <input type="text" name="soal[${soalCount}][opsi_a]" placeholder="Opsi A" required class="w-full border border-gray-100 bg-gray-50 rounded-xl py-3.5 px-4 text-sm">
                        <input type="text" name="soal[${soalCount}][opsi_b]" placeholder="Opsi B" required class="w-full border border-gray-100 bg-gray-50 rounded-xl py-3.5 px-4 text-sm">
                        <input type="text" name="soal[${soalCount}][opsi_c]" placeholder="Opsi C" required class="w-full border border-gray-100 bg-gray-50 rounded-xl py-3.5 px-4 text-sm">
                        <input type="text" name="soal[${soalCount}][opsi_d]" placeholder="Opsi D" required class="w-full border border-gray-100 bg-gray-50 rounded-xl py-3.5 px-4 text-sm">
                    </div>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        soalCount++;
    }

    // Laravel Flash Message to SweetAlert
    @if(session('success'))
        showSuccessPopup("{{ session('success') }}");
    @endif
</script>
@endsection