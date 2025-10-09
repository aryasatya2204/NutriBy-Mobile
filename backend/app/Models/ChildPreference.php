<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildPreference extends Model
{
    use HasFactory;

    // Tabel ini tidak memiliki kolom 'updated_at'
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'child_id',
        'preferable_type',
        'preferable_id',
    ];
}