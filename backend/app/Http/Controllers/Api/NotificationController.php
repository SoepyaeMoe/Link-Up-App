<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function getNoti(Request $request)
    {
        $paginate = 10;
        $user = $request->user();
        $notifications = $user->notifications()->paginate($paginate);
        $notifications = NotificationResource::collection($notifications);
        $last_page = ceil($notifications->total() / $paginate);
        return response()->json([
                'status' => 200,
                'data' => $notifications,
                'last_page' => $last_page
        ]);
    }

    public function markAsRead(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();
        return response()->json([
            'status' => 200,
            'message' => 'all noti mark as read.',
        ]);
    }

    public function removeNoti(Request $request)
    {
        $targetNoti;
        foreach($request->user()->notifications as $noti){
            if($request->id == $noti->id){
                $targetNoti = $noti;
            }
        }
        if(!empty($targetNoti)){
            $notification = $targetNoti->delete();
            return response()->json([
                'status' => 200,
                'data' => $notification
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Not found.'
            ]);
        }
    }
}
