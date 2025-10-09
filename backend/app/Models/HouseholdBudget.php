<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseholdBudget extends Model
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
        'user_id',
        'income_id',
        'method',
        'suggested_monthly_mpasi_budget_idr',
        'committed_monthly_mpasi_budget_idr',
        'calc_version',
        'effective_from',
        'notes',
    ];
}