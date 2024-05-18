<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Comment;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\NewCommentResource;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    // get comments in a content
    public function getComments(Request $request, $content_id)
    {
        $comment_count = Comment::where('content_id', $content_id)->get();
        $paginate = $request->paginate ? $request->paginate : 10;
        $comments = Comment::where('content_id', $content_id)->paginate($paginate);
        $comments = CommentResource::collection($comments);
        return response()->json([
            'status' => 200,
            'data' => $comments,
            'total' => count($comment_count),
            'last_page' => ceil(count($comment_count)/$paginate),
        ]);
    }

    // send comment to a content
    public function sendComment(Request $request, $content_id)
    {
        $validaor = Validator::make($request->all(), [
            'comment' => 'required',
        ]);

        if($validaor->fails()){
            return;
        }

        $user = $request->user();
        $content = Content::find($content_id);
        $to_user = User::find($content->user_id);
        $comment = Comment::create([
            'user_id' => $user->id,
            'content_id' => $request->content_id,
            'comment' => $request->comment,
        ]);

        $message = "comment in  your content.";
        if($user->id != $to_user->id){
            Notification::send($to_user, new UserNotification($user->id, $message, $content->id));
        }
        $comment = new NewCommentResource($comment);
        return response()->json([
            'status' => 200,
            'data' => $comment,
        ]);
    }

    // edit commment
    public function editComment(Request $request, $comment_id)
    {
        $comment = Comment::find($comment_id);
        $comment_creator = User::find($comment->user_id);
        if($request->user()->id == $comment_creator->id){
            $comment->comment = $request->comment;
            $comment->update();
            return response()->json([
                'status' => 200,
                'data' => $comment,
            ]);
        }else{
            return response()->json([
                'message' => "You don't have permision to edit.",
            ], 400);
        }
    }

    // delete comment
    public function deleteComment(Request $request,$id)
    {
        $comment = Comment::find($id);
        $content = Content::find($comment->content_id);
        if($comment->user_id == $request->user()->id ||$request->user()->id == $content->user_id){
            $comment->delete();
            return response()->json([
                'status' => 200,
                'data' => $comment,
            ]);
        }else{
            return response()->json([
                'message' => "You don't have permision to delete."
            ], 400);
        }
    }
}
