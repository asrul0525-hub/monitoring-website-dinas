<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinas extends Model
{
    use HasFactory;

    protected $table = 'dinas'; // Pastikan nama tabel sesuai di database

    protected $fillable = [
        'nama_dinas',
        'singkatan',
        'klaster_opd_id',
        'domain_url',
        'status',
        'http_status',
        'response_time',
    ];

    // Relasi ke Model KlasterOpd
    public function klaster()
    {
        return $this->belongsTo(KlasterOpd::class, 'klaster_opd_id');
    }
}
