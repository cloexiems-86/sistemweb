<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kelulusan</title>
    <style>
        /* Mengatur ukuran kertas murni A4 Landscape */
        @page {
            size: A4 landscape;
            margin: 0; /* Margin dinolkan agar kita atur lewat CSS */
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            /* Warna latar belakang abu-abu hanya untuk mode Preview di web */
            background-color: {{ isset($mode) && $mode == 'preview' ? '#f0f2f5' : '#ffffff' }};
            margin: 0;
            padding: 0;
            text-align: center;
        }

        /* --- PANEL TOMBOL KHUSUS ADMIN (HILANG SAAT CETAK PDF) --- */
        .action-panel {
            background-color: #ffffff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: {{ isset($mode) && $mode == 'preview' ? 'flex' : 'none' }};
            justify-content: center;
            gap: 15px;
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

        /* --- BINGKAI SERTIFIKAT UTAMA --- */
        .cert-container {
            /* Ukuran piksel aman untuk A4 Landscape di DomPDF */
            width: 1040px; 
            height: 720px;
            margin: {{ isset($mode) && $mode == 'preview' ? '20px auto' : '35px auto' }};
            background-color: #ffffff;
            box-sizing: border-box;
            position: relative;
        }

        /* Garis Double Hijau yang presisi */
        .cert-border {
            border: 12px double #1E5128;
            width: 990px;
            height: 670px;
            margin: 0 auto;
            position: relative;
            box-sizing: border-box;
            padding: 40px;
        }

        /* --- TIPOGRAFI & KONTEN --- */
        .header h2 { margin: 0; font-size: 24pt; color: #111; font-weight: normal;}
        .header h3 { margin: 5px 0 15px 0; font-size: 20pt; color: #333; }
        .garis-kop { border-top: 3px solid #1E5128; border-bottom: 1px solid #1E5128; height: 2px; margin-bottom: 30px; }
        
        .judul-sertifikat {
            font-size: 36pt;
            font-weight: bold;
            color: #1E5128;
            letter-spacing: 3px;
            margin: 0 0 10px 0;
        }
        
        .nomor-surat { font-size: 14pt; margin-bottom: 30px; font-style: italic; }
        
        .pengantar { font-size: 16pt; margin-bottom: 15px; }

        /* Nama Catin */
        .nama-peserta {
            font-size: 32pt;
            font-weight: bold;
            color: #000;
            text-decoration: underline;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .nik-peserta { font-size: 14pt; color: #555; margin-bottom: 30px; }

        .keterangan { font-size: 16pt; line-height: 1.5; margin-bottom: 10px;}
        .skor { font-size: 18pt; font-weight: bold; margin-bottom: 30px; }

        /* --- TANDA TANGAN (Posisi Kanan Bawah Mutlak) --- */
        .footer-ttd {
            position: absolute;
            bottom: 40px;
            right: 60px;
            width: 300px;
            text-align: center;
            font-size: 14pt;
        }
        .signature-space { height: 90px; }
        .nama-kua { font-weight: bold; text-decoration: underline; margin-bottom: 5px;}

        /* Sembunyikan panel aksi dari proses render PDF DomPDF */
        @media print {
            .action-panel { display: none !important; }
        }
    </style>
</head>
<body>

    @if(isset($mode) && $mode == 'preview')
    <div class="action-panel">
        <a href="{{ route('admin.sertifikat.index') }}" class="btn-kembali">🔙 Kembali</a>
        <a href="{{ route('admin.sertifikat.download', ['id' => $ujian->id, 'person' => $person]) }}" class="btn-cetak">⬇️ Unduh PDF</a>
    </div>
    @endif

    <div class="cert-container">
        <div class="cert-border">
            
            <div class="header">
                <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
                <h3>KANTOR URUSAN AGAMA KECAMATAN MOJO</h3>
                <div class="garis-kop"></div>
            </div>

            <div class="judul-sertifikat">SERTIFIKAT KELULUSAN</div>
            <div class="nomor-surat">Nomor: {{ $ujian->id }}/KUA.MOJO/BIMWIN/{{ date('Y') }}</div>

            <div class="pengantar">Diberikan Kepada Calon {{ ucfirst($person) }}:</div>
            
            @if($person == 'suami')
                <div class="nama-peserta">{{ $ujian->catin->nama_suami }}</div>
                <div class="nik-peserta">NIK: {{ $ujian->catin->nik_suami }}</div>
            @else
                <div class="nama-peserta">{{ $ujian->catin->nama_istri }}</div>
                <div class="nik-peserta">NIK: {{ $ujian->catin->nik_istri }}</div>
            @endif

            <div class="keterangan">
                Atas keberhasilannya menyelesaikan Ujian E-Learning Bimbingan Perkawinan<br>
                dengan hasil evaluasi yang memenuhi standar kelulusan:
            </div>
            
            <div class="skor">SKOR: {{ $ujian->skor }} (LULUS)</div>

            <div class="footer-ttd">
                <p style="margin:0 0 5px 0;">Kediri, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="margin:0;">Kepala KUA Mojo,</p>
                <div class="signature-space"></div>
                <div class="nama-kua">Zainal Abidin</div>
                <div>NIP. ...........................................</div>
            </div>

        </div>
    </div>

</body>
</html>