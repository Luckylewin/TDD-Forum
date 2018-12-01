<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/1
 * Time: 14:50
 */

namespace App\Filters;


use App\User;


class ThreadsFilters extends Filters
{


    protected $filters = ['by'];

    /**
     * @param $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::query()->where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}