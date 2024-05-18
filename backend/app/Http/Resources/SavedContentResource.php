<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\React;
use App\Models\Comment;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $content = Content::findOrFail($this->content_id);
        $user = User::find($content->user_id);
        $react_status = React::where('user_id', $this->user_id)->where('content_id', $this->content_id)->first();
        $comments = Comment::where('content_id', $content->id)->get();

        $profile_photo_path = null;
        if(!empty($user)){
            $profile_photo_path = $user->profile_photo_path;
        }else{
            $content_user_name = 'Unknown User';
            $profile_photo_path = "https://ui-avatars.com/api/?name=Unknown+User";
        }
        return [
        'id' => $this->id,
        'saved_user_id' => $this->user_id,
        'user_name' => $user->name,
        'user_photo' => $profile_photo_path,
        'content_id' => $this->content_id,
        'title' => $content->title,
        'content' => $content->content,
        'content_image' => $content->image,
        'content_created_at' => $content->created_at->format("d-M-Y H:i a"),
        'content_updated_at' => $content->updated_at->format("d-M-Y H:i a"),
        'react' => $content->react,
        'react_status' => !empty($react_status) ? true : false,
        'comment_count' => count($comments),
        'updated_at' => $this->updated_at->format('d-M-Y H:i a'),
        'created_at' => $this->created_at->format('d-M-Y H:i a'),
    ];
    }
}