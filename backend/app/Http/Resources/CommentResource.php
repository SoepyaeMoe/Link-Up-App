<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => new UserResource(User::find($this->user_id)),
            'content_id' => $this->content_id,
            'comment' => $this->comment,
            'updated_at' => $this->updated_at->format("d-M-Y H:i:a"),
            'created_at' => $this->created_at->format("d-M-Y H:i:a"),
        ];
    }
}