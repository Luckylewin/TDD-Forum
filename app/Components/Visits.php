<?php

namespace App\Components;

use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $thread;

    /**
     * Visits constructor.
     * @param $thread
     */
    public function __construct($thread)
    {
        $this->thread = $thread;
    }


    public function cacheKey()
    {
        return "threads." . $this->thread->id . ".visits";
    }

    // 重置浏览量
    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    // 记录浏览量
    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    // 获取浏览量
    public function count()
    {
        return Redis::get($this->cacheKey()) ?: 0;
    }
}