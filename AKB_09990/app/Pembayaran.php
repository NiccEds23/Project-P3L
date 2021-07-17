<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pembayaran extends Model
{
    protected $fillable =[
        'id_reservasi', 'id_karyawan', 'kode_pembayaran', 'status_pembayaran', 'jenis_pembayaran', 'kode_verifikasi', 'jumlah_pembayaran', 'waktu_pembayaran', 'tanggal_pembayaran'
    ];

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
