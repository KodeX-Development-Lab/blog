<?php
namespace Modules\Blog\Models;

use App\Models\User;

// use Modules\Blog\Database\Factories\FollowFactory;

class BlogUser extends User
{
    protected $table = "users";

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, Follow::class, 'following_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, Follow::class, 'follower_id', 'following_id');
    }
}
