<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\React;
use App\Models\Comment;
use App\Models\Content;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $content = Content::findOrFail($this->content_id);
        // $user = User::find($content->user_id);
        $react_status = React::where('user_id', $this->user_id)->where('content_id', $this->id)->first();
        $comments = Comment::where('content_id', $this->id)->get();

         // follow status
        $follow_status = false;
        $follow = Follower::where('following', $request->user()->id)->where('follower', $this->user_id)->first();
        if(!empty($follow)){
            $follow_status = true;
        }

        $content_user ;
        $content_user_name = null;
        $profile_photo_path = null;
        if(!empty(User::find($this->user_id))){
            $content_user = User::findOrFail($this->user_id);
            $content_user_name = $content_user->name;
            $profile_photo_path = $content_user->profile_photo_path;
        }else{
            $content_user_name = 'Unknown User';
            $profile_photo_path = "https://ui-avatars.com/api/?name=Unknown+User";
        }

        return [
            'id' => $this->id,
            'saved_user_id' => $this->user_id,
            'user_name' => $content_user_name,
            'content_user_id' => $this->user_id,
            'user_photo' => $profile_photo_path,
            'title' => $this->title,
            'content' => $this->content,
            'content_image' => $this->image,
            'follow_status' => $follow_status,
            'react' => $this->react,
            'react_status' => !empty($react_status) ? true : false,
            'comment_count' => count($comments),
            'content_created_at' => $this->created_at->format("d-M-Y H:i a"),
            'content_updated_at' => $this->updated_at->format("d-M-Y H:i a"),
        ];
    }
}
