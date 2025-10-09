<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancePolicy extends Model
{
    use HasFactory;

    // Tabel ini tidak memiliki 'updated_at'
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'calc_version',
        'food_share_pct',
        'mpasi_share_pct_per_child',
        'lower_cap_idr',
        'upper_cap_idr',
    ];
}