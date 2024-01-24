<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'date',
        'status',
        'message',
        'use_transportation_expense',
        'transportation_expense_type',
        'transportation_expense',
        'use_shift',
        'shift_start_time',
        'shift_end_time',
        'shift_break_time',
        'use_off_shift',
        'off_shift_qa_time',
        'off_shift_training_time',
        'off_shift_absent_time',
        'off_shift_additional_time',
        'off_shift_break_time_type',
        'off_shift_blog_url',
        'off_shift_notes',
        'use_night_work',
        'night_time',
        'training_type',
        'training_time',
        'note',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
