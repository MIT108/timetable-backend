<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ClassRoomCourse;
use App\Models\Course;
use Illuminate\Http\Request;

class ClassRoomCourseController extends Controller
{
    //
    public function createFromCourse($course){
        $classRooms = ClassRoom::where('room_id', $course->room_id);

        if($classRooms->count() > 0){

            $classRooms = $classRooms->get();

            foreach ($classRooms as $classroom) {
                if (ClassRoomCourse::where('course_id', $course->id)->where('class_room_id', $classroom->id)->count() == 0) {
                    $fields = [
                        'course_id' => $course->id,
                        'hoursDone' => 0,
                        'class_room_id' => $classroom->id
                    ];

                    try {
                        ClassRoomCourse::create($fields);
                    } catch (\Throwable $th) {
                        dd($th->getMessage());
                    }
                }
            }

        }

        return true;
    }

    public function createFromClassRoom($classroom){
        $courses = Course::where('room_id', $classroom->room_id);

        if ($courses->count() > 0) {
            $courses = $courses->get();

            foreach ($courses as $course) {
                if (ClassRoomCourse::where('course_id', $course->id)->where('class_room_id', $classroom->id)->count() == 0) {
                    $fields = [
                        'course_id' => $course->id,
                        'hoursDone' => 0,
                        'class_room_id' => $classroom->id
                    ];

                    try {
                        ClassRoomCourse::create($fields);
                    } catch (\Throwable $th) {
                        dd($th->getMessage());
                    }
                }
            }

        }
        return true;
    }
}
