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
 * @return mixed
 */
function create($class,$attributes = [])
{
    return factory($class)->create($attributes);
}

/**
 * @param $class
 * @param array $attributes
 * @return mixed
 */
function make($class,$attributes = [])
{
    return factory($class)->make($attributes);
}

/**
 * @param $class
 * @param array $attributes
 * @return mixed
 */
function raw($class,$attributes = [])
{
    return factory($class)->raw($attributes);
}