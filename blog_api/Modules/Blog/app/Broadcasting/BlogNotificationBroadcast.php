<?php

namespace Modules\Blog\Broadcasting;

use Illuminate\Support\Facades\Auth;
use Modules\Blog\Models\BlogUser;

class BlogNotificationBroadcast
{

    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Authenticate the user's access to the channel.
     */
    public function join(BlogUser $user): array|bool
    {
        return Auth::check() && (int) $user->id == Auth::id();
    }
   
}