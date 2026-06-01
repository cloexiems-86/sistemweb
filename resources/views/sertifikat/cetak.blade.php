<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kelulusan - {{ ucfirst($person) }}</title>
    <style>
        /* 1. Hilangkan margin kertas bawaan DomPDF, kita atur sendiri dari nol */
        @page {
            size: A4 landscape;
            margin: 0; 
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            color: #000;
            
            /* Latar belakang abu-abu hanya jalan kalau di Web (Preview) */
            @if(isset($mode) && $mode == 'preview')
                background-color: #f0f2f5;
                padding: 20px;
            @else
                background-color: #ffffff;
            @endif
        }

        /* --- PANEL TOMBOL KHUSUS ADMIN (TIDAK IKUT TERCETAK) --- */
        .action-panel {
            background-color: #ffffff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: {{ isset($mode) && $mode == 'preview' ? 'flex' : 'none' }};
            justify-content: center;
            gap: 15px;
            width: 1060px; /* Menyesuaikan lebar sertifikat */
            margin-left: auto;
            margin-right: auto;
            box-sizing: border-box;
        }
        .action-panel a {
            padding: 10px 20px;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;
        }
        .btn-kembali { background-color: #4b5563; color: white; }
        .btn-cetak { background-color: #2563eb; color: white; }

        /* --- KANVAS UTAMA (Kunci agar pas 1 layar A4) --- */
        .sertifikat-canvas {
            position: relative;
            background-color: #ffffff;
            box-sizing: border-box;
            
            @if(isset($mode) && $mode == 'preview')
                /* Ukuran presisi A4 Landscape di Web */
                width: 1060px;
                height: 740px;
                margin: 0 auto;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                overflow: hidden;
            @else
                /* Saat jadi PDF, ukurannya full seluas kertas */
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0; left: 0; bottom: 0; right: 0;
            @endif
        }

        /* --- BINGKAI DALAM --- */
        .sertifikat-frame {
            position: absolute;
            top: 40px; 
            bottom: 40px; 
            left: 40px; 
            right: 40px;
            border: 10px double #1E5128; 
            padding: 30px;
            text-align: center;
            box-sizing: border-box;
        }

        /* --- TIPOGRAFI & KONTEN --- */
        .header h2 { margin: 0; font-size: 22pt; font-weight: normal; }
        .header h3 { margin: 5px 0 15px 0; font-size: 18pt; }
        .garis-kop { border-top: 3px solid #1E5128; border-bottom: 1px solid #1E5128; height: 2px; margin-bottom: 25px; }

        .judul-sertifikat { font-size: 34pt; font-weight: bold; color: #1E5128; letter-spacing: 2px; margin-bottom: 5px; }
        .nomor-surat { font-size: 12pt; margin-bottom: 25px; font-style: italic; }

        .pengantar { font-size: 14pt; margin-bottom: 10px; }

        .nama-peserta { font-size: 28pt; font-weight: bold; text-decoration: underline; margin-bottom: 5px; text-transform: uppercase; }
        .nik-peserta { font-size: 12pt; color: #333; margin-bottom: 25px; }

        .keterangan { font-size: 14pt; line-height: 1.5; margin-bottom: 10px;}
        .skor { font-size: 16pt; font-weight: bold; }

        /* --- POSISI MUTLAK UNTUK TANDA TANGAN --- */
        .ttd-box {
            position: absolute;
            bottom: 90px; 
            right: 50px;  
            width: 300px;
            text-align: center;
        }
        .ttd-text { font-size: 14pt; margin: 0 0 5px 0; }
        .ttd-space { height: 60px; } 
        .ttd-name { font-size: 14pt; font-weight: bold; text-decoration: underline; margin-bottom: 0; }

    </style>
</head>
<body>

    @if(isset($mode) && $mode == 'preview')
    <div class="action-panel">
        <a href="{{ route('admin.sertifikat.index') }}" class="btn-kembali">🔙 Kembali</a>
        <a href="{{ route('admin.sertifikat.download', ['id' => $catin->id, 'person' => $person]) }}" class="btn-cetak">⬇️ Unduh PDF</a>
    </div>
    @endif

    <div class="sertifikat-canvas">
        <div class="sertifikat-frame">
            
            <div class="header">
                <div style="width: 70px; height: 70px; border: 1px solid #000; margin: 0 auto 10px auto; line-height: 70px; text-align: center;">Logo</div>
                <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
                <h3>KANTOR URUSAN AGAMA KECAMATAN MOJO</h3>
                <div class="garis-kop"></div>
            </div>

            <div class="judul-sertifikat">SERTIFIKAT KELULUSAN</div>
            <div class="nomor-surat">Nomor: {{ $ujian->id }}/KUA.MOJO/BIMWIN/{{ date('Y') }}</div>

            <div class="pengantar">Diberikan Kepada Calon {{ ucfirst($person) }}:</div>

            @if($person == 'suami')
                <div class="nama-peserta">{{ $catin->nama_suami }}</div>
                <div class="nik-peserta">NIK: {{ $catin->nik_suami }}</div>
            @else
                <div class="nama-peserta">{{ $catin->nama_istri }}</div>
                <div class="nik-peserta">NIK: {{ $catin->nik_istri }}</div>
            @endif

            <div class="keterangan">
                Atas keberhasilannya menyelesaikan Ujian E-Learning Bimbingan Perkawinan<br>
                dengan hasil evaluasi yang memenuhi standar kelulusan:
            </div>

            <div class="skor">SKOR: {{ $ujian->skor }} (LULUS)</div>

            <div class="ttd-box">
                <p class="ttd-text">Kediri, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="ttd-text">Kepala KUA Mojo,</p>
                <div class="ttd-space"></div>
                <div class="ttd-name">Zainal Abidin</div>
            </div>

        </div>
    </div>

</body>
</html>