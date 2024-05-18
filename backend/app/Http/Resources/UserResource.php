<?php

namespace App\Http\Resources;

use App\Models\Content;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $unread_noti = count($user->unreadNotifications);
        $contents = Content::where('user_id', $this->id)->get();
        $contents_count = count($contents);
        $follow_status = false;
        $follow = Follower::where('following', $request->user()->id)->where('follower', $this->id)->first();
        if(!empty($follow)){
            $follow_status = true;
        }
        $heart = 0;
        foreach($contents as $content){
            $heart += $content->react;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'follow' => $this->follow,
            'follow_status' => $follow_status,
            'heart' => $heart,
            'contents_count' => $contents_count,
            'date_of_birth' => $this->date_of_birth,
            'address' => $this->address,
            'unread_noti' => !empty($unread_noti) ? $unread_noti  : 0,
            'profile_photo_path' => $this->profile_photo_path, // ? $this->profile_photo_path : 'https://ui-avatars.com/api/?color=7F9CF5&background=EBF4FF&name='.$this->name
            'cover_photo' => $this->cover_photo,
            'profile_photo_url' => $this->profile_photo_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
         ];
    }
}