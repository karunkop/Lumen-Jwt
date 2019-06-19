<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;

class MessageController extends Controller
{
    public function addMessage(Request $request){
        $this->validate($request,[
            'text'=>'required|max:256',
            'user_id'=>'required',
            'group_id'=>'required',
        ]);
        $message = Message::create($request->all());
        return $message;
    }
    public function deleteMessage($id){
        Message::destroy($id);
        return [
            'msg'=>'message deleted'
        ];
    }

    public function getMessages($group_id,Request $request){
        $this->validate($request,[
            'limit'=>'max:50'
        ]);
        $limit;
        if($request->has('limit')){
            $limit = $request->input('limit');
        }else{
            $limit = 20;
        }
        if($request->has('loaded_message_id')){
            $loaded_message_id = $request->input('loaded_message_id');
            $messages = Message::with('user')->where([
                            ['group_id','=',$group_id],
                            ['id','>',$loaded_message_id],
                            ])
                            ->latest()->get();
            return $messages;
        }
        $messages = Message::with('user')->where('group_id',$group_id)
                            ->latest()
                            ->limit($limit)->get();
        return $messages;
    }
}
