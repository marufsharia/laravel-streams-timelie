<?php

namespace Marufsharia\ActivityStreams\Contracts;

interface ActivityActor
{
    public function getType(): string ;
    public function getIdentifier(): string ;
    public function getExtraData(): array ;
}
