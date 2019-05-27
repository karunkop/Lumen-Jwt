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
                "token" => $jwt,


            ];
        } else {
            return [
                "mssg"=>'Login failed'
            ];
        }

    }


    public function signup(Request $request){
     $this->validate($request,[
         "username"=> 'required|unique:users',
         "email"=> 'required|email|unique:users',
         "password"=>'required|min:6',
         "phone_no"=>'required',
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
    public function events(){
        return [
            "events" => Auth::user()->events,
        ];
    }


    public function attachEvent($id, $event_id){
        $user = User::findOrFail($id);

        $user->events()->syncWithoutDetaching([$event_id]);

        return [
            "message" => "Event was attached."
        ];
    }

    public function detachEvent($id, $event_id){
        $user = User::findOrFail($id);

        $user->events()->detach($event_id);

        return [
            "message" => "Event was detached."
        ];
    }



}


