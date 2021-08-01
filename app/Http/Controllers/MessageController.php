<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;

class MessageController extends Controller
{
    public function sendMessage(Request $request){
      
        $FromId = $request->from_id;
        $ToId = $request->to_id;
        $message = $request->message;

        $sendMessage = new Message();
        $sendMessage->from_id = $FromId;
        $sendMessage->to_id = $ToId;
        $sendMessage->message = $message;
        $sendMessage->created_at = now();
        $sendMessage->save();
    }

    public function read($fromId, $toId){
        /*
        $statusRead = $request->status;
        $fromId = $request->from_id;
        $toId = $request->to_id;
        */

        $whereCondition = ['from_id' => $fromId, 'to_id' => $toId];
        $dataFrom = Message::where($whereCondition)->get();
        $dataTo = Message::where($whereCondition)->get();
        return response($dataFrom);

    }

    public function message($idUserAuth, $idUser){

        $to = [
            ['from_id', $idUser],
            ['to_id', $idUserAuth]
        ];
      
        $messages = Message::with('users')->where($to);

        $first = $messages;

        if ($first->exists()) {

            DB::table('message')->where($to)->update(['read_at' => now()]);

        }

        $messages = $messages->orWhere([
            ['from_id', $idUserAuth],
            ['to_id', $idUser]
        ])->get();
       
        $messages = MessageResource::collection($messages);
      
        return response()->json($messages);

    }

    public function listChatUser($query, $idUser){
        
        $field = ['user_id', 'email', 'username', 'img_profile'];
        $id = $idUser;

        if ($query === 'all') {
            
            $users = Message::with(['userFrom', 'userTo'])
                ->where('message.to_id', $id)
                ->orWhere('message.from_id', $id)
                ->latest();

            $keys = [];

            foreach ($users->get() as $key => $user) {
                if ($user->userFrom->user_id == $id) {
                    $keys[$key] = $user->userTo->user_id;
                } else {
                    $keys[$key] = $user->userFrom->user_id;
                }
            }

            $keys = array_unique($keys);
          
            $ids = implode(',', $keys);

            $users = User::whereIn('user_id', $keys);

            if (!empty($key)) {
                $users = $users->orderBy(DB::raw("FIELD(user_id, $ids)"));
            }

        } else {

            $users = User::where('username', 'like', "%{$query}%")->where('user_id', '!=', $id);

        }
        $users = $users->get($field);
        //$users = new UserResource($users->get(),$idUser);
        //$users = new UserResource($users,$idUser);
        //$users = UserResource::collection($users->get())->additional($idUser);
       
           
        $itemMessage = array();
        $itemCount = array();
        foreach ($keys as $ids => $value) {
            $to = [
                ['to_id', $idUser],
                ['from_id', $value]
            ];
            $message = Message::where([
                ['from_id', $idUser],
                ['to_id', $value]
            ])->orWhere($to)->latest()->first();

            $count = Message::where($to)->whereNull('read_at')->count();
            $itemMessage[] = $message;
            $itemCount[] = $count;
        }
     
        $output = [
            'user_data' =>$users,
            'message' => $itemMessage,
            'count' => $itemCount
        ];
        //$message = array_values($message);
       

 
      
        return response()->json($output);
    }
}
