<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GrowthRecord;

class Child extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sex',
        'birth_date',
        'user_id', 
    ];

    /**
     * Relasi ke model User (pemilik).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model GrowthRecord.
     */
    public function growthRecords()
    {
        return $this->hasMany(GrowthRecord::class);
    }
}