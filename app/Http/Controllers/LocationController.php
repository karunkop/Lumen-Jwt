<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function getLocations(){
        return Location::all();
    }
    public function getUserLocation(){
        $id = Auth::user()['id'];
        $locations = Location::select('long','lat')->where('user_id',$id)->get();
        if(count($locations) === 1){
            return $locations[0];
        }else{
            return ["msg"=>"no location of the user"];
        }
    }
    public function addUserLocation(Request $request){
        $id = Auth::user()['id'];
        $this->validate($request,[
            'long'=> 'required|string',
            'lat'=> 'required|string',
        ]);
        $long = $request->input('long');
        $lat = $request->input('lat');
        Location::updateOrInsert(['user_id'=>$id],['long'=>$long,'lat'=>$lat]);
        info("successfully added location $long, $lat");
        return [
            "success" => true
        ];
    }
    public function deleteUserLocation(){
        $id = Auth::user()['id'];
        Location::where('user_id',$id)->delete();
        return [
            "success" => true
        ];
    }
    function distance_calc($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function getNearbyUsers( Request $request){
        $this->validate($request,[
            'long'=>'required',
            'lat'=>'required',
        ]);
        $id = Auth::user()['id'];
        $user_long =(double) $request->input('long');
        $user_lat = (double) $request->input('lat');

        $locations = Location::with('user')->get();
        $loc_array = [];
        foreach($locations as $location){

            if( $location['user_id']!=$id){
                $long = (double)$location['long'];
                $lat = (double)$location['lat'];
                $distance = $this->distance_calc( $user_lat, $user_long, $lat, $long ,'K');
                //if distance is less than 1 km, push the  user in the location
                if($distance<=1){
                    //select info of user to send
                    $userInfo = [
                        'username'=>$location['user']->username,
                        'bio'=>$location['user']->bio,
                    ];
                    //push the nearby user to the array
                    array_push($loc_array,[
                    'user'=>$userInfo,
                    'long'=>$long,
                    'lat'=>$lat,
                    ]);
                }
            }
        }
        return $loc_array;
    }
}
