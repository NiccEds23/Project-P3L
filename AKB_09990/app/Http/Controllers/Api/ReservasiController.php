<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Reservasi;
use App\Meja;
// use Carbon\Carbon;

class ReservasiController extends Controller
{
    //Show all
    public function index(){
        // $reservasis = Reservasi::all();
        $reservasis = DB::table('reservasis')
        ->leftJoin('customers', 'reservasis.id_customer', '=', 'customers.id')
        ->leftJoin('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->leftJoin('users', 'reservasis.id_karyawan', '=', 'users.id')
        ->select('reservasis.*', 'customers.nama_customer as nama_customer',
        'users.name as nama_karyawan', 'mejas.no_meja as no_meja')->get();

        if(count($reservasis) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $reservasis
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        // $reservasis = Reservasi::find($id);
        $reservasis = DB::table('reservasis')
        ->leftJoin('customers', 'reservasis.id_customer', '=', 'customers.id')
        ->leftJoin('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->leftJoin('users', 'reservasis.id_karyawan', '=', 'users.id')
        ->select('reservasis.*', 'customers.nama_customer as nama_customer',
        'users.name as nama_karyawan', 'mejas.no_meja as no_meja')
        ->where('reservasis.id', '=', $id)->get($id); //mencari data berdasarkan id
        if(!is_null($reservasis)) {
            return response([
            'message' => 'Retrieve Reservasi Success',
            'data' => $reservasis
            ],200);
        } //return data yang ditemukan dalam bentuk json

        return response([
            'message' => 'Reservasi Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'id_customer' => 'required',
        'id_meja' => 'required',
        'id_karyawan' => 'required',
        'sesi_reservasi' => 'required|in:Lunch,Dinner,On The Spot',
        'tanggal_kunjung_reservasi' => 'required|date_format:Y-m-d|after:yesterday',
        'status_reservasi' => 'required|in:Finished,Unfinished'
        ]); // membuat rule validasi input

        // if($storeData['sesi_reservasi'] == 'On The Spot'){
        //     $mejas = DB::table('reservasis')
        //     ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        //     ->where('reservasis.id_meja', '=', $storeData['id_meja'])
        //     ->update(['mejas.status_meja' => 'Unavailable']);

        // }

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $reservasis = Reservasi::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add Reservasi Success',
            'data' => $reservasis,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $reservasis = Reservasi::find($id); //mencari data  berdasarkan id

        if(is_null($reservasis)) {
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
            ],404);
    }
    if($reservasis->delete()){
        return response([
            'message' => 'Delete Reservasi Success',
            'data' => $reservasis,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete Reservasi Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $reservasis = Reservasi::find($id); //mencari data  berdasarkan id
        if(is_null($reservasis)){
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'id_customer' => '',
        'id_meja' => '',
        'sesi_reservasi' => 'in:Lunch,Dinner,On The Spot',
        'tanggal_kunjung_reservasi' => 'date|date_format:Y-m-d|after:yesterday',
        'status_reservasi' => 'in:Finished,Unfinished'
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $reservasis->id_customer = $updateData['id_customer'];
    $reservasis->id_meja = $updateData['id_meja'];
    $reservasis->sesi_reservasi = $updateData['sesi_reservasi'];
    $reservasis->tanggal_kunjung_reservasi = $updateData['tanggal_kunjung_reservasi'];
    $reservasis->status_reservasi = $updateData['status_reservasi'];

    if($reservasis->save()){
        return response([
            'message' => 'Update Reservasi Success',
            'data' => $reservasis,
        ],200);
    }
    return response([
        'message' => 'Update Reservasi Falled',
        'data' => null,
    ],400);
    }
}
