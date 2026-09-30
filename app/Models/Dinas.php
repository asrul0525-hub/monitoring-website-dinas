<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinas extends Model
{
    use HasFactory;

    protected $table = 'dinas';

    protected $fillable = [
        'nama_dinas',
        'url_website',
        'klaster',
        'status',
        'http_status_code',
        'response_time_ms',
        'last_checked_at',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
    ];

    // Relasi ke Klaster OPD
    public function klaster()
    {
        return $this->belongsTo(KlasterOpd::class, 'klaster_opd_id');
    }

    // Relasi ke RSS Logs
    public function rssLogs()
    {
        return $this->hasMany(RssLog::class, 'dinas_id');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'dinas_id');
    }
}
