<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RssLog extends Model
{
    use HasFactory;

    protected $table = 'rss_logs';

    protected $fillable = [
        'dinas_id',
        'article_title',
        'article_link',
        'published_at',
        'fetch_status',
        'error_message',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relasi ke Dinas
    public function dinas()
    {
        return $this->belongsTo(Dinas::class, 'dinas_id');
    }
}