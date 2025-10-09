<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsightMessage extends Model
{
    use HasFactory;

    // Tabel ini tidak memiliki 'updated_at' dan 'created_at'
    // karena kita menggunakan 'generated_at'
    public $timestamps = false;

    protected $fillable = [
        'child_id',
        'growth_assessment_id',
        'message_rule_id',
        'message',
    ];
}