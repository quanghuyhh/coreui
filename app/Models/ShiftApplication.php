<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id',
        'user_id',
        'status',
        'data',
    ];

    protected $casts = [
        'data' => 'json',
    ];
}
