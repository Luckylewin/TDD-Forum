<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Favoritable;

class Reply extends Model
{
    use Favoritable;
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['owner', 'favorites']; // 注意此处
    protected $appends = ['favoritesCount', 'isFavorited'];

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

    public function path()
    {
        return $this->thread->path() . '#reply-' . $this->id;
    }
}
