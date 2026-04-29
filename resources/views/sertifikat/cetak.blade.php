<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Times New Roman', serif; }
        .certificate-container {
            border: 10px double #1a5c37; /* Hijau KUA Mojo */
            padding: 50px;
            width: 800px;
            margin: auto;
            position: relative;
            background-color: #fff;
        }
        .header { text-align: center; }
        .logo { width: 80px; margin-bottom: 10px; }
        .title { font-size: 34px; font-weight: bold; color: #1a5c37; margin: 20px 0; }
        .subtitle { font-size: 20px; font-style: italic; margin-bottom: 30px; }
        .content { text-align: center; font-size: 18px; line-height: 1.6; }
        .name { font-size: 28px; font-weight: bold; text-decoration: underline; margin: 10px 0; }
        .footer { margin-top: 50px; display: flex; justify-content: flex-end; }
        .signature { text-align: center; width: 250px; }
        .watermark {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
            width: 400px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="header">
            <img src="logo-kemenag.png" class="logo">
            <div style="font-size: 16px; font-weight: bold;">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
            <div style="font-size: 14px;">KANTOR URUSAN AGAMA KECAMATAN MOJO</div>
        </div>

        <div class="header">
            <div class="title">SERTIFIKAT KELULUSAN</div>
            <div class="subtitle">Nomor: {{ 'CERT/'.$ujian->id.'/'.date('Y') }}</div>
        </div>

        <div class="content">
            Diberikan kepada:<br>
            <div class="name">{{ strtoupper($ujian->catin->nama_lengkap) }}</div>
            Telah dinyatakan <strong>LULUS</strong> dalam mengikuti kegiatan<br>
            <strong>E-Learning Bimbingan Perkawinan (Bimwin) Digital</strong><br>
            dengan perolehan nilai evaluasi: <strong>{{ $ujian->skor }}</strong>.
        </div>

        <div class="footer">
            <div class="signature">
                Mojo, {{ date('d F Y') }}<br>
                Kepala KUA Mojo,<br><br><br><br>
                <strong>( ................................ )</strong><br>
                NIP. ...........................
            </div>
        </div>
    </div>
</body>
</html>