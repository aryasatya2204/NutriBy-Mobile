<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthRecord extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'child_id',
        'measured_at',
        'weight_kg',
        'length_cm',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Mendefinisikan relasi one-to-one ke GrowthAssessment.
     */
    public function assessment()
    {
        return $this->hasOne(GrowthAssessment::class);
    }
}