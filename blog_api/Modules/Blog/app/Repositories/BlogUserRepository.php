<?php
namespace Modules\Blog\Repositories;

use Modules\Blog\Models\BlogUser;
use Modules\Blog\Models\Follow;
use Modules\Blog\Models\PostReaction;

class BlogUserRepository
{

    public function getAll($request): \Illuminate\Database\Eloquent\Collection
    {
        return PostReaction::with(['user:id,name,username,photo', 'reactionType'])
            ->where('post_id', $request->post_id)
            ->where(function ($query) use ($request) {
                if ($request->reaction_type != 'all' && $request->reaction_type != null) {
                    $query->where('reaction_type_id', $request->reaction_type);
                }

                if ($request->search != null) {
                    $query->whereHas('user', function ($q) use ($request) {
                        $q->where('name', 'LIKE', "%$request->search%");
                    });
                }
            })
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function getByParams($request)
    {
        $per_page = $request->per_page ? $request->per_page : 10;

        return PostReaction::with(['user:id,name,username,photo', 'reactionType'])
            ->where('post_id', $request->post_id)
            ->where(function ($query) use ($request) {
                if ($request->reaction_type != 'all' && $request->reaction_type != null) {
                    $query->where('reaction_type_id', $request->reaction_type);
                }

                if ($request->search != null) {
                    $query->whereHas('user', function ($q) use ($request) {
                        $q->where('name', 'LIKE', "%$request->search%");
                    });
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($per_page);
    }

    public function getUser($user_id)
    {
        return BlogUser::with(['followers:id,name,username,photo', 'followings:id,name,username,photo'])
            ->findOrFail($user_id);
    }

    public function follow($data)
    {
        $record = Follow::where('follower_id', $data['follower_id'])
            ->where('following_id', $data['following_id'])
            ->first();

        if ($record) {
            $record->delete();
            return;
        }

        Follow::create(
            [
                'follower_id'  => $data['follower_id'],
                'following_id' => $data['following_id'],
            ]
        );

        return;
    }
}
