<?php

namespace Marufsharia\ActivityStreams\Events;

use Marufsharia\ActivityStreams\Models\Activity;

class ActivityCreated extends Event
{
    /**
     * @var Activity
     */
    public $activity;

    public function __construct(Activity $activity)
    {
        $this->activity  = $activity;
    }
}
