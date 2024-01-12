<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'status',
        'month',
        'date',
        'data',
    ];

    protected $casts = [
        'data' => 'json',
    ];
}
