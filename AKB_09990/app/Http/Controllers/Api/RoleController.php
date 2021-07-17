<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Role;

class RoleController extends Controller
{
    //Show all
    public function index(){
        $roles = Role::all();

        if(count($roles) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $roles
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        $roles = Role::find($id); //mencari data berdasarkan id
        if(!is_null($roles)) {
            return response([
            'message' => 'Retrieve Role Success',
            'data' => $roles
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'Role Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'nama_role' => 'required'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $roles = Role::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add Role Success',
            'data' => $roles,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $roles = Role::find($id); //mencari data  berdasarkan id

        if(is_null($roles)) {
            return response([
                'message' => 'Role Not Found',
                'data' => null
            ],404);
    }
    if($roles->delete()){
        return response([
            'message' => 'Delete Role Success',
            'data' => $roles,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete Role Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $roles = Role::find($id); //mencari data  berdasarkan id
        if(is_null($roles)){
            return response([
                'message' => 'Role Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'nama_role' => ['required', Rule::unique('roles')->ignore($roles)]
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $roles->nama_role = $updateData['nama_role'];

    if($roles->save()){
        return response([
            'message' => 'Update Role Success',
            'data' => $roles,
        ],200);
    }
    return response([
        'message' => 'Update Role Falled',
        'data' => null,
    ],400);
    }
}
