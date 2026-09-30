<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RssSyncService;

class SyncRssCommand extends Command
{
    /**
     * Nama perintah artisan yang dipanggil
     */
    protected $signature = 'rss:sync {dinas_id?}';

    protected $description = 'Sinkronisasi RSS feed OPD/Dinas untuk memeriksa keaktifan postingan';

    public function handle(RssSyncService $rssService)
    {
        $dinasId = $this->argument('dinas_id');

        if ($dinasId) {
            $dinas = \App\Models\Dinas::find($dinasId);
            if ($dinas) {
                $this->info("Memulai sinkronisasi RSS untuk: {$dinas->nama_dinas}...");
                $rssService->syncDinas($dinas);
                $this->info("Selesai!");
            } else {
                $this->error("OPD dengan ID {$dinasId} tidak ditemukan.");
            }
        } else {
            $this->info("Memulai sinkronisasi seluruh RSS OPD...");
            $rssService->syncAll();
            $this->info("Seluruh sinkronisasi RSS selesai!");
        }

        return Command::SUCCESS;
    }
}