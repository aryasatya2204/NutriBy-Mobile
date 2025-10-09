<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
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
        'name',
        'category',
        'unit_default',
        'average_price',
        'edible_portion_pct',
        'choking_min_age_month',
        'is_halal',
    ];

    /**
     * Mendefinisikan relasi many-to-many ke AllergenGroup melalui tabel pivot 'ingredient_allergens'.
     */
    public function allergenGroups()
    {
        return $this->belongsToMany(AllergenGroup::class, 'ingredient_allergens');
    }
}