<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAv extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'period_id',
        'week_id',
        'status'
    ];
}
