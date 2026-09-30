<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\KlasterOpd;
use App\Models\Dinas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class MonitoringSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Kominfo
        User::create([
            'name' => 'Admin Kominfo',
            'email' => 'admin@kominfo.go.id',
            'password' => Hash::make('password123'),
        ]);

        // 2. Data Klaster OPD
        $dinasKlaster = KlasterOpd::create(['nama_klaster' => 'Dinas Daerah']);
        $badanKlaster = KlasterOpd::create(['nama_klaster' => 'Badan Daerah']);
        $kecamatanKlaster = KlasterOpd::create(['nama_klaster' => 'Kecamatan']);

        // 3. Data Dinas & Status Awal (Simulasi Hasil Penarikan RSS)
        Dinas::create([
            'klaster_opd_id' => $dinasKlaster->id,
            'nama_dinas' => 'Dinas Kesehatan',
            'singkatan' => 'DK',
            'domain_url' => 'https://dinkes.namadaerah.go.id',
            'rss_url' => 'https://dinkes.namadaerah.go.id/feed',
            'pic_nama' => 'Subbag Informasi & Humas',
            'last_post_title' => 'Sosialisasi Imunisasi Polio Tahap 2 di Posyandu',
            'last_post_link' => 'https://dinkes.namadaerah.go.id/sosialisasi-polio',
            'last_post_date' => Carbon::now()->subHours(4),
            'status' => 'aktif',
        ]);

        Dinas::create([
            'klaster_opd_id' => $dinasKlaster->id,
            'nama_dinas' => 'Dinas Pendidikan',
            'singkatan' => 'DP',
            'domain_url' => 'https://disdik.namadaerah.go.id',
            'rss_url' => 'https://disdik.namadaerah.go.id/feed',
            'pic_nama' => 'Tim Pengelola TIK',
            'last_post_title' => 'Pengumuman Hasil Seleksi Beasiswa Prestasi Daerah',
            'last_post_link' => 'https://disdik.namadaerah.go.id/beasiswa',
            'last_post_date' => Carbon::now()->subDays(12),
            'status' => 'kurang_aktif',
        ]);

        Dinas::create([
            'klaster_opd_id' => $badanKlaster->id,
            'nama_dinas' => 'Badan Perencanaan Pembangunan Daerah',
            'singkatan' => 'BAPPEDA',
            'domain_url' => 'https://bappeda.namadaerah.go.id',
            'rss_url' => 'https://bappeda.namadaerah.go.id/feed',
            'pic_nama' => 'Bidang Data & Informasi',
            'last_post_title' => 'Rapat Koordinasi Penyusunan RKPD Tahun Depan',
            'last_post_link' => 'https://bappeda.namadaerah.go.id/rkpd',
            'last_post_date' => Carbon::now()->subDays(40),
            'status' => 'pasif',
        ]);
    }
}