<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Timetable;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TimetableController extends Controller
{
    //

    public function initialize(){

        $identifier = Str::random(8);

        while ($this->checkIdentifier($identifier)) {
            $identifier = Str::random(8);
        }

        $periods = Period::get();
        $week = Week::where('status', 1);

        $response = [];

        if (count($periods) > 0) {
            if ($week->count() > 0) {

                if (Timetable::where('week_id', $week->get()[0]->id)->count() == 0) {

                    foreach ($periods as $period) {
                        $fields = [
                            'identifier' => $identifier,
                            'week_id' => $week->get()[0]->id,
                            'period_id' => $period->id
                        ];

                        Timetable::create($fields);
                    }

                    $timetable = Timetable::where('identifier', $identifier)->get();
                    $response = [
                        'data' => $timetable,
                        'message' => 'timetable initialized successfully'
                    ];
                }else{
                    $response = [
                        'error' => 'the active week has already been initialized',
                        'message' => 'the active week has already been initialized',
                    ];

                }


            }else{
                $response = [
                    'error' => 'no active weeks found',
                    'message' => 'no active weeks found',
                ];
            }

        }else{
            $response = [
                'error' => 'no period found',
                'message' => 'no period found',
            ];
        }

        return response($response, 200);
    }


    public function checkIdentifier($identifier)
    {
        if (Week::where('identifier', '=', $identifier)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function listTimeTable(){
        $activeWeek = Week::where('status', 1);
        $response = [];
        if ($activeWeek->count() > 0) {

            try {
                $timetable = Timetable::where('week_id', '=', $activeWeek->get()[0]->id)->get();
                $response = [
                    'data' => $timetable,
                    'message' => 'Timetable listed successfully'
                ];
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not list timetable',
                ];
            }

        }else{
            $response = [
                'error' => 'no active weeks found',
                'message' => 'no active weeks found',
            ];
        }

        return response($response, 200);
    }

}
