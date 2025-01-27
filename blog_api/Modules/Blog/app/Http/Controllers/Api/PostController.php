<?php
namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\PostRequest;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\PostService;
use Modules\Blog\Transformers\PostResource;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = $this->postService->getByParams($request);

        $items = $posts->getCollection();

        $items = collect($items)->map(function ($item) {
            return new PostResource($item);
        });

        $posts = $posts->setCollection($items);

        return $this->responseFromAPI("success", 200, ['posts' => $posts], null);
    }

    public function getAll(Request $request)
    {
        $posts = $this->postService->getAll($request);

        return $this->responseFromAPI("success", 200, ['posts' => PostResource::collection($posts)], null);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $request->merge(['user_id' => auth()->user()->id]);

        $post = $this->postService->create($request->toArray());
        $post = $this->postService->findById($post->id);

        return $this->responseFromAPI("success", 201, ['post' => new PostResource($post)], "Successfully saved", null);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $post = $this->postService->findById($id);

        return $this->responseFromAPI("success", 200, ['post' => new PostResource($post)], "Success", null);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->postService->update($post->id, $request->toArray());

        $post = $this->postService->findById($post->id);

        return $this->responseFromAPI("success", 200, ['post' => new PostResource($post)], "Successfully updated", null);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $success = $this->postService->delete($id);

        if ($success) {
            return response()->json([], 204);
        }

        return $this->responseFromAPI("error", 500, null, "Error", null);
    }
}
