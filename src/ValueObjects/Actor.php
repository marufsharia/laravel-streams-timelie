<?php

namespace Marufsharia\ActivityStreams\ValueObjects;

use Illuminate\Database\Eloquent\Model;
use Marufsharia\ActivityStreams\Contracts\ActivityActor;
use Marufsharia\ActivityStreams\Contracts\ReturnsExtraData;

class Actor implements ActivityActor
{
    /**
     * @var string
     */
    protected $actorType;
    /**
     * @var string
     */
    protected $actorIdentifier;
    /**
     * @var array
     */
    protected $extraData;

    public function __construct(string $actorType, string $actorIdentifier, array $extraData = [])
    {
        $this->actorType = $actorType;
        $this->actorIdentifier = $actorIdentifier;
        $this->extraData = $extraData;
    }

    public static function createFromModel(Model $model, $extraData = []): Actor
    {
        if ($model instanceof ReturnsExtraData) {
            $extraData = $model->getExtraData();
        }

        return new static(get_class($model), $model->getKey(), $extraData);
    }

    public function getType(): string
    {
        return $this->actorType;
    }

    public function getIdentifier(): string
    {
        return $this->actorIdentifier;
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }
}
