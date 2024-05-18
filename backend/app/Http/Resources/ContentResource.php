<?php

namespace App\Http\Resources;

use App\Models\React;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\SaveContent;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $react_status;
        $user = $request->user();
        $react = React::where('user_id', $user->id)->where('content_id', $this->id)->first();
        if(!empty($react)){
            $react_status = true;
        }else{
            $react_status = false;
        }

        // check has user?
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

        // follow status
        $follow_status = false;
        $follow = Follower::where('following', $request->user()->id)->where('follower', $this->user_id)->first();
        if(!empty($follow)){
            $follow_status = true;
        }

        // is save ?
        $save = SaveContent::where('user_id', $request->user()->id)->where('content_id', $this->id)->first() ? true : false;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' =>  $content_user_name,
            'user_photo' => $profile_photo_path,
            'title' => $this->title,
            'content' => $this->content,
            'react' => $this->react,
            'react_status' => $react_status,
            'is_save' => $save,
            // 'video_link' => $this->video_link,
            'content_image' => $this->image,
            'follow_status' => $follow_status,
            'comment_count' => count(Comment::where('content_id', $this->id)->get()),
            'updated_at' => $this->updated_at->format("d-M-Y H:i a"),
            'created_at' => $this->created_at->format("d-M-Y H:i a"),
        ];
    }
}