<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/29
 * Time: 18:44
 */

/**
 * @param $class
 * @param array $attributes
 * @param integer|null $times
 * @return mixed
 */
function create($class,$attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}


/**
 * @param $class
 * @param array $attributes
 * @param null|integer $times
 * @return mixed
 */
function make($class,$attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}

/**
 * @param $class
 * @param array $attributes
 * @param null $times
 * @return mixed
 */
function raw($class,$attributes = [],$times = null)
{
    return factory($class, $times)->raw($attributes);
}