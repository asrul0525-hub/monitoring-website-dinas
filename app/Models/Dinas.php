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
        'http_status',
        'response_time',
        'http_status_code',
        'response_time_ms',
        'last_checked_at',
        'pic_nama',
        'status',
    ];

    public function klaster()
    {
        return $this->belongsTo(KlasterOpd::class, 'klaster_opd_id');
    }
}