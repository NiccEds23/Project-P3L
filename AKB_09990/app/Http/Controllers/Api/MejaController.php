<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Meja;
use App\Reservasi;
use Carbon\Carbon;

class MejaController extends Controller
{
    //Show all
    public function index(){
        $reservasis = DB::table('reservasis')
        ->select('reservasis.tanggal_kunjung_reservasi')->get();
        $todayDate = Carbon::now();
        // $todayDate->yesterday();

        // $mejas = DB::table('reservasis')
        // ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        // ->select('mejas.id', 'mejas.no_meja', 'mejas.status_meja', 'reservasis.status_reservasi', 'reservasis.created_at')
        // ->orderBy('reservasis.created_at', 'desc')->get();

        // $mejas = DB::table('mejas')
        // ->update(['mejas.status_meja' => 'Available']);

        $mejas = DB::table('reservasis')
        ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->where('reservasis.status_reservasi', '=', 'Finished')
        // ->orderBy('reservasis.created_at', 'desc')
        ->update(['mejas.status_meja' => 'Available']);

        $mejas = DB::table('reservasis')
        ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->where('reservasis.tanggal_kunjung_reservasi', '=', $todayDate->toDateString())
        ->where('reservasis.status_reservasi', '=', 'Unfinished')
        // ->orderBy('reservasis.created_at', 'desc')
        ->update(['mejas.status_meja' => 'Unavailable']);


        $mejas = Meja::all();
        // $id_meja[] = (array)$idganti;
        // if($reservasis == $todayDate->toDateString()){
            // $mejasUpdate = DB::table('mejas')
            //     ->where('mejas.id',$id_meja->toString())
            //     ->update(['mejas.status_meja' => 'Unavailable']);

        // } else{
        //     $mejasUpdate = DB::table('reservasis')
        //     ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        //     ->update(['mejas.status_meja' => 'Available']);
        // }

        if(count($mejas) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mejas
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }
    //Show meja kosong
    public function mejaKosong(){
        // $mejas = Meja::all();
        $mejas=DB::table('mejas')
        ->where('status_meja', '=', 'Available')->get();

        if(count($mejas) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mejas
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }
    public function show($id){
        $mejas = Meja::find($id); //mencari data berdasarkan id
        if(!is_null($mejas)) {
            return response([
            'message' => 'Retrieve Meja Success',
            'data' => $mejas
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'Meja Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'no_meja' => 'required|regex:/[0-9]/',
        'status_meja' => 'required|in:Available,Unavailable'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $mejas = Meja::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add Meja Success',
            'data' => $mejas,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $mejas = Meja::find($id); //mencari data  berdasarkan id

        if(is_null($mejas)) {
            return response([
                'message' => 'Meja Not Found',
                'data' => null
            ],404);
    }
    if($mejas->delete()){
        return response([
            'message' => 'Delete Meja Success',
            'data' => $mejas,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete Meja Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $mejas = Meja::find($id); //mencari data  berdasarkan id
        if(is_null($mejas)){
            return response([
                'message' => 'Meja Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'no_meja' => ['regex:/[0-9]/', Rule::unique('mejas')->ignore($mejas)],
        'status_meja' => 'in:Available,Unavailable'
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $mejas->no_meja = $updateData['no_meja'];
    $mejas->status_meja = $updateData['status_meja'];

    if($mejas->save()){
        return response([
            'message' => 'Update Meja Success',
            'data' => $mejas,
        ],200);
    }
    return response([
        'message' => 'Update Meja Falled',
        'data' => null,
    ],400);
    }
}
