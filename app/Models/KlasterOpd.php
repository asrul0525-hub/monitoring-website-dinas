<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlasterOpd extends Model
{
    use HasFactory;

    protected $table = 'klaster_opds';
    protected $fillable = ['nama_klaster'];

    // Relasi: Satu Klaster memiliki banyak Dinas
    public function dinas()
    {
        return $this->hasMany(Dinas::class, 'klaster_opd_id');
    }
}