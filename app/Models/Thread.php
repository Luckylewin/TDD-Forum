<?php

namespace App\Models;

use App\Events\ThreadHasNewReply;
use App\Filters\Filters;
use App\Traits\RecordsActivity;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
     use RecordsActivity;

     protected $guarded = [];
     protected $with = ['creator','channel'];

     // 是否订阅访问器
     public function getIsSubscribedToAttribute()
     {
         return $this->subscriptions()->where('user_id', auth()->id())->exists();
     }

     public static function boot()
     {
         parent::boot();
         // 全局作用域

         // replies_count 加到 threads 表
         /*static::addGlobalScope('replyCount', function ($builder) {
             $builder->withCount('replies');
         });*/

         // 删除对应的回复
         static::deleting(function ($thread) {
             // 逐条删除 触发 Reply 删除事件
             $thread->replies->each->delete();
         });
     }

     public function path()
     {
         return "/threads/{$this->channel->slug}/{$this->id}";
     }

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

     public function ScopeFilter($query, Filters $filters)
     {
         return $filters->apply($query);
     }

     // 新增回复
     public function addReply($reply)
     {
         $reply = $this->replies()->create($reply);

         $this->notifySubscribers($reply);

         return $reply;
     }

     // 向订阅者发送通知
     protected function notifySubscribers($reply)
     {
         $this->subscriptions
             ->where('user_id','!=',$reply->user_id)
             ->each
             ->notify($reply);
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


     public function subscriptions()
     {
         return $this->hasMany(ThreadSubscription::class);
     }

}


