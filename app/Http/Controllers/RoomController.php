<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Exception;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    //
    public function create(Request $request){
        $field = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'department_id' => 'required|integer'
        ]);

        if ($this->checkDepartment($field['name'], $field['department_id'])) {
            try {
                $Room  = Room::create($field);
                $response = [
                    "data" => $Room,
                    "message" => "Room added successfully"
                ];

            } catch (Exception $e) {
                $response = [
                    "error" => $e->getMessage(),
                    "message" => "could not add room"
                ];
            }
        }else{
            $response = [
                "error" => "this room already exist on this day",
                "message" => "this room already exist on this day",
            ];
        }
        return response($response, 200);
    }


    public function listRooms(){
        $response = [];

        try {
            $rooms = Room::join('departments', 'departments.id', '=' , 'rooms.department_id')->get(['rooms.*', 'departments.name as department_name',  'departments.status as department_status']);

            $response = [
                "data" => $rooms,
                "message" => "Rooms listed successfully"
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                "message" => "could not list courses"
            ];
        }
        return response($response, 200);
    }

    public function listRoomsInDepartment(Request $request){
        $department_id = $request->route('id');

        try {
            $rooms = Room::join('departments', 'departments.id', '=' , 'rooms.department_id')->where('rooms.department_id','=', $department_id)->get(['rooms.*', 'departments.name as department_name',  'departments.status as department_status']);

            $response = [
                "data" => $rooms,
                "message" => "rooms listed successfully"
            ];

        } catch (\Throwable $th) {
            $response = [
                "error" => $th->getMessage(),
                "message" => "could not list rooms"
            ];
        }
        return response($response, 200);
    }

    public function update(Request $request){

        $fields = $request->validate([
            'room_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);

        $Room = Room::find($fields['room_id']);

        if ($Room) {

            try {
                if($fields["name"] != null){
                    $Room->name = $fields["name"];
                }
                if($fields["description"] != null){
                    $Room->description = $fields["description"];
                }

                $Room->save();
                $response = [
                    'data' => $Room,
                    'message' => "Room updated successfully"
                ];
            } catch (\Throwable $th) {

                $response = [
                    'error' => $th->getMessage(),
                    'message' => "could not update room"
                ];
            }
        }else {
            $response = [
                "error" => "no such room",
                "message" => "no such room",
            ];
        }
        return response($response, 200);
    }

    public function delete(Request $request){
        $response = [];

        if(Room::find($request->route('id'))){
            try {
                Room::where('id', '=', $request->route('id'))->delete();
                $response = [
                    "message" => "Room deleted successfully"
                ];
            } catch (\Throwable $th) {
                $response = [
                    "error" => $th->getMessage(),
                    'message' => "Could ont delete room"
                ];
            }

        }else{
            $response = [
                'error' => "no such room",
                'message' => "no such room"
            ];
        }
        return response($response, 200);
    }
    public function checkDepartment($name, $department_id){
        if (Room::where('name', '=', $name)->where('department_id', '=', $department_id)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }
}
