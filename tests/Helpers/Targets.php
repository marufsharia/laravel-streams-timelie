<?php

namespace Marufsharia\ActivityStreams\Tests\Helpers\Targets;

use Marufsharia\ActivityStreams\Contracts\ActivityTarget;

class SampleTarget implements ActivityTarget
{
    public function getType(): string
    {
        return 'medium.blog';
    }

    public function getIdentifier(): string
    {
        return 'tag:example.org,2011:abc123';
    }

    public function getExtraData(): array
    {
        // TODO: Implement getTargetDetails() method.
        return [];
    }
}
