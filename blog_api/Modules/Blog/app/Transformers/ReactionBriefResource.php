<?php
namespace Modules\Blog\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReactionBriefResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->reactionType->id,
            'name'         => $this->reactionType->name,
            'reactedIcon' => $this->reactionType->reacted_icon,
            'count'        => $this->count,
        ];
    }
}
