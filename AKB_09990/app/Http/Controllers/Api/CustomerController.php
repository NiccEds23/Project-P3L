<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Customer;

class CustomerController extends Controller
{
    //Show all
    public function index(){
        $customers = Customer::all();

        if(count($customers) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customers
            ],200);
        } //return semua data dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],404); //return message data kosong
    }

    public function show($id){
        $customers = Customer::find($id); //mencari data berdasarkan id
        if(!is_null($customers)) {
            return response([
            'message' => 'Retrieve Customer Success',
            'data' => $customers
            ],200);
    } //return data yang ditemukan dalam bentuk json

    return response([
        'message' => 'Customer Not Found',
        'data' => null
    ],404);
    }

    public function store(Request $request){
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
        'nama_customer' => 'required|max:50|regex:/^[\pL\s]+$/u',
        'email_customer' => 'nullable|email:rfc,dns',
        'notelp_customer' => 'nullable|min:10|max:13|regex:/(0)(8)[0-9]/'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); //return error invalid input

        $customers = Customer::create($storeData); //menambah data  baru
        return response([
            'message' => 'Add Customer Success',
            'data' => $customers,
        ],200); //return data  baru dalam bentuk json
    }

    public function destroy($id) {
        $customers = Customer::find($id); //mencari data  berdasarkan id

        if(is_null($customers)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ],404);
    }
    if($customers->delete()){
        return response([
            'message' => 'Delete Customer Success',
            'data' => $customers,
        ],200);
    } //return mage seat berhasil menghapus data preduct
    return response([
        'message' => 'Delete Customer Falled',
        'data' => null,
    ],400);
    }

    public function update (Request $request, $id){
        $customers = Customer::find($id); //mencari data  berdasarkan id
        if(is_null($customers)){
            return response([
                'message' => 'Customer Not Found',
                'data' => null
    ], 404);
    } //return message saat data  tidak ditemukan

    $updateData = $request->all(); //mengambil semua input dari api client
    $validate = Validator::make ($updateData, [
        'nama_customer' => ['max:50|regex:/^[\pL\s]+$/u', Rule::unique('customers')->ignore($customers)],
        'email_customer' => 'nullable|email:rfc,dns',
        'notelp_customer' => 'nullable|min:10|max:13|regex:/(0)(8)[0-9]/'
    ]); //membuat rule validasi input

    if($validate->fails())
        return response(['message' => $validate->errors()],400); //return error invalid Input
    $customers->nama_customer = $updateData['nama_customer'];
    $customers->email_customer = $updateData['email_customer'];
    $customers->notelp_customer = $updateData['notelp_customer'];

    if($customers->save()){
        return response([
            'message' => 'Update Customer Success',
            'data' => $customers,
        ],200);
    }
    return response([
        'message' => 'Update Customer Falled',
        'data' => null,
    ],400);
    }
}
