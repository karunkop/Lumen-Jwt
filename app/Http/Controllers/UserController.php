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

            return response()->json([
                "mssg"=>'Login successfull',
                "user"=>$user,
                "token" => $jwt,
            ],200);
        } else {
            return response()->json([
                "mssg"=>'Login failed'
            ],400);
        }

    }
    public function update($id, Request $request){
        $this->validate($request,[
            "username"=> 'unique:users',
            "bio"=>'',
            "f_name"=> '',
            "l_name"=> '',
            "email"=> 'email|unique:users',
            "phone_no"=>'',
            "address"=>''
        ]);
        $user = User::findOrFail($id);
        if($request->has('username')){

            $user['username'] = $request->input('username');
        }
        if($request->has('bio')){

            $user['bio'] = $request->input('bio');
        }
        if($request->has('f_name')){

            $user['f_name'] = $request->input('f_name');
        }
        if($request->has('l_name')){

            $user['l_name'] = $request->input('l_name');
        }
        if($request->has('email')){
            $user['email'] = $request->input('email');
        }
        if($request->has('phone_no')){

            $user['phone_no'] = $request->input('phone_no');
        }
        if($request->has('address')){
            $user['address'] = $request->input('address');
        }
        $user->save();
        return response()->json([
            "mssg"=>'Update Success',
            'user'=>$user
        ],200);
    }
    public function changePermission(Request $request){

        $this->validate($request,[
            'permission'=>'required'
        ]);
        $user = Auth::user();
        $user['permission'] = $request->input('permission');
        $user->save();
        return $user;
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


