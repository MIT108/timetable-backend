<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;
    protected $fillable=[
        'identifier',
        'week_id', 
        'period_id',
        'course_id',
        'occasion_id',
        'status'
    ];
}
