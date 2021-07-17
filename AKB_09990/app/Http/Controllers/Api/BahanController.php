<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Bahan;

class BahanController extends Controller
{
    //Show all
    public function index(){
        $bahans = Bahan::all();

        if(count($bahans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bahans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    //Show kosong
    public function bahanKosong(){
        // $bahans = Bahan::all();

        $bahans = DB::table('bahans')
        ->where('bahans.jumlah_bahan', '=', '0')
        ->get();

        if(count($bahans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bahans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        $bahans = Bahan::find($id); //mencari data berdasarkan id
        if(!is_null($bahans)) {
            return response([
            'message' => 'Retrieve Bahan Success',
            'data' => $bahans
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'Bahan Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'nama_bahan' => 'required|max:30',
        'jumlah_bahan' => 'nullable|numeric',
        'unit_bahan' => 'required|max:10'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $bahans = Bahan::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add Bahan Success',
            'data' => $bahans,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $bahans = Bahan::find($id); //mencari data  berdasarkan id

        if(is_null($bahans)) {
            return response([
                'message' => 'Bahan Not Found',
                'data' => null
            ],404);
    }
    if($bahans->delete()){
        return response([
            'message' => 'Delete Bahan Success',
            'data' => $bahans,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete Bahan Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $bahans = Bahan::find($id); //mencari data  berdasarkan id
        if(is_null($bahans)){
            return response([
                'message' => 'Bahan Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'nama_bahan' => ['max:30', Rule::unique('bahans')->ignore($bahans)],
        'unit_bahan' => 'max:10'
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $bahans->nama_bahan = $updateData['nama_bahan'];
    $bahans->unit_bahan = $updateData['unit_bahan'];

    if($bahans->save()){
        return response([
            'message' => 'Update Bahan Success',
            'data' => $bahans,
        ],200);
    }
    return response([
        'message' => 'Update Bahan Falled',
        'data' => null,
    ],400);
    }
}
