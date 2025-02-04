<?php
namespace Modules\Blog\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user'       => $this->user,
            'post_id'    => $this->post_id,
            'comment_id' => $this->comment_id,
            'type'       => $this->type,
            'content'    => $this->content,
            'is_read'    => $this->is_read ? true : false,
            'readAt'     => $this->read_at,
            'createdAt'  => $this->created_at,
            'updatedAt'  => $this->updated_at,
        ];
    }
}
