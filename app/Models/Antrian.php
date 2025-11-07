<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Antrian extends Model
{
    protected $fillable = [
        'loket_id',
        'nomor_antrian',
        'status',
        'waktu_panggil',
    ];

    protected $casts = [
        'waktu_panggil' => 'datetime',
    ];

    /**
     * Get the loket that owns the antrian
     */
    public function loket(): BelongsTo
    {
        return $this->belongsTo(Loket::class);
    }

    /**
     * Generate nomor antrian based on loket
     */
    public static function generateNomorAntrian(int $loketId): string
    {
        // Get the loket prefix (first letter of loket name or use ID)
        $loket = Loket::find($loketId);
        $prefix = strtoupper(substr($loket->nama_loket, 0, 1));
        
        // Get the last antrian number for today for this loket
        $lastAntrian = self::where('loket_id', $loketId)
            ->whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastAntrian) {
            // Extract number from last antrian (e.g., "A001" -> 1)
            $lastNumber = (int) substr($lastAntrian->nomor_antrian, 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format: A001, A002, etc.
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope to get antrians by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get antrians for today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
