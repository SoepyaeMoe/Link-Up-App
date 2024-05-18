<?php

use App\Livewire\UserInfo;
use App\Livewire\UserDetails;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryTrackerController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('dashboard');
    }
    return view('auth.login');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'userDetail']);
    // admin user
    Route::get('admin-users', [AdminController::class, 'index']);
    // add admin page
    Route::get('add-admin', [AdminController::class, 'addAdmin']);
    // create admin
    Route::post('add-admin', [AdminController::class, 'createAdmin']);
    // history
    Route::get('admin-history', [HistoryTrackerController::class, 'index']);
    // admin user history
    Route::get('admin-history/{id}', [HistoryTrackerController::class, 'index']);

    Route::get('following/{id}', [UserController::class, 'following']);
    Route::get('follower/{id}', [UserController::class, 'follower']);
    // edit user info
    Route::post('edit-user-info', [UserController::class, 'editUserInfo']);
    // delete follower and following
    Route::get('remove-follower', [UserController::class, 'removeFollower']);
    // delete user
    Route::get('delete-user', [UserController::class, 'deleteUser']);
    // reset password
    Route::post('reset-password', [UserController::class, 'resetPassword']);

    // get contents
    Route::get('contents', [ContentController::class, 'index']);
    // delete content
    Route::get('delete-content', [ContentController::class, 'deleteContent']);
    // content detail
    Route::get('contents/{id}', [ContentController::class, 'contentDetail']);
    // reacts list in content
    Route::get('reacts/{content_id}', [ContentController::class, 'reacts']);
    // remove react
    Route::get('delete-react', [ContentController::class, 'deleteReact']);
    // comments in content
    Route::get('comments/{content_id}', [ContentController::class, 'comments']);
    // delete comment
    Route::get('delete-comment', [ContentController::class, 'deleteComment']);

});