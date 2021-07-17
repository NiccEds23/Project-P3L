<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use App\StokBahanMasuk;
use App\HistoryStokBahan;
use App\Bahan;

class StokBahanMasukController extends Controller
{
    //Show all
    public function index(){
        // $stok_bahan_masuks = StokBahanMasuk::all();


        $stok_bahan_masuks = DB::table('stok_bahan_masuks')
        ->join('bahans', 'stok_bahan_masuks.id_bahan', '=', 'bahans.id')
        ->join('users', 'stok_bahan_masuks.id_karyawan', '=', 'users.id')
        ->select('stok_bahan_masuks.*', 'bahans.nama_bahan', 'users.name')->get();

        if(count($stok_bahan_masuks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $stok_bahan_masuks
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }


    public function show($id){
        $stok_bahan_masuks = StokBahanMasuk::find($id); //mencari data berdasarkan id
        if(!is_null($stok_bahan_masuks)) {
            return response([
            'message' => 'Retrieve StokBahanMasuk Success',
            'data' => $stok_bahan_masuks
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'StokBahanMasuk Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'id_bahan' => 'required',
        'id_karyawan' => 'required',
        'jumlah_masuk' => 'required|numeric',
        'unit_stok_bahan' => 'required',
        'harga_stok_bahan' => 'required',
        'tanggal_history' => 'required|date|date_format:Y-m-d'
        ]); // membuat rule validasi input

        // $bahans = DB::table('bahans')
        // ->join('stok_bahan_masuks', 'stok_bahan_masuks.id_bahan', '=', 'bahans.id')
        // ->where('stok_bahan_masuks.id_bahan', '=', $storeData['id_bahan'])
        // ->update(['bahans.jumlah_bahan' => ((int)'bahans.jumlah_bahan' + $storeData['jumlah_masuk'])]);


        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $history_stok_bahans = HistoryStokBahan::create($storeData); //menambah data history
        $stok_bahan_masuks = StokBahanMasuk::create($storeData); //menambah data  baru
        $bahans = Bahan::find($storeData['id_bahan']);
        $bahans->jumlah_bahan = $bahans->jumlah_bahan + $storeData['jumlah_masuk'];
        $bahans->save();
        $history_sisa = HistoryStokBahan::find($history_stok_bahans->id);
        $history_sisa->jumlah_sisa = $bahans->jumlah_bahan;
        $history_sisa->save();
        return response([
            'message' => 'Add StokBahanMasuk Success',
            'data' => $stok_bahan_masuks,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $stok_bahan_masuks = StokBahanMasuk::find($id); //mencari data  berdasarkan id

        if(is_null($stok_bahan_masuks)) {
            return response([
                'message' => 'StokBahanMasuk Not Found',
                'data' => null
            ],404);
    }
    if($stok_bahan_masuks->delete()){
        return response([
            'message' => 'Delete StokBahanMasuk Success',
            'data' => $stok_bahan_masuks,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete StokBahanMasuk Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $stok_bahan_masuks = StokBahanMasuk::find($id); //mencari data  berdasarkan id
        if(is_null($stok_bahan_masuks)){
            return response([
                'message' => 'StokBahanMasuk Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'id_bahan' => '',
        // 'id_karyawan' => '',
        'jumlah_masuk' => 'numeric',
        'unit_stok_bahan' => '',
        'harga_stok_bahan' => '',
        'tanggal_history' => 'date|date_format:Y-m-d'
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input

    $bahans = Bahan::find($stok_bahan_masuks->id_bahan);
    $bahans->jumlah_bahan = $bahans->jumlah_bahan - $stok_bahan_masuks->jumlah_masuk;
    $bahans->save();

    // $history_stok_bahans = HistoryStokBahan::find($stok_bahan_masuks->id_bahan)
    // ->where('history_stok_bahans.jumlah_masuk', '=', $stok_bahan_masuks->jumlah_masuk)
    // ->where('history_stok_bahans.tanggal_history', '=', $stok_bahan_masuks->tanggal_history)
    // ->get();

    $stok_bahan_masuks->id_bahan = $updateData['id_bahan'];
    // $stok_bahan_masuks->id_karyawan = $updateData['id_karyawan'];
    $stok_bahan_masuks->jumlah_masuk = $updateData['jumlah_masuk'];
    $stok_bahan_masuks->unit_stok_bahan = $updateData['unit_stok_bahan'];
    $stok_bahan_masuks->harga_stok_bahan = $updateData['harga_stok_bahan'];
    // $stok_bahan_masuks->tanggal_masuk_stok_bahan = $updateData['tanggal_masuk_stok_bahan'];

    $bahans = Bahan::find($updateData['id_bahan']);
    $bahans->jumlah_bahan = $bahans->jumlah_bahan + $updateData['jumlah_masuk'];
    $bahans->save();

    // $history_stok_bahans->jumlah_masuk = $updateData['jumlah_masuk'];
    // $history_stok_bahans->

    if($stok_bahan_masuks->save()){
        return response([
            'message' => 'Update StokBahanMasuk Success',
            'data' => $stok_bahan_masuks,
        ],200);
    }
    return response([
        'message' => 'Update StokBahanMasuk Falled',
        'data' => null,
    ],400);
    }
}
