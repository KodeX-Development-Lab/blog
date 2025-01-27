<?php
namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CommentReactionRequest;
use Modules\Blog\Models\ReactionType;
use Modules\Blog\Services\CommentReactionService;
use Modules\Blog\Transformers\ReactionResource;

class CommentReactionController extends Controller
{
    private $commentReactionService;

    public function __construct(CommentReactionService $commentReactionService)
    {
        $this->commentReactionService = $commentReactionService;
    }

    public function getGroupByCount(Request $request, $post_id, $comment_id)
    {
        $reactionsCounts = $this->commentReactionService->getGroupByCount($comment_id);

        return $this->responseFromAPI("success", 200, ['reactionsCounts' => $reactionsCounts], null);
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

        return $this->responseFromAPI("success", 200, [], null);
    }

}
