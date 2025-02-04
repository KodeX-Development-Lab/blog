<?php
namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CommentReactionRequest;
use Modules\Blog\Models\ReactionType;
use Modules\Blog\Services\CommentReactionService;
use Modules\Blog\Services\CommentService;
use Modules\Blog\Transformers\CommentResource;
use Modules\Blog\Transformers\ReactionResource;

class CommentReactionController extends Controller
{
    private $commentReactionService;
    private $commentService;

    public function __construct(CommentReactionService $commentReactionService, CommentService $commentService)
    {
        $this->commentReactionService = $commentReactionService;
        $this->commentService = $commentService;
    }

    public function getGroupByCount(Request $request, $post_id, $comment_id)
    {
        $reactionsCounts = $this->commentReactionService->getGroupByCount($comment_id);

        $allReactionsCount = $reactionsCounts->toArray();
        array_unshift($allReactionsCount,
            [
                "id"    => "",
                "name"  => "All",
                "icon"  => "",
                "count" => $reactionsCounts->sum('count') ?? 0,
            ]);

        return $this->responseFromAPI("success", 200, ['reactionsCounts' => $allReactionsCount], null);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $post_id, $comment_id)
    {
        $reactions = $this->commentReactionService->getByParams($request);

        $items = $reactions->getCollection();

        $items = collect($items)->map(function ($item) {
            return new ReactionResource($item);
        });

        $reactions = $reactions->setCollection($items);

        return $this->responseFromAPI("success", 200, ['reactions' => $reactions], null);
    }

    public function getAll(Request $request)
    {
        $reactions = $this->commentReactionService->getAll($request);

        return $this->responseFromAPI("success", 200, ['reactions' => ReactionResource::collection($reactions)], null);
    }

    public function makeReaction(CommentReactionRequest $request, $post_id, $comment_id)
    {
        $request->merge([
            'user_id'    => auth()->user()->id,
            'comment_id' => $comment_id,
        ]);

        $this->commentReactionService->makeReaction($request->toArray());

        return $this->responseFromAPI("success", 200, [
            'comment' => new CommentResource($this->commentService->findById($comment_id))
        ], null);
    }

}
