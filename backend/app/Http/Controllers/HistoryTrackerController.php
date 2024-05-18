<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryTracker;

class HistoryTrackerController extends Controller
{
    public function index($id=0)
    {
        if($id != 0){
            $histories = HistoryTracker::orderBy('created_at', 'desc')->where('user', $id)->paginate(15);
            return view('admin.history_track', ['histories' => $histories]);
        }else{
            $histories = HistoryTracker::orderBy('created_at', 'desc')
                            ->when(request('search_history'), function($q){
                                $q->where('content', 'like', '%'.request('search_history').'%');
                            })->paginate(15)->withQueryString();
            return view('admin.history', ['histories' => $histories]);
        }
    }
}