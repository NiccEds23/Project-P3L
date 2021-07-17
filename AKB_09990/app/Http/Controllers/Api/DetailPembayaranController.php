<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use App\DetailPembayaran;
use App\Pembayaran;
use App\Reservasi;
use App\Meja;
use App\Customer;
use App\Menu;

class DetailPembayaranController extends Controller
{
    //Show all
    public function index(){
        // $detail_pembayarans = DetailPembayaran::all();
        $detail_pembayarans = DB::table('detail_pembayarans')
        ->join('pembayarans', 'detail_pembayarans.id_pembayaran', '=', 'pembayarans.id')
        ->join('menus', 'detail_pembayarans.id_menu', '=', 'menus.id')
        ->join('reservasis', 'pembayarans.id_reservasi', '=', 'reservasis.id')
        ->join('customers', 'reservasis.id_customer', '=', 'customers.id')
        ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->select('detail_pembayarans.*', 'pembayarans.kode_pembayaran', 'mejas.no_meja', 'customers.nama_customer', 'menus.nama_menu')->get();

        if(count($detail_pembayarans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detail_pembayarans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    //show Cook
    public function cook(){
        // $detail_pembayarans = DetailPembayaran::all();
        $detail_pembayarans = DB::table('detail_pembayarans')
        ->join('pembayarans', 'detail_pembayarans.id_pembayaran', '=', 'pembayarans.id')
        ->join('menus', 'detail_pembayarans.id_menu', '=', 'menus.id')
        ->join('reservasis', 'pembayarans.id_reservasi', '=', 'reservasis.id')
        ->join('customers', 'reservasis.id_customer', '=', 'customers.id')
        ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->where('detail_pembayarans.status_pesanan', '=', 'Cook')
        ->select('detail_pembayarans.*', 'pembayarans.kode_pembayaran', 'mejas.no_meja', 'customers.nama_customer', 'menus.nama_menu')->get();

        if(count($detail_pembayarans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detail_pembayarans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    //show Cook
    public function ready(){
        // $detail_pembayarans = DetailPembayaran::all();
        $detail_pembayarans = DB::table('detail_pembayarans')
        ->join('pembayarans', 'detail_pembayarans.id_pembayaran', '=', 'pembayarans.id')
        ->join('menus', 'detail_pembayarans.id_menu', '=', 'menus.id')
        ->join('reservasis', 'pembayarans.id_reservasi', '=', 'reservasis.id')
        ->join('customers', 'reservasis.id_customer', '=', 'customers.id')
        ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->where('detail_pembayarans.status_pesanan', '=', 'Ready')
        ->select('detail_pembayarans.*', 'pembayarans.kode_pembayaran', 'mejas.no_meja', 'customers.nama_customer', 'menus.nama_menu')->get();

        if(count($detail_pembayarans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detail_pembayarans
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        $detail_pembayarans = DetailPembayaran::find($id); //mencari data berdasarkan id
        if(!is_null($detail_pembayarans)) {
            return response([
            'message' => 'Retrieve DetailPembayaran Success',
            'data' => $detail_pembayarans
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'DetailPembayaran Not Found',
        'data' => null
    ],404);
    }

    public function pesanan($id){
        // $detail_pembayarans = DetailPembayaran::find($id); //mencari data berdasarkan id
        // $detail_pembayarans = DB::table('detail_pembayarans')
        // ->where('detail_pembayarans.id_pembayaran', '=', $id)
        // ->get();

        $detail_pembayarans = DB::table('detail_pembayarans')
        ->join('pembayarans', 'detail_pembayarans.id_pembayaran', '=', 'pembayarans.id')
        ->join('menus', 'detail_pembayarans.id_menu', '=', 'menus.id')
        ->join('reservasis', 'pembayarans.id_reservasi', '=', 'reservasis.id')
        ->join('customers', 'reservasis.id_customer', '=', 'customers.id')
        ->join('mejas', 'reservasis.id_meja', '=', 'mejas.id')
        ->where('detail_pembayarans.id_pembayaran', '=', $id)
        ->select('detail_pembayarans.*', 'pembayarans.kode_pembayaran', 'mejas.no_meja', 'customers.nama_customer', 'menus.nama_menu', 'menus.harga_menu')->get();

        if(!is_null($detail_pembayarans)) {
            return response([
            'message' => 'Retrieve DetailPembayaran Success',
            'data' => $detail_pembayarans
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'DetailPembayaran Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'id_pembayaran' => 'required',
        'id_menu' => 'required',
        'jumlah_item' => 'required|numeric',
        'total_harga_item' => 'required|numeric',
        'status_pesanan' => 'required|in:Cook,Ready,Served'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $detail_pembayarans = DetailPembayaran::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add DetailPembayaran Success',
            'data' => $detail_pembayarans,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $detail_pembayarans = DetailPembayaran::find($id); //mencari data  berdasarkan id

        if(is_null($detail_pembayarans)) {
            return response([
                'message' => 'DetailPembayaran Not Found',
                'data' => null
            ],404);
    }
    if($detail_pembayarans->delete()){
        return response([
            'message' => 'Delete DetailPembayaran Success',
            'data' => $detail_pembayarans,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete DetailPembayaran Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $detail_pembayarans = DetailPembayaran::find($id); //mencari data  berdasarkan id
        if(is_null($detail_pembayarans)){
            return response([
                'message' => 'DetailPembayaran Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'id_pembayaran' => '',
        'id_menu' => '',
        'jumlah_item' => 'numeric',
        'total_harga_item' => 'numeric',
        'status_pesanan' => 'in:Cook,Ready,Served'
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $detail_pembayarans->id_pembayaran = $updateData['id_pembayaran'];
    $detail_pembayarans->id_menu = $updateData['id_menu'];
    $detail_pembayarans->jumlah_item = $updateData['jumlah_item'];
    $detail_pembayarans->total_harga_item = $updateData['total_harga_item'];
    $detail_pembayarans->status_pesanan = $updateData['status_pesanan'];

    if($detail_pembayarans->save()){
        return response([
            'message' => 'Update DetailPembayaran Success',
            'data' => $detail_pembayarans,
        ],200);
    }
    return response([
        'message' => 'Update DetailPembayaran Falled',
        'data' => null,
    ],400);
    }

    public function updateServed (Request $request, $id){
        $detail_pembayarans = DetailPembayaran::find($id); //mencari data  berdasarkan id
        if(is_null($detail_pembayarans)){
            return response([
                'message' => 'DetailPembayaran Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'status_pesanan' => 'in:Cook,Ready,Served'
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $detail_pembayarans->status_pesanan = $updateData['status_pesanan'];

    if($detail_pembayarans->save()){
        return response([
            'message' => 'Makanan Disajikan',
            'data' => $detail_pembayarans,
        ],200);
    }
    return response([
        'message' => 'Update DetailPembayaran Falled',
        'data' => null,
    ],400);
    }
}
