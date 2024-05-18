<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Content;
use App\Models\HistoryTracker;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();
        $contents = Content::get();
        $admin_users = User::where('role', 'admin')->get();
        $histories = HistoryTracker::get();
        return view('dashboard', [
            'users' => $users,
            'contents' => $contents,
            'admin_users' => $admin_users,
            'histories' => $histories
        ]);
    }
}