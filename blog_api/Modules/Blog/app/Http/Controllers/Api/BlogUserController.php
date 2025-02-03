<?php
namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        return $this->responseFromAPI("success", 200, ['posts' => PostResource::collection($posts)], null);
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
