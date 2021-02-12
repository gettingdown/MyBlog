<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BlacklistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('api')->group(function() {
    Route::post('/register',[AuthoController::class,'register']);
    Route::post('/auth',[AuthoController::class,'auth']);
    Route::middleware('auth:api')->group(function() {
        Route::get('/profile/{userid}', [UserController::class, 'getUser'])->middleware('ChekBlackList:user');
        Route::get('/profile', [UserController::Class, 'myprofile']);
        Route::post('/profile/edit', [UserController::Class, 'profile_edit']);
        Route::get('/blacklist',[BlacklistController::Class,'getBlacklist']);
        Route::post('/blacklist/{id}',[BlacklistController::Class,'blacklistuser']);
        Route::post('/subscribe',[SubscriptionController::Class,'subsribetouser'])->middleware('ChekBlacklist:user');
        Route::post('/posts/new_post',[PostController::Class,'createpost']);
        Route::delete('/posts/delete/{id}',[PostController::Class,'deletePost']);
        Route::get('/{id}/posts',[PostController::Class,'getpost'])->middleware('ChekBlacklist:user');
        Route::get('/profile/posts',[PostController::Class,'mypost']);
        Route::get('/profile/subscriptions',[SubscriptionController::Class,'getsubscriptions']);
        Route::post('/{postid}/create_comment',[CommentController::Class,'createcomment'])->middleware('chekBlacklist:post');
        Route::get('/{postid}/comments',[CommentController::Class,'getcomments'])->middleware('chekBlacklist:post');
        Route::delete('/post/{commentid}/delete',[CommentController::Class,'deletecomment']);
    });

});

