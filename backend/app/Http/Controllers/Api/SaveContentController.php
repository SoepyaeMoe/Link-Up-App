<?php

namespace App\Http\Controllers\Api;

use App\Models\Content;
use App\Models\SaveContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SavedContentResource;
use App\Http\Resources\ContentDetailResource;

class SaveContentController extends Controller
{
    // get saved content
    public function savedContent(Request $request)
    {
        $saved_contents = SaveContent::orderBy('updated_at', 'desc')->where('user_id', $request->user()->id)->get();
        $saved_contents = SavedContentResource::collection($saved_contents);
        return response()->json([
            'status' => 200,
            'data' => $saved_contents,
        ]);
    }

    // save content
    public function saveContent(Request $request)
    {
        $save_content = SaveContent::where('user_id', $request->user()->id)->where('content_id', $request->content_id)->first();
        if(!empty($save_content)){
            $save_content->delete();
            return response()->json([
                'status' => 200,
                'data' => $save_content,
            ]);
        }else{
            $save_content = SaveContent::create([
                'user_id' => $request->user()->id,
                'content_id' => $request->content_id,
            ]);
            return response()->json([
                'status' => 200,
                'data' => $save_content,
            ]);
        }
    }

    // content detail
    public function contentDetail(Request $request, $id)
    {
        $content = Content::find($id);
        if(!empty($content)){
            $content = new ContentDetailResource($content);
            return response()->json([
                'status' => 200,
                'data' => $content,
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Unknown content.'
            ]);
        }
    }
}
