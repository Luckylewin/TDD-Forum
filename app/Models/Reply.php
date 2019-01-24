<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Favoritable;

class Reply extends Model
{
    use Favoritable;
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['owner', 'favorites']; // 注意此处
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function($reply) {
            $reply->thread->decrement('replies_count');
        });
    }

    /**
     * 话题创建者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // 关联关系
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    // 回复url
    public function path()
    {
        return $this->thread->path() . '#reply-' . $this->id;
    }

    // 判断是否刚刚发表过回复
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    // 通过名字提醒用户
    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    /**
     * body 字段修改器
     * @param $body
     */
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/','<a href="/profiles/$1">$0</a>', $body);
    }

    /**
     * 是否为最佳回复
     */
    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
}
