<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class React extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content_id', 'react'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}