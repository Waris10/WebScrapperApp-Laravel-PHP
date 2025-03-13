<?php

namespace App\Events;

use App\Models\Scraper;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ScraperCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $scraper;
    /**
     * Create a new event instance.
     */
    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('private.scraper.' . $this->scraper->user_id);
    }

    public function broadcastAs(): string
    {
        return 'scraper-created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->scraper->id,
            'user_id' => $this->scraper->user_id,
            'url' => $this->scraper->url,
        ];
    }
}
