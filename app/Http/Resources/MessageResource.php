<?php

namespace App\Http\Resources;
use App\Models\Message;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $count = Message::where([
            ['from_id', $this->from_id],
            ['to_id', $this->to_id],
        ])->whereNull('read_at')->count();
        
        return [
            'user_id' => $this->users->user_id,
            'username' => $this->users->username,
            'from_id' => $this->from_id,
            'to_id' => $this->to_id,
            'message' => $this->message,
            'created_at' => $this->created_at,
            'count' => $count
        ];
    }
}
