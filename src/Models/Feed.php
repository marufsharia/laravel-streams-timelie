<?php

namespace Marufsharia\ActivityStreams\Models;

use Illuminate\Database\Eloquent\Model;
use Marufsharia\ActivityStreams\Events\FeedCreated;
use Marufsharia\ActivityStreams\Events\FeedDeleted;
use Marufsharia\ActivityStreams\Traits\Followable;

class Feed extends Model
{
    use Followable;

    protected $fillable = [
        'extra',
        'feedable_type',
        'feedable_id'
    ];
    protected $casts = [
        'extra' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function($feed) {
            event(new FeedCreated($feed));
        });

        static::deleted(function($feed) {
            event(new FeedDeleted($feed));
        });
    }

    public function activities()
    {
        return $this->belongsToMany(
            Activity::class,
            'feed_activities',
            'feed_id',
            'activity_id'
        );
    }
}
