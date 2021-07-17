<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use App\User;

class KaryawanController extends Controller
{
    //Show all
    public function index(){
        // $users = User::all();
        $users = DB::table('users')
        ->join('roles', 'users.id_role', '=', 'roles.id')
        ->select('users.*','roles.nama_role as nama_role')->get();

        if(count($users) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $users
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        // $users = User::find($id); //mencari data berdasarkan id
        $users = DB::table('users')
        ->join('roles', 'users.id_role', '=', 'roles.id')
        ->select('users.*','roles.nama_role as nama_role')
        ->where('users.id','=', $id)->get();

        if(!is_null($users)) {
            return response([
            'message' => 'Retrieve Karyawan Success',
            'data' => $users
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'Karyawan Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'id_role' => 'required',
            'name' => 'required|max:50|regex:/^[\pL\s]+$/u',
            'email'=> 'required|email:rfc,dns|unique:users',
            'password'=>'required',
            'jenis_kelamin_karyawan' => 'required|in:Laki-laki,Perempuan',
            'notelp_karyawan' => 'required|min:10|max:13|regex:/(0)(8)[0-9]/',
            'tanggal_gabung_karyawan' => 'required|date|date_format:Y-m-d',
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $storeData['password'] = bcrypt($request->password);
        $users = User::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add Karyawan Success',
            'data' => $users,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $users = User::find($id); //mencari data  berdasarkan id
        // $users = DB::table('users')
        // ->join('roles', 'users.id_role', '=', 'roles.id')
        // ->select('users.*','roles.nama_role as nama_role')
        // ->where('users.id','=', $id)->get();

        if(is_null($users)) {
            return response([
                'message' => 'Karyawan Not Found',
                'data' => null
            ],404);
        }

        if($users->delete()){
            return response([
                'message' => 'Delete Karyawan Success',
                'data' => $users,
            ],200);
        } //return mage seat berhasil menghapus data preduct

        return response([
            'message' => 'Delete Karyawan Falled',
            'data' => null,
        ],400);
    }

    public function update (Request $request, $id){
        //mencari data  berdasarkan id
        $users = User::find($id);
        // $users = DB::table('users')
        // ->join('roles', 'users.id_role', '=', 'roles.id')
        // ->select('users.*','roles.nama_role as nama_role')
        // ->where('users.id','=', $id)->get();

        if(is_null($users)){
            return response([
                'message' => 'Karyawan Not Found',
                'data' => null
            ], 404);
        } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'id_role' => 'numeric',
        'name' => 'max:50|regex:/^[\pL\s]+$/u',
        'email' => '',
        'jenis_kelamin_karyawan' => 'in:Laki-laki,Perempuan',
        'notelp_karyawan' => 'min:10|max:13|regex:/(0)(8)[0-9]/',
        'tanggal_gabung_karyawan' => 'date_format:Y-m-d',
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input

    $users->id_role = $updateData['id_role'];
    $users->name = $updateData['name'];
    $users->email = $updateData['email'];
    $users->jenis_kelamin_karyawan = $updateData['jenis_kelamin_karyawan'];
    $users->notelp_karyawan = $updateData['notelp_karyawan'];
    $users->tanggal_gabung_karyawan = $updateData['tanggal_gabung_karyawan'];

    if($users->save() && $users->save()){
        return response([
            'message' => 'Update Karyawan Success',
            'data' => $users,
        ],200);
    }
    return response([
        'message' => 'Update Karyawan Falled',
        'data' => null,
    ],400);
    }

    public function restore($id)
    {

        /**
         * Find content only among those deleted.
         */

        $users = User::withTrashed()->find($id);

        $users->restore();

        return response()->json($users, 200);

    }
}
