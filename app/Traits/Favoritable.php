<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/3
 * Time: 18:42
 */
namespace App\Traits;

use App\Models\Favorite;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }
    
    /**
     * 多态关联
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * 点赞
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * 取消点赞
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()->where($attributes)->get()->each->delete();
    }

    /**
     * 是否点赞
     * @return bool
     */
    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * 访问器
     * @return bool
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    /**
     * 单条回复的赞数量
     * @return mixed
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}