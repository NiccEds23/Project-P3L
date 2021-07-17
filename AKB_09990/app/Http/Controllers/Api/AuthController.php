<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'id_role' => 'required',
            'name' => 'required|max:50|regex:/^[\pL\s]+$/u',
            'email'=> 'required|email:rfc,dns|unique:users',
            'password'=>'required',
            'jenis_kelamin_karyawan' => 'required|in:Laki-laki,Perempuan',
            'notelp_karyawan' => 'required|min:10|max:13|regex:/(0)(8)[0-9]/',
            'tanggal_gabung_karyawan' => 'required|date|date_format:Y-m-d',
            'flags' => 'required|regex:/[0-1]/'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $registrationData['password'] = bcrypt($request->password);
        // $karyawan = Karyawan::create($registrationData);
        $user = User::create($registrationData);
        return response([
            'message' => 'Register Success',
            'user' => $user,
            'karyawan' => $karyawan,
        ],200);
    }

    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'],401);

        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response([
            'message' => 'Successfully logged out'
        ]);
    }
}
