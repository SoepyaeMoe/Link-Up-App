<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\React;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class ReactController extends Controller
{
    public function toggleReact(Request $request, $content_id)
    {
        $user = $request->user();
        $content = Content::where('id', $content_id)->first();
        $to_user = User::find($content->user_id);
        $react = React::where('user_id', $user->id)->where('content_id', $content_id)->first();
        if(!empty($react)){
            $content->react = $content->react - 1;
            $content->save();
            $react->delete();
        }else{
            $content->react = $content->react + 1;
            $content->save();
            $message = "react to your content.";
            if($user->id != $to_user->id){
                Notification::send($to_user, new UserNotification($user->id, $message, $content->id));
            }
            $react = React::create([
                'user_id' => $user->id,
                'content_id' => $content_id,
                'react' => 'heart'
            ]);
        }
        return response()->json([
            'status' => 200,
            'data' => $react,
        ]);
    }
}
