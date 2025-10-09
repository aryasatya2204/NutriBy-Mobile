<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AllergenGroup;

class AllergyFact extends Model
{
    use HasFactory;

    // Tabel ini tidak memiliki 'updated_at'
    const UPDATED_AT = null;

    protected $fillable = [
        'allergen_group_id',
        'title',
        'content',
        'symptoms',
        'triggers',
    ];

    /**
     * Mendefinisikan relasi ke model AllergenGroup.
     */
    public function allergenGroup()
    {
        return $this->belongsTo(AllergenGroup::class);
    }
}