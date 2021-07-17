<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use App\HistoryStokBahan;
use App\Bahan;

class HistoryStokBahanController extends Controller
{
    //Show all
    public function index(){
        $history_stok_bahans = DB::table('history_stok_bahans')
        ->join('bahans', 'history_stok_bahans.id_bahan', '=', 'bahans.id')
        ->join('users', 'history_stok_bahans.id_karyawan', '=', 'users.id')
        ->select('history_stok_bahans.*', "bahans.nama_bahan", "users.name")->get();

        if(count($history_stok_bahans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $history_stok_bahans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    //Show Bahan Masuk
    public function bahanMasuk(){
        // $history_stok_bahans = HistoryStokBahan::all();
        $history_stok_bahans = DB::table('history_stok_bahans')
        ->join('bahans', 'history_stok_bahans.id_bahan', '=', 'bahans.id')
        ->join('users', 'history_stok_bahans.id_karyawan', '=', 'users.id')
        ->where('jumlah_masuk', '!=', null)
        ->select('history_stok_bahans.*', "bahans.nama_bahan", "users.name")->get();

        if(count($history_stok_bahans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $history_stok_bahans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    //Show Bahan Keluar
    public function bahanKeluar(){
        // $history_stok_bahans = HistoryStokBahan::all();
        $history_stok_bahans = DB::table('history_stok_bahans')
        ->join('bahans', 'history_stok_bahans.id_bahan', '=', 'bahans.id')
        ->join('users', 'history_stok_bahans.id_karyawan', '=', 'users.id')
        ->where('jumlah_keluar', '!=', null)
        ->select('history_stok_bahans.*', "bahans.nama_bahan", "users.name")->get();

        if(count($history_stok_bahans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $history_stok_bahans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    //Show Bahan Terbuang
    public function bahanTerbuang(){
        // $history_stok_bahans = HistoryStokBahan::all();
        $history_stok_bahans = DB::table('history_stok_bahans')
        ->join('bahans', 'history_stok_bahans.id_bahan', '=', 'bahans.id')
        ->join('users', 'history_stok_bahans.id_karyawan', '=', 'users.id')
        ->where('jumlah_terbuang', '!=', null)
        ->select('history_stok_bahans.*', "bahans.nama_bahan", "users.name")->get();

        if(count($history_stok_bahans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $history_stok_bahans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        $history_stok_bahans = HistoryStokBahan::find($id); //mencari data berdasarkan id
        if(!is_null($history_stok_bahans)) {
            return response([
            'message' => 'Retrieve HistoryStokBahan Success',
            'data' => $history_stok_bahans
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'HistoryStokBahan Not Found',
        'data' => null
    ],404);
    }

    public function storeBuang(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'id_bahan' => 'required',
        'id_karyawan' => 'required',
        'tanggal_history' => 'required|date|date_format:Y-m-d',
        // 'jumlah_masuk' => 'nullable|numeric',
        // 'jumlah_keluar' => 'nullable|numeric',
        'jumlah_terbuang' => 'nullable|numeric'
        // 'jumlah_sisa' => 'nullable|numeric'
        ]); // membuat rule validasi input

        // if(!is_null($storeData['jumlah_keluar'])){
        //     $bahans = Bahan::find($storeData['id_bahan']);
        //     $bahans->jumlah_bahan = $bahans->jumlah_bahan - $storeData['jumlah_keluar'];
        //     $bahans->save();
        // }

        if(!is_null($storeData['jumlah_terbuang'])){
            $bahans = Bahan::find($storeData['id_bahan']);
            if($bahans->jumlah_bahan < $storeData['jumlah_terbuang'])
                return response(['message' => "Jumlah Bahan Kurang atau Sudah Habis"],406);
            else {
                $bahans->jumlah_bahan = $bahans->jumlah_bahan - $storeData['jumlah_terbuang'];
                $bahans->save();
                $storeData['jumlah_sisa'] = $bahans->jumlah_bahan;

            }
        }

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $history_stok_bahans = HistoryStokBahan::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add HistoryStokBahan Success',
            'data' => $history_stok_bahans,
        ],200); //return data  baru dalam bentuk json
    }

    public function storeKeluar(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'id_bahan' => 'required',
        'id_karyawan' => 'required',
        'tanggal_history' => 'required|date|date_format:Y-m-d',
        // 'jumlah_masuk' => 'nullable|numeric',
        'jumlah_keluar' => 'nullable|numeric',
        // 'jumlah_terbuang' => 'nullable|numeric',
        // 'jumlah_sisa' => 'nullable|numeric'
        ]); // membuat rule validasi input

        if(!is_null($storeData['jumlah_keluar'])){
            $bahans = Bahan::find($storeData['id_bahan']);
            if($bahans->jumlah_bahan < $storeData['jumlah_keluar'])
                return response(['message' => "Jumlah Bahan Kurang atau Sudah Habis"],406);
            else {
                $bahans->jumlah_bahan = $bahans->jumlah_bahan - $storeData['jumlah_keluar'];
                $bahans->save();
                $storeData['jumlah_sisa'] = $bahans->jumlah_bahan;

            }
        }

        // if(!is_null($storeData['jumlah_terbuang'])){
        //     $bahans = Bahan::find($storeData['id_bahan']);
        //     $bahans->jumlah_bahan = $bahans->jumlah_bahan - $storeData['jumlah_terbuang'];
        //     $bahans->save();
        // }

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $history_stok_bahans = HistoryStokBahan::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add HistoryStokBahan Success',
            'data' => $history_stok_bahans,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $history_stok_bahans = HistoryStokBahan::find($id); //mencari data  berdasarkan id

        if(is_null($history_stok_bahans)) {
            return response([
                'message' => 'HistoryStokBahan Not Found',
                'data' => null
            ],404);
    }
    if($history_stok_bahans->delete()){
        return response([
            'message' => 'Delete HistoryStokBahan Success',
            'data' => $history_stok_bahans,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete HistoryStokBahan Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $history_stok_bahans = HistoryStokBahan::find($id); //mencari data  berdasarkan id
        if(is_null($history_stok_bahans)){
            return response([
                'message' => 'HistoryStokBahan Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'id_bahan' => '',
        'id_karyawan' => '',
        'tanggal_history' => 'date|date_format:Y-m-d',
        'jumlah_masuk' => 'nullable|numeric',
        'jumlah_keluar' => 'nullable|numeric',
        'jumlah_terbuang' => 'nullable|numeric',
        'jumlah_sisa' => 'nullable|numeric'
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $history_stok_bahans->id_bahan = $updateData['id_bahan'];
    $history_stok_bahans->id_karyawan = $updateData['id_karyawan'];
    $history_stok_bahans->tanggal_history = $updateData['tanggal_history'];
    $history_stok_bahans->jumlah_masuk = $updateData['jumlah_masuk'];
    $history_stok_bahans->jumlah_keluar = $updateData['jumlah_keluar'];
    $history_stok_bahans->jumlah_terbuang = $updateData['jumlah_terbuang'];
    $history_stok_bahans->jumlah_sisa = $updateData['jumlah_sisa'];

    if($history_stok_bahans->save()){
        return response([
            'message' => 'Update HistoryStokBahan Success',
            'data' => $history_stok_bahans,
        ],200);
    }
    return response([
        'message' => 'Update HistoryStokBahan Falled',
        'data' => null,
    ],400);
    }
}
