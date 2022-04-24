<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //
    public function create(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        $response = [];

        if ($this->checkDepartment($fields['name'])) {
            try {
                $department = Department::create($fields);

                $response = [
                    'data' => $department,
                    'message' => 'Department created successfully'
                ];
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'error while creating the department'
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

    public function listDepartment()
    {

        $response = [];
        try {
            $departments = Department::get();
            $response = [
                'data' => $departments,
                'message' => 'Department was successfully retrieved'
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list the departments'
            ];
        }

        return response($response, 200);
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'department_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);

        $response = [];

        $department = Department::find($fields['department_id']);

        if ($department) {

            if ($department->name == $fields['name']) {
                try {
                    $department->description = $fields['description'];
                    $department->save();
                    $response = [
                        'data' => $department,
                        'message' => 'Department updated successfully'
                    ];
                } catch (\Throwable $th) {
                    $response = [
                        'error' => $th->getMessage(),
                        'message' => 'Could not update the department'
                    ];
                }
            } else {
                if ($this->checkDepartment($fields['name'])) {

                    try {
                        $department->name = $fields['name'];
                        $department->description = $fields['description'];
                        $department->save();
                        $response = [
                            'data' => $department,
                            'message' => 'Department updated successfully'
                        ];
                    } catch (\Throwable $th) {
                        $response = [
                            'error' => $th->getMessage(),
                            'message' => 'Could not update the department'
                        ];
                    }
                } else {
                    $response = [
                        'error' => 'Department name is not available',
                        'message' => 'Department name is not available',
                    ];
                }
            }
        } else {
            $response = [
                'message' => 'no department found',
                'error' => 'no department found'
            ];
        }


        return response($response, 200);
    }

    public function delete(Request $request)
    {
        $department_id = $request->route('id');
        $response = [];

        if (Department::find($department_id)) {
            try {
                Department::where('id', $department_id)->delete();
                $response = [
                    'message' => 'Department deleted successfully'
                ];
            } catch (\Throwable $th) {
                $response = [
                    'message' => 'Department could not me deleted',
                    'error' => $th->getMessage(),
                ];
            }
        } else {
            $response = [
                'error' => 'Department does not exist',
                'message' => 'Department does not exist',
            ];
        }

        return response($response, 200);
    }

    public function checkDepartment($name)
    {
        if (Department::where('name', $name)->count() > 0) {
            return false;
        } else {
            return true;
        }
    }
}
