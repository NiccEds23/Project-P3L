<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\InfoPembayaran;

class InfoPembayaranController extends Controller
{
    //Show all
    public function index(){
        $info_pembayarans = InfoPembayaran::all();

        if(count($info_pembayarans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $info_pembayarans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        $info_pembayarans = InfoPembayaran::find($id); //mencari data berdasarkan id
        if(!is_null($info_pembayarans)) {
            return response([
            'message' => 'Retrieve InfoPembayaran Success',
            'data' => $info_pembayarans
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'InfoPembayaran Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'id_pembayaran' => 'required',
        'jenis_kartu' => 'required',
        'nomor_kartu' => 'required',
        'nama_pemilik_kartu' => 'required',
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $info_pembayarans = InfoPembayaran::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add InfoPembayaran Success',
            'data' => $info_pembayarans,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $info_pembayarans = InfoPembayaran::find($id); //mencari data  berdasarkan id

        if(is_null($info_pembayarans)) {
            return response([
                'message' => 'InfoPembayaran Not Found',
                'data' => null
            ],404);
    }
    if($info_pembayarans->delete()){
        return response([
            'message' => 'Delete InfoPembayaran Success',
            'data' => $info_pembayarans,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete InfoPembayaran Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $info_pembayarans = InfoPembayaran::find($id); //mencari data  berdasarkan id
        if(is_null($info_pembayarans)){
            return response([
                'message' => 'InfoPembayaran Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'id_pembayaran' => '',
        'jenis_kartu' => '',
        'nomor_kartu' => ['', Rule::unique('info_pembayarans')->ignore($info_pembayarans)],
        'nama_pemilik_kartu' => ''
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $info_pembayarans->id_pembayaran = $updateData['id_pembayaran'];
    $info_pembayarans->jenis_kartu = $updateData['jenis_kartu'];
    $info_pembayarans->nomor_kartu = $updateData['nomor_kartu'];
    $info_pembayarans->nama_pemilik_kartu = $updateData['nama_pemilik_kartu'];

    if($info_pembayarans->save()){
        return response([
            'message' => 'Update InfoPembayaran Success',
            'data' => $info_pembayarans,
        ],200);
    }
    return response([
        'message' => 'Update InfoPembayaran Falled',
        'data' => null,
    ],400);
    }
}
