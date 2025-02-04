<?php
namespace Modules\Blog\Repositories;

use Carbon\Carbon;
use Modules\Blog\Models\BlogNotification;

class BlogNotificationRepository
{
    public function getAll($request): \Illuminate\Database\Eloquent\Collection
    {
        return BlogNotification::with(['user:id,name,username,photo'])
            ->where('user_id', $request->user_id)
            ->where(function ($query) use ($request) {
                if ($request->search != null) {
                    $query->where('content', 'LIKE', "%$request->search%");
                }
            })
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function getByParams($request)
    {
        $per_page = $request->per_page ? $request->per_page : 10;

        return BlogNotification::with(['user:id,name,username,photo'])
            ->where('user_id', $request->user_id)
            ->where(function ($query) use ($request) {
                if ($request->search != null) {
                    $query->where('content', 'LIKE', "%$request->search%");
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($per_page);
    }

    public function findById($id): ?BlogNotification
    {
        return BlogNotification::with(['user:id,name,username,photo'])
            ->findOrFail($id);
    }

    public function save($data)
    {
        return BlogNotification::create($data);
    }

    public function read($noti_id)
    {
        return BlogNotification::where('id', $noti_id)->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);
    }

    public function markAsRead($user_id)
    {
        return BlogNotification::where('user_id', $user_id)->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);
    }
}
