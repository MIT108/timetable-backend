<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Exception;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    //


    public function create(Request $request)
    {
        $field = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'room_id' => 'required|integer'
        ]);

        $response = [];

        if ($this->checkRoom($field['name'])) {
            try {
                $classroom  = ClassRoom::create($field);

                app('App\Http\Controllers\ClassRoomCourseController')->createFromClassRoom($classroom);

                $response = [
                    "data" => $classroom,
                    "message" => "classroom added successfully"
                ];
            } catch (Exception $e) {
                $response = [
                    "error" => $e->getMessage(),
                    "message" => "could not add classroom"
                ];
            }
        } else {
            $response = [
                "error" => "this classroom already exist on this day",
                "message" => "this classroom already exist on this day",
            ];
        }


        return response($response, 200);
    }


    public function listClassRooms()
    {
        $response = [];

        try {
            $classrooms = ClassRoom::join('rooms', 'rooms.id', '=', 'class_rooms.room_id')->get(['class_rooms.*', 'rooms.name as room_name',  'rooms.status as room_status']);

            $response = [
                "data" => $classrooms,
                "message" => "classrooms listed successfully"
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                "message" => "could not list classrooms"
            ];
        }
        return response($response, 200);
    }

    public function listClassRoomsInRoom(Request $request)
    {
        $room_id = $request->route('id');

        try {
            $classrooms = ClassRoom::join('rooms', 'rooms.id', '=', 'class_rooms.room_id')->where('class_rooms.room_id', '=', $room_id)->get(['class_rooms.*', 'rooms.name as room_name',  'rooms.status as room_status']);

            $response = [
                "data" => $classrooms,
                "message" => "classrooms listed successfully"
            ];
        } catch (\Throwable $th) {
            $response = [
                "error" => $th->getMessage(),
                "message" => "could not list classrooms"
            ];
        }
        return response($response, 200);
    }

    public function update(Request $request)
    {

        $fields = $request->validate([
            'classroom_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);

        $classroom = ClassRoom::find($fields['classroom_id']);

        if ($classroom) {

            try {
                if ($fields["name"] != null) {
                    $classroom->name = $fields["name"];
                }
                if ($fields["description"] != null) {
                    $classroom->description = $fields["description"];
                }

                $classroom->save();
                $response = [
                    'data' => $classroom,
                    'message' => "classroom updated successfully"
                ];
            } catch (\Throwable $th) {

                $response = [
                    'error' => $th->getMessage(),
                    'message' => "could not update classroom"
                ];
            }
        } else {
            $response = [
                "error" => "no such classroom",
                "message" => "no such classroom",
            ];
        }
        return response($response, 200);
    }

    public function delete(Request $request)
    {
        $response = [];
        if (ClassRoom::find($request->route('id'))) {
            try {
                ClassRoom::where('id', '=', $request->route('id'))->delete();
                $response = [
                    "message" => "Classroom deleted successfully"
                ];
            } catch (\Throwable $th) {
                $response = [
                    "error" => $th->getMessage(),
                    'message' => 'Could ont delete Classroom'
                ];
            }
        } else {

            $response = [
                "error" => 'Classroom not found',
                'message' => 'Classroom not found'
            ];
        }
        return response($response, 200);
    }
    public function checkRoom($name)
    {
        if (ClassRoom::where('name', '=', $name)->count() > 0) {
            return false;
        } else {
            return true;
        }
    }
}
