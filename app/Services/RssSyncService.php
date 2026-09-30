<?php

namespace App\Services;

use App\Models\Dinas;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RssSyncService
{
    public function syncAll()
    {
        $dinasList = Dinas::whereNotNull('url_website')->get();

        foreach ($dinasList as $dinas) {
            $this->syncDinas($dinas);
        }
    }

    public function syncDinas(Dinas $dinas)
    {
        $url = $dinas->url_website ?: $dinas->url_domain;

        if (empty($url)) {
            return;
        }

        // Pastikan URL diawali http:// atau https://
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }

        $startTime = microtime(true);
        $statusCode = null;
        $responseTimeMs = null;
        $status = 'pasif';

        try {
            // Lakukan HTTP Request ringan dengan timeout 5 detik
            $response = Http::timeout(5)
                ->withoutVerifying() // Abaikan jika ada issue SSL sertifikat
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) MonitoringBot/1.0',
                ])
                ->get($url);

            $responseTimeMs = round((microtime(true) - $startTime) * 1000);
            $statusCode = $response->status();

            // Jika mengembalikan respon sukses (2xx) atau redirect (3xx)
            if ($response->successful() || $response->redirect()) {
                $status = 'aktif';
            } else {
                $status = 'pasif';
            }
        } catch (\Exception $e) {
            // Jika Timeout, Connection Refused, atau DNS Error
            $statusCode = 0; // Menandakan Unreachable / Connection Timeout
            $responseTimeMs = null;
            $status = 'pasif';
            Log::warning("Website OPD Unreachable [{$url}]: " . $e->getMessage());
        }

        // Update data status server dinas
        $dinas->status = $status;
        $dinas->http_status_code = $statusCode;
        $dinas->response_time_ms = $responseTimeMs;
        $dinas->last_checked_at = Carbon::now();
        $dinas->save();

        // Buat notifikasi jika website offline
        if ($status === 'pasif') {
            $this->createNotification(
                $dinas,
                'danger',
                'Website OPD Tidak Dapat Diakses',
                "Server website {$dinas->nama_dinas} ({$url}) mengalami offline/unreachable."
            );
        }
    }

    private function createNotification(Dinas $dinas, string $type, string $title, string $message)
    {
        $exists = Notifikasi::where('dinas_id', $dinas->id)
            ->where('title', $title)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->exists();

        if (!$exists) {
            Notifikasi::create([
                'dinas_id' => $dinas->id,
                'type'     => $type,
                'title'    => $title,
                'message'  => $message,
                'is_read'  => false,
            ]);
        }
    }
}
