<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\KlasterOpd;
use App\Services\RssSyncService; // Panggil Service pengecek status server
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Filter dari Request
        $search = $request->input('search');
        $klasterId = $request->input('klaster_id');
        $status = $request->input('status');

        // 2. Query Data Dinas dengan Relasi Klaster
        $query = Dinas::with('klaster');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_dinas', 'like', "%{$search}%")
                  ->orWhere('singkatan', 'like', "%{$search}%");
            });
        }

        if ($klasterId) {
            $query->where('klaster_opd_id', $klasterId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $dinasList = $query->latest('updated_at')->paginate(10);

        // 3. Hitung Ringkasan Statistik Server (Online vs Offline)
        $stats = [
            'total_dinas' => Dinas::count(),
            'aktif'       => Dinas::where('status', 'aktif')->count(), // Server Online
            'kurang_aktif' => 0, // Diberi nilai 0 agar tidak error di Blade
            'pasif'       => Dinas::where('status', 'pasif')->count(), // Server Offline
        ];

        // 4. Ambil Daftar Klaster untuk Dropdown Filter
        $klasters = KlasterOpd::all();

        // 5. Return View Dashboard (Kirim $dinasList DAN $dinas agar aman dari error variable di Blade)
        return view('dashboard', [
            'dinasList' => $dinasList,
            'dinas'     => $dinasList, // Menghindari error 'Undefined variable $dinas' di Blade
            'stats'     => $stats,
            'klasters'  => $klasters,
        ]);
    }

    public function syncRss(RssSyncService $syncService)
    {
        try {
            // Memanggil RssSyncService yang sudah diubah ke HTTP Status Check
            $syncService->syncAll();

            return redirect()->back()->with('success', 'Pengecekan status server seluruh website OPD berhasil dijalankan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status server: ' . $e->getMessage());
        }
    }
}