<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\KlasterOpd;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    /**
     * Tampilkan Halaman Utama Dashboard Monitoring Status Website OPD
     */
    public function index(Request $request)
    {
        $query = Dinas::with('klaster');

        // 1. Fitur Pencarian berdasarkan nama dinas atau singkatan
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_dinas', 'like', '%' . $request->search . '%')
                  ->orWhere('singkatan', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Filter Berdasarkan Klaster Dinas
        if ($request->filled('klaster_id')) {
            $query->where('klaster_opd_id', $request->klaster_id);
        }

        // 3. Filter Berdasarkan Status Server (online, warning, offline)
        if ($request->filled('status')) {
            if ($request->status === 'offline') {
                $query->whereIn('status', ['offline', 'pasif']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Ambil daftar dinas terurut dari yang terbaru
        $dinasList = $query->latest()->get();

        // Kalkulasi Statistik Status
        $totalWebsite     = Dinas::count();
        $aktifCount       = Dinas::where('status', 'online')->count();
        $kurangAktifCount = Dinas::where('status', 'warning')->count();
        $pasifCount       = Dinas::whereIn('status', ['offline', 'pasif'])->count();

        // Master data klaster untuk dropdown filter
        $klasters = KlasterOpd::all();

        return view('dashboard', compact(
            'dinasList',
            'klasters',
            'totalWebsite',
            'aktifCount',
            'kurangAktifCount',
            'pasifCount'
        ));
    }

    /**
     * Memeriksa keaktifan seluruh website OPD secara manual via Tombol (dengan Flash Message & Redirect)
     */
    public function checkAllStatus()
    {
        // Panggil logika ping tanpa membuat duplikasi kode
        $this->checkAllStatusWithoutRedirect();

        return redirect()->route('dashboard')->with('success', 'Pengecekan keaktifan website selesai diperbarui!');
    }

    /**
     * Pengecekan HTTP Status via Auto-Sync Background / AJAX (Mengembalikan Response JSON)
     */
    public function checkAllStatusJson()
    {
        // 1. Jalankan proses HTTP Ping di background
        $this->checkAllStatusWithoutRedirect();

        // 2. Ambil data terbaru beserta relasi klaster
        $dinasList = Dinas::with('klaster')->latest()->get();

        // 3. Kembalikan data dalam format JSON
        return response()->json([
            'status'           => 'success',
            'totalWebsite'     => Dinas::count(),
            'aktifCount'       => Dinas::where('status', 'online')->count(),
            'kurangAktifCount' => Dinas::where('status', 'warning')->count(),
            'pasifCount'       => Dinas::whereIn('status', ['offline', 'pasif'])->count(),
            'data'             => $dinasList
        ]);
    }

    /**
     * Private Helper: Inti Logika HTTP Ping ke Semua Website OPD
     */
    private function checkAllStatusWithoutRedirect()
    {
        $dinasList = Dinas::all();

        if ($dinasList->isEmpty()) {
            return;
        }

        // 1. Siapkan URL yang valid untuk setiap dinas
        $validDinas = [];
        foreach ($dinasList as $dinas) {
            $url = trim($dinas->domain_url ?? $dinas->url_website ?? '');

            if (!$url) {
                $dinas->update([
                    'http_status'      => 0,
                    'http_status_code' => 0,
                    'response_time'    => null,
                    'response_time_ms' => null,
                    'last_checked_at'  => now(),
                    'status'           => 'offline',
                ]);
                continue;
            }

            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $url = "https://" . $url;
            }

            $validDinas[] = [
                'model' => $dinas,
                'url'   => $url
            ];
        }

        if (empty($validDinas)) {
            return;
        }

        // 2. Eksekusi HTTP Ping secara Paralel (Asynchronous Concurrent Requests)
        $startTimes = [];
        $responses = Http::pool(function (Pool $pool) use ($validDinas, &$startTimes) {
            $requests = [];
            foreach ($validDinas as $item) {
                $id = $item['model']->id;
                $startTimes[$id] = microtime(true);
                
                $requests[] = $pool->as((string) $id)
                                ->withoutVerifying()
                                ->connectTimeout(5)
                                ->timeout(5)
                                ->get($item['url']);
            }
            return $requests;
        });

        // 3. Olah hasil respon secara bersamaan
        foreach ($validDinas as $item) {
            $dinas = $item['model'];
            $id    = $dinas->id;
            $res   = $responses[(string) $id] ?? null;

            if ($res instanceof \Throwable || $res === null) {
                $dinas->update([
                    'http_status'      => 0,
                    'http_status_code' => 0,
                    'response_time'    => null,
                    'response_time_ms' => null,
                    'last_checked_at'  => now(),
                    'status'           => 'offline',
                ]);
                continue;
            }

            $responseTime = isset($startTimes[$id]) ? round((microtime(true) - $startTimes[$id]) * 1000) : null;
            $statusCode   = $res->status();

            if ($statusCode === 200) {
                $bodyContent = strtolower($res->body());
                $isSuspended = str_contains($bodyContent, 'account suspended') || 
                            str_contains($bodyContent, 'domain parked') ||
                            str_contains($bodyContent, 'cgi-sys/defaultwebpage.cgi');

                $status = $isSuspended ? 'offline' : (($responseTime > 2000) ? 'warning' : 'online');
            } else {
                $status = ($statusCode >= 300 && $statusCode < 400) ? 'warning' : 'offline';
            }

            $dinas->update([
                'http_status'      => $statusCode,
                'http_status_code' => $statusCode,
                'response_time'    => $responseTime,
                'response_time_ms' => $responseTime,
                'last_checked_at'  => now(),
                'status'           => $status,
            ]);
        }
    }
}