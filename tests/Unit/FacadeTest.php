<?php

namespace Marufsharia\ActivityStreams\Tests\Unit;

use Illuminate\Support\Collection;
use Marufsharia\ActivityStreams\ActivityStreams;
use Marufsharia\ActivityStreams\Exceptions\InvalidActivityVerbException;
use Marufsharia\ActivityStreams\Models\Activity;
use Marufsharia\ActivityStreams\Models\Feed;
use Marufsharia\ActivityStreams\Tests\Helpers\Models\User;
use Marufsharia\ActivityStreams\Tests\Helpers\SampleObject;
use Marufsharia\ActivityStreams\Tests\Helpers\Targets\SampleTarget;
use Marufsharia\ActivityStreams\Tests\TestCase;
use Marufsharia\ActivityStreams\ValueObjects\Actor;
use Marufsharia\ActivityStreams\ValueObjects\Verbs;

class FacadeTest extends TestCase
{
    /**
     * @var ActivityStreams
     */
    private $activityStreams;

    public function setUp(): void
    {
        parent::setUp();
        $this->activityStreams = app(ActivityStreams::class);
    }

    public function testGetDefinedVerbs()
    {
        $verbs = $this->activityStreams->verbs();

        $this->assertIsArray($verbs);
        $this->assertEquals('post', $verbs['VERB_POST']);
    }

    /**
     * @dataProvider activitiesDataProvider
     * @throws InvalidActivityVerbException
     */
    public function testCreateActivity($actor, $target, $object)
    {
        $actor = isset($actor['is_model']) ? factory(User::class)->create() : $actor['value'];
        $target = isset($target['is_model']) ? factory(User::class)->create() : $target['value'];
        $object = isset($object['is_model']) ? factory(User::class)->create() : $object['value'];

        $activity = $this->activityStreams->setActor($actor)
            ->setVerb(Verbs::VERB_POST)
            ->setTarget($target)
            ->setObject($object)
            ->createActivity();

        $this->assertInstanceOf(Activity::class, $activity);
    }

    public function testAddActivityToFeed()
    {
        $user = factory(User::class)->create();
        $feed = $user->createFeed();

        $activity = $this->activityStreams->setActor($user)
            ->setVerb(Verbs::VERB_POST)
            ->setObject(new SampleObject())
            ->setTarget(new SampleTarget())
            ->createActivity();

        $this->activityStreams->addActivityToFeed($feed, $activity);

        $this->assertEquals(1, $feed->activities()->count());
    }

    public function testAddMultipleActivitiesToFeed()
    {
        $user = factory(User::class)->create();

        /** @var Feed $feed */
        $feed = $user->createFeed();

        $activities = factory(Activity::class, 2)->create();

        $this->activityStreams->addActivityToFeed($feed, $activities);

        $this->assertEquals(2, $feed->activities()->count());
    }

    public function testAddActivityToMultipleFeeds()
    {
        $users = factory(User::class, 5)->create();

        $feeds = [];
        foreach ($users as $user) {
           $feeds[] = $user->createFeed();
        }

        $feeds = collect($feeds);

        /** @var Activity $activity */
        $activity = factory(Activity::class)->create();

        $this->activityStreams->addActivityToMultipleFeeds($feeds, $activity);

        $this->assertEquals(5, $activity->feeds()->count());
    }

    public function testFeedFollowing()
    {
        /** @var User $user1 */
        $user1 = factory(User::class)->create();
        /** @var Feed $user1Feed */
        $user1Feed = $user1->createFeed();

        /** @var User $user1 */
        $user2 = factory(User::class)->create();
        /** @var Feed $user2Feed */
        $user2Feed = $user2->createFeed();

        $user1Feed->follow($user2Feed);

        $this->assertDatabaseHas('follows',[
            'follower_id' => $user1Feed->getKey(),
            'follower_type' => get_class($user1Feed),
            'followable_type' => get_class($user2Feed),
            'followable_id' => $user2Feed->getKey(),
        ]);

    }

    public function activitiesDataProvider()
    {
        return [
            [
                'actor' => [
                    'is_model' => true,
                    'value' => null,
                ],
                'target' => [
                    'is_model' => true,
                    'value' => null,
                ],
                'object' => [
                    'is_model' => true,
                    'value' => null,
                ],
            ],
            [
                'actor' => [
                    'value' => new Actor('twitter_user', 126626)
                ],
                'target' => [
                    'value' => new SampleTarget()
                ],
                'object' => [
                    'is_model' => true,
                    'value' => null,
                ],
            ],
            [
                'actor' => [
                    'is_model' => true,
                    'value' => null,
                ],
                'target' => [
                    'value' => new SampleTarget()
                ],
                'object' => [
                    'value' => new SampleObject()
                ],
            ],
            [
                'actor' => [
                    'value' => new Actor('twitter_user', 126626)
                ],
                'target' => [
                    'value' => new SampleTarget()
                ],
                'object' => [
                    'value' => new SampleObject()
                ],
            ],
            [
                'actor' => [
                    'value' => new Actor('twitter_user', 126626)
                ],
                'target' => [
                    'value' => new SampleTarget()
                ],
                'object' => [
                    'is_model' => true,
                    'value' => null,
                ],
            ],
        ];
    }
}
