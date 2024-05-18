<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryTracker extends Model
{
    use HasFactory;

    protected $fillable = ['user', 'content', 'owner'];

    public function ownerInf()
    {
        return $this->belongsTo(User::class, 'owner', 'id');
    }

    public function userInf()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }
}