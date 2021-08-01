<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ApiToken;
class AuthController extends Controller
{

    public function login(Request $request){
        $username = $request->username;
        $password   = $request->password;
        if (Auth::attempt(['username' => $username, 'password' => $password])) {
           
            $user = DB::table('user')
                        ->where('username', $username)
                        ->first();
            if ($this->middleware(['auth','verified'])) {
              
                return response([
                    'status' => '1',
                    'message' => 'Selamat Datang',
                    'data_user' => $user
                ], 200);
            }
           
        }else{
            return response([
                'status' => '0',
                'message' => 'Username atau Password Salah',
            ], 200);
        }
    }

    public function register(Request $request){
        // cek apakah nip sudah terdaftar atau belum
        $username = User::firstWhere('username', $request->username);
        //cek
        if($username){
            //response rest api
            return response([
                'status' => '0',
                'message' => 'Akun Sudah Terdaftar'
            ], 200);
           
        }else{
          
            $user = new User;
            $user->username = strtolower($request->username);
            $user->fullname = ucwords(strtolower($request->fullname));
            $user->email = strtolower($request->email);
            $user->password = Hash::make($request->password);
            $user->address = ucwords(strtolower($request->address));
            $user->gender = $request->gender;
            $user->phone = strtolower($request->phone);
          

            if ($request->hasFile('img_profile')) {
                $image = $request->file('img_profile');
                $name = time().'.'.$image->getClientOriginalExtension();
                $image->storeAs('images_profile', $name);
                $user->img_profile = $name;
                $user->save();

                return response([
                    'status' => '1',
                    'message' => 'Berhasil Mendaftar'
                ], 200);
            }else{
                return response([
                    'status' => '0',
                    'message' => 'Gagal Mendaftar'
                ], 400);
            }
            
        }
    }
}
