<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\React;
use App\Models\Comment;
use App\Models\Content;
use App\Models\Follower;
use App\Models\SaveContent;
use Illuminate\Http\Request;
use App\Models\HistoryTracker;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // get users
    public function userList(Request $request)
    {
       $user = $request->user();
       $neglace_id = [$user->id];
       $follow = Follower::where('following', $user->id)->get();
       foreach($follow as $f){
            array_push($neglace_id, $f->follower);
       }
       $users = User::orderBy('created_at', 'desc')
                    ->when(request('key'), function($q){
                        $q->where('name', 'like', '%'.request('key').'%');
                    })->whereNotIn('id', $neglace_id)->where('role', 'user')->paginate(15);
       return response()->json([
            'status' => 200,
            'data' => $users
       ]);
    }

    // get user data
    public function userInfo(Request $request)
    {
        $user ;
        if(!empty($request->id)){
            $user = User::where('id', $request->id)->where('role', 'user')->first();
        }else{
            $user = $request->user();
        }
        if($user){
            return response()->json([
                'status' => 200,
                'userInfo' => new UserResource($user),
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Unknown user'
            ]);
        }
    }

    // update user data
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
            // 'email' => 'required|email|unique:users,email,'.$request->user()->id,
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 403,
                $validator->errors(),
            ]);
        }

        $user = $request->user();
        $user->name = $request->name;
        // $user->email = $request->email;
        $user->phone = $request->phone;
        $user->bio = $request->bio;
        $user->date_of_birth = $request->date_of_birth;
        $user->address = $request->address;
        $user->update();
        $user = new UserResource($user);
        return response()->json([
            'status' => 200,
            'userInfo' => $user
        ]);
    }

    // change password
    public function changePassword(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8|max:20|same:confirm_password',
            'confirm_password' => 'required|min:8|max:20',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 403,
                $validator->errors(),
            ]);
        }

        if(Hash::check($request->old_password, $user->password)){
            $user->password = Hash::make($request->new_password);
            $user->update();

            return response()->json([
                'status' => 200,
                'userInfo' => $user,
            ]);
        }else{
            return response()->json([
                'status' => 403,
                ['old_password' => ['Old password incorrect!']],
            ]);
        }
    }

    // profile photo upload
    public function uploadProfile (Request $request)
    {
        $file = $request->file('file');
        if($file){
            $validator = Validator::make($request->all(), [
                'image' => 'file|mimes:png,jpg,jpeg,pdf,psd,tiff,ai|max:20000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 403,
                    $validator->errors(),
                ]);
            }else{
                $user = $request->user();
                if($user->profile_photo_path){
                    Storage::delete('public/profile/'.$user->profile_photo_path);
                }
                $name = $file->hashName();
                $file->storeAs('public/profile/'.$name );
                $user->profile_photo_path = $name;
                $user->update();

                return response()->json([
                    'status' => 201,
                    'message' => 'Upload Success!',
                    'data' => $name,
                ]);
            }
        }
    }

    // password check
    public function passwordCheck(Request $request)
    {
        if(Hash::check($request->password, $request->user()->password)){
            return response()->json([
                'status' => 200,
                'password' => true,
            ]);
        }else{
            return response()->json([
                'message' => 'Wroung password!'
            ]);
        }
    }

    // delete accound
    public function deleteAccound(Request $request)
    {
        $user = $request->user();
        if($user->profile_photo_path){
            Storage::delete('public/profile/'.$user->profile_photo_path);
        }
        if($user->cover_photo){
            Storage::delete('public/profile/'.$user->cover_photo);
        }
        Follower::where('following', $user->id)->orWhere('follower', $user->id)->delete();
        SaveContent::where('user_id', $user->id)->delete();
        Content::where('user_id', $user->id)->delete();
        Comment::where('user_id', $user->i)->delete();
        React::where('user_id', $user->id)->delete();
        HistoryTracker::where('owner', $user->id)->delete();
        $user->notifications()->delete();

        $user->delete();
        return response()->json([
            'status' => 200,
            'data' => $user
        ]);
    }
}