<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;

class LocationController extends Controller
{
    public function getLocations(){
        return Location::all();
    }
    public function getUserLocation($id){
        $locations = Location::select('long','lat')->where('user_id',$id)->get();
        if(count($locations) === 1){
            return $locations[0];
        }else{
            return ["msg"=>"no location of the user"];
        }
    }
    public function addUserLocation($id, Request $request){
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
    public function deleteUserLocation($id){
        Location::where('user_id',$id)->delete();
        return [
            "success" => true
        ];
    }
    function distance($lat1, $lon1, $lat2, $lon2, $unit) {

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
    public function getNearbyUsers($id, Request $request){

        $user_long = $request->input('long');
        $user_lat = $request->input('lat');

        $locations = Location::with('user:id,f_name,l_name,phone')->get();
        $loc_array = [];
        foreach($locations as $location){
            if( $location['user_id']!=$id){
                $long = (double)$location['long'];
                $lat = (double)$location['lat'];
                $distance = $this->distance( $user_lat, $user_long, $lat, $long ,'K');
                if($distance<=1){
                    array_push($loc_array,[
                    'user'=>$location['user'],
                    'long'=>$long,
                    'lat'=>$lat,
                    ]);
                }
            }
        }
        return $loc_array;
    }

}
