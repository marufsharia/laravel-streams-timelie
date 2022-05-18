<?php

namespace Marufsharia\ActivityStreams\Tests\Helpers\Models;

use Illuminate\Database\Eloquent\Model;
use Marufsharia\ActivityStreams\Contracts\ReturnsExtraData;
use Marufsharia\ActivityStreams\Traits\HasFeed;

class User extends Model implements ReturnsExtraData
{
    use HasFeed;

    public function getExtraData()
    {
        return [
            'title' => 'This is a test',
        ];
    }
}

class Blog extends Model {}
