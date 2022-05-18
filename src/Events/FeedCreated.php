<?php

namespace Marufsharia\ActivityStreams\Events;

use Marufsharia\ActivityStreams\Models\Feed;

class FeedCreated extends Event
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
