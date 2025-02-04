<?php
namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Blog\Http\Requests\FollowRequest;
use Modules\Blog\Services\BlogUserService;
use Modules\Blog\Services\PostService;
use Modules\Blog\Transformers\BlogUserResource;
use Modules\Blog\Transformers\PostResource;

class BlogUserController extends Controller
{
    private $blogUserService, $postService;

    public function __construct(BlogUserService $blogUserService, PostService $postService)
    {
        $this->blogUserService = $blogUserService;
        $this->postService     = $postService;
    }

    public function getMyProfileData()
    {
        $user = Auth::user();
        $user = $this->blogUserService->getUser($user->id);

        return $this->responseFromAPI("success", 200, ['user' => new BlogUserResource($user)], null);
    }

    public function getAllMyPosts(Request $request)
    {
        $user = Auth::user();

        $request->merge([
            'user_id' => $user->id,
        ]);

        $posts = $this->postService->getAll($request);

        return $this->responseFromAPI("success", 200, ['posts' => PostResource::collection($posts)], null);
    }

    public function getMyPostsByParams(Request $request)
    {
        $user = Auth::user();

        $request->merge([
            'user_id' => $user->id,
        ]);

        $posts = $this->postService->getByParams($request);
        $items = $posts->getCollection();

        $items = collect($items)->map(function ($item) {
            return new PostResource($item);
        });

        $posts = $posts->setCollection($items);

        return $this->responseFromAPI("success", 200, ['posts' => $posts], null);
    }

    /**
     * Display a listing of the resource.
     */
    public function show(Request $request, $user_id)
    {
        $user = $this->blogUserService->getUser($user_id);

        return $this->responseFromAPI("success", 200, ['user' => new BlogUserResource($user)], null);
    }

    public function getPostByParams(Request $request, $user_id)
    {
        $request->merge([
            'user_id' => $user_id,
        ]);

        $posts = $this->postService->getByParams($request);
        $items = $posts->getCollection();

        $items = collect($items)->map(function ($item) {
            return new PostResource($item);
        });

        $posts = $posts->setCollection($items);

        return $this->responseFromAPI("success", 200, ['posts' => $posts], null);
    }

    public function getAllPosts(Request $request, $user_id)
    {
        $request->merge([
            'user_id' => $user_id,
        ]);

        $posts = $this->postService->getAll($request);

        return $this->responseFromAPI("success", 200, ['posts' => PostResource::collection($posts)], null);
    }

    public function follow(FollowRequest $request, $user_id)
    {
        $request->merge([
            'follower_id'  => auth()->user()->id,
            'following_id' => $user_id,
        ]);

        $this->blogUserService->follow($request->toArray());

        return $this->responseFromAPI("success", 200, [], null);
    }

}
