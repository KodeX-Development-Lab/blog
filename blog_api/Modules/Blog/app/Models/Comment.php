<?php
namespace Modules\Blog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Database\Factories\CommentFactory;

// use Modules\Blog\Database\Factories\CommentFactory;

class Comment extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'post_id', 'parent_comment_id', 'content'];

    protected static function newFactory(): CommentFactory
    {
        return CommentFactory::new ();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function childComments()
    {
        return $this->hasMany(Comment::class, 'parent_comment_id');
    }

    public function reactionBrief()
    {
        return $this->hasMany(CommentReaction::class)
            ->with(['reactionType'])
            ->select('comment_reactions.comment_id', 'comment_reactions.reaction_type_id', DB::raw('COUNT(comment_reactions.id) AS count'))
            ->groupBy('comment_reactions.reaction_type_id')
            ->groupBy('comment_reactions.comment_id');
    }

    public function reactions()
    {
        return $this->hasMany(CommentReaction::class);
    }

    public function myReaction()
    {
        return $this->hasOne(CommentReaction::class)->with(['reactionType'])->where('user_id', Auth::id());
    }
}
