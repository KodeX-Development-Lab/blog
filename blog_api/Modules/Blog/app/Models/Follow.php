<?php
namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Blog\Database\Factories\FollowFactory;

class Follow extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['follower_id', 'following_id'];

    // protected static function newFactory(): FollowFactory
    // {
    //     // return FollowFactory::new();
    // }
}
