<?php
namespace Modules\Blog\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Blog\Models\CommentReaction;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\ReactionType;

class CommentReactionRepository
{
    public function getGroupByCount($comment_id)
    {
        return ReactionType::join('comment_reactions', 'comment_reactions.reaction_type_id', 'reaction_types.id')
            ->where('comment_reactions.comment_id', $comment_id)
            ->select('reaction_types.id', 'reaction_types.name', 'reaction_types.icon', DB::raw('COUNT(comment_reactions.id) AS count'))
            ->groupBy('comment_reactions.reaction_type_id')
            ->get();
    }

    public function getAll($request): \Illuminate\Database\Eloquent\Collection
    {
        return CommentReaction::with(['user:id,name,username,photo', 'reactionType'])
            ->where('comment_id', $request->comment_id)
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

        return CommentReaction::with(['user:id,name,username,photo', 'reactionType'])
            ->where('comment_id', $request->comment_id)
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
        $record = CommentReaction::where('comment_id', $data['comment_id'])
            ->where('user_id', $data['user_id'])
            ->where('reaction_type_id', $data['reaction_type_id'])
            ->first();

        if ($record) {
            $record->delete();
            return;
        }

        return CommentReaction::updateOrCreate(
            [
                'comment_id' => $data['comment_id'],
                'user_id'    => $data['user_id'],
            ],
            [
                'comment_id'       => $data['comment_id'],
                'user_id'          => $data['user_id'],
                'reaction_type_id' => $data['reaction_type_id'],
            ]
        );
    }
}
