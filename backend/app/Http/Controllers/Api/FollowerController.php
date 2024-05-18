<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class FollowerController extends Controller
{
    public function toggleFollow (Request $request, $id)
    {
        $user = $request->user();
        $follow_user = User::findOrFail($id);
        $follower = Follower::where('following', $user->id)->where('follower', $id)->first();
        if(!empty($follower)){
            $follow_user->follow = $follow_user->follow - 1;
            $follow_user->save();
            $follower->delete();
        }else{
            $follow_user->follow = $follow_user->follow + 1;
            $follow_user->save();
            $message = 'followed you.';
            Notification::send($follow_user, new UserNotification($user->id, $message));
            $follower = Follower::create([
                'following' => $user->id,
                'follower' => $id,
            ]);
        }
        return response()->json([
            'status' => 200,
            'data' => $follower,
        ]);
    }
}