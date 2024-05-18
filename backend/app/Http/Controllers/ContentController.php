<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\React;
use App\Models\Comment;
use App\Models\Content;
use App\Helper\HistorySave;
use App\Models\SaveContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $contents = Content::orderBy('created_at', 'desc')
                        ->when($request->search_content, function($q){
                            $q->where('content', 'like', '%'.request('search_content').'%')
                                ->orWhere('title', 'like', '%'.request('search_content').'%');
                        })->paginate(15)->withQueryString();
        return view('contents.contents', ['contents' => $contents]);
    }

    // content detail
    public function contentDetail($id)
    {
        $content = Content::find($id);
        return view('contents.content_detail', ['content' => $content]);
    }

    // delete content
    public function deleteContent(Request $request)
    {
        $content = Content::find($request->id);
        $user = User::find($content->user_id);
        if(!empty($content)){
            SaveContent::where('content_id', $content->id)->delete();
            React::where('content_id', $request->id)->delete();
            Comment::where('content_id', $request->id)->delete();
            if($content->image){
                Storage::delete('public/contents/'.$content->image);
            }
            $content->delete();
            $message = "Your content ".$content->title." has been removed by admin team!";
            Notification::send($user, new GeneralNotification($message));
            HistorySave::save(auth()->user(), 'Removed Content', $user);
            return response()->json([
                'status' => 200,
                'message' => 'delete success'
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Not found content'
            ]);
        }
    }

    // react list in content
    public function reacts($id)
    {
        $reacts = React::where('content_id', $id)->orderBy('created_at', 'desc')->paginate(15);
        $content = Content::find($id);
        return view('contents.reacts', ['reacts' => $reacts, 'content' => $content]);
    }

    // delete react
    public function deleteReact(Request $request)
    {
        $react = React::find($request->id);
        $user = User::find($react->user_id);
        if($react){
            HistorySave::save(auth()->user(), 'Removed a react', $user);
            $react->delete();
            return response()->json([
                'status' => 200,
                'message' => 'delete success'
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'not fond!'
            ]);
        }
    }

    // comments in content
    public function comments($id)
    {
        $comments = Comment::orderBy('created_at', 'desc')
                    ->where('content_id', $id)
                    ->when(request('search_comments'), function($q){
                        $q->where('comment', 'like', '%'.request('search_comments').'%');
                    })->paginate(15)->withQueryString();

        $content = Content::find($id);
        return view('contents.comments', ['comments' => $comments, 'content' => $content]);
    }

    // delete comment
    public function deleteComment(Request $request)
    {
        $comment = Comment::find($request->id);
        $user = User::find($comment->user_id);
        if($comment){
            $message = "Your comment (".$comment->comment.") was removed by admin team.";
            Notification::send($user, new GeneralNotification($message));
            HistorySave::save(auth()->user(), 'Removed Comment', $user);
            $comment->delete();
            return response()->json([
                'status' => 200,
                'message' => 'delete success'
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'not fond!'
            ]);
        }
    }
}