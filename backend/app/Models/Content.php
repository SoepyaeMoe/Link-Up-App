<?php

namespace App\Models;

use App\Models\User;
use App\Models\React;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Content extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'content', 'image', 'video_link', 'react'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'content_id', 'id');
    }

    public function reacts()
    {
        return $this->hasMany(React::class, 'content_id', 'id');
    }
}