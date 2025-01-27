<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Media\Services\ObjectStorage;

// use Modules\Blog\Database\Factories\ReactionTypeFactory;

class ReactionType extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'icon', 'reacted_icon', 'order', 'is_default'];

    // protected static function newFactory(): ReactionTypeFactory
    // {
    //     // return ReactionTypeFactory::new();
    // }

    public function getIconAttribute($icon)
    {
        $storage = new ObjectStorage();

        return $icon ? $storage->getUrl($icon) : null;
    }

    public function getReactedIconAttribute($icon)
    {
        $storage = new ObjectStorage();

        return $icon ? $storage->getUrl($icon) : null;
    }

}