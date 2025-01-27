<?php
namespace Modules\Blog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Database\Factories\PostFactory;

// use Modules\Blog\Database\Factories\PostFactory;

class Post extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'content', 'last_updated_at'];

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new ();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function directComments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_comment_id');
    }

    public function reactionBrief()
    {
        return $this->hasMany(PostReaction::class)
            ->with(['reactionType'])
            ->select('post_reactions.post_id', 'post_reactions.reaction_type_id', DB::raw('COUNT(post_reactions.id) AS count'))
            ->groupBy('post_reactions.reaction_type_id')
            ->groupBy('post_reactions.post_id');
    }

    public function reactions()
    {
        return $this->hasMany(PostReaction::class, 'post_id');
    }

    public function myReaction()
    {
        return $this->hasOne(PostReaction::class)->with(['reactionType'])->where('user_id', Auth::id());
    }
}
