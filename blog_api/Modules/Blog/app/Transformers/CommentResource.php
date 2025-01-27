<?php
namespace Modules\Blog\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $user = auth()->user();

        return [
            'id'             => $this->id,
            'user'           => $this->user,
            'content'        => $this->content,
            'myReaction'     => $this->myReaction,
            'reactionBrief'  => ReactionBriefResource::collection($this->reactionBrief),
            'reactionsCount' => $this->reactions_count,
            'createdAt'      => $this->created_at,
            'updatedAt'      => $this->updated_at,
            'canEdit'        => $this->user_id == $user->id ? true : false,
            'canDelete'      => $this->user_id == $user->id ? true : false,
        ];
    }
}
