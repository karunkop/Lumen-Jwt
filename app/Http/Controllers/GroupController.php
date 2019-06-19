<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\User;
class GroupController extends Controller
{
    public function addGroup(Request $request){
        $this->validate($request,[
            "name"=>"required|min:5",
            "event_id"=>"required|unique:groups"
        ]);
        $group = Group::create($request->all());
        return $group;
    }
    public function getGroup($id){
        $groups = Group::with('users')->get();
        foreach ($groups as $group) {
            if($group->id == $id){
                $group->event;
                $group->host = User::findOrFail($group->event->host);
                return $group;
            }
        }
        abort(404);
    }
    public function deleteGroup($id){
        Group::destroy($id);
        return [
            "msg"=>"Group deleted."
        ];
    }
    public function attachUser(Request $request){
        $this->validate($request,[
            'group_id'=>'required',
            'user_id'=>'required',
        ]);
        $group_id = (int)$request->input('group_id');
        $user_id = (int)$request->input('user_id');
        $group = Group::findOrFail($group_id);
        $group->users()->syncWithoutDetaching($user_id);
        return [
            "msg"=>"user added to group."
        ];
    }
    public function getGroupOfEvent($event_id){
        $groups = Group::where('event_id',$event_id)->get();
        return $groups[0];
    }
    public function detachUser(Request $request){
        $this->validate($request,[
            'group_id'=>'required',
            'user_id'=>'required',
        ]);
        $group_id = (int)$request->input('group_id');
        $user_id = (int)$request->input('user_id');
        $group = Group::findOrFail($group_id);
        $group->users()->detach($user_id);
        return [
            "msg"=>"user removed from group."
        ];
    }
}
