<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Pembayaran;
use App\Meja;
use App\Customer;
use App\Reservasi;
use App\Karyawan;
// use App\DetailPembayaran;
use App\InfoPembayaran;

class PembayaranController extends Controller
{
    //Show all
    public function index(){
        // $pembayarans = Pembayaran::all();
        $pembayarans = DB::table('pembayarans')
        ->join('reservasis', 'pembayarans.id_reservasi', '=', 'reservasis.id')
        ->join('users', 'pembayarans.id_karyawan', '=', 'users.id')
        ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->join('customers', 'reservasis.id_customer', '=', 'customers.id')
        ->select('pembayarans.*', 'users.name', 'customers.nama_customer', 'mejas.no_meja')
        ->get();

        if(count($pembayarans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pembayarans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function unfinished(){
        // $pembayarans = Pembayaran::all();
        $pembayarans = DB::table('pembayarans')
        ->join('reservasis', 'pembayarans.id_reservasi', '=', 'reservasis.id')
        ->join('users', 'pembayarans.id_karyawan', '=', 'users.id')
        ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->join('customers', 'reservasis.id_customer', '=', 'customers.id')
        ->where('pembayarans.status_pembayaran', '=', 'Unfinished')
        ->select('pembayarans.*', 'users.name', 'customers.nama_customer', 'mejas.no_meja')
        ->get();

        if(count($pembayarans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pembayarans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function finished(){
        // $pembayarans = Pembayaran::all();
        $pembayarans = DB::table('pembayarans')
        ->join('reservasis', 'pembayarans.id_reservasi', '=', 'reservasis.id')
        ->join('users', 'pembayarans.id_karyawan', '=', 'users.id')
        ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->join('customers', 'reservasis.id_customer', '=', 'customers.id')
        ->where('pembayarans.status_pembayaran', '=', 'Finished')
        ->select('pembayarans.*', 'users.name', 'customers.nama_customer', 'mejas.no_meja')
        ->get();

        if(count($pembayarans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pembayarans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        $pembayarans = Pembayaran::find($id); //mencari data berdasarkan id
        if(!is_null($pembayarans)) {
            return response([
            'message' => 'Retrieve Pembayaran Success',
            'data' => $pembayarans
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'Pembayaran Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'id_reservasi' => 'required',
        'id_karyawan' => 'required',
        'kode_pembayaran' => 'required',
        'status_pembayaran' => 'required',
        'jenis_pembayaran' => 'nullable',
        'kode_verifikasi' => 'nullable',
        'jumlah_pembayaran' => 'required',
        'waktu_pembayaran' => 'nullable|date_format:H:i',
        'tanggal_pembayaran' => 'nullable|date|date_format:Y-m-d'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $pembayarans = Pembayaran::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add Pembayaran Success',
            'data' => $pembayarans,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $pembayarans = Pembayaran::find($id); //mencari data  berdasarkan id

        if(is_null($pembayarans)) {
            return response([
                'message' => 'Pembayaran Not Found',
                'data' => null
            ],404);
    }
    if($pembayarans->delete()){
        return response([
            'message' => 'Delete Pembayaran Success',
            'data' => $pembayarans,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete Pembayaran Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $pembayarans = Pembayaran::find($id); //mencari data  berdasarkan id
        if(is_null($pembayarans)){
            return response([
                'message' => 'Pembayaran Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'id_reservasi' => '',
        'id_karyawan' => '',
        'kode_pembayaran' => ['', Rule::unique('pembayarans')->ignore($pembayarans)],
        'status_pembayaran' => '',
        'jenis_pembayaran' => 'nullable',
        'kode_verifikasi' => 'nullable',
        'jumlah_pembayaran' => '',
        'waktu_pembayaran' => 'nullable|date_format:H:i',
        'tanggal_pembayaran' => 'nullable|date|date_format:Y-m-d'
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $pembayarans->id_reservasi = $updateData['id_reservasi'];
    $pembayarans->id_karyawan = $updateData['id_karyawan'];
    $pembayarans->kode_pembayaran = $updateData['kode_pembayaran'];
    $pembayarans->status_pembayaran = $updateData['status_pembayaran'];
    $pembayarans->jenis_pembayaran = $updateData['jenis_pembayaran'];
    $pembayarans->kode_verifikasi = $updateData['kode_verifikasi'];
    $pembayarans->jumlah_pembayaran = $updateData['jumlah_pembayaran'];
    $pembayarans->waktu_pembayaran = $updateData['waktu_pembayaran'];
    $pembayarans->tanggal_pembayaran = $updateData['tannggal_pembayaran'];

    if($pembayarans->save()){
        return response([
            'message' => 'Update Pembayaran Success',
            'data' => $pembayarans,
        ],200);
    }
    return response([
        'message' => 'Update Pembayaran Falled',
        'data' => null,
    ],400);
    }
}
