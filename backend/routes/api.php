<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReactController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\SaveContentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Auth\RegisterController;

// login
Route::post('login', [LoginController::class, 'login']);

// register
Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // get users
    Route::get('users', [UserController::class, 'userList']);

    // logout
    Route::post('logout', [LoginController::class, 'logout']);

    // delete accound
    Route::delete('delete-accound', [UserController::class, 'deleteAccound']);

    // password check
    Route::post('password-check', [UserController::class, 'passwordCheck']);

    // get auth user data
    Route::get('user', [UserController::class, 'userInfo']);

    // update auth user data
    Route::post('user', [UserController::class, 'update']);

    // change password
    Route::post('change-password', [UserController::class, 'changePassword']);

    // get contents
    Route::get('contents', [ContentController::class, 'getContnets']);

    // get user's contents -->id => user_id
    Route::get('contents/{id}', [ContentController::class, 'getUserContents']);

    // content image upload
    Route::post('upload-content-img', [ContentController::class, 'upload']);

    // content delete
    Route::delete('content/{id}', [ContentController::class, 'deleteContent']);

    // content cancel upload
    Route::post('upload-cancel', [ContentController::class, 'uploadCancel']);

    // content crete
    Route::post('create-content', [ContentController::class, 'create']);

    // send comment
    Route::post('comments/{content_id}', [CommentController::class, 'sendComment']);

    // get comments in a content , will need content id
    Route::get('comments/{content_id}', [CommentController::class, 'getComments']);

    // edit comment
    Route::put('comments/{comment_id}', [CommentController::class, 'editComment']);

    // delete comment
    Route::delete('comments/{comment_id}', [CommentController::class, 'deleteComment']);

    // sent react or unsent react
    Route::post('react/{content_id}', [ReactController::class, 'toggleReact']);

    // follow other user
    Route::post('follow/{user_id}', [FollowerController::class, 'toggleFollow']);

    // get saved contents
    Route::get('saved-content', [SaveContentController::class, 'savedContent']);

    // save contents
    Route::post('save-content', [SaveContentController::class, 'saveContent']);

    // saved content detail
    Route::get('content-detail/{content_id}', [SaveContentController::class, 'contentDetail']);

    // search content
    Route::post('search-content', [ContentController::class, 'searchContent']);

    // profile photo upload
    Route::post('profile-upload', [UserController::class, 'uploadProfile']);

    // get notifications
    Route::get('notification', [NotificationController::class, 'getNoti']);

    Route::delete('notification', [NotificationController::class, 'removeNoti']);

    // mark as read notifications
    Route::get('notification-read', [NotificationController::class, 'markAsRead']);
});
