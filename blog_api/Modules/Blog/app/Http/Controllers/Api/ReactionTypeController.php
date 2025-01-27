<?php
namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\PostRequest;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\ReactionType;
use Modules\Blog\Services\PostService;
use Modules\Blog\Transformers\PostResource;

class ReactionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reactionTypes = ReactionType::orderBy('order')->paginate(10);

        return $this->responseFromAPI("success", 200, ['reactionTypes' => $reactionTypes], null);
    }

    /**
     * Display a listing of the resource.
     */
    public function getAll(Request $request)
    {
        $reactionTypes = ReactionType::orderBy('order')->get();

        return $this->responseFromAPI("success", 200, ['reactionTypes' => $reactionTypes], null);
    }
}
