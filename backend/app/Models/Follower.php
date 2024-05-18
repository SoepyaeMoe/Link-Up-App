<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = ['following', 'follower'];

    public function follower_user()
    {
        return $this->belongsTo(User::class, 'follower', 'id');
    }

    public function following_user()
    {
        return $this->belongsTo(User::class, 'following', 'id');
    }
}
