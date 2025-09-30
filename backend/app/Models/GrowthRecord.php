<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthRecord extends Model
{
    use HasFactory;

    // Nonaktifkan 'updated_at' karena tabel kita tidak memilikinya
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'child_id',
        'measured_at',
        'weight_kg',
        'length_cm',
    ];

    /**
     * Relasi ke model Child (pemilik).
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}