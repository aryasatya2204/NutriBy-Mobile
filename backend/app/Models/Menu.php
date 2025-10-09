<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Tabel kita tidak memiliki kolom 'updated_at'
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'image_url',
        'age_min_month',
        'age_max_month',
        'texture',
        'instructions',
        'safety_flags',
        'estimated_cost_avg',
    ];

    /**
     * Mendefinisikan relasi many-to-many ke Ingredient melalui tabel pivot 'menu_ingredients'.
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'menu_ingredients');
    }
}