<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $from_user = array_key_exists('from_user_id', $this->data) ? User::find($this->data['from_user_id']) : '';
        return [
            'id' => $this->id,
            'message' => $this->data['message'],
            'from_id' => $from_user ? $from_user->id : '',
            'from_name' => $from_user ? $from_user->name : 'Admin',
            'from_profile_photo' => $from_user ? $from_user->profile_photo_path : '',
            'target_id' => array_key_exists('target_id', $this->data) ? $this->data['target_id'] : '',
            'read' => $this->read_at ? $this->read_at : 'unread',
            'created_at' => $this->created_at->format("d-M-Y H:i:a"),
        ];
    }
}