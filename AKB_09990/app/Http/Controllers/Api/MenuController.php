<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Menu;

class MenuController extends Controller
{
    //Show all
    public function index(){
        // $menus = Menu::all();
        $menus = DB::table('menus')
        -> join('bahans', 'menus.id_bahan', '=', 'bahans.id')
        -> select('menus.*', 'bahans.nama_bahan')->get();

        if(count($menus) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $menus
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        // $menus = Menu::find($id);
        $menus = DB::table('menus')
        -> join('bahans', 'menus.id_bahan', '=', 'bahans.id')
        -> select('menus.*', 'bahans.nama_bahan')
        -> where('menus.id', '=', $id)->get($id); //mencari data berdasarkan id
        if(!is_null($menus)) {
            return response([
            'message' => 'Retrieve Menu Success',
            'data' => $menus
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'Menu Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'id_bahan' => 'required',
        'nama_menu' => 'required',
        'deskripsi_menu' => 'required',
        'kategori_menu' => 'required',
        'unit_menu' => 'required',
        'harga_menu' => 'required',
        'img_url' => 'required'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $menus = Menu::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add Menu Success',
            'data' => $menus,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $menus = Menu::find($id); //mencari data  berdasarkan id

        if(is_null($menus)) {
            return response([
                'message' => 'Menu Not Found',
                'data' => null
            ],404);
    }
    if($menus->delete()){
        return response([
            'message' => 'Delete Menu Success',
            'data' => $menus,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete Menu Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $menus = Menu::find($id); //mencari data  berdasarkan id
        if(is_null($menus)){
            return response([
                'message' => 'Menu Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'id_bahan' => '',
        'nama_menu' => ['', Rule::unique('menus')->ignore($menus)],
        'deskripsi_menu' => '',
        'kategori_menu' => '',
        'unit_menu' => '',
        'harga_menu' => '',
        'img_url' => '',
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $menus->id_bahan = $updateData['id_bahan'];
    $menus->nama_menu = $updateData['nama_menu'];
    $menus->deskripsi_menu = $updateData['deskripsi_menu'];
    $menus->kategori_menu = $updateData['kategori_menu'];
    $menus->unit_menu = $updateData['unit_menu'];
    $menus->harga_menu = $updateData['harga_menu'];
    $menus->img_url = $updateData['img_url'];

    if($menus->save()){
        return response([
            'message' => 'Update Menu Success',
            'data' => $menus,
        ],200);
    }
    return response([
        'message' => 'Update Menu Falled',
        'data' => null,
    ],400);
    }
}
