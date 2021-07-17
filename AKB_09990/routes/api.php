<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register','Api\KaryawanController@store');
Route::post('login','Api\AuthController@login');

Route::get('menu','Api\MenuController@index');
Route::get('menu/{id}','Api\MenuController@show');



Route::group(['middleware'=>'auth:api'],function(){
    Route::get('bahan','Api\BahanController@index');
    Route::get('bahanKosong','Api\BahanController@bahanKosong');
    Route::get('bahan/{id}','Api\BahanController@show');
    Route::post('bahan','Api\BahanController@store');
    Route::put('bahan/{id}','Api\BahanController@update');
    Route::delete('bahan/{id}','Api\BahanController@destroy');

    Route::get('customer', 'Api\CustomerController@index');
    Route::get('customer/{id}', 'Api\CustomerController@show');
    Route::post('customer', 'Api\CustomerController@store');
    Route::put('customer/{id}', 'Api\CustomerController@update');
    Route::delete('customer/{id}', 'Api\CustomerController@destroy');

    Route::get('detailPembayaran','Api\DetailPembayaranController@index');
    Route::get('detailPembayaranCook','Api\DetailPembayaranController@cook');
    Route::get('detailPembayaranReady','Api\DetailPembayaranController@ready');
    Route::get('detailPembayaran/{id}','Api\DetailPembayaranController@show');
    Route::get('pesanan/{id}','Api\DetailPembayaranController@pesanan');
    Route::post('detailPembayaran','Api\DetailPembayaranController@store');
    Route::put('detailPembayaran/{id}','Api\DetailPembayaranController@update');
    Route::put('detailPembayaranServed/{id}','Api\DetailPembayaranController@updateServed');
    Route::delete('detailPembayaran/{id}','Api\DetailPembayaranController@destroy');

    Route::get('historyStokBahan','Api\HistoryStokBahanController@index');
    Route::get('historyStokBahanMasuk','Api\HistoryStokBahanController@bahanMasuk');
    Route::get('historyStokBahanKeluar','Api\HistoryStokBahanController@bahanKeluar');
    Route::get('historyStokBahanTerbuang','Api\HistoryStokBahanController@bahanTerbuang');
    Route::get('historyStokBahan/{id}','Api\HistoryStokBahanController@show');
    Route::post('historyStokBahanBuang','Api\HistoryStokBahanController@storeBuang');
    Route::post('historyStokBahanKeluar','Api\HistoryStokBahanController@storeKeluar');
    Route::put('historyStokBahan/{id}','Api\HistoryStokBahanController@update');
    Route::delete('historyStokBahan/{id}','Api\HistoryStokBahanController@destroy');

    Route::get('infoPembayaran','Api\InfoPembayaranController@index');
    Route::get('infoPembayaran/{id}','Api\InfoPembayaranController@show');
    Route::post('infoPembayaran','Api\InfoPembayaranController@store');
    Route::put('infoPembayaran/{id}','Api\InfoPembayaranController@update');
    Route::delete('infoPembayaran/{id}','Api\InfoPembayaranController@destroy');

    Route::get('karyawan','Api\KaryawanController@index');
    Route::get('karyawan/{id}','Api\KaryawanController@show');
    Route::post('karyawan','Api\KaryawanController@store');
    Route::put('karyawan/{id}','Api\KaryawanController@update');
    Route::delete('karyawan/{id}','Api\KaryawanController@destroy');
    Route::post('karyawan/{id}', 'Api\KaryawanController@restore');

    Route::get('meja','Api\MejaController@index');
    Route::get('mejakosong','Api\MejaController@mejaKosong');
    Route::get('meja/{id}','Api\MejaController@show');
    Route::post('meja','Api\MejaController@store');
    Route::put('meja/{id}','Api\MejaController@update');
    Route::delete('meja/{id}','Api\MejaController@destroy');

    // Route::get('menu','Api\MenuController@index');
    // Route::get('menu/{id}','Api\MenuController@show');
    Route::post('menu','Api\MenuController@store');
    Route::put('menu/{id}','Api\MenuController@update');
    Route::delete('menu/{id}','Api\MenuController@destroy');

    Route::get('pembayaran','Api\PembayaranController@index');
    Route::get('pembayaranUnfinished','Api\PembayaranController@unfinished');
    Route::get('pembayaranFinished','Api\PembayaranController@finished');
    Route::get('pembayaran/{id}','Api\PembayaranController@show');
    Route::post('pembayaran','Api\PembayaranController@store');
    Route::put('pembayaran/{id}','Api\PembayaranController@update');
    Route::delete('pembayaran/{id}','Api\PembayaranController@destroy');

    Route::get('reservasi','Api\ReservasiController@index');
    Route::get('reservasi/{id}','Api\ReservasiController@show');
    Route::post('reservasi','Api\ReservasiController@store');
    Route::put('reservasi/{id}','Api\ReservasiController@update');
    Route::delete('reservasi/{id}','Api\ReservasiController@destroy');

    Route::get('role','Api\RoleController@index');
    Route::get('role/{id}','Api\RoleController@show');
    Route::post('role','Api\RoleController@store');
    Route::put('role/{id}','Api\RoleController@update');
    Route::delete('role/{id}','Api\RoleController@destroy');

    Route::get('stokBahanMasuk','Api\StokBahanMasukController@index');
    Route::get('stokBahanMasuk/{id}','Api\StokBahanMasukController@show');
    Route::post('stokBahanMasuk','Api\StokBahanMasukController@store');
    Route::put('stokBahanMasuk/{id}','Api\StokBahanMasukController@update');
    Route::delete('stokBahanMasuk/{id}','Api\StokBahanMasukController@destroy');

    Route::post('logout','Api\AuthController@logout');
});
