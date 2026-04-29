<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Kelulusan - {{ $ujian->catin->nama_lengkap ?? 'Nama Catin' }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; text-align: center; padding: 20px; }
        .border-outer { border: 10px double #2ecc71; padding: 20px; }
        .header { margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 30pt; color: #2c3e50; }
        .content { margin-top: 50px; }
        .name { font-size: 24pt; font-weight: bold; text-decoration: underline; margin: 20px 0; }
        .score { font-size: 16pt; margin-bottom: 50px; }
        .footer { margin-top: 60px; float: right; width: 300px; }
        .signature-space { height: 80px; }
    </style>
</head>
<body>
    <div class="border-outer">
        <div class="header">
            <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
            <h3>KANTOR URUSAN AGAMA KECAMATAN MOJO</h3>
            <hr>
            <h1>SERTIFIKAT KELULUSAN</h1>
        </div>

        <div class="content">
            <p>Diberikan kepada:</p>
            <div class="name">{{ strtoupper($ujian->catin->nama_lengkap ?? 'NAMA CATIN') }}</div>
            <p>Atas keberhasilannya menyelesaikan Ujian Bimbingan Perkawinan dengan hasil:</p>
            <div class="score"><strong>SKOR: {{ $ujian->skor }} (LULUS)</strong></div>
        </div>

        <div class="footer">
            <p>Kediri, {{ date('d F Y') }}</p>
            <p>Kepala KUA Mojo,</p>
            <div class="signature-space"></div>
            <p><strong>( ____________________ )</strong></p>
            <p>NIP. ............................</p>
        </div>
    </div>
</body>
</html>