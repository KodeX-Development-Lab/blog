<?php
namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CommentRequest;
use Modules\Blog\Models\Comment;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\CommentService;
use Modules\Blog\Transformers\CommentResource;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $post_id)
    {
        $comments = $this->commentService->getByParams($request);

        $items = $comments->getCollection();

        $items = collect($items)->map(function ($item) {
            return new CommentResource($item);
        });

        $comments = $comments->setCollection($items);

        return $this->responseFromAPI("success", 200, ['comments' => $comments], null);
    }

    public function getAll(Request $request, $post_id)
    {
        $comments = $this->commentService->getAll($request);

        return $this->responseFromAPI("success", 200, ['comments' => CommentResource::collection($comments)], null);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, $post_id)
    {
        $request->merge([
            'user_id' => auth()->user()->id,
            'post_id' => $post_id,
        ]);

        $comment = $this->commentService->create($request->toArray());
        $comment = $this->commentService->findById($comment->id);

        return $this->responseFromAPI("success", 201, ['comment' => new CommentResource($comment)], "Successfully saved", null);
    }

    /**
     * Show the specified resource.
     */
    public function show($post_id, $comment_id)
    {
        $comment = $this->commentService->findById($comment_id);

        return $this->responseFromAPI("success", 200, ['comment' => new CommentResource($comment)], "Success", null);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, $post_id, $comment_id)
    {
        $this->commentService->update($comment_id, $request->toArray());

        $comment = $this->commentService->findById($comment_id);

        return $this->responseFromAPI("success", 200, ['comm$comment' => new CommentResource($comment)], "Successfully updated", null);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($post_id, $comment_id)
    {
        $success = $this->commentService->delete($comment_id);

        if ($success) {
            return response()->json([], 204);
        }

        return $this->responseFromAPI("error", 500, null, "Error", null);
    }
}
