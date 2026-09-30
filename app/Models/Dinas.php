<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinas extends Model
{
    use HasFactory;

    protected $table = 'dinas';
    
    protected $fillable = [
        'klaster_opd_id',
        'nama_dinas',
        'singkatan',
        'domain_url',
        'rss_url',
        'pic_nama',
        'last_post_title',
        'last_post_link',
        'last_post_date',
        'status',
    ];

    protected $casts = [
        'last_post_date' => 'datetime',
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
}