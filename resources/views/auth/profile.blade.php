@extends('layouts.app') {{-- Sesuaikan dengan nama layout admin kamu --}}

@section('page-title', 'Pengaturan Profil')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    {{-- TOMBOL KEMBALI --}}
    <div class="flex justify-start mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-black text-xs uppercase tracking-widest hover:text-amber-500 transition-all">
            <span class="material-symbols-outlined text-sm group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Card Profil --}}
    <div class="bg-white dark:bg-[#065f46] rounded-3xl shadow-sm border border-emerald-100 dark:border-white/10 overflow-hidden">
        <div class="p-8">
            <div class="flex items-center gap-6 mb-8">
                {{-- Avatar Besar --}}
                <div class="size-20 rounded-2xl bg-[#065f46] dark:bg-emerald-800 text-amber-400 flex items-center justify-center text-3xl font-black shadow-lg border-2 border-emerald-100 dark:border-emerald-700">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-2xl font-black text-[#064e3b] dark:text-emerald-50 tracking-tight">{{ $user->name }}</h3>
                    <p class="text-emerald-600 dark:text-emerald-300 font-bold uppercase tracking-widest text-xs">{{ $user->role }}</p>
                </div>
            </div>

            <hr class="border-emerald-50 dark:border-white/5 mb-8">

            {{-- Form Update --}}
            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-xs font-black text-[#064e3b] dark:text-emerald-50 uppercase mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                            class="w-full px-5 py-3 rounded-xl border border-emerald-100 focus:border-amber-400 focus:ring-2 focus:ring-amber-200 outline-none transition-all font-medium text-sm">
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-xs font-black text-[#064e3b] dark:text-emerald-50 uppercase mb-2 ml-1">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                            class="w-full px-5 py-3 rounded-xl border border-emerald-100 focus:border-amber-400 focus:ring-2 focus:ring-amber-200 outline-none transition-all font-medium text-sm">
                    </div>

                    {{-- Password Baru --}}
                    <div class="relative">
                        <label class="block text-xs font-black text-[#064e3b] dark:text-emerald-50 uppercase mb-2 ml-1">Password Baru (Opsional)</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak diganti"
                                class="w-full px-5 py-3 rounded-xl border border-emerald-100 focus:border-amber-400 focus:ring-2 focus:ring-amber-200 outline-none transition-all font-medium text-sm pr-12">
                            <button type="button" onclick="togglePassword('password', 'eye-icon-1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-600">
                                <span id="eye-icon-1" class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="relative">
                        <label class="block text-xs font-black text-[#064e3b] dark:text-emerald-50 uppercase mb-2 ml-1">Konfirmasi Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password baru"
                                class="w-full px-5 py-3 rounded-xl border border-emerald-100 focus:border-amber-400 focus:ring-2 focus:ring-amber-200 outline-none transition-all font-medium text-sm pr-12">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-600">
                                <span id="eye-icon-2" class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-[#065f46] hover:bg-[#054d39] text-amber-400 px-8 py-3 rounded-xl text-sm font-black transition-all shadow-lg shadow-emerald-200/50 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Alert Success --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#065f46',
    });
</script>
@endif

{{-- Alert Error Validation --}}
@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ $errors->first() }}",
        confirmButtonColor: '#d33',
    });
</script>
@endif

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerText = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerText = 'visibility';
        }
    }
</script>
@endsection