<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loket extends Model
{
    protected $fillable = [
        'nama_loket',
        'deskripsi',
    ];

    /**
     * Get all antrians for this loket
     */
    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }

    /**
     * Get waiting antrians for this loket
     */
    public function antrianMenunggu(): HasMany
    {
        return $this->hasMany(Antrian::class)->where('status', 'menunggu')->orderBy('created_at');
    }

    /**
     * Get currently called antrian for this loket
     */
    public function antrianDipanggil(): HasMany
    {
        return $this->hasMany(Antrian::class)->where('status', 'dipanggil');
    }
}
