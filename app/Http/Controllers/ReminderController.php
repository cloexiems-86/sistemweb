<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    /**
     * Fungsi utama untuk mengirim pengingat otomatis ke Pendamping / Fasilitator Sesi
     * Logika: Memindai semua jadwal 'Upcoming' dari H-0 (Hari ini) sampai H-7 (7 hari ke depan)
     */
    public function sendH7Reminder(Request $request)
    {
        // 1. Tentukan rentang waktu (Hari ini sampai 7 Hari ke depan)
        $hariIni = Carbon::today();
        $h7 = Carbon::today()->addDays(7);

        // 2. Ambil semua jadwal dalam rentang waktu tersebut yang statusnya Upcoming
        $jadwals = Jadwal::with('pendamping')
            ->where('status', 'Upcoming')
            ->whereBetween('tanggal', [$hariIni, $h7])
            ->get();

        // 3. Jika tidak ada jadwal yang sesuai, kembalikan notifikasi
        if ($jadwals->isEmpty()) {
            return back()->with('error', 'Tidak ada jadwal bimbingan Upcoming dalam 7 hari ke depan yang perlu diingatkan.');
        }

        $pesanTerkirim = 0;
        $namaPendampingTerkirim = [];

        // 4. Looping / Kirim pesan massal ke setiap pendamping yang bertugas
        foreach ($jadwals as $jadwal) {
            $pendamping = $jadwal->pendamping;

            if ($pendamping) {
                // Ambil nomor HP (cek kolom whatsapp / no_hp / no_whatsapp)
                $no_hp = $pendamping->whatsapp ?? $pendamping->no_hp ?? $pendamping->no_whatsapp;

                if (!empty($no_hp)) {
                    $tanggalSesi = Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y');
                    $waktuSesi = $jadwal->sesi ?? '09:00 - 11:00';
                    $topikSesi = $jadwal->topik ?? 'Bimbingan Perkawinan';
                    $lokasiSesi = $jadwal->lokasi ?? 'KUA Mojo';

                    // Susun teks pesan
                    $pesanPendamping = "Assalamualaikum Wr. Wb.\n\n"
                                    . "Halo Bapak/Ibu *{$pendamping->nama}*,\n"
                                    . "Ini adalah pesan pengingat otomatis dari sistem E-BIMWIN KUA Mojo. Anda dijadwalkan untuk bertindak sebagai *Fasilitator / Pendamping* dalam Bimbingan Perkawinan pada:\n\n"
                                    . "📚 Topik: {$topikSesi}\n"
                                    . "📅 Tanggal: {$tanggalSesi}\n"
                                    . "⏰ Sesi: {$waktuSesi}\n"
                                    . "📍 Lokasi: {$lokasiSesi}\n\n"
                                    . "Mohon kesediaannya untuk hadir tepat waktu. Terima kasih banyak.";

                    // Eksekusi kirim WA
                    $this->kirimWhatsAppApi($no_hp, $pesanPendamping);
                    
                    $pesanTerkirim++;
                    $namaPendampingTerkirim[] = $pendamping->nama;
                }
            }
        }

        // 5. Berikan laporan hasil pengiriman ke layar Admin
        if ($pesanTerkirim > 0) {
            // Hapus nama duplikat jika ada 1 fasilitator mengajar 2 sesi
            $listNama = implode(', ', array_unique($namaPendampingTerkirim));
            return back()->with('success', "Berhasil mengirim {$pesanTerkirim} notifikasi WA otomatis ke Fasilitator: {$listNama}!");
        } else {
            return back()->with('error', "Gagal! Jadwal ditemukan, tapi nomor WA Fasilitator kosong di database.");
        }
    }

    /**
     * Fungsi Helper untuk menembak API WhatsApp Gateway (Fonnte)
     */
    private function kirimWhatsAppApi($no_hp, $pesan)
    {
        // Standarisasi awalan nomor 08 menjadi 628
        if (substr($no_hp, 0, 1) == '0') {
            $no_hp = '62' . substr($no_hp, 1);
        }

        // PERHATIAN: JANGAN LUPA MASUKKAN TOKEN FONNTE KAMU DI SINI
        $token = 'vhsfwsu4jQBZ9ECwMdHa'; // Ganti dengan token Fonnte kamu
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $no_hp,
                'message' => $pesan,
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token" 
            ),
            // Penyelamat Error SSL di Localhost/XAMPP
            CURLOPT_SSL_VERIFYPEER => false, 
            CURLOPT_SSL_VERIFYHOST => false, 
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        return $response;
    }
}