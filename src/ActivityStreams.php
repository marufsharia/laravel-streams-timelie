<?php

namespace Marufsharia\ActivityStreams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Marufsharia\ActivityStreams\Managers\ActivityManager;
use Marufsharia\ActivityStreams\Managers\ConfigurationManager;
use Marufsharia\ActivityStreams\Models\Activity;
use Marufsharia\ActivityStreams\Models\Feed;
use Marufsharia\ActivityStreams\ValueObjects\ActivityObject;
use Marufsharia\ActivityStreams\ValueObjects\Actor;
use Marufsharia\ActivityStreams\ValueObjects\Target;

class ActivityStreams
{
    /**
     * @var ActivityManager
     */
    public $activityManager;

    /**
     * @var ConfigurationManager
     */
    protected $configurationManager;

    /**
     * ActivityStreams constructor.
     * @param ActivityManager $activityManager
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ActivityManager $activityManager, ConfigurationManager $configurationManager)
    {
        $this->activityManager = $activityManager;
        $this->configurationManager = $configurationManager;
    }

    /**
     * Add an activity to a feed.
     *
     * @param Feed $feed
     * @param $activity
     */
    public function addActivityToFeed(Feed $feed, $activity): void
    {
          if ($activity instanceof Collection) {
              $filteredActivities = $activity->whereInstanceOf(Activity::class);

              // TODO batch insert
              foreach ($filteredActivities as $activity) {
                  $this->activityManager->addActivityToFeed($feed, $activity);
              }
//              $this->activityManager->addMultipleActivitiesToFeed($feed, $filteredActivities);
              return;
          }

        $this->activityManager->addActivityToFeed($feed, $activity);
    }

    public function addActivityToMultipleFeeds(Collection $feeds, Activity $activity)
    {
        $filteredFeeds = $feeds->whereInstanceOf(Feed::class);

        foreach ($filteredFeeds as $feed) {
            $this->activityManager->addActivityToFeed($feed, $activity);
        }
    }

    /**
     * Sets activity actor.
     *
     * @param $actor
     * @return ActivityStreams
     */
    public function setActor($actor): self
    {
        if ($actor instanceof Model) {
            $actor = $this->actorFromModel($actor);
        }

        $this->activityManager->setActor($actor);

        return $this;
    }

    /**
     * @param Model $model
     * @return Actor
     */
    public function actorFromModel(Model $model)
    {
        return Actor::createFromModel($model);
    }

    /**
     * @param string $verb
     * @return $this
     * @throws Exceptions\InvalidActivityVerbException
     */
    public function setVerb(string $verb)
    {
        $this->activityManager->setVerb($verb);

        return $this;
    }

    /**
     * Set activity target.
     *
     * @param $target
     * @return ActivityStreams
     */
    public function setTarget($target): self
    {
        if ($target instanceof Model) {
            $target = $this->targetFromModel($target);
        }

        $this->activityManager->setTarget($target);

        return $this;
    }

    /**
     * @param Model $model
     * @return Target
     */
    public function targetFromModel(Model $model)
    {
        return Target::createFromModel($model);
    }

    /**
     * Sets activity object.
     *
     * @param $activityObject
     * @return ActivityStreams
     */
    public function setObject($activityObject): self
    {
        if ($activityObject instanceof Model) {
            $activityObject = $this->objectFromModel($activityObject);
        }

        $this->activityManager->setObject($activityObject);

        return $this;
    }

    /**
     * @param Model $model
     * @return ActivityObject
     */
    public function objectFromModel(Model $model)
    {
        return ActivityObject::createFromModel($model);
    }

    /**
     * Gets supported verbs.
     *
     * @return array
     */
    public function verbs(): array
    {
        return $this->configurationManager->getVerbs();
    }

    /**
     * Persist the activity.
     *
     * @return Activity
     */
    public function createActivity(): Activity
    {
        return $this->activityManager->createActivity();
    }
}
