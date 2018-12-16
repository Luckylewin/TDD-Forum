<?php

namespace App\Models;

use App\Filters\Filters;
use App\Traits\RecordsActivity;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
     use RecordsActivity;

     protected $guarded = [];
     protected $with = ['creator','channel'];

     public static function boot()
     {
         parent::boot();
         // 全局作用域
         static::addGlobalScope('replyCount', function ($builder) {
             $builder->withCount('replies');
         });
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

     public function creator()
     {
         return $this->belongsTo(User::class, 'user_id');
     }

     public function channel()
     {
         return $this->belongsTo(Channel::class);
     }

     public function ScopeFilter($query, Filters $filters)
     {
         return $filters->apply($query);
     }

     public function addReply($reply)
     {
         return $this->replies()->create($reply);
     }

}


