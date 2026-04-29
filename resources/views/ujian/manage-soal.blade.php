@extends('layouts.app') {{-- Sesuaikan dengan nama layout utamamu --}}

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid px-4 py-4">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Ujian Akhir</h1>
            <p class="text-gray-500 text-sm">Kelola bank soal dan pantau riwayat hasil ujian Catin.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.ujian.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2">
                <span class="material-symbols-outlined">arrow_back</span> Kembali
            </a>
            <button onclick="toggleModal('modalTambahSoal')" class="bg-[#2ecc71] hover:bg-[#27ae60] text-white px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                <span class="material-symbols-outlined">add_circle</span> Tambah Soal
            </button>
        </div>
    </div>

    {{-- Navigasi Tab --}}
    <div class="flex gap-4 mb-6 border-b">
        <button onclick="switchTab('bank-soal')" id="btn-bank" class="pb-2 border-b-2 border-green-600 text-green-600 font-bold transition-all duration-200">
            Bank Soal
        </button>
        <button onclick="switchTab('history-ujian')" id="btn-history" class="pb-2 border-b-2 border-transparent text-gray-500 hover:text-green-600 transition-all duration-200">
            Riwayat Ujian
        </button>
    </div>

    {{-- Tab 1: Bank Soal --}}
    <div id="tab-bank-soal">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#f8f9fa]">
                        <tr>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700 border-b w-16">No</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700 border-b">Pertanyaan</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700 border-b">Pilihan Jawaban</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700 border-b w-24">Kunci</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700 border-b w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($soals as $index => $soal)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                {{ Str::limit($soal->pertanyaan, 100) }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                <ul class="list-none">
                                    <li><span class="font-bold text-green-600">A.</span> {{ $soal->pil_a }}</li>
                                    <li><span class="font-bold text-green-600">B.</span> {{ $soal->pil_b }}</li>
                                    <li><span class="font-bold text-green-600">C.</span> {{ $soal->pil_c }}</li>
                                    <li><span class="font-bold text-green-600">D.</span> {{ $soal->pil_d }}</li>
                                </ul>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold uppercase">
                                    {{ $soal->kunci_jawaban }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button onclick="openEditModal({{ json_encode($soal) }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>

                                    <form action="{{ route('admin.ujian.soal.destroy', $soal->id) }}" method="POST" id="delete-form-{{ $soal->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $soal->id }})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                <span class="material-symbols-outlined text-4xl mb-2">quiz</span>
                                <p>Belum ada soal. Klik Tambah Soal untuk memulai.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tab 2: Riwayat Ujian --}}
    <div id="tab-history-ujian" class="hidden">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700 border-b">Nama Catin</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700 border-b text-center">Skor</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700 border-b text-center">Status</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700 border-b text-right">Tanggal Ujian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($history as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                {{-- Memastikan data catin ada agar tidak error --}}
                                <div class="font-bold text-gray-800">{{ $item->catin->nama_lengkap ?? 'Data Catin Hilang' }}</div>
                                <div class="text-xs text-gray-400">NIK: {{ $item->catin->nik_suami ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-lg font-bold {{ $item->skor >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $item->skor }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->skor >= 70)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">LULUS</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">REMEDIAL</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-500">
                                {{ $item->created_at->translatedFormat('d F Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-gray-300 text-5xl mb-2">history_toggle_off</span>
                                    <p class="text-gray-400 italic">Belum ada Catin yang menyelesaikan ujian.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Soal --}}
<div id="modalTambahSoal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
        <div class="bg-[#2ecc71] p-4 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg">Tambah Soal Ujian</h3>
            <button onclick="toggleModal('modalTambahSoal')" class="text-white hover:text-gray-200">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.ujian.soal.store') }}" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pertanyaan</label>
                <textarea name="pertanyaan" rows="3" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 outline-none" required></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div><label class="block text-xs font-bold text-gray-500 uppercase">Pilihan A</label><input type="text" name="pil_a" class="w-full border rounded-lg p-2 outline-none" required></div>
                <div><label class="block text-xs font-bold text-gray-500 uppercase">Pilihan B</label><input type="text" name="pil_b" class="w-full border rounded-lg p-2 outline-none" required></div>
                <div><label class="block text-xs font-bold text-gray-500 uppercase">Pilihan C</label><input type="text" name="pil_c" class="w-full border rounded-lg p-2 outline-none" required></div>
                <div><label class="block text-xs font-bold text-gray-500 uppercase">Pilihan D</label><input type="text" name="pil_d" class="w-full border rounded-lg p-2 outline-none" required></div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Kunci Jawaban</label>
                <select name="kunci_jawaban" class="w-full border rounded-lg p-2 outline-none" required>
                    <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="toggleModal('modalTambahSoal')" class="px-6 py-2 text-gray-500 font-bold">Batal</button>
                <button type="submit" class="bg-[#2ecc71] text-white px-8 py-2 rounded-lg font-bold shadow-lg">Simpan Soal</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Soal --}}
<div id="modalEditSoal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
        <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg">Edit Soal Ujian</h3>
            <button onclick="toggleModal('modalEditSoal')" class="text-white hover:text-gray-200">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="formEditSoal" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pertanyaan</label>
                <textarea id="edit_pertanyaan" name="pertanyaan" rows="3" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none" required></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div><label class="block text-xs font-bold text-gray-500 uppercase">Pilihan A</label><input type="text" id="edit_pil_a" name="pil_a" class="w-full border rounded-lg p-2 outline-none" required></div>
                <div><label class="block text-xs font-bold text-gray-500 uppercase">Pilihan B</label><input type="text" id="edit_pil_b" name="pil_b" class="w-full border rounded-lg p-2 outline-none" required></div>
                <div><label class="block text-xs font-bold text-gray-500 uppercase">Pilihan C</label><input type="text" id="edit_pil_c" name="pil_c" class="w-full border rounded-lg p-2 outline-none" required></div>
                <div><label class="block text-xs font-bold text-gray-500 uppercase">Pilihan D</label><input type="text" id="edit_pil_d" name="pil_d" class="w-full border rounded-lg p-2 outline-none" required></div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Kunci Jawaban</label>
                <select id="edit_kunci" name="kunci_jawaban" class="w-full border rounded-lg p-2 outline-none" required>
                    <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="toggleModal('modalEditSoal')" class="px-6 py-2 text-gray-500 font-bold">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg font-bold shadow-lg">Update Soal</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi Switch Tab
    function switchTab(tabName) {
        const bankTab = document.getElementById('tab-bank-soal');
        const historyTab = document.getElementById('tab-history-ujian');
        const btnBank = document.getElementById('btn-bank');
        const btnHistory = document.getElementById('btn-history');

        if(tabName === 'bank-soal') {
            bankTab.classList.remove('hidden');
            historyTab.classList.add('hidden');
            btnBank.className = "pb-2 border-b-2 border-green-600 text-green-600 font-bold transition-all duration-200";
            btnHistory.className = "pb-2 border-b-2 border-transparent text-gray-500 transition-all duration-200";
        } else {
            bankTab.classList.add('hidden');
            historyTab.classList.remove('hidden');
            btnHistory.className = "pb-2 border-b-2 border-green-600 text-green-600 font-bold transition-all duration-200";
            btnBank.className = "pb-2 border-b-2 border-transparent text-gray-500 transition-all duration-200";
        }
    }

    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function openEditModal(soal) {
        document.getElementById('formEditSoal').action = `/admin/ujian/soal-master/${soal.id}`;
        document.getElementById('edit_pertanyaan').value = soal.pertanyaan;
        document.getElementById('edit_pil_a').value = soal.pil_a;
        document.getElementById('edit_pil_b').value = soal.pil_b;
        document.getElementById('edit_pil_c').value = soal.pil_c;
        document.getElementById('edit_pil_d').value = soal.pil_d;
        document.getElementById('edit_kunci').value = soal.kunci_jawaban.toLowerCase();
        
        toggleModal('modalEditSoal');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Soal ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif
</script>
@endsection