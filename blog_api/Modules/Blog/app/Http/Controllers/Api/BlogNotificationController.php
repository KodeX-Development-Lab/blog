<?php
namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Blog\Services\BlogNotificationService;
use Modules\Blog\Transformers\BlogNotificationResource;

class BlogNotificationController extends Controller
{
    private BlogNotificationService $blogNotificationService;

    public function __construct(BlogNotificationService $blogNotificationService)
    {
        $this->blogNotificationService = $blogNotificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $request->merge(['user_id' => $user->id]);

        $notifications = $this->blogNotificationService->getByParams($request);

        $items = $notifications->getCollection();

        $items = collect($items)->map(function ($item) {
            return new BlogNotificationResource($item);
        });

        $notifications = $notifications->setCollection($items);

        return $this->responseFromAPI("success", 200, ['notifications' => $notifications], null);
    }

    public function getAll(Request $request)
    {
        $user = Auth::user();
        $request->merge(['user_id' => $user->id]);

        $notifications = $this->blogNotificationService->getAll($request);

        return $this->responseFromAPI("success", 200, ['notifications' => BlogNotificationResource::collection($notifications)], null);
    }

    public function show($notification_id)
    {
        $user = Auth::user();

        $notification = $this->blogNotificationService->findById($notification_id);

        if($notification->user_id != $user->id) {
            return $this->responseUnAuthorizedFromAPI();
        }

        return $this->responseFromAPI("success", 200, ['notification' => new BlogNotificationResource($notification)], null);
    }

    public function read($notification_id)
    {
        $user = Auth::user();

        $notification = $this->blogNotificationService->findById($notification_id);

        if($notification->user_id != $user->id) {
            return $this->responseUnAuthorizedFromAPI();
        }

        $this->blogNotificationService->read($notification_id);

        $notification = $this->blogNotificationService->findById($notification_id);

        return $this->responseFromAPI("success", 200, ['notification' => new BlogNotificationResource($notification)], null);
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();

        $this->blogNotificationService->markAsRead($user->id);

        $request->merge(['user_id' => $user->id]);
        $notifications = $this->blogNotificationService->getAll($request);

        return $this->responseFromAPI("success", 200, ['notification' => BlogNotificationResource::collection($notifications)], null);
    }
}
