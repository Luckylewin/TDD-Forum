<?php

namespace App\Models;

use App\Filters\Filters;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
     protected $guarded = [];

     public static function boot()
     {
         parent::boot();

         static::addGlobalScope('replyCount', function ($builder) {
             $builder->withCount('replies');
         });
     }

     public function path()
     {
         return "/threads/{$this->channel->slug}/{$this->id}";
     }

     public function replies()
     {
         return $this->hasMany(Reply::class)->orderBy('created_at', 'desc');
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
         $this->replies()->create($reply);
     }
}
