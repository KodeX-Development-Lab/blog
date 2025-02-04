<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\Api\BlogNotificationController;
use Modules\Blog\Http\Controllers\Api\BlogUserController;
use Modules\Blog\Http\Controllers\Api\CommentController;
use Modules\Blog\Http\Controllers\Api\CommentReactionController;
use Modules\Blog\Http\Controllers\Api\PostController;
use Modules\Blog\Http\Controllers\Api\PostReactionController;
use Modules\Blog\Http\Controllers\Api\ReactionTypeController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(JwtMiddleware::class)->prefix('v1')->group(function () {

    Route::get('all-reaction-types', [ReactionTypeController::class, 'getAll'])->name('posts.all');
    Route::get('reaction-types', [ReactionTypeController::class, 'index'])->name('posts.index');

    Route::get('all-posts', [PostController::class, 'getAll'])->name('posts.all');
    Route::apiResource('posts', PostController::class)->names('posts');

    Route::get('users/{user_id}', [BlogUserController::class, 'show'])->name('users.show');
    Route::post('users/{user_id}/follow', [BlogUserController::class, 'follow'])->name('users.follow');

    Route::get('users/{user_id}/all-posts', [BlogUserController::class, 'getAllPosts'])->name('users.show.posts.all');
    Route::get('users/{user_id}/posts', [BlogUserController::class, 'getPostByParams'])->name('users.show.posts.index');

    Route::get('posts/{post_id}/brief-reactions', [PostReactionController::class, 'getGroupByCount'])->name('post.show.reactions.brief');
    Route::get('posts/{post_id}/all-reactions', [PostReactionController::class, 'getAll'])->name('post.show.reactions.all');
    Route::get('posts/{post_id}/reactions', [PostReactionController::class, 'index'])->name('post.show.reactions.index');
    Route::post('posts/{post_id}/reactions', [PostReactionController::class, 'makeReaction'])->name('post.show.reactions.store');

    Route::get('posts/{post_id}/all-comments', [CommentController::class, 'getAll'])->name('post.show.comments.all');
    Route::apiResource('posts/{post_id}/comments', CommentController::class)->names('post.show.comments');

    Route::get('posts/{post_id}/comments/{comment_id}/brief-reactions', [CommentReactionController::class, 'getGroupByCount'])->name('comments.show.reactions.brief');
    Route::get('posts/{post_id}/comments/{comment_id}/all-reactions', [CommentReactionController::class, 'getAll'])->name('comments.show.reactions.all');
    Route::get('posts/{post_id}/comments/{comment_id}/reactions', [CommentReactionController::class, 'index'])->name('comments.show.reactions.index');
    Route::post('posts/{post_id}/comments/{comment_id}/reactions', [CommentReactionController::class, 'makeReaction'])->name('comments.show.reactions.store');

    Route::prefix('/blog-app')->group(function () {
        Route::get('profile', [BlogUserController::class, 'getMyProfileData'])->name('myprofile.index');
        Route::get('profile/all-my-posts', [BlogUserController::class, 'getAllMyPosts'])->name('myprofile.posts.all');
        Route::get('profile/my-posts', [BlogUserController::class, 'getMyPostsByParams'])->name('myprofile.posts.index');

        Route::get('all-notifications', [BlogNotificationController::class, 'getAll'])->name('notifications.all');
        Route::apiResource('notifications', BlogNotificationController::class)->names('notifications');
        Route::patch('notifications/{notification_id}/read', [BlogNotificationController::class, 'read'])->name('notifications.read');
        Route::patch('mark-as-read-notifications', [BlogNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    });
});
