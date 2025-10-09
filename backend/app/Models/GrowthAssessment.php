<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthAssessment extends Model
{
    use HasFactory;

    // Tabel ini tidak memiliki 'updated_at'
    const UPDATED_AT = null;

    protected $fillable = [
        'growth_record_id',
        'child_id',
        'assessed_at',
        'z_waz',
        'z_haz',
        'z_wlz',
        'status_waz',
        'status_haz',
        'status_wlz',
        'composite_status',
    ];

    /**
     * Mendefinisikan relasi one-to-one ke InsightMessage.
     */
    public function insightMessage()
    {
        return $this->hasOne(InsightMessage::class);
    }
}