<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergenGroup extends Model
{
    use HasFactory;

    // Tabel ini tidak punya timestamps default (created_at, updated_at)
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'image_url',
    ];
}