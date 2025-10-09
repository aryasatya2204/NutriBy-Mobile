<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRule extends Model
{
    use HasFactory;

    // Tabel ini hanya memiliki created_at, jadi nonaktifkan updated_at
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rule_key',
        'waz_min',
        'waz_max',
        'haz_min',
        'haz_max',
        'wlz_min',
        'wlz_max',
        'composite_status_is',
        'priority',
        'message_template',
        'is_active',
    ];
}