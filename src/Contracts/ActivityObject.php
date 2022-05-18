<?php

namespace Marufsharia\ActivityStreams\Contracts;

interface ActivityObject
{
    public function getType(): string ;
    public function getIdentifier(): string ;
    public function getExtraData(): array ;
}
