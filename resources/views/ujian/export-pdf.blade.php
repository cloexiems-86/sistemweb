<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Ujian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead {
            background-color: #f5f5f5;
        }
        table th {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }
        table tbody tr:nth-child(odd) {
            background-color: #fafafa;
        }
        .status-lulus {
            color: green;
            font-weight: bold;
        }
        .status-remedial {
            color: red;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <h1>📋 LAPORAN HASIL UJIAN CALON PENGANTIN (CATIN)</h1>
    
    <div class="info">
        <p>Tanggal Laporan: {{ now()->format('d-m-Y H:i:s') }}</p>
        <p>Sistem Manajemen KUA Mojo</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">NO</th>
                <th style="width: 25%">NAMA CATIN</th>
                <th style="width: 20%">PENDAMPING</th>
                <th style="width: 10%">BENAR</th>
                <th style="width: 10%">SALAH</th>
                <th style="width: 10%">SKOR</th>
                <th style="width: 15%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ujianResults as $index => $ujian)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $ujian->catin->nama_suami }}</td>
                    <td>{{ $ujian->catin->pendamping_assigned ?? 'Tanpa Pendamping' }}</td>
                    <td style="text-align: center">{{ $ujian->jawaban_benar }}</td>
                    <td style="text-align: center">{{ $ujian->jawaban_salah }}</td>
                    <td style="text-align: center; font-weight: bold">{{ $ujian->skor }}</td>
                    <td class="@if($ujian->status_kelulusan == 'lulus') status-lulus @else status-remedial @endif">
                        {{ strtoupper($ujian->status_kelulusan) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">Tidak ada data ujian</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh Sistem Informasi KUA Mojo</p>
        <p>&copy; 2026 - Kantor Urusan Agama Kecamatan Mojo</p>
    </div>
</body>
</html>
