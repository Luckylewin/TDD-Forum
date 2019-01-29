<?php

namespace App\Models;

use App\Components\Visits;
use Illuminate\Database\Eloquent\Model;
use App\Events\ThreadReceivedNewReply;
use App\Filters\Filters;
use App\Traits\RecordsActivity;
use App\User;

class Thread extends Model
{
     use RecordsActivity;

     protected $guarded = [];
     protected $with = ['creator','channel'];

     // 路由key-name
     public function getRouteKeyName()
     {
         return 'slug';
     }

     public static function boot()
     {
         parent::boot();

         // 生成slug
         static::created(function ($thread) {
             $thread->update([
                 'slug' => $thread->title
             ]);
         });

         // 删除对应的回复
         static::deleting(function ($thread) {
             // 逐条删除 触发 Reply 删除事件
             $thread->replies->each->delete();
         });
     }

    // slug - 修改器
    public function setSlugAttribute($value)
    {
        tap(str_slug($value),function ($slug) {
            if (static::whereSlug($slug)->where('id','!=',$this->id)->exists()) {
                $slug = $slug . '-' . $this->id;
            }

            $this->attributes['slug'] = $slug;
        });
    }

    // 是否订阅访问器
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

     // 话题url
     public function path()
     {
         return "/threads/{$this->channel->slug}/{$this->slug}";
     }

     // 回复关联关系
     public function replies()
     {
         return $this->hasMany(Reply::class)
                     ->orderBy('created_at', 'desc');
     }

     // 创建人
     public function creator()
     {
         return $this->belongsTo(User::class, 'user_id');
     }

     // 频道
     public function channel()
     {
         return $this->belongsTo(Channel::class);
     }

     // 过滤
     public function ScopeFilter($query, Filters $filters)
     {
         return $filters->apply($query);
     }

     // 新增回复
     public function addReply($reply)
     {
         $reply = $this->replies()->create($reply);

         // 事件触发通知
         event(new ThreadReceivedNewReply($reply));

         return $reply;
     }

     public function locks()
     {
        $this->update(['locked' => true]);
     }

     // 订阅话题
     public function subscribe($userId = null)
     {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
     }

     // 取消订阅
     public function unsubscribe($userId = null)
     {
         $this->subscriptions()
             ->where('user_id','=', $userId ?: auth()->id())
             ->delete();
     }

     // 订阅关系
     public function subscriptions()
     {
         return $this->hasMany(ThreadSubscription::class);
     }

     // 是否被阅读过
     public function hasUpdatesFor(User $user)
     {
        // 获取键名
         $key = $user->visitedThreadCacheKey($this);

         return $this->updated_at > cache($key);
     }

     // 标记最佳回复
     public function markBestReply(Reply $reply)
     {
         $this->update(['best_reply_id' => $reply->id]);
     }

}


