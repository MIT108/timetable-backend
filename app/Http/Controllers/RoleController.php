<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function create(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        $response = [];

        if ($this->checkRoleName($fields['name'])) {

            try {
                $role = Role::create($fields);
                $response = [
                    'data' => $role,
                    'message' => 'Role created successfully'
                ];
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not create role'
                ];
            }

        }else {
            $response = [
                'error' => 'this role name has already been assigned',
                'message' => 'Role name has already been assigned'
            ];
        }

        return response($response, 200);
    }

    public function update(Request $request){
        $fields = $request->validate([
            'role_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'order' => 'required'
        ]);
        if ($fields['role_id'] != 1) {
            $role = Role::find($fields['role_id']);
            $response = [];
            if ($role) {
                if($this->checkOrder($fields['order'])){
                    if ($role->name == $fields['name']) {
                        try {
                            $role->order = $fields["order"];
                            $role->description = $fields["description"];
                            $role->save();

                            $response = [
                                'data' => $role,
                                'message' => 'role update was successful'
                            ];
                        } catch (\Throwable $th) {
                            $response = [
                                'error' => $th->getMessage(),
                                'message' => 'could not update role'
                            ];
                        }
                    }else{
                        if ($this->checkRoleName($fields['name'])) {
                            try {
                                $role->order = $fields["order"];
                                $role->name = $fields["name"];
                                $role->description = $fields["description"];
                                $role->save();

                                $response = [
                                    'data' => $role,
                                    'message' => 'role update was successful'
                                ];

                            } catch (\Throwable $th) {
                                $response = [
                                    'error' => $th->getMessage(),
                                    'message' => 'could not update role'
                                ];
                            }

                        }else {
                            $response = [
                                'error' => 'this role name has already been assigned',
                                'message' => 'this role name has already been assigned'
                            ];
                        }
                    }

                }else{
                    $response = [
                        'error' => 'this role priority has already been used',
                        'message' => 'this role priority has already been used'
                    ];

                }
            }else {
                $response = [
                    'error' => 'role does not exit',
                    'message' => 'role does not exit'
                ];
            }
        }else{
            $response = [
                'error' => 'could not update role',
                'message' => 'could not update role'
            ];
        }

        return response($response, 200);
    }

    public function listAll(){
        $response = [];
        try {
            $roles = Role::where('id', '!=', 1)->get();
            $response = [
                'data' => $roles,
                'message' => 'role list was successful'
            ];
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list roles'
            ];
        }
        return response($response, 200);
    }

    public function delete(Request $request){
        $response = [];
        if ($request->route('id') != 1) {
            try {
                Role::where('id', $request->route('id'))->delete();
                $response = [
                    'message' => 'role delete was successful'
                ];
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not delete role'
                ];
            }

        }else{
            $response = [
                'error' => 'cant delete this role',
                'message' => 'Cant delete this role'
            ];
        }

        return response($response, 200);
    }

    public function checkRoleName($name){
        if (Role::where('name', $name)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }

    public function checkOrder($order){
        if (Role::where('order', $order)->count() == 0) {
            return true;
        }else {
            return false;
        }
    }
}
