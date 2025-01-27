<?php
namespace Modules\Blog\Repositories;

use Modules\Blog\Models\Post;

class PostRepository
{
    public function getAll($request): \Illuminate\Database\Eloquent\Collection
    {
        return Post::with(['user:id,name,username,photo', 'myReaction', 'reactionBrief'])
            ->withCount(['comments', 'reactions'])
            ->where(function ($query) use ($request) {
                if ($request->user_id) {
                    $query->where('posts.user_id', $request->user_id);
                }

                if ($request->search != null) {
                    $query->where(function ($subquery) use ($request) {
                        $subquery->where('content', 'LIKE', "%$request->search%")
                            ->orWhereHas('user', function ($q) use ($request) {
                                $q->where('name', 'LIKE', "%$request->search%");
                            });
                    });
                }
            })
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function getByParams($request)
    {
        $per_page = $request->per_page ? $request->per_page : 10;

        return Post::with(['user:id,name,username,photo', 'myReaction', 'reactionBrief'])
            ->withCount(['comments', 'reactions'])
            ->where(function ($query) use ($request) {
                if ($request->user_id) {
                    $query->where('posts.user_id', $request->user_id);
                }

                if ($request->query != null) {
                    $query->where(function ($subquery) use ($request) {
                        $subquery->where('content', 'LIKE', "%$request->search%")
                            ->orWhereHas('user', function ($q) use ($request) {
                                $q->where('name', 'LIKE', "%$request->search%");
                            });
                    });
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($per_page);
    }

    public function findById($id): ?Post
    {
        return Post::with(['user:id,name,username,photo', 'myReaction', 'reactionBrief'])
            ->withCount(['comments', 'reactions'])
            ->findOrFail($id);
    }

    public function create($data): Post
    {
        return Post::create($data);
    }

    public function update($id, $data): bool
    {
        $model = Post::findOrFail($id);
        return $model ? $model->update($data) : false;
    }

    public function delete($id): bool
    {
        $model = Post::findOrFail($id);
        return $model ? $model->delete() : false;
    }
}
