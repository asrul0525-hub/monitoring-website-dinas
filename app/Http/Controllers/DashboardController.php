<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\KlasterOpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Filter dari Request (jika ada pencarian / filter klaster)
        $search = $request->input('search');
        $klasterId = $request->input('klaster_id');
        $status = $request->input('status');

        // 2. Query Data Dinas dengan Relasi Klaster
        $query = Dinas::with('klaster');

        if ($search) {
            $query->where('nama_dinas', 'like', "%{$search}%")
                  ->orWhere('singkatan', 'like', "%{$search}%");
        }

        if ($klasterId) {
            $query->where('klaster_opd_id', $klasterId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $dinasList = $query->latest('updated_at')->paginate(10);

        // 3. Hitung Ringkasan Statistik untuk Card Dashboard
        $stats = [
            'total_dinas'   => Dinas::count(),
            'aktif'         => Dinas::where('status', 'aktif')->count(),
            'kurang_aktif'  => Dinas::where('status', 'kurang_aktif')->count(),
            'pasif'         => Dinas::where('status', 'pasif')->count(),
        ];

        // 4. Ambil Daftar Klaster untuk Dropdown Filter
        $klasters = KlasterOpd::all();

        // 5. Return View Dashboard beserta Data
        return view('dashboard', compact('dinasList', 'stats', 'klasters'));
    }

    public function syncRss()
    {
        try {
            // Memanggil Artisan Command 'rss:fetch' yang sudah kita buat
            Artisan::call('rss:fetch');

            return redirect()->back()->with('success', 'Penarikan data RSS dari seluruh website OPD berhasil dijalankan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data RSS: ' . $e->getMessage());
        }
    }
}