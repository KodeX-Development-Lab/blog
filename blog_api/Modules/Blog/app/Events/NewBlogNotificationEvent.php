<?php
namespace Modules\Blog\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBlogNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $channel_name;
    private $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel_name, $message)
    {
        $this->channel_name = $channel_name;
        $this->message      = $message;
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel($this->channel_name);
    }

    public function broadcastAs()
    {
        return 'noti-received';
    }
}
