<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\React;
use App\Models\Comment;
use App\Models\Content;
use App\Models\Follower;
use App\Helper\HistorySave;
use App\Models\SaveContent;
use Illuminate\Http\Request;
use App\Models\HistoryTracker;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')
                ->where('role', 'user')
                ->when(request('search_users'), function($q){
                    $q->where('name', 'like', '%'.request('search_users').'%')
                        ->orWhere('phone', 'like', '%'.request('search_users').'%')
                        ->orWhere('phone', 'like', '%'.request('search_users').'%')
                        ->orWhere('address', 'like', '%'.request('search_users').'%');
                })->paginate(15)->withQueryString();
        return view('users.index', compact('users'));
    }


    // user info detail
    public function userInfo($id)
    {
        $user = User::where('role', 'user')->where('id', $id)->first();
        if($user){
            $contents = Content::orderBy('updated_at', 'desc')->where('user_id', $id)->paginate(10);
            $following = Follower::orderBy('updated_at', 'desc')->where('following', $id)->paginate(10);
            $follower = Follower::orderBy('updated_at', 'desc')->where('follower', $id)->paginate(10);
            return [
                'user' => $user,
                'contents' => $contents,
                'following' => $following,
                'follower' => $follower
            ];
        }else{
            return abort(404);
        }
    }

    // user info detail
    public function userDetail($id)
    {
        return view('users.contents', $this->userInfo($id));
    }

    // edit user info
    public function editUserInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|max:30|min:3'
        ]);
        $user = User::find($request->id);

        $message = "Your some information was update by admin team.";
        Notification::send($user, new GeneralNotification($message));
        HistorySave::save(auth()->user(), 'Update User Info', $user);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->bio = $request->bio;
        $user->date_of_birth = $request->date_of_birth;
        $user->update();
        return back();
    }

    // get following user
    public function following($id)
    {
        return view('users.following', $this->userInfo($id));
    }

    // get follower user
    public function follower($id)
    {
        return view('users.followers', $this->userInfo($id));
    }

    // remove follower
    public function removeFollower(Request $request)
    {
        if(Follower::find($request->id)){
            $follower = Follower::find($request->id);

            $user = User::find($follower->follower);
            HistorySave::save(auth()->user(), 'Remove follower', $user);

            $follower->delete();
            return response()->json([
                'status' => 200,
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Remove fail.'
            ]);
        }
    }

    // delete user
    public function deleteUser(Request $request)
    {
        $user = User::find($request->id);
        if($user->profile_photo_path){
            Storage::delete('public/profile/'.$user->profile_photo_path);
        }
        if($user->cover_photo){
            Storage::delete('public/profile/'.$user->cover_photo);
        }
        Follower::where('following', $user->id)->orWhere('follower', $user->id)->delete();
        SaveContent::where('user_id', $user->id)->delete();
        Content::where('user_id', $user->id)->delete();
        Comment::where('user_id', $user->id)->delete();
        React::where('user_id', $user->id)->delete();
        HistoryTracker::where('owner', $user->id)->delete();
        $user->notifications()->delete();

        // HistorySave::save(auth()->user(), 'Deleted User', $user);

        $user->delete();
        return response()->json([
            'status' => 200,
            'data' => $user
        ]);
    }

    // reset pasword
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|max:20'
        ]);

        $user = User::find($request->id);
        $message = "Your password was reseted.";
        if($user){
            HistorySave::save(auth()->user(), 'Reset Password', $user);
            Notification::send($user, new GeneralNotification($message));
            $user->password = Hash::make($request->password);
            $user->update();
            return back()->with('reset_password', $request->password);
        }
    }
}