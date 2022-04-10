<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Exception;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    //
    public function addPeriod(Request $request){
        $field = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'day_id' => 'required|integer'
        ]);

        if ($this->checkPeriod($field['name'], $field['day_id'])) {
            try {
                $period  = Period::create($field);
                $response = [
                    "period" => $period,
                    "success" => "period added successfully"
                ];

            } catch (Exception $e) {
                $response = [
                    "error" => $e->getMessage(),
                ];
            }
        }else{
            $response = [
                "error" => "this period already exist on this day",
            ];
        }
        return response($response, 200);
    }


    public function listPeriods(){
        $periods = Period::join('days', 'days.id', '=' , 'periods.day_id')->get(['periods.*', 'days.name as day_name',  'days.status as day_status']);
        $response = [
            "periods" => $periods
        ];
        return response($response, 200);
    }

    public function listPeriodsForDay(Request $request){
        $day_id = $request->route('day_id');
        $periods = Period::join('days', 'days.id', '=' , 'periods.day_id')->where('periods.day_id','=', $day_id)->get(['periods.*', 'days.name as day_name',  'days.status as day_status']);

        $response = [
            "periods" => $periods,
        ];
        return response($response, 200);
    }

    public function updatePeriod(Request $request){

        $field = $request->validate([
            'period_id' => 'required',
            'name' => '',
            'description' => ''
        ]);

        $period = Period::find($field['period_id']);

        if ($period) {
            if($field["name"] != null){
                $period->name = $field["name"];
            }
            if($field["description"] != null){
                $period->description = $field["description"];
            }

            $period->save();
            $response = [
                'period' => $period,
                'message' => "period updated successfully"
            ];
        }else {
            $response = [
                "error" => "no such period",
            ];
        }
        return response($response, 200);
    }

    public function deletePeriod(Request $request){
        if (Period::where('id', '=', $request->route('id'))->delete()){
            $response = [
                "message" => "Period deleted successfully"
            ];
        }else{
            $response = [
                "error" => "no such period"
            ];
        }
        return response($response, 200);
    }
    public function checkPeriod($name, $day_id){
        if (Period::where('name', '=', $name)->where('day_id', '=', $day_id)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }
}
