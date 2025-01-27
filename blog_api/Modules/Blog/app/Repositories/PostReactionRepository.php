<?php
namespace Modules\Blog\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Blog\Models\PostReaction;
use Modules\Blog\Models\ReactionType;

class PostReactionRepository
{
    public function getGroupByCount($post_id)
    {
        return ReactionType::join('post_reactions', 'post_reactions.reaction_type_id', 'reaction_types.id')
            ->where('post_reactions.post_id', $post_id)
            ->select('reaction_types.id', 'reaction_types.name', 'reaction_types.icon', DB::raw('COUNT(post_reactions.id) AS count'))
            ->groupBy('post_reactions.reaction_type_id')
            ->get();
    }

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

    public function makeReaction($data)
    {
        $record = PostReaction::where('post_id', $data['post_id'])
            ->where('user_id', $data['user_id'])
            ->where('reaction_type_id', $data['reaction_type_id'])
            ->first();

        if ($record) {
            $record->delete();
            return;
        }

        return PostReaction::updateOrCreate(
            [
                'post_id' => $data['post_id'],
                'user_id' => $data['user_id'],
            ],
            [
                'post_id'          => $data['post_id'],
                'user_id'          => $data['user_id'],
                'reaction_type_id' => $data['reaction_type_id'],
            ]
        );
    }
}
