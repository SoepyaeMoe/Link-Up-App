<?php

namespace App\Http\Controllers\Api;

use App\Models\React;
use App\Models\Comment;
use App\Models\Content;
use App\Models\SaveContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    // get contents
    public function getContnets(Request $request)
    {
        $page = 15;
        $contents = Content::orderBy('created_at', 'desc')->paginate($page);
        $contents_count = Content::orderBy('created_at', 'desc')->get();
        $contents = ContentResource::collection($contents);
        return response()->json([
            'status' => 200,
            'data' => $contents,
            'last_page' => ceil(count($contents_count) / $page),
        ]);
    }

    public function getUserContents($id)
    {
        $page = 10;
        $contents = Content::orderBy('created_at', 'desc')->where('user_id', $id)->paginate($page);
        $contents_count = Content::orderBy('created_at', 'desc')->where('user_id', $id)->get();
        $contents = ContentResource::collection($contents);
        return response()->json([
            'status' => 200,
            'data' => $contents,
            'last_page' => ceil(count($contents_count) / $page),
        ]);
    }

    // content create
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 403,
                $validator->errors(),
            ]);
        }

        $content = Content::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $request->image,
            'video_link' => $request->video_link,
        ]);

        return response()->json([
            'status' => 201,
            'data' => $content,
        ]);

    }

    // delete content
    public function deleteContent (Request $request, $id)
    {
        $content = Content::find($id);
        if($content->user_id == $request->user()->id){
            Comment::where('content_id', $id)->delete();
            React::where('content_id', $id)->delete();
            SaveContent::where('content_id', $id)->delete();
            $content->delete();
            return response()->json([
                'status' => 200,
                'data' => $content
            ]);
        }else{
            return response()->json([
                'message' => "Stop! You don't have permision to delete."
            ], 400);
        }
    }

    // content image upload
    public function upload(Request $request)
    {
        $file = $request->file('image');
        if($file){

            $validator = Validator::make($request->all(), [
                'image' => 'file|mimes:png,svg,jpg,jpeg,pdf,psd,tiff,ai|max:20000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 403,
                    $validator->errors(),
                ]);
            }else{
                $name = $file->hashName();
                $file->storeAs('public/contents/'.$name );

                return response()->json([
                    'status' => 201,
                    'message' => 'Upload Success!',
                    'data' => $name,
                ]);
            }
        }
    }

    // content image upload cancel
    public function uploadCancel(Request $request)
    {
        if($request->image){
            Storage::delete('public/contents/'.$request->image);

            return response()->json([
                'status' => 200,
                'message' => 'Cancel success!',
            ]);
        }
    }

    // search content
    public function searchContent (Request $request)
    {
        $key = $request->key;
        $contents = Content::where('title', 'like', '%'.$key.'%')
                    ->orWhere('content', 'like', '%'.$key.'%')
                    ->get();
        return response()->json([
            'status' => 200,
            'data' => $contents
        ]);
    }
}
