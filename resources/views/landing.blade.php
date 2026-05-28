<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learning Bimbingan Perkawinan - KUA Mojo</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#0e6d31",
                        secondary: "#f97316"
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        section {
            scroll-margin-top: 100px;
        }

        .hero-bg {
            background:
                linear-gradient(rgba(14, 109, 49, 0.9),
                    rgba(10, 80, 36, 0.8)),
                url("{{ asset('background.jpeg') }}");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body class="font-display bg-slate-50 text-slate-800">

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-500 py-4">

        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">

            <div class="flex items-center gap-3">
                <img src="{{ asset('images.png') }}"
                    alt="Logo Kemenag"
                    class="h-10">

                <div>
                    <h1 id="nav-title"
                        class="text-xl font-extrabold text-white leading-none">
                        KUA MOJO
                    </h1>

                    <p id="nav-subtitle"
                        class="text-[10px] text-white opacity-80 font-medium tracking-widest uppercase">
                        Kabupaten Kediri
                    </p>
                </div>
            </div>

            <div class="space-x-8 hidden md:flex items-center text-white font-semibold">

                <a href="#tentang"
                    class="hover:text-yellow-400 transition">
                    Tentang
                </a>

                <a href="#fitur"
                    class="hover:text-yellow-400 transition">
                    Fitur
                </a>

                <a href="#alur"
                    class="hover:text-yellow-400 transition">
                    Alur
                </a>

                <a href="{{ route('admin.login') }}"
                    class="bg-yellow-500 hover:bg-yellow-400 text-green-900 px-6 py-2.5 rounded-full text-sm font-bold shadow-lg transform transition active:scale-95">

                    PORTAL LOGIN
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="relative min-h-screen flex items-center hero-bg text-white overflow-hidden">

        <div class="absolute top-20 right-[-10%] w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center relative z-10">

            <div class="text-left">

                <span
                    class="inline-block px-4 py-1.5 bg-yellow-500/20 border border-yellow-500/50 text-yellow-400 text-xs font-bold rounded-full mb-6 tracking-widest uppercase">

                    Official E-Learning System
                </span>

                <h2 class="text-5xl md:text-6xl font-black leading-tight mb-6">

                    Membangun Keluarga <br>

                    <span class="text-yellow-400 text-4xl md:text-5xl">
                        Sakinah Mawaddah Warahmah
                    </span>
                </h2>

                <p class="text-lg mb-10 opacity-90 leading-relaxed max-w-xl">

                    Digitalisasi bimbingan perkawinan KUA Mojo.
                    Akses materi edukasi, kuis, dan monitoring perkembangan calon
                    pengantin secara mandiri dan transparan.
                </p>

                <a href="{{ route('admin.login') }}"
                    class="inline-flex items-center gap-2 bg-white text-green-700 px-10 py-4 rounded-xl font-bold shadow-xl hover:bg-green-50 hover:shadow-2xl transition duration-300">

                    Masuk Dashboard

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor">

                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            <div class="hidden md:block relative">

                <div
                    class="relative z-10 bg-white/5 backdrop-blur-sm p-4 rounded-3xl border border-white/20 shadow-2xl">

                    <img src="{{ asset('wedding.png') }}"
                        class="w-full drop-shadow-2xl"
                        alt="Wedding Illustration">
                </div>
            </div>
        </div>
    </section>

    <!-- STATISTIK -->
    <section class="py-12 bg-white border-b relative">

        <div class="max-w-6xl mx-auto px-6 grid grid-cols-2 md:grid-cols-3 gap-8">

            <div class="text-center border-r last:border-0 border-slate-100">

                <h3 class="text-4xl font-black text-green-700">
                    ADMIN
                </h3>

                <p class="text-slate-500 font-medium">
                    Panel Kendali Utama
                </p>
            </div>

            <div class="text-center border-r last:border-0 border-slate-100">

                <h3 class="text-4xl font-black text-green-700">
                    MONITOR
                </h3>

                <p class="text-slate-500 font-medium">
                    Bimbingan Catin
                </p>
            </div>

            <div class="text-center md:block hidden">

                <h3 class="text-4xl font-black text-green-700">
                    EDUKASI
                </h3>

                <p class="text-slate-500 font-medium">
                    Materi Digital
                </p>
            </div>
        </div>
    </section>

    <!-- TENTANG -->
    <section id="tentang" class="py-24 bg-white">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-16">

                <h3 class="text-green-700 font-bold uppercase tracking-widest text-sm mb-2">
                    Tentang Sistem
                </h3>

                <h2 class="text-4xl font-black text-slate-900">
                    E-Learning Bimbingan Perkawinan
                </h2>

                <div class="w-20 h-1.5 bg-yellow-500 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">

                <div>
                    <img src="{{ asset('wedding.png') }}"
                        class="rounded-3xl shadow-xl">
                </div>

                <div>

                    <p class="text-slate-600 leading-relaxed text-lg mb-6">

                        Sistem informasi ini dirancang untuk membantu proses
                        bimbingan perkawinan calon pengantin di KUA Mojo
                        secara digital, modern, dan mudah diakses.
                    </p>

                    <p class="text-slate-600 leading-relaxed text-lg">

                        Calon pengantin dapat mempelajari materi,
                        mengikuti evaluasi, dan memantau perkembangan
                        bimbingan secara online.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FITUR -->
    <section id="fitur" class="py-24 bg-slate-50">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-20">

                <h3 class="text-green-700 font-bold uppercase tracking-widest text-sm mb-2">
                    Layanan Kami
                </h3>

                <h2 class="text-4xl font-black text-slate-900">
                    Fitur Utama Sistem
                </h2>

                <div class="w-20 h-1.5 bg-yellow-500 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-10">

                <!-- FITUR 1 -->
                <div
                    class="group bg-white p-10 rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 border border-slate-100">

                    <div
                        class="w-16 h-16 bg-green-100 text-green-700 rounded-2xl flex items-center justify-center mb-8">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-8 w-8"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                        </svg>
                    </div>

                    <h4 class="text-xl font-extrabold mb-4">
                        Manajemen Catin
                    </h4>

                    <p class="text-slate-600 leading-relaxed italic">
                        Digitalisasi data calon pengantin untuk pelayanan
                        yang lebih cepat dan transparan.
                    </p>
                </div>

                <!-- FITUR 2 -->
                <div
                    class="group bg-white p-10 rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 border border-slate-100">

                    <div
                        class="w-16 h-16 bg-yellow-100 text-yellow-700 rounded-2xl flex items-center justify-center mb-8">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-8 w-8"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7" />
                        </svg>
                    </div>

                    <h4 class="text-xl font-extrabold mb-4">
                        Plotting Pendamping
                    </h4>

                    <p class="text-slate-600 leading-relaxed italic">
                        Penugasan pembimbing bimbingan perkawinan secara
                        sistematis dan terpantau.
                    </p>
                </div>

                <!-- FITUR 3 -->
                <div
                    class="group bg-white p-10 rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 border border-slate-100">

                    <div
                        class="w-16 h-16 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center mb-8">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-8 w-8"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5" />
                        </svg>
                    </div>

                    <h4 class="text-xl font-extrabold mb-4">
                        E-Learning Materi
                    </h4>

                    <p class="text-slate-600 leading-relaxed italic">
                        Modul belajar mandiri lengkap dengan video tutorial
                        dan evaluasi pemahaman.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ALUR -->
    <section id="alur" class="py-24 bg-white">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-20">

                <h3 class="text-green-700 font-bold uppercase tracking-widest text-sm mb-2">
                    Alur Sistem
                </h3>

                <h2 class="text-4xl font-black text-slate-900">
                    Cara Menggunakan Sistem
                </h2>

                <div class="w-20 h-1.5 bg-yellow-500 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-4 gap-8">

                <div class="text-center">

                    <div
                        class="w-20 h-20 bg-green-700 text-white rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-6">
                        1
                    </div>

                    <h4 class="font-bold text-xl mb-3">
                        Pendaftaran
                    </h4>

                    <p class="text-slate-500">
                        Admin mendaftarkan calon pengantin ke sistem.
                    </p>
                </div>

                <div class="text-center">

                    <div
                        class="w-20 h-20 bg-green-700 text-white rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-6">
                        2
                    </div>

                    <h4 class="font-bold text-xl mb-3">
                        Belajar
                    </h4>

                    <p class="text-slate-500">
                        Catin mempelajari materi bimbingan perkawinan.
                    </p>
                </div>

                <div class="text-center">

                    <div
                        class="w-20 h-20 bg-green-700 text-white rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-6">
                        3
                    </div>

                    <h4 class="font-bold text-xl mb-3">
                        Kuis
                    </h4>

                    <p class="text-slate-500">
                        Menyelesaikan evaluasi dan kuis online.
                    </p>
                </div>

                <div class="text-center">

                    <div
                        class="w-20 h-20 bg-green-700 text-white rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-6">
                        4
                    </div>

                    <h4 class="font-bold text-xl mb-3">
                        Sertifikat
                    </h4>

                    <p class="text-slate-500">
                        Mendapatkan sertifikat bimbingan perkawinan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-white py-12 border-t-4 border-yellow-500">

        <div class="max-w-7xl mx-auto px-6 text-center">

            <img src="{{ asset('images.png') }}"
                alt="Logo Kemenag"
                class="h-16 mx-auto mb-6 grayscale opacity-50">

            <p class="font-bold text-lg mb-2 italic">
                "Ikhlas Beramal"
            </p>

            <p class="text-slate-400 text-sm">
                © 2026 KUA Mojo – Kantor Urusan Agama Kecamatan Mojo
            </p>

            <p class="text-slate-500 text-[10px] mt-2 uppercase tracking-widest">
                Kabupaten Kediri, Jawa Timur
            </p>
        </div>
    </footer>

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#0e6d31',
        });
    </script>
    @endif

    <!-- NAVBAR EFFECT -->
    <script>
        window.addEventListener("scroll", function() {

            let navbar = document.getElementById("navbar");
            let title = document.getElementById("nav-title");
            let subtitle = document.getElementById("nav-subtitle");

            let navLinks = navbar.querySelectorAll("a:not(.bg-yellow-500)");

            if (window.scrollY > 50) {

                navbar.classList.add("bg-white", "shadow-xl", "py-2");
                navbar.classList.remove("py-4");

                title.classList.replace("text-white", "text-green-800");
                subtitle.classList.replace("text-white", "text-slate-500");

                navLinks.forEach(link => {
                    link.classList.remove("text-white");
                    link.classList.add("text-slate-600");
                });

            } else {

                navbar.classList.remove("bg-white", "shadow-xl", "py-2");
                navbar.classList.add("py-4");

                title.classList.replace("text-green-800", "text-white");
                subtitle.classList.replace("text-slate-500", "text-white");

                navLinks.forEach(link => {
                    link.classList.remove("text-slate-600");
                    link.classList.add("text-white");
                });
            }
        });
    </script>

</body>

</html>