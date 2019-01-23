<?php
namespace App\Traits;

use Illuminate\Support\Facades\Redis;

/**
 * Trait RecordsVisits
 * @package App\Traits
 */
Trait RecordsVisits
{
    public function visitsCacheKey()
    {
        return "threads.{$this->id}.visits";
    }

    // 重置浏览量
    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }

    // 记录浏览量
    public function recordVisit()
    {
        Redis::incr($this->visitsCacheKey());

        return $this;
    }

    // 获取浏览量
    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?: 0;
    }
}