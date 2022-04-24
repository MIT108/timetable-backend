<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Exception;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //
    public function create(Request $request){
        $field = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'hours' => 'required|integer',
            'room_id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);

        $response = [];

        if ($field['hours'] > 0) {
            if ($this->checkRoom($field['name'], $field['room_id'])) {
                try {
                    $course  = Course::create($field);

                    app('App\Http\Controllers\ClassRoomCourseController')->createFromCourse($course);

                    $response = [
                        "data" => $course,
                        "message" => "course added successfully"
                    ];

                } catch (Exception $e) {
                    $response = [
                        "error" => $e->getMessage(),
                        "message" => "could not add course"
                    ];
                }
            }else{
                $response = [
                    "error" => "this course already exist on this day",
                    "message" => "this course already exist on this day",
                ];
            }

        }else{
            $response = [
                'error' => 'Number of hours should be greater than 0',
                'message' => 'Number of hours should be greater than 0'
            ];
        }
        return response($response, 200);
    }


    public function listCourses(){
        $response = [];

        try {
            $Courses = Course::join('rooms', 'rooms.id', '=' , 'courses.room_id')->get(['courses.*', 'rooms.name as room_name',  'rooms.status as room_status']);

            $response = [
                "data" => $Courses,
                "message" => "courses listed successfully"
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                "message" => "could not list courses"
            ];
        }
        return response($response, 200);
    }

    public function listCourseInRoom(Request $request){
        $room_id = $request->route('id');

        try {
            $Courses = Course::join('rooms', 'rooms.id', '=' , 'courses.room_id')->where('courses.room_id','=', $room_id)->get(['courses.*', 'rooms.name as room_name',  'rooms.status as room_status']);

            $response = [
                "data" => $Courses,
                "message" => "courses listed successfully"
            ];

        } catch (\Throwable $th) {
            $response = [
                "error" => $th->getMessage(),
                "message" => "could not list courses"
            ];
        }
        return response($response, 200);
    }

    public function update(Request $request){

        $fields = $request->validate([
            'course_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);

        $course = Course::find($fields['course_id']);

        if ($course) {

            try {
                if($fields["name"] != null){
                    $course->name = $fields["name"];
                }
                if($fields["description"] != null){
                    $course->description = $fields["description"];
                }

                $course->save();
                $response = [
                    'course' => $course,
                    'message' => "course updated successfully"
                ];
            } catch (\Throwable $th) {

                $response = [
                    'error' => $th->getMessage(),
                    'message' => "could not update course"
                ];
            }
        }else {
            $response = [
                "error" => "no such course",
                "message" => "no such course",
            ];
        }
        return response($response, 200);
    }

    public function delete(Request $request){
        $response = [];
        if (Course::find( $request->route('id'))) {
            try {
                Course::where('id', '=', $request->route('id'))->delete();
                $response = [
                    "message" => "Course deleted successfully"
                ];
            } catch (\Throwable $th) {
                $response = [
                    "error" => $th->getMessage(),
                    'message' => 'Could ont delete course'
                ];
            }
        }else{

            $response = [
                "error" => 'course not found',
                'message' => 'course not found'
            ];
        }
        return response($response, 200);
    }
    public function checkRoom($name, $room_id){
        if (Course::where('name', '=', $name)->where('room_id', '=', $room_id)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }
}
