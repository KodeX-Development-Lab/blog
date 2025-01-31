<?php
namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\PostReactionRequest;
use Modules\Blog\Services\PostReactionService;
use Modules\Blog\Transformers\ReactionResource;

class PostReactionController extends Controller
{
    private $postReactionService;

    public function __construct(PostReactionService $postReactionService)
    {
        $this->postReactionService = $postReactionService;
    }

    public function getGroupByCount(Request $request, $post_id)
    {
        $reactionsCounts = $this->postReactionService->getGroupByCount($post_id);

        return $this->responseFromAPI("success", 200, ['reactionsCounts' => $reactionsCounts], null);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $post_id)
    {
        $request->merge([
            'post_id' => $post_id,
        ]);

        $reactions = $this->postReactionService->getByParams($request);

        $items = $reactions->getCollection();

        $items = collect($items)->map(function ($item) {
            return new ReactionResource($item);
        });

        $reactions = $reactions->setCollection($items);

        return $this->responseFromAPI("success", 200, ['reactions' => $reactions], null);
    }

    public function getAll(Request $request, $post_id)
    {
        $request->merge([
            'post_id' => $post_id,
        ]);

        $reactions = $this->postReactionService->getAll($request);

        return $this->responseFromAPI("success", 200, ['reactions' => ReactionResource::collection($reactions)], null);
    }

    public function makeReaction(PostReactionRequest $request, $post_id)
    {
        $request->merge([
            'user_id' => auth()->user()->id,
            'post_id' => $post_id,
        ]);

        $this->postReactionService->makeReaction($request->toArray());

        return $this->responseFromAPI("success", 200, [], null);
    }

}
