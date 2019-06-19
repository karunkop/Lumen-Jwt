<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Event;
use App\Group;
class EventController extends Controller
{
    public function addEvent(Request $request){
        $this->validate($request,[
            "name"=>"required",
            "description"=>"required",
            "start_date_time"=>"required",
            "end_date_time"=>"required",
            "host"=>"required",
            "completed"=>"required",
        ]);
        $values = json_encode($request->all());
        $values_decoded = json_decode($values,true);
        $values_decoded['completed'] = (bool) $values_decoded['completed'];
        $event = Event::create($values_decoded);
        $group = new Group;
        $group->name = $request->input('name');
        $group->event_id = $event->id;
        $group->save();
        return [
            'event'=>$event,
            'group'=>$group
        ];
        abort(400);
    }
    public function getEvent($id){
        $event = Event::findOrFail($id);
        $host = $event->host()->get();
        $event['host']= $host[0];
        return $event;
    }
    public function getEventsOfHost($id){
        $events = Event::with('participants')->where('host',$id)->orderBy('updated_at','DESC')->get();
        return $events;
    }
    public function getAllEvents(Request $request){
        // $this->validate($request,[
        //     'host'=>'boolean'
        // ])
        if($request->has('host')){
            $host = (bool)$request->input('host');
            if($host){
                $events = Event::with(['host','participants'])->orderBy('updated_at',"DESC")->get();
                return $events;
            }
        }
        $events = Event::with('participants')->get();
        return $events;
    }
    public function addParticipant($event_id,$parti_id){
        $event = Event::findOrFail($event_id);
        $event->participants()->syncWithoutDetaching($parti_id);
        return [
            'msg'=>'participant attached.'
        ];
    }
    public function removeParticipant($event_id,$parti_id){
        $event = Event::findOrFail($event_id);
        $event->participants()->detach($parti_id);
        return [
            'msg'=>'participant detached.'
        ];
    }
    public function getParticipants($id){
        $events = Event::with('participants')->get();
        foreach($events as $event){
            if($event->id == $id){
                return $event->participants;
            }
        }
        abort(404);
    }
    public function updateEvent($id,Request $request){
        $event = Event::findOrFail($id);
        if($request->has('name')){
            $event['name']= $request->input('name');
        }
        if($request->has('description')){
            $event['description']= $request->input('description');
        }
        if($request->has('start_date_time')){
            $event['start_date_time']= $request->input('start_date_time');
        }
        if($request->has('end_date_time')){
            $event['end_date_time']= $request->input('end_date_time');
        }
        if($request->has('host')){
            $event['host']= $request->input('host');
        }
        if($request->has('completed')){
            $event['completed']= $request->input('completed');
        }
        $event->save();
        return $event;
    }
    public function deleteEvent($id, Request $request){
        $event = Event::findOrFail($id);
        $name = $event->name;
        if($event->host == Auth::user()->id){
            $event->delete();
            return [
                "msg"=>"Deleted $id. $name"
            ];
        }
        return response()->json([
            "msg"=>"Cannot delete others' event."
        ],403);

    }

    public function getGroup($event_id){
        $event = Event::findOrFail($event_id);
        $event->group;
        return $event;
    }
}
