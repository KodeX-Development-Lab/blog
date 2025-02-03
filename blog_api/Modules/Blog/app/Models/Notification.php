<?php
namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Blog\Database\Factories\NotificationFactory;

class Notification extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['type', 'content', 'post_id', 'user_id', 'is_read', 'read_at'];

    // protected static function newFactory(): NotificationFactory
    // {
    //     // return NotificationFactory::new();
    // }
}
