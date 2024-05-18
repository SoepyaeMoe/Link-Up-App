<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminCreateRequest;

class AdminController extends Controller
{
    public function index()
    {
        $admin_users = User::when(request('search_admins'), function($q){
                            $q->where('name', 'like', '%'.request('search_admins').'%')
                                ->where('role', 'admin')
                                ->orWhere('email', 'like', '%'.request('search_admins').'%');
                        })->where('role', 'admin')->get();
       return view('admin.index', ['admin_users' => $admin_users]);
    }
    public function addAdmin()
    {
        return view('admin.add_admin');
    }
    public function createAdmin(AdminCreateRequest $request)
    {
        $admin = new User;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->role = 'admin';
        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->back()->with('success', 'Admin accound was created for '.$request->name);
    }
}