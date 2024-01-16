<?php

namespace App\Models;

use App\Enums\ShiftStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'status',
        'month',
        'data',
    ];

    protected $casts = [
        'month' => 'date',
        'data' => 'json',
    ];

    public function scopePublished(Builder $builder)
    {
        $table = $this->getTable();
        return $builder->where("$table.status", ShiftStatusEnum::COMPLETED->value);
    }

    public function appliers()
    {
        return $this->belongsToMany(
            User::class,
            ShiftApplication::class,
        )->withPivot(['id', 'user_id', 'shift_id', 'data']);
    }
}
