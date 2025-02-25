<?php

use Illuminate\Support\Facades\Broadcast;
use Modules\Blog\Broadcasting\BlogNotificationBroadcast;

Broadcast::channel('blog-notification', BlogNotificationBroadcast::class);
