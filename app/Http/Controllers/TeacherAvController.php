<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\TeacherAv;
use App\Models\User;
use App\Models\Week;
use Illuminate\Http\Request;

class TeacherAvController extends Controller
{
    //
    public function create(Request $request)
    {
        $fields = $request->validate([
            'period_id' => 'required',
            'status' => 'required',
        ]);

        $user_id =  auth()->user()["id"];
        $fields += ['user_id' => $user_id];

        $response = [];

        $activeWeek = Week::where('status', 1);

        if ($activeWeek->count() > 0) {
            if ($fields['status'] == 1 || $fields['status'] == 0) {
                if (Period::find($fields['period_id'])) {
                    if ($this->check($activeWeek->get()[0]->id, $fields['period_id'], $user_id)) {
                        try {
                            $fields += ['week_id' => $activeWeek->get()[0]->id];
                            $teacherAvailability = TeacherAv::create($fields);
                            $response = [
                                'data' => $teacherAvailability,
                                'message' => 'Teacher availability successfully created'
                            ];
                        } catch (\Throwable $th) {
                            $response = [
                                'error' => $th->getMessage(),
                                'message' => 'Teacher availability could not be created'
                            ];
                        }
                    } else {
                        $response = [
                            'error' => 'this action has already been performed',
                            'message' => 'this action has already been performed'
                        ];
                    }
                } else {
                    $response = [
                        'error' => 'No such period',
                        'message' => 'No such period'
                    ];
                }
            } else {
                $response = [
                    'error' => 'The status can only be one 0 or 1',
                    'message' => 'The status can only be one 0 or 1'
                ];
            }
        } else {
            $response = [
                'error' => 'No active week found',
                'message' => 'No active week found'
            ];
        }

        return response($response, 200);
    }

    public function check($week_id, $period_id, $user_id)
    {
        if (TeacherAv::where('week_id', $week_id)->where('period_id', $period_id)->where('user_id', $user_id)->count() == 0) {
            return true;
        } else {
            return false;
        }
    }
}
