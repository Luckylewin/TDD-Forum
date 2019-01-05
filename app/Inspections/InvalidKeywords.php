<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2019/1/5
 * Time: 18:04
 */

namespace App\Inspections;


class InvalidKeywords
{
    protected $keywords = [
        'something forbidden'
    ];

    /**
     * 检测非法词汇
     * @param $body
     * @throws \Exception
     */
    public function detect($body)
    {
        foreach ($this->keywords as $invalidKeyWord) {
            if (stripos($body, $invalidKeyWord) !== false) {
                throw new \Exception('Your reply contains spam.');
            }
        }
    }
}