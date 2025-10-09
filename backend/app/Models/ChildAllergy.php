<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildAllergy extends Model
{
    use HasFactory;

    // Tabel ini tidak memiliki timestamps (created_at, updated_at)
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'child_id',
        'allergen_group_id',
    ];
}