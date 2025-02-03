<?php
namespace Modules\Blog\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class BlogUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $auth_user_id = Auth::id();

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'username'        => $this->username,
            'bio'             => $this->bio,
            'photo'           => $this->photo,
            'joinedAt'        => $this->created_at,
            'alreadyFollowed' => in_array($auth_user_id, $this->followers->pluck('id')->toArray()) ? true : false,
            'followers'       => $this->followers,
            'followings'      => $this->followings,
        ];
    }
}
