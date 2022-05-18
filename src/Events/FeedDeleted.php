<?php

namespace Marufsharia\ActivityStreams\Events;

use Marufsharia\ActivityStreams\Models\Feed;

class FeedDeleted extends Event
{
    /**
     * @var Feed
     */
    public $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }
}
