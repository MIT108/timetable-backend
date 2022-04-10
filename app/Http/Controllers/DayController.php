<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DayController extends Controller
{

    public function addDay(Request $request){
        $field = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $field += ['user_id' => auth()->user()["id"]];

        if ($this->checkDay($field['name'])) {
            $day = Day::create($field);
            $response = [
                'day' => $day,
                'success' => "Day created successfully"
            ];
        }else{
            $response = [
                'error' => "This day name already exist"
            ];
        }

        return response($response, 200);

    }

    public function updateDay(Request $request){
        $field = $request->validate([
            'day_id' => 'required',
            'name' => '',
            'description' => ''
        ]);

        $day = Day::find($field["day_id"]);

        if ($day) {
            if($field["name"] != null){
                $day->name = $field["name"];
            }
            if($field["description"] != null){
                $day->description = $field["description"];
            }

            $day->save();
            $response = [
                'day' => $day,
                'message' => "day updated successfully"
            ];

        }else{
            $response = [
                'error' => "there is no day with that id"
            ];
        }

        return response($response, 200);

    }

    public function listDays(){
        $days = Day::get();
        $response = [
            'days' => $days,
        ];
        return response($response, 200);
    }

    public function listDaysPerStatus(Request $request){
        $days = Day::where("status", '=', $request->route('status'))->get();
        $response = [
            'days' => $days,
            'success' => "day updated successfully"
        ];
        return response($response, 200);
    }

    public function changeStatus(Request $request){
        $id = $request->route('id');

        $day = Day::find($id);
        if ($day) {
            if ($day->status == 0) {
                $day->status = 1;
            }else {
                $day->status = 0;
            }
            $day->save();

            $response = [
                'day' => $day,
                'message' => "status updated successfully"
            ];
        }else{
            $response = [
                "error" => "no such day found"
            ];
        }

        return response($response, 200);
    }
    public function deleteDay(Request $request){
        if (Day::where("id", '=', $request->route('id'))->delete()) {
            $response = [
                "success" => "delete successfully"
            ];
        }else{
            $response = [
                "error" => "the day does not exist"
            ];
        }
        return response($response, 200);
    }
    public function checkDay($day){
        if (Day::where('name', '=', $day)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }
}
