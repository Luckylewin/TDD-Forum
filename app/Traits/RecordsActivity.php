<?php

namespace App\Traits;

use App\Models\Activity;

Trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;

        // 记录一个动作流
        foreach (static::getActivityToRecord() as $event) {
            static::created(function ($thread) use ($event) {
                /* @var $thread RecordsActivity */
                $thread->recordActivity($event);
            });
        }
    }

    public static function getActivityToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
            'subject_id' => $this->id,
            'subject_type' => get_class($this)
        ]);
    }

    protected function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
}