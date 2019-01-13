<?php

namespace App;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    /**
     * 路由key
     * @return string
     */
    public function getRouteKeyName()
    {
         return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    // 阅读后进行缓存
    public function read($thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            Carbon::now()
        );
    }

    // 阅读话题的缓存键
    public function visitedThreadCacheKey($thread)
    {
        return $key = sprintf("user.%s.visits.%s", $this->id, $thread->id);
    }

    // 获取用户最新的回复
    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }
}
