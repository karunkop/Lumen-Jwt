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
            "email"=>$request->input('email'),
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
                "token" => $jwt
            ];
        } else {
            return [
                "mssg"=>'Login failed'
            ];
        }

    }
    public function signup(Request $request){
     $this->validate($request,[
         "name"=> 'required',
         "email"=> 'required|email|unique:users',
         "password"=>'required|min:6'
     ]);

     $inputs= $request->all();
     $inputs['password']= hash('sha256',$inputs['password'].env('APP_SALT'));

     $user= User::create($inputs);

     return [
         "mssg"=>'Registration successfull'
     ];

    }

    public function home(){
        return [
            "message" => "Hello",
            "user" => Auth::user()
        ];
    }
}


