<?php

namespace Marufsharia\ActivityStreams\Tests\Unit;

use Marufsharia\ActivityStreams\Tests\Helpers\Models\User;
use Marufsharia\ActivityStreams\Tests\TestCase;

class FeedTest extends TestCase
{
    public function testCreateModelFeed()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $data = [
            'title' => 'My Feed',
            'description' => 'My description'
        ];

        $feed = $user->createFeed($data);

        $this->assertEquals($data, $feed->extra);

        $this->assertDatabaseHas(TestCase::FEEDS_TABLE, [
            'id' => 1,
            'feedable_type' => get_class($user),
            'feedable_id' => $user->id
        ]);
    }
}
