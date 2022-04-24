<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoomCourse extends Model
{
    use HasFactory;
    protected $fillable=[
        'hoursDone',
        'class_room_id',
        'course_id',
        'status'
    ];
}
