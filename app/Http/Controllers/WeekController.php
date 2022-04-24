<?php

namespace App\Http\Controllers;

use App\Models\Week;
use Illuminate\Http\Request;

class WeekController extends Controller
{
    //
    public function create(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        $response = [];

        if ($this->checkWeek($fields['name'])) {
            try {
                $week = Week::create($fields);

                $response = [
                    'data' => $week,
                    'message' => 'week created successfully'
                ];
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'error while creating the week'
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

    public function listWeeks()
    {

        $response = [];
        try {
            $weeks = Week::get();
            $response = [
                'data' => $weeks,
                'message' => 'weeks was successfully retrieved'
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list the weeks'
            ];
        }

        return response($response, 200);
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'week_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);

        $response = [];

        $week = week::find($fields['week_id']);

        if ($week) {

            if ($week->name == $fields['name']) {
                try {
                    $week->description = $fields['description'];
                    $week->save();
                    $response = [
                        'data' => $week,
                        'message' => 'week updated successfully'
                    ];
                } catch (\Throwable $th) {
                    $response = [
                        'error' => $th->getMessage(),
                        'message' => 'Could not update the week'
                    ];
                }
            } else {
                if ($this->checkWeek($fields['name'])) {

                    try {
                        $week->name = $fields['name'];
                        $week->description = $fields['description'];
                        $week->save();
                        $response = [
                            'data' => $week,
                            'message' => 'week updated successfully'
                        ];
                    } catch (\Throwable $th) {
                        $response = [
                            'error' => $th->getMessage(),
                            'message' => 'Could not update the week'
                        ];
                    }
                } else {
                    $response = [
                        'error' => 'week name is not available',
                        'message' => 'week name is not available',
                    ];
                }
            }
        } else {
            $response = [
                'message' => 'no week found',
                'error' => 'no week found'
            ];
        }


        return response($response, 200);
    }

    public function delete(Request $request)
    {
        $week_id = $request->route('id');
        $response = [];

        if (Week::find($week_id)) {
            try {
                Week::where('id', $week_id)->delete();
                $response = [
                    'message' => 'Week deleted successfully'
                ];
            } catch (\Throwable $th) {
                $response = [
                    'message' => 'Week could not me deleted',
                    'error' => $th->getMessage(),
                ];
            }
        } else {
            $response = [
                'error' => 'Week does not exist',
                'message' => 'Week does not exist',
            ];
        }

        return response($response, 200);
    }

    public function checkWeek($name)
    {
        if (Week::where('name', $name)->count() > 0) {
            return false;
        } else {
            return true;
        }
    }
}
