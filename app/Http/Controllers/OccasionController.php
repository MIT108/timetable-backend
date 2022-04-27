<?php

namespace App\Http\Controllers;

use App\Models\Occasion;
use App\Models\Period;
use App\Models\Timetable;
use App\Models\Week;
use Illuminate\Http\Request;

class OccasionController extends Controller
{
    //
    public function create(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        $response = [];

        if ($this->checkOccasion($fields['name'])) {
            try {
                $occasion = Occasion::create($fields);

                $response = [
                    'data' => $occasion,
                    'message' => 'occasion created successfully'
                ];
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'error while creating the occasion'
                ];
            }
        } else {
            $response = [
                'error' => 'This name is already taken',
                'message' => 'This name is already taken'
            ];
        }

        return response($response, 200);
    }

    public function listOccasions()
    {

        $response = [];
        try {
            $Occasions = Occasion::get();
            $response = [
                'data' => $Occasions,
                'message' => 'Occasion was successfully retrieved'
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list the Occasions'
            ];
        }

        return response($response, 200);
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'occasion_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);

        $response = [];

        $occasion = Occasion::find($fields['occasion_id']);

        if ($occasion) {

            if ($occasion->name == $fields['name']) {
                try {
                    $occasion->description = $fields['description'];
                    $occasion->save();
                    $response = [
                        'data' => $occasion,
                        'message' => 'occasion updated successfully'
                    ];
                } catch (\Throwable $th) {
                    $response = [
                        'error' => $th->getMessage(),
                        'message' => 'Could not update the occasion'
                    ];
                }
            } else {
                if ($this->checkOccasion($fields['name'])) {

                    try {
                        $occasion->name = $fields['name'];
                        $occasion->description = $fields['description'];
                        $occasion->save();
                        $response = [
                            'data' => $occasion,
                            'message' => 'occasion updated successfully'
                        ];
                    } catch (\Throwable $th) {
                        $response = [
                            'error' => $th->getMessage(),
                            'message' => 'Could not update the occasion'
                        ];
                    }
                } else {
                    $response = [
                        'error' => 'occasion name is not available',
                        'message' => 'occasion name is not available',
                    ];
                }
            }
        } else {
            $response = [
                'message' => 'no occasion found',
                'error' => 'no occasion found'
            ];
        }


        return response($response, 200);
    }

    public function delete(Request $request)
    {
        $occasion_id = $request->route('id');
        $response = [];

        if (Occasion::find($occasion_id)) {
            try {
                Occasion::where('id', $occasion_id)->delete();
                $response = [
                    'message' => 'Occasion deleted successfully'
                ];
            } catch (\Throwable $th) {
                $response = [
                    'message' => 'Occasion could not me deleted',
                    'error' => $th->getMessage()
                ];
            }
        } else {
            $response = [
                'error' => 'Occasion does not exist',
                'message' => 'Occasion does not exist'
            ];
        }

        return response($response, 200);
    }

    public function checkOccasion($name)
    {
        if (Occasion::where('name', $name)->count() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function occasionToTimetable(Request $request)
    {
        $fields = $request->validate([
            'occasion_id' => 'required',
            'period_id' => 'required'
        ]);

        $activeWeek = Week::where('status', 1);
        $occasion = Occasion::find($fields['occasion_id']);
        $response = [];

        if ($activeWeek->count() > 0) {
            if (Period::find($fields['period_id'])) {
                if ($occasion) {

                    try {
                        $timetable = Timetable::where('week_id', $activeWeek->get()[0]->id)
                            ->where('period_id', $fields['period_id'])
                            ->update([
                                'occasion_id' => $occasion->id
                            ]);
                        $response = [
                            'data' => $timetable,
                            'message' => 'Timetable updated successfully'
                        ];
                    } catch (\Throwable $th) {
                        $response = [
                            'error' => $th->getMessage(),
                            'message' => 'could not update timetable'
                        ];
                    }
                } else {
                    $response = [
                        'error' => 'Occasion does not exist',
                        'message' => 'Occasion does not exist'
                    ];
                }
            } else {
                $response = [
                    'error' => 'Period does not exist',
                    'message' => 'Period does not exist'
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

    public function removeOccasionToTimetable(Request $request)
    {

        $fields = $request->validate([
            'period_id' => 'required'
        ]);

        $activeWeek = Week::where('status', 1);
        $response = [];

        if ($activeWeek->count() > 0) {
            if (Period::find($fields['period_id'])) {
                try {
                    Timetable::where('week_id', $activeWeek->get()[0]->id)
                        ->where('period_id', $fields['period_id'])
                        ->update([
                            'occasion_id' => Null
                        ]);
                    $timetable = Timetable::where('week_id', $activeWeek->get()[0]->id)
                    ->where('period_id', $fields['period_id'])
                    ->get();
                    $response = [
                        'data' => $timetable,
                        'message' => 'Timetable updated successfully'
                    ];
                } catch (\Throwable $th) {
                    $response = [
                        'error' => $th->getMessage(),
                        'message' => 'could not update timetable'
                    ];
                }
            } else {
                $response = [
                    'error' => 'Period does not exist',
                    'message' => 'Period does not exist'
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
}
