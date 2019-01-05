<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2019/1/5
 * Time: 18:04
 */

namespace App\Inspections;


class KeyHeldDown
{
    /**
     * 检测键入无意义的重复
     * @param $body
     * @throws \Exception
     */
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your reply contains spam.');
        }
    }
}