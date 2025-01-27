<?php
namespace Modules\Blog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Blog\Database\Factories\CommentReactionFactory;

class CommentReaction extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['comment_id', 'user_id', 'reaction_type_id'];

    // protected static function newFactory(): CommentReactionFactory
    // {
    //     // return CommentReactionFactory::new();
    // }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactionType()
    {
        return $this->belongsTo(ReactionType::class);
    }
}
