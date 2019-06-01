<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request){
        $user = User::where([
            "username"=>$request->input('username'),
            "password"=>hash('sha256',$request->input('password').env('APP_SALT')),
        ])->first();

        if($user){

            $payload= [
                "id" => $user->id,
                "iat"=> time()
            ];


            $jwt = JWT::encode($payload, env('APP_KEY'));

            return [
                "mssg"=>'Login successfull',
                "user"=>$user,
                "token" => $jwt,
            ];
        } else {
            return response()->json([
                "mssg"=>'Login failed'
            ],400);
        }

    }


    public function signup(Request $request){
     $this->validate($request,[
         "username"=> 'required|unique:users',
         "f_name"=> 'required',
         "l_name"=> 'required',
         "email"=> 'required|email|unique:users',
         "password"=>'required|min:6',
         "phone_no"=>'required',
         "permission"=>'required',
         "bio"=>'required',
         "address"=>'required'
     ]);

     $inputs= $request->all();
     $inputs['password']= hash('sha256',$inputs['password'].env('APP_SALT'));

     $user= User::create($inputs);

     return [
         "mssg"=>'Registration successfull'
     ];

    }
    public function list(){
         $users = User::all();
        return [
            "mssg"=> 'Details of all Users',
            "details"=> $users
        ];
    }

    public function home(){
        return [
            "user" => Auth::user(),
        ];
    }

}


