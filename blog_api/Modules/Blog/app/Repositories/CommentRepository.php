<?php
namespace Modules\Blog\Repositories;

use Modules\Blog\Models\Comment;

class CommentRepository
{
    public function getAll($request): \Illuminate\Database\Eloquent\Collection
    {
        return Comment::with(['user:id,name,username,photo', 'myReaction', 'reactionBrief'])
            ->withCount(['reactions'])
            ->where('post_id', $request->post_id)
            ->where(function ($query) use ($request) {
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

        return Comment::with(['user:id,name,username,photo', 'myReaction', 'reactionBrief'])
            ->withCount(['reactions'])
            ->where('post_id', $request->post_id)
            ->where(function ($query) use ($request) {
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
            ->paginate($per_page);
    }

    public function findById($id): ?Comment
    {
        return Comment::with(['user:id,name,username,photo', 'myReaction', 'reactionBrief'])
            ->withCount(['reactions'])
            ->findOrFail($id);
    }

    public function create($data): Comment
    {
        return Comment::create($data);
    }

    public function update($id, $data): bool
    {
        $model = Comment::findOrFail($id);
        return $model ? $model->update($data) : false;
    }

    public function delete($id): bool
    {
        $model = Comment::findOrFail($id);
        return $model ? $model->delete() : false;
    }
}
