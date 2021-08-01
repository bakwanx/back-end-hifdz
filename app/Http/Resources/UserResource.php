<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Message;
class UserResource extends JsonResource
{
      /**
     * @var
     */
    private $foo;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $foo)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        
        $this->foo = $foo;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $users = $this->resource;
        $userId = [];
        $username = [];
        $email = [];
        $img_profile = [];
        foreach ($users  as $key => $value) {
          
            $userId[] =  $value->user_id; 
            $username[] = $value->username;
            $email[] = $value->email;
            $img_profile[] = $value->img_profile;
        }

        $to = [
            ['to_id', $this->foo],
            ['from_id', $userId]
        ];
       
        $message = Message::where([
            ['from_id', $this->foo],
            ['to_id', $userId]
        ])->orWhere($to)->latest()->first();

        $count = Message::where($to)->whereNull('read_at')->count();
        
        return [
            'user_id' => $userId,
            'username' => $username,
            'email' => $email,
            'img_profile' => $img_profile,
            'from_id' => $message->from_id ?? '',
            'to_id' => $message->to_id ?? '',
            'message' => $message->message ?? '',
            'count' => $count
        ];
    }

}
